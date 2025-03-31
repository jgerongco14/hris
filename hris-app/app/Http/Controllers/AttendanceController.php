<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class AttendanceController extends Controller
{
    //hr attendance management
    public function import(Request $request)
    {
        try {
            $request->validate([
                'attendance_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ]);

            $file = $request->file('attendance_file');
            $reader = IOFactory::createReaderForFile($file->getRealPath());

            // Handle CSV-specific settings
            if ($reader instanceof Csv) {
                $reader->setDelimiter(','); // or ';' based on your file
                $reader->setEnclosure('"');
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $counter = Attendance::count() + 1;
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // header row
                if (count($row) < 10) continue;

                $empID = trim($row[0]);
                $month = trim($row[2]);
                $day = trim($row[4]);
                $year = trim($row[3]);

                try {
                    $date = Carbon::createFromFormat('F j Y', "$month $day $year")->format('Y-m-d');
                } catch (\Exception $e) {
                    continue;
                }

                Attendance::create([
                    'empID'           => $empID,
                    'empAttID'        => 'ATT-' . now()->format('Ymd') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'empAttDate'      => $date,
                    'empAttTimeIn'    => $row[5] ?? null,
                    'empAttBreakOut'  => $row[6] ?? null,
                    'empAttBreakIn'   => $row[7] ?? null,
                    'empAttTimeOut'   => $row[8] ?? null,
                    'empAttRemarks'   => $row[9] ?? null,
                ]);

                $counter++;
            }

            return redirect()->back()->with('success', 'Attendance data imported successfully.');
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            logger()->error('Reader error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'File type is unreadable.');
        } catch (\Exception $e) {
            logger()->error('Import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function showAttendance(Request $request)
    {
        $attendance = Attendance::with('employee')->orderBy('empAttDate', 'desc');

        if ($request->filled('date_range')) {
            $range = explode(' - ', $request->input('date_range'));

            if (count($range) === 2) {
                $start = Carbon::parse($range[0])->startOfDay();
                $end = Carbon::parse($range[1])->endOfDay();

                $attendance = $attendance->whereBetween('empAttDate', [$start, $end]);
            } else {
                $date = Carbon::parse($range[0]);
                $attendance = $attendance->whereDate('empAttDate', $date);
            }
        }
        if ($request->filled('employee_name')) {
            $attendance->whereHas('employee', function ($query) use ($request) {
                $query->where('empFname', 'like', '%' . $request->employee_name . '%')
                    ->orWhere('empLname', 'like', '%' . $request->employee_name . '%');
            });
        }

        return view('pages.hr.attendance_management', [
            'attendance' => $attendance->get(),
        ]);
    }



    // public function showAttendance()
    // {
    //     $attendance = Attendance::with('employee')->get(); // if you're using the employee relation
    //     return view('pages.hr.attendance_management', compact('attendance'));
    // }
}
