<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Programs;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Http\Request;
use App\Models\Offices;
use Illuminate\Support\Facades\DB;


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

            if ($reader instanceof Csv) {
                $reader->setDelimiter(',');
                $reader->setEnclosure('"');
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header

                $departmentCode = isset($row[0]) ? trim($row[0]) : null;
                $departmentName = isset($row[1]) ? trim($row[1]) : null;
                $programCode = isset($row[2]) ? trim($row[2]) : null;
                $programName = isset($row[3]) ? trim($row[3]) : null;

                // Create department
                $department = Departments::firstOrCreate(
                    ['departmentCode' => $departmentCode],
                    ['departmentName' => $departmentName]
                );
                // Check if department already has the program
                if (empty($departmentCode) || empty($departmentName)) {
                    continue;
                }

                // Create or get department
                $department = Departments::firstOrCreate(
                    ['departmentCode' => $departmentCode],
                    ['departmentName' => $departmentName]
                );

                // Create or get program
                $program = Programs::firstOrCreate(
                    ['programCode' => $programCode],
                    ['programName' => $programName]
                );

                // ✅ Sync program to department without detaching existing ones
                if ($program) {
                    $department->programs()->syncWithoutDetaching([$program->id]);
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
                'departmentCode' => 'required|string|max:255|unique:departments,departmentCode',
                'programs' => 'nullable|array',
                'programs.*.programCode' => 'nullable|string|max:255',
                'programs.*.programName' => 'nullable|string|max:255',
            ]);

            // Create department
            $department = Departments::create([
                'departmentCode' => $request->departmentCode,
                'departmentName' => $request->name
            ]);

            // Process programs
            $programIds = [];
            if (is_array($request->programs)) {
                foreach ($request->programs as $programData) {
                    $program = Programs::firstOrCreate(
                        ['programCode' => $programData['programCode']],
                        ['programName' => $programData['programName']]
                    );
                    $programIds[] = $program->id;

                    if (!empty($programIds)) {
                        $department->programs()->syncWithoutDetaching($programIds);
                    }
                }
            }


            return redirect()->back()->with('success', 'Department and programs created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
        }
    }

    public function editDepartment($id)
    {
        $department = Departments::with('programs')->findOrFail($id);
        return response()->json($department);
    }

    public function updateDepartment(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'departmentCode' => 'required|string|max:255',
                'programs' => 'nullable|array',
                'programs.*.programCode' => 'nullable|string|max:255',
                'programs.*.programName' => 'nullable|string|max:255',
                'programs.*.id' => 'nullable|exists:programs,id'
            ]);

            $department = Departments::findOrFail($id);
            $department->update([
                'departmentName' => $request->input('name'),
                'departmentCode' => $request->input('departmentCode')
            ]);

            // Handle programs update
            if ($request->has('programs')) {
                $programIds = [];
                foreach ($request->input('programs') as $programData) {
                    if (!empty($programData['id'])) {
                        // Update existing program
                        $program = Programs::find($programData['id']);
                        if ($program) {
                            $program->update([
                                'programCode' => $programData['programCode'],
                                'programName' => $programData['programName']
                            ]);
                            $programIds[] = $program->id;
                        }
                    } elseif (!empty($programData['programCode'])) {
                        // Create new program
                        $program = Programs::firstOrCreate(
                            ['programCode' => $programData['programCode']],
                            ['programName' => $programData['programName']]
                        );
                        $programIds[] = $program->id;
                    }
                }
                $department->programs()->sync($programIds);
            } else {
                $department->programs()->detach();
            }

            return redirect()->back()->with('success', 'Department and programs updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update department: ' . $e->getMessage());
        }
    }

    public function removeProgram($departmentId, $programId)
    {
        try {
            $department = Departments::findOrFail($departmentId);

            if (!$programId) {
                return redirect()->back()->with('error', 'No program selected to remove.');
            }

            $department->programs()->detach($programId);

            return redirect()->back()->with('success', 'Program removed from department successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove program: ' . $e->getMessage());
        }
    }



    public function deleteDepartment($id)
    {
        try {
            $department = Departments::with('programs')->findOrFail($id);

            // Delete all related programs
            foreach ($department->programs as $program) {
                $program->delete();
            }

            // Delete the department (will remove from pivot too if set to cascade)
            $department->delete();

            return redirect()->back()->with('success', 'Department and its programs deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}
