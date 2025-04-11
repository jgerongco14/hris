<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Attendance;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Exception;



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

                $empID = isset($row[0]) ? trim($row[0]) : null;
                $month = isset($row[2]) ? trim($row[2]) : null;
                $day = isset($row[4]) ? trim($row[4]) : null;
                $year = isset($row[3]) ? trim($row[3]) : null;

                $dateString = "$month $day $year";
                $formats = ['F j Y', 'M j Y', 'F d Y', 'Y-m-d', 'm/d/Y'];

                $date = null;
                foreach ($formats as $format) {
                    try {
                        $parsed = Carbon::createFromFormat($format, $dateString);
                        if ($parsed !== false) {
                            $date = $parsed->format('Y-m-d');
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if (!$date) {
                    logger()->warning("Unable to parse date for row: " . json_encode($row));
                    continue;
                }

                // Check for duplicate
                $exists = Attendance::where('empID', $empID)
                    ->whereDate('empAttDate', $date)
                    ->exists();

                if ($exists) {
                    logger()->info("Duplicate entry skipped for empID: $empID on $date");
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
        } catch (Exception $e) {
            logger()->error('Reader error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'File type is unreadable.');
        } catch (\Exception $e) {
            logger()->error('Import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function showAttendance(Request $request)
    {
        try {
            // Apply filters
            $attendanceQuery = Attendance::with('employee')->orderBy('empAttDate', 'desc');

            if ($request->filled('date_range')) {
                $range = explode(' - ', $request->input('date_range'));
                if (count($range) === 2) {
                    $start = Carbon::parse($range[0])->startOfDay();
                    $end = Carbon::parse($range[1])->endOfDay();
                    $attendanceQuery->whereBetween('empAttDate', [$start, $end]);
                } else {
                    $date = Carbon::parse($range[0]);
                    $attendanceQuery->whereDate('empAttDate', $date);
                }
            } else {
                $attendanceQuery->whereDate('empAttDate', Carbon::today());
            }

            if ($request->filled('employee_name')) {
                $attendanceQuery->whereHas('employee', function ($query) use ($request) {
                    $query->where('empFname', 'like', '%' . $request->employee_name . '%')
                        ->orWhere('empLname', 'like', '%' . $request->employee_name . '%');
                });
            }
            // Count absents before pagination
            $allFiltered = (clone $attendanceQuery)->get();

            $totalAbsents = $allFiltered->filter(fn($item) => strtolower($item->empAttRemarks) === 'absent')->count();
            $totalPresent = $allFiltered->filter(fn($item) => strtolower($item->empAttRemarks) === 'present')->count();


            // Paginate and load relationships after
            $attendance = $attendanceQuery->paginate(10)->appends($request->only(['date_range', 'employee_name']));
            $attendance->load('leaves.status');

            return view('pages.hr.attendance_management', [
                'attendance' => $attendance,
                'totalAbsents' => $totalAbsents,
                'totalPresent' => $totalPresent,
            ]);
        } catch (\Exception $e) {
            logger()->error('Show attendance error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.' . $e->getMessage());
        }
    }



    //Employee side
    public function showEmployeeAttendance(Request $request)
    {
        try {
            $attendance = Attendance::with('employee')
                ->where('empID', Auth::user()->empID)
                ->orderBy('empAttDate', 'desc');

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

            $attendance = $attendance->paginate(10);

            return view('pages.employee.attendance', [
                'attendance' => $attendance,
            ]);
        } catch (\Exception $e) {
            logger()->error('Show employee attendance error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
