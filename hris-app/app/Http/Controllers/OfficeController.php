<?php

namespace App\Http\Controllers;

use App\Models\Offices;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Http\Request;

class OfficeController extends Controller
{

    public function importOfficeCSV(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'office_file' => 'required|mimes:csv,txt|max:2048',
            ]);

            // Load the CSV file
            $file = $request->file('office_file');
            $reader = IOFactory::createReaderForFile($file->getRealPath());

            // Handle CSV-specific settings
            if ($reader instanceof Csv) {
                $reader->setDelimiter(','); // or ';' based on your file
                $reader->setEnclosure('"');
            }
            $spreadsheet = $reader->load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
            foreach ($rows as $index => $row) {
                if ($index === 0) continue;
                // Skip header row

                if (empty($row[0]) || empty($row[1])) continue; // Skip empty rows

                $officeCode = isset($row[0]) ? trim($row[0]) : null;
                $officeName = isset($row[1]) ? trim($row[1]) : null;

                // Check if office already exists
                $office = Offices::where('officeCode', $officeCode)
                    ->where('officeName', $officeName)
                    ->first();
                if ($office) {
                    // Update existing office
                    $office->update([
                        'officeCode' => $officeCode,
                        'officeName' => $officeName,
                    ]);
                } else {
                    // Create new office
                    Offices::create([
                        'officeCode' => $officeCode,
                        'officeName' => $officeName,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Offices imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import offices: ' . $e->getMessage());
        }
    }


    public function createOffice(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'officeCode' => 'required|string|max:255',
                'officeName' => 'required|string|max:255',
            ]);

            // Create a new office instance
            Offices::create([
                'officeCode' => $request->input('officeCode'),
                'officeName' => $request->input('officeName'),
            ]);

            return redirect()->back()->with('success', 'Office created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create office: ' . $e->getMessage());
        }
    }

    public function editOffice($id)
    {
        $office = Offices::findOrFail($id);
        return response()->json($office);
    }

    public function updateOffice(Request $request, $id)
    {
        try {
            $request->validate([
                'officeCode' => 'required|string|max:255',
                'officeName' => 'required|string|max:255',
            ]);

            $office = Offices::findOrFail($id);
            $office->update([
                'officeCode' => $request->input('officeCode'),
                'officeName' => $request->input('officeName')
            ]);

           return redirect()->back()->with('success', 'Office updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update office: ' . $e->getMessage());
        }
    }

    public function deleteOffice($id)
    {
        try {
            $office = Offices::findOrFail($id);
            $office->delete();
            return redirect()->back()->with('success', 'Office deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete office: ' . $e->getMessage());
        }
    }
}
