<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainings;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogsActivity;

class TrainingsController extends Controller
{
    use LogsActivity;
    public function createTraining(Request $request)
    {
        try {
            $request->validate([
                'empTrainName' => 'required|string|max:100',
                'empTrainDescription' => 'nullable|string|max:500',
                'empTrainFromDate' => 'nullable|date',
                'empTrainToDate' => 'nullable|date',
                'empTrainLocation' => 'nullable|string|max:500',
                'empTrainConductedBy' => 'nullable|string|max:200',
                'empTrainCertificate' => 'nullable|array',
                'empTrainCertificate.*' => 'file|mimes:pdf|max:2048',
            ]);

            $empID = Auth::user()->employee->empID;

            $attachmentPaths = [];

            if ($request->hasFile('empTrainCertificate')) {
                foreach ($request->file('empTrainCertificate') as $file) {
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('certificates', $filename, 'public');
                    $attachmentPaths[] = $path;
                }
            }

            Trainings::create([
                'empID' => $empID,
                'empTrainName' => $request->empTrainName,
                'empTrainDescription' => $request->empTrainDescription,
                'empTrainFromDate' => $request->empTrainFromDate,
                'empTrainToDate' => $request->empTrainToDate,
                'empTrainLocation' => $request->empTrainLocation,
                'empTrainConductedBy' => $request->empTrainConductedBy,
                'empTrainCertificate' => json_encode($attachmentPaths),
            ]);

            // Log the activity
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
            ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
            : 'Unknown Employee';
            $this->logActivity('Create', "Employee $fullName created a training record.", $currentUser->id);
            return redirect()->back()->with('success', 'Training created successfully!');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
            ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
            : 'Unknown Employee';
            $this->logActivity('Error', "Employee $fullName encountered an error while creating training: " . $e->getMessage(), $currentUser->id);
            return redirect()->back()->with('error', 'Error creating training: ' . $e->getMessage());
        }
    }

    public function showTrainings()
    {
        try {
            $empID = Auth::user()->employee->empID;

            $trainings = Trainings::where('empID', $empID)->paginate(10);

            return view('pages.employee.training', compact('trainings'));
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
            ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
            : 'Unknown Employee';
            $this->logActivity('Error', "Employee $fullName encountered an error while fetching trainings: " . $e->getMessage(), $currentUser->id);
            return redirect()->back()->with('error', 'Error fetching trainings');
        }
    }

    public function editTraining($id)
    {
        try {
            $training = Trainings::findOrFail($id);
            return view('pages.employee.training_edit', compact('training'));
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
            ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
            : 'Unknown Employee';
            $this->logActivity('Error', "Employee $fullName encountered an error while fetching training for edit: " . $e->getMessage(), $currentUser->id);
            return redirect()->back()->with('error', 'Error fetching training:');
        }
    }

    public function updateTraining(Request $request, $id)
    {
        try {
            $request->validate([
                'empTrainName' => 'required|string|max:100',
                'empTrainDescription' => 'nullable|string|max:500',
                'empTrainFromDate' => 'nullable|date',
                'empTrainToDate' => 'nullable|date',
                'empTrainLocation' => 'nullable|string|max:500',
                'empTrainConductedBy' => 'nullable|string|max:200',
                'empTrainCertificate' => 'nullable|array',
                'empTrainCertificate.*' => 'file|mimes:pdf|max:2048',
                'existingCertificates' => 'nullable|array',
            ]);

            $training = Trainings::findOrFail($id);

            $existing = $request->existingCertificates ?? [];
            $current = json_decode($training->empTrainCertificate, true) ?? [];
            $finalFiles = array_values(array_intersect($current, $existing));

            if ($request->hasFile('empTrainCertificate')) {
                foreach ($request->file('empTrainCertificate') as $file) {
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('certificates', $filename, 'public');
                    $finalFiles[] = $path;
                }
            }

            $training->update([
                'empTrainName' => $request->empTrainName,
                'empTrainDescription' => $request->empTrainDescription,
                'empTrainFromDate' => $request->empTrainFromDate,
                'empTrainToDate' => $request->empTrainToDate,
                'empTrainLocation' => $request->empTrainLocation,
                'empTrainConductedBy' => $request->empTrainConductedBy,
                'empTrainCertificate' => json_encode($finalFiles),
            ]);
            
            // Log the activity
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
            ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
            : 'Unknown Employee';
            $this->logActivity('Update', "Employee $fullName updated training record.", $currentUser->id);
            return redirect()->back()->with('success', 'Training updated successfully!');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
            ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
            : 'Unknown Employee';
            $this->logActivity('Error', "Employee $fullName encountered an error while updating training: " . $e->getMessage(), $currentUser->id);
            return redirect()->back()->with('error', 'Error updating training: ' . $e->getMessage());
        }
    }

    public function deleteTraining($id)
    {
        try {
            // Log the activity
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
            ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
            : 'Unknown Employee';
            $this->logActivity('Delete', "Employee $fullName deleted a training record.", $currentUser->id);
            $training = Trainings::findOrFail($id);
            $training->delete();

            return redirect()->back()->with('success', 'Training deleted successfully!');
        } catch (Exception $e) {
            // Log the error
            $currentUser = Auth::user();
            $employee = $currentUser->employee;
            $fullName = $employee
            ? trim("{$employee->empPrefix} {$employee->empFname} {$employee->empMname} {$employee->empLname} {$employee->empSuffix}")
            : 'Unknown Employee';
            $this->logActivity('Error', "Employee $fullName encountered an error while deleting training: " . $e->getMessage(), $currentUser->id);
            return redirect()->back()->with('error', 'Error deleting training: ' . $e->getMessage());
        }
    }
}
