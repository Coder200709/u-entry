<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function athletes()
    {
        $athletes = Athlete::all();
        return view('admin.athletes.index', compact('athletes'));
    }

    public function createAthlete()
    {
        return view('admin.athletes.create');
    }

    public function storeAthlete(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'region' => 'required|string|max:255',
            'passport_copy' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $athlete = new Athlete();
        $athlete->name = $request->name;
        $athlete->family_name = $request->family_name;
        $athlete->gender = $request->gender;
        $athlete->date_of_birth = $request->date_of_birth;
        $athlete->region = $request->region;

        if ($request->hasFile('passport_copy')) {
            $athlete->passport_copy = $request->file('passport_copy')->store('passport_copies', 'public');
        }

        $athlete->save();

        return redirect()->route('admin.athletes')->with('success', 'Athlete created successfully.');
    }

    public function editAthlete($id)
    {
        $athlete = Athlete::findOrFail($id);
        return view('admin.athletes.edit', compact('athlete'));
    }

    public function updateAthlete(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'region' => 'required|string|max:255',
            'passport_copy' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $athlete = Athlete::findOrFail($id);
        $athlete->name = $request->name;
        $athlete->family_name = $request->family_name;
        $athlete->gender = $request->gender;
        $athlete->date_of_birth = $request->date_of_birth;
        $athlete->region = $request->region;

        if ($request->hasFile('passport_copy')) {
            // Delete old passport copy if exists
            if ($athlete->passport_copy) {
                \Storage::disk('public')->delete($athlete->passport_copy);
            }
            $athlete->passport_copy = $request->file('passport_copy')->store('passport_copies', 'public');
        }

        $athlete->save();

        return redirect()->route('admin.athletes')->with('success', 'Athlete updated successfully.');
    }

    public function destroyAthlete($id)
    {
        $athlete = Athlete::findOrFail($id);
        // Delete passport copy if exists
        if ($athlete->passport_copy) {
            \Storage::disk('public')->delete($athlete->passport_copy);
        }
        $athlete->delete();

        return redirect()->route('admin.athletes')->with('success', 'Athlete deleted successfully.');
    }
}