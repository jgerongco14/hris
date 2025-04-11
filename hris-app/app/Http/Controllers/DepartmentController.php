<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Programs;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Http\Request;
use App\Models\Offices;

class DepartmentController extends Controller
{
    // In DepartmentController.php or create a new controller
    public function displayManagementPage()
    {
        $departments = Departments::with('programs')->get();
        $offices = Offices::all();

        return view('pages.admin.departments_offices_management', compact('departments', 'offices'));
    }

    public function importDepartment(Request $request)
    {
        try {
            $request->validate([
                'department_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ]);

            $file = $request->file('department_file');
            $reader = IOFactory::createReaderForFile($file->getRealPath());

            // Handle CSV-specific settings
            if ($reader instanceof Csv) {
                $reader->setDelimiter(','); // or ';' based on your file
                $reader->setEnclosure('"');
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header row

                $departmentCode = isset($row[0]) ? trim($row[0]) : null;
                $departmentName = isset($row[1]) ? trim($row[1]) : null;
                $programCode = isset($row[2]) ? trim($row[2]) : null;
                $programName = isset($row[3]) ? trim($row[3]) : null;

                // Skip if departmentCode or departmentName is missing
                if (empty($departmentCode) || empty($departmentName)) {
                    continue;
                }

                // Check if the department exists or create it
                $department = Departments::firstOrCreate(
                    ['departmentCode' => $departmentCode],
                    ['departmentName' => $departmentName]
                );

                // Skip if programCode is missing
                if (empty($programCode)) {
                    continue;
                }

                // Check if the program exists or create it
                $program = Programs::firstOrCreate(
                    ['programCode' => $programCode],
                    ['programName' => $programName]
                );

                // Attach the program to the department if not already attached
                if (!$department->programs->contains($program->id)) {
                    $department->programs()->attach($program->id);
                }
            }

            return redirect()->back()->with('success', 'Departments imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import departments: ' . $e->getMessage());
        }
    }


    public function createDepartment(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'programName' => 'nullable|string|max:255', // Validate the optional program name
            ]);

            // Create the department
            $department = Departments::create([
                'departmentName' => $request->input('name'),
            ]);

            // If a program name is provided, create the program and associate it with the department
            if ($request->filled('programName')) {
                $program = Programs::create([
                    'programName' => $request->input('programName'),
                ]);

                $department->programs()->attach($program->id);
            }

            return redirect()->back()->with('success', 'Department created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
        }
    }

    public function removeProgram(Request $request, $departmentId)
    {
        try {
            $department = Departments::findOrFail($departmentId);
            $programId = $request->input('programId');

            // Assuming you have a many-to-many relationship set up
            $department->programs()->detach($programId);

            return redirect()->back()->with('success', 'Program removed from department successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove program: ' . $e->getMessage());
        }
    }

    public function deleteDepartment($id)
    {
        try {
            $department = Departments::findOrFail($id);
            $department->delete();

            return redirect()->back()->with('success', 'Department deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}
