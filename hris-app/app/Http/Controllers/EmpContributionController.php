<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmpContributionController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Generate empConNo
            $empConNo = $request->empID . '-' . date('Y', strtotime($request->empConDate));
            $existingContribution = Contribution::where('empConNo', $empConNo)->first();
            if ($existingContribution) {
                return redirect()->back()->with('error', 'Contribution already exists for this employee.');
            }

            Contribution::create([
                'empConNo' => $empConNo,
                'empID' => $request->empID,
                'empContype' => $request->empContype,
                'empConAmount' => $request->empConAmount,
                'employeerContribution' => $request->employeerContribution,
                'empConDate' => Carbon::parse($request->empConDate)->format('Y-m-d'),
                'payRefNo' => $request->payRefNo,
            ]);

            return redirect()->back()->with('success', 'Contribution successfully added.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while adding contribution: ' . $e->getMessage());
        }
    }

    public function importContributions(Request $request)
    {
        try {
            $request->validate([
                'contribution_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ]);

            $file = $request->file('contribution_file');
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getRealPath());

            if ($reader instanceof \PhpOffice\PhpSpreadsheet\Reader\Csv) {
                $reader->setDelimiter(',');
                $reader->setEnclosure('"');
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header row

                $empID = isset($row[0]) ? trim($row[0]) : null;
                $empContype = isset($row[1]) ? trim($row[1]) : null;
                $empConAmountRaw = isset($row[2]) ? trim($row[2]) : null;
                $employeerContributionRaw = isset($row[3]) ? trim($row[3]) : null;
                $empPRNo = isset($row[4]) ? trim($row[4]) : null;
                $empConDate = isset($row[5]) ? trim($row[5]) : null;

                // Debug: Log the raw data
                Log::debug("Row $index data:", [
                    'empID' => $empID,
                    'empContype' => $empContype,
                    'empConAmountRaw' => $empConAmountRaw,
                    'employeerContributionRaw' => $employeerContributionRaw,
                    'empPRNo' => $empPRNo,
                    'empConDate' => $empConDate
                ]);

                // Validate required fields
                if (empty($empID) || empty($empContype) || empty($empConDate)) {
                    return redirect()->back()->with('error', "Missing required data in row " . ($index + 1));
                }

                // Handle empConAmount
                $empConAmount = null;
                if (!empty($empConAmountRaw)) {
                    if (strtoupper($empConAmountRaw) == 'NO EARNINGS') {
                        $empConAmount = 'No Earnings';
                    } elseif (is_numeric(str_replace(',', '', $empConAmountRaw))) {
                        $empConAmount = number_format((float)str_replace(',', '', $empConAmountRaw), 2, '.', '');
                    }
                }

                // Handle employeerContribution
                $employeerContribution = null;
                if (!empty($employeerContributionRaw)) {
                    if (strtoupper($employeerContributionRaw) == 'NO EARNINGS') {
                        $employeerContribution = 'No Earnings';
                    } elseif (is_numeric(str_replace(',', '', $employeerContributionRaw))) {
                        $employeerContribution = number_format((float)str_replace(',', '', $employeerContributionRaw), 2, '.', '');
                    }
                }

                // Parse date
                $formats = ['Y-m-d', 'm/d/Y', 'd-m-Y', 'm-d-Y'];
                $formattedDate = null;

                foreach ($formats as $format) {
                    try {
                        $formattedDate = \Carbon\Carbon::createFromFormat($format, $empConDate)->format('Y-m-d');
                        break;
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if (!$formattedDate) {
                    return redirect()->back()->with('error', "Invalid date format in row " . ($index + 1) . ". Expected formats: Y-m-d, m/d/Y, d-m-Y, or m-d-Y.");
                }

                // Generate unique empConNo
                $existingCount = Contribution::where('empID', $empID)
                    ->where('empConDate', $formattedDate)
                    ->count();
                $incremental = $existingCount + 1;
                $empConNo = $empID . $formattedDate . str_pad($incremental, 0, '0', STR_PAD_LEFT);

                // Debug: Log the data before insertion
                Log::debug("Inserting data:", [
                    'empConNo' => $empConNo,
                    'empID' => $empID,
                    'empContype' => $empContype,
                    'empConAmount' => $empConAmount,
                    'employeerContribution' => $employeerContribution,
                    'empPRNo' => $empPRNo,
                    'empConDate' => $formattedDate
                ]);

                // Insert new contribution
                $contribution = Contribution::create([
                    'empConNo' => $empConNo,
                    'empID' => $empID,
                    'empContype' => $empContype,
                    'empConAmount' => $empConAmount,
                    'employeerContribution' => $employeerContribution,
                    'empPRNo' => $empPRNo, // Make sure this matches your DB column
                    'empConDate' => $formattedDate,
                ]);

                // Debug: Verify the inserted record
                if ($contribution) {
                    Log::debug("Record inserted successfully:", $contribution->toArray());
                } else {
                    Log::error("Failed to insert record for row $index");
                }
            }

            return redirect()->back()->with('success', 'Contributions successfully imported.');
        } catch (\Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error importing: ' . $e->getMessage());
        }
    }

    public function showContributionManagement(Request $request)
    {
        try {
            $search = $request->input('search');
            $activeType = $request->input('contribution_type', 'SSS');
            $employees = Employee::all();

            // Shared filter logic
            $searchFilter = function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('empID', 'like', "%{$search}%")
                        ->orWhereHas('employee', function ($q) use ($search) {
                            $q->whereRaw("CONCAT(empFname, ' ', empLname) LIKE ?", ["%{$search}%"]);
                        });
                });
            };

            // Always load all contributions
            $sssContributions = Contribution::with('employee')
                ->where('empContype', 'SSS')
                ->when($request->input('contribution_type') === 'SSS' && $search, $searchFilter)
                ->paginate(10, ['*'], 'sss_page');

            $pagibigContributions = Contribution::with('employee')
                ->where('empContype', 'PAG-IBIG')
                ->when($request->input('contribution_type') === 'PAG-IBIG' && $search, $searchFilter)
                ->paginate(10, ['*'], 'pagibig_page');

            $tinContributions = Contribution::with('employee')
                ->where('empContype', 'TIN')
                ->when($request->input('contribution_type') === 'TIN' && $search, $searchFilter)
                ->paginate(10, ['*'], 'tin_page');

            return view('pages.hr.contribution_management', compact(
                'sssContributions',
                'pagibigContributions',
                'tinContributions',
                'employees',
                'activeType' // for keeping tab active in Blade
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }



    public function exportWord(Request $request)
    {
        try {
            $contributionType = $request->input('contribution_type', 'SSS');
            $search = $request->input('search');

            // Fetch contributions and employee info
            $contributions = Contribution::with('employee')
                ->where('empContype', $contributionType)
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('empID', 'like', "%{$search}%")
                            ->orWhereHas('employee', function ($q) use ($search) {
                                $q->whereRaw("CONCAT(empFname, ' ', empLname) LIKE ?", ["%{$search}%"]);
                            });
                    });
                })->orderBy('empConDate')->get();

            if ($contributions->isEmpty()) {
                return back()->with('error', 'No contributions found for this filter.');
            }

            // Use the first employee record to fill certificate header
            $first = $contributions->first();
            $employee = $first->employee;

            $fullName = $employee ? "{$employee->empFname} {$employee->empMname} {$employee->empLname}" : 'N/A';
            $idNo = match ($contributionType) {
                'SSS' => $employee->empSSSNum ?? 'N/A',
                'PAG-IBIG' => $employee->empPagIbigNum ?? 'N/A',
                'TIN' => $employee->empTinNum ?? 'N/A',
                default => 'N/A',
            };

            // Compute coverage period
            $start = \Carbon\Carbon::parse($contributions->first()->empConDate)->format('F Y');
            $end = \Carbon\Carbon::parse($contributions->last()->empConDate)->format('F Y');
            $coveragePeriod = "$start to $end";

            // Load the certificate template
            $templatePath = public_path('template/contribution_template.docx');
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            // Set certificate header fields
            $templateProcessor->setValue('empConType', strtoupper($contributionType));
            $templateProcessor->setValue('empName', $fullName);
            $templateProcessor->setValue('empSSS', $idNo);
            $templateProcessor->setValue('coveragePeriod', $coveragePeriod);

            // Prepare and clone table rows
            $contributions = $contributions->sortBy('empConDate')->values();
            $templateProcessor->cloneRow('month', $contributions->count());

            $totalAmount = 0;
            foreach ($contributions as $index => $contribution) {
                $row = $index + 1;
                $date = \Carbon\Carbon::parse($contribution->empConDate);

                $year = $date->format('Y');
                $month = $date->format('F');

                $premium = is_numeric($contribution->empConAmount)
                    ? number_format($contribution->empConAmount, 2)
                    : 'No Earnings';

                $ec = is_numeric($contribution->employeerContribution)
                    ? number_format($contribution->employeerContribution, 2)
                    : 'No Earnings';


                $prNumber = $contribution->payRefNo ?? 'No Earnings';
                $paymentDate = $date->format('m/d/Y');

                // Set values in the Word template
                $templateProcessor->setValue("year#{$row}", $year);
                $templateProcessor->setValue("month#{$row}", $month);
                $templateProcessor->setValue("sssPremium#{$row}", is_numeric($contribution->empConAmount) ? "Php {$premium}" : $premium);
                $templateProcessor->setValue("ec#{$row}", is_numeric($contribution->employeerContribution) ? "Php {$ec}" : $ec);
                $templateProcessor->setValue("prNumber#{$row}", $prNumber);
                $templateProcessor->setValue("paymentDate#{$row}", $paymentDate);

                // Total amount calculation
                $totalAmount += is_numeric($contribution->empConAmount) ? $contribution->empConAmount : 0;
            }


            // Set total
            $templateProcessor->setValue('totalPremium', number_format($totalAmount, 2));

            // Downloadable file
            $fileName = strtoupper($contributionType) . '_contributions_' . now()->format('Ymd_His') . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'cert');
            $templateProcessor->saveAs($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return back()->with('error', 'Error generating document: ' . $e->getMessage());
        }
    }

    // Show Contribution Edit Form
    // Fetch the contribution data for editing
    public function edit($id)
    {
        try {
            $contribution = Contribution::with('employee')->findOrFail($id);
            $contribution->empConDate = Carbon::parse($contribution->empConDate)->format('Y-m-d');  // Ensure it's in 'YYYY-MM-DD' format
            return view('pages.hr.contribution_management', compact('contribution'));
        } catch (\Exception $e) {
            // Catch any error, and return a helpful message
            return response()->json(['error' => 'Contribution not found'], 404);
        }
    }

    // Update the contribution data
    public function update(Request $request, $id)
    {
        try {
            $contribution = Contribution::findOrFail($id);

            // Validate the incoming data
            $validatedData = $request->validate([
                'empConAmount' => 'nullable',
                'employeerContribution' => 'nullable',
                'payRefNo' => 'nullable',
                'empConDate' => 'required|date',

            ]);

            $contribution->update([
                'empConAmount' => $validatedData['empConAmount'] ?? null,
                'employeerContribution' => $validatedData['employeerContribution'] ?? null,
                'empConDate' => Carbon::parse($validatedData['empConDate'])->format('Y-m'),  // Only 'YYYY-MM'
                'payRefNo' => $validatedData['payRefNo'] ?? null,
            ]);

            return redirect()->route('contribution.management', [
                'contribution_type' => $request->input('contribution_type', 'SSS'),  // Pass the current contribution type
                'search' => $request->input('search')  // Optionally pass any search filters back
            ])->with('success', 'Contribution successfully updated.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Pass the validation error to the session
            return redirect()->back()->with('error', 'Validation failed: ' . implode(", ", $e->errors()));
        } catch (\Exception $e) {
            Log::error('Error updating contribution: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error occurred while updating the contribution: ' . $e->getMessage());
        }
    }

    // Delete Contribution
    public function destroy($id)
    {
        try {
            $contribution = Contribution::findOrFail($id);
            $contribution->delete();

            return redirect()->route('contribution.management')->with('success', 'Contribution successfully deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while deleting the contribution: ' . $e->getMessage());
        }
    }

    //Employee
    public function employeeContribution(Request $request)
    {
        try {
            $activeType = $request->input('contribution_type', 'SSS');  // Default to 'SSS'
            $employee = Auth::user()->employee;

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee record not found for the authenticated user.');
            }

            // Get the empID of the authenticated user
            $empID = $employee->empID;

            // Retrieve contributions for each type, filtered by the empID
            $sssContributions = Contribution::with('employee')
                ->where('empContype', 'SSS')
                ->where('empID', $empID)  // Filter by empID
                ->paginate(10, ['*'], 'sss_page');

            $pagibigContributions = Contribution::with('employee')
                ->where('empContype', 'PAG-IBIG')
                ->where('empID', $empID)  // Filter by empID
                ->paginate(10, ['*'], 'pagibig_page');

            $tinContributions = Contribution::with('employee')
                ->where('empContype', 'TIN')
                ->where('empID', $empID)  // Filter by empID
                ->paginate(10, ['*'], 'tin_page');

            return view('pages.employee.my_contribution', compact(
                'sssContributions',
                'pagibigContributions',
                'tinContributions',
                'employee',
                'activeType'  // Active type passed for tab highlighting
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while fetching contributions: ' . $e->getMessage());
        }
    }
}
