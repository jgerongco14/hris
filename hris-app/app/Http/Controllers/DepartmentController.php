<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Programs;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Http\Request;
use App\Models\Offices;
use Illuminate\Support\Facades\Log;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;


class DepartmentController extends Controller
{
    use LogsActivity;
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
            // Log the import activity
            $currentUser = Auth::user();
            $this->logActivity('Import', "Admin imported departments successfully.", $currentUser->id);
            

            return redirect()->back()->with('success', 'Departments imported successfully!');
        } catch (\Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $this->logActivity('Import', "Admin encountered an error while importing departments: " . $e->getMessage(), $currentUser->id);
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
            
            // Log the creation activity
            $currentUser = Auth::user();
            $this->logActivity('Create', "Admin created department and programs successfully.", $currentUser->id);

            return redirect()->back()->with('success', 'Department and programs created successfully!');
        } catch (\Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $this->logActivity('Create', "Admin encountered an error while creating department: " . $e->getMessage(), $currentUser->id);
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
                'departmentCode' => 'required|string|max:255|unique:departments,departmentCode,' . $id,
                'programs' => 'nullable|array',
                'programs.*.programCode' => 'required|string|max:255',
                'programs.*.programName' => 'required|string|max:255',
                'programs.*.id' => 'nullable|exists:programs,id'
            ]);

            Log::debug('Received data:', $request->all());

            // Update department
            $department = Departments::findOrFail($id);
            $department->update([
                'departmentName' => $request->name,
                'departmentCode' => $request->departmentCode
            ]);

            // Process programs
            $programIds = [];
            if ($request->programs) {
                foreach ($request->programs as $programData) {
                    // Skip if required fields are empty
                    if (empty($programData['programCode'])) {
                        continue;
                    }

                    // Update existing or create new program
                    if (!empty($programData['id'])) {
                        $program = Programs::find($programData['id']);
                        if ($program) {
                            $program->update([
                                'programCode' => $programData['programCode'],
                                'programName' => $programData['programName']
                            ]);
                            $programIds[] = $program->id;
                        }
                    } else {
                        $program = Programs::firstOrCreate(
                            ['programCode' => $programData['programCode']],
                            ['programName' => $programData['programName']]
                        );
                        $programIds[] = $program->id;
                    }
                }
            }

            // Sync programs (this will handle attaching/detaching)
            $department->programs()->sync($programIds);

            return redirect()->back()->with('success', 'Department updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update department: ' . $e->getMessage());
        }
    }

    public function addProgramToDepartment(Request $request)
    {
        try {
            $request->validate([
                'department_id' => 'required|exists:departments,id',
                'programs' => 'required|array',
                'programs.*.programCode' => 'required|string|max:255',
                'programs.*.programName' => 'required|string|max:255',
            ]);

            $department = Departments::findOrFail($request->department_id);
            $programIds = [];

            foreach ($request->programs as $programData) {
                $program = Programs::firstOrCreate(
                    ['programCode' => $programData['programCode']],
                    ['programName' => $programData['programName']]
                );
                $programIds[] = $program->id;
            }

            $department->programs()->syncWithoutDetaching($programIds);

            // Log the addition activity
            $currentUser = Auth::user();
            $this->logActivity('Add', "Admin added programs to department successfully.", $currentUser->id);
            return redirect()->back()->with('success', 'Program(s) successfully added to the department.');
        } catch (\Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $this->logActivity('Add', "Admin encountered an error while adding programs to department: " . $e->getMessage(), $currentUser->id);
            return redirect()->back()->with('error', 'Failed to add programs: ' . $e->getMessage());
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
