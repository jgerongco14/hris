<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class AssignmentController extends Controller
{

    public function storePosition(Request $request)
    {
        // Validate and store the position data
        try {
            $request->validate([
                'positionID' => 'required|string|max:255|unique:positions,positionID',
                'positionName' => 'required|string|max:255',
                'positionDescription' => 'required|string',
            ]);

            // Check if position already exists
            $existingPosition = Position::where('positionID', $request->positionID)->first();
            $existingPositionName = Position::where('positionName', $request->positionName)->first();
            if ($existingPosition && $existingPositionName) {
                return redirect()->back()->with('error', 'Position with ID ' . $request->positionID . ' and Name ' . $request->positionName . ' already exists.');
            }

            // Store the position in the database (assuming you have a Position model)
            Position::create($request->only('positionID', 'positionName', 'positionDescription'));

            return redirect()->route('assignment_management')->with('success', 'Position added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while creating position: ' . $e->getMessage());
        }
    }

    public function showPositionList(Request $request)
    {
        $query = Position::query();

        if ($request->filled('search')) {
            $query->where('positionID', 'like', '%' . $request->search . '%')
                ->orWhere('positionName', 'like', '%' . $request->search . '%');
        }

        $positions = $query->paginate(10);

        return view('pages.admin.assignment_management', compact('positions'));
    }



    public function editPosition($id)
    {
        // Fetch the position by ID (assuming you have a Position model)
        try {
            $position = Position::findOrFail($id);

            return view('assignment.edit_position', [
                'position' => $position,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while fetching position: ' . $e->getMessage());
        }
    }



    public function updatePosition(Request $request, $id)
    {
        // Validate and update the position data
        try {
            $request->validate([
                'positionID' => 'required|string|max:255|unique:positions,positionID,' . $id,
                'positionName' => 'required|string|max:255',
                'positionDescription' => 'required|string',
            ]);

            // Update the position in the database (assuming you have a Position model)
            $position = Position::findOrFail($id);
            $position->update($request->only('positionID', 'positionName', 'positionDescription'));

            return redirect()->route('assignment_management')->with('success', 'Position updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while updating position: ' . $e->getMessage());
        }
    }

    public function deletePosition($id)
    {
        // Delete the position by ID (assuming you have a Position model)
        try {
            $position = Position::findOrFail($id);
            $position->delete();

            return redirect()->route('assignment_management')->with('success', 'Position deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred while deleting position: ' . $e->getMessage());
        }
    }
}
