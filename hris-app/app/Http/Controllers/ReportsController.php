<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Reports;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;


class ReportsController extends Controller
{

    public function createReport(Request $request)
    {
        try {
            $request->validate([
                'empID' => 'required',
                'semester' => 'required',
                'year' => 'required',
                'reason' => 'required',
                'attachment' => 'nullable|array',
                'attachment.*' => 'file|mimes:pdf|max:2048',

            ]);

            $empID = $request->input('empID');
            $semester = $request->input('semester');
            $year = $request->input('year');
            $reason = $request->input('reason');
            $attachmentPaths = [];

            // Handle file uploads
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('report_attachments', $filename, 'public');
                    $attachmentPaths[] = $path;
                }
            }

            // Check if the employee exists
            $employee = Employee::where('empID', $empID)->first();
            if (!$employee) {
                return redirect()->back()->with('error', 'Employee not found.');
            }

            // Check if the employee is active
            if (strtolower($employee->status) !== 'active') {
                return redirect()->back()->with('error', 'Employee is not active.');
            }

            Employee::where('empID', $empID)->update(['status' => 'resigned']);

            Reports::create([
                'empID' => $empID,
                'semester' => $semester,
                'year' => $year,
                'reason' => $reason,
                'attachments' => json_encode($attachmentPaths),
            ]);


            return redirect()->back()->with('success', 'Report created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request.' . $e->getMessage());
        }
    }


    public function viewReport(Request $request)
    {
        try {
            $employeeSearch = $request->input('employee_search');
            $reportSearch = $request->input('report_search');

            $employees = Employee::whereHas('user', function ($query) {
                $query->where('role', '!=', 'admin');
            })
                ->when($employeeSearch, function ($query) use ($employeeSearch) {
                    $query->where('empID', 'like', "%$employeeSearch%")
                        ->orWhere('empFname', 'like', "%$employeeSearch%")
                        ->orWhere('empLname', 'like', "%$employeeSearch%");
                })
                ->paginate(10)
                ->appends(['employee_search' => $employeeSearch]);

            $reports = Reports::with(['employee' => function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('role', '!=', 'admin');
                });
            }])
                ->when($reportSearch, function ($query) use ($reportSearch) {
                    $query->whereHas('employee', function ($subQuery) use ($reportSearch) {
                        $subQuery->where('empID', 'like', "%$reportSearch%")
                            ->orWhere('empFname', 'like', "%$reportSearch%")
                            ->orWhere('empLname', 'like', "%$reportSearch%");
                    });
                })
                ->paginate(10)
                ->appends(['report_search' => $reportSearch]);

            $latestReports = Reports::select('empID', DB::raw('MAX(id) as latest_id'))
                ->groupBy('empID')
                ->pluck('latest_id');

            $activeCount = Employee::whereHas('user', function ($query) {
                $query->where('role', '!=', 'admin');
            })
                ->where(function ($query) {
                    $query->whereNull('status')
                        ->orWhereRaw('LOWER(status) = ?', ['active']);
                })
                ->count();

            $resignedCount = Employee::whereHas('user', function ($query) {
                $query->where('role', '!=', 'admin');
            })
                ->whereRaw('LOWER(status) = ?', ['resigned'])
                ->count();

            return view('pages.hr.reports', compact(
                'employees',
                'reports',
                'employeeSearch',
                'reportSearch',
                'activeCount',
                'resignedCount'
            ));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching the reports. ' . $e->getMessage());
        }
    }




    public function deleteReport($id)
    {
        try {
            $report = Reports::findOrFail($id);
            $report->delete();
            return redirect()->back()->with('success', 'Report deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the report.' . $e->getMessage());
        }
    }
}
