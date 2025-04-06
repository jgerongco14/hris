<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as PhpWordIOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

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
                'empConDate' => $request->empConDate,
                'empConRemarks' => $request->empConRemarks,
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
            $reader = IOFactory::createReaderForFile($file->getRealPath());

            if ($reader instanceof Csv) {
                $reader->setDelimiter(',');
                $reader->setEnclosure('"');
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header row
                if (count($row) < 5) continue; // Ensure there are enough columns (5 columns in your case)

                $empID = trim($row[0]);
                $empContype = trim($row[1]);
                $empConAmount = trim($row[2]);
                $empConDate = trim($row[3]);
                $empConRemarks = trim($row[4]);

                // Format empConDate as Y-m (Month-Year)
                $formattedDate = Carbon::createFromFormat('Y-m', $empConDate)->format('Y-m');

                // Get the count of existing contributions for the same empID and empConDate
                $existingCount = Contribution::where('empID', $empID)
                    ->where('empConDate', $formattedDate)
                    ->count();

                // Generate empConNo as empID + empConDate + incremental number
                $incremental = $existingCount + 1;
                $empConNo = $empID  . $formattedDate .  str_pad($incremental, 0, '0', STR_PAD_LEFT);

                // Insert the new contribution
                Contribution::create([
                    'empConNo' => $empConNo,
                    'empID' => $empID,
                    'empContype' => $empContype,
                    'empConAmount' => $empConAmount,
                    'empConDate' => $formattedDate,
                    'empConRemarks' => $empConRemarks,
                ]);
            }

            return redirect()->back()->with('success', 'Contributions successfully imported.');
        } catch (\Exception $e) {
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

                $premium = number_format($contribution->empConAmount, 2);
                $ec = $contribution->empConEC ?? 'No Earnings';
                $prNumber = $contribution->empConPRNo ?? 'No Earnings';
                $paymentDate = $date->format('m/d/Y');

                $templateProcessor->setValue("year#{$row}", $year);
                $templateProcessor->setValue("month#{$row}", $month);
                $templateProcessor->setValue("sssPremium#{$row}", is_numeric($premium) ? "Php {$premium}" : "No Earnings");
                $templateProcessor->setValue("ec#{$row}", is_numeric($ec) ? "Php {$ec}" : $ec);
                $templateProcessor->setValue("prNumber#{$row}", $prNumber);
                $templateProcessor->setValue("paymentDate#{$row}", $paymentDate);

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
}
