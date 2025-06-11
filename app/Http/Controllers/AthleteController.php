<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Athlete;
use Illuminate\Support\Facades\DB;  
use App\Exports\AthletesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AthleteController extends Controller
{
    public function index()
{
   

    if (auth()->user()->isAdmin()) {
        $athletes = Athlete::all();
    } else {
        
        $athletes = Athlete::where('region', auth()->user()->region)->get();
    }

    return view('dashboard', compact('athletes'));
}



public function store(Request $request)
{
    $request->validate([
        'gender' => 'required|string|max:10',
        'family_name' => 'required|string|max:255',
        'given_name' => 'required|string|max:255',
        'date_of_birth' => 'required|date',
        'nation' => 'required|string|max:255',
        'region' => 'required|string|max:255',
        'adams_id' => 'required|string|max:255|unique:athletes',
        'picture' => 'nullable|image|max:2048', // Ensure it's an image and < 2MB
        'id_card_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $data = $request->all();

    // Handle image upload
    if ($request->hasFile('picture')) {
        $data['picture'] = $request->file('picture')->store('pictures', 'public');
    }
    if ($request->hasFile('id_card_picture')) {
        $data['id_card_picture'] = $request->file('id_card_picture')->store('pictures', 'public');
    }
    
    if ($request->hasFile('certificate')) {
        $data['certificate'] = $request->file('certificate')->store('pictures', 'public');
    }
    Athlete::create($data);

    return redirect()->back()->with('success', 'Athlete added successfully!');
}
public function edit($id)
{
    $athlete = Athlete::findOrFail($id);
    return view('admin.athletes.edit', compact('athlete'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'gender' => 'required|string|max:10',
        'family_name' => 'required|string|max:255',
        'given_name' => 'required|string|max:255',
        'date_of_birth' => 'required|date',
        'nation' => 'required|string|max:255',
        'region' => 'required|string|max:255',
        'adams_id' => 'required|string|max:255|unique:athletes,adams_id,' . $id,
        'picture' => 'nullable|image|max:2048', // Optional image upload
        'id_card_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        
    ], ['picture.max' => 'Picture must be less than 2MB.',
    'adams_id.unique' => 'This Adams ID already exists!',]);

    $athlete = Athlete::findOrFail($id);
    $data = $request->all();

    // Handle picture update
    if ($request->hasFile('picture')) {
        $data['picture'] = $request->file('picture')->store('pictures', 'public');
    }
    if ($request->hasFile('id_card_picture')) {
        $data['id_card_picture'] = $request->file('id_card_picture')->store('pictures', 'public');
    }
    
    if ($request->hasFile('certificate')) {
        $data['certificate'] = $request->file('certificate')->store('pictures', 'public');
    }

    $athlete->update($data);

    return redirect()->route('dashboard')->with('success', 'Athlete updated successfully!');
}


    public function destroy($id)
{
    // Find and delete the athlete
    Athlete::findOrFail($id)->delete();

    // Reorder the IDs
    $athletes = Athlete::orderBy('id')->get();
    $counter = 1;

    foreach ($athletes as $athlete) {
        $athlete->id = $counter;
        $athlete->save();
        $counter++;
    }

    // Reset auto-increment to max ID + 1
    DB::statement("ALTER TABLE athletes AUTO_INCREMENT = " . ($counter));

    return redirect()->route('dashboard')->with('success', 'Athlete deleted successfully!');
}
public function export($format)
{
    $user = auth()->user();

    // If the user is an admin, export all athletes
    if ($user->isAdmin()) {
        $athletes = Athlete::all();
    } else {
        // If the user is regional, export only athletes from their region
        $athletes = Athlete::where('region', $user->region)->get();
    }

    if ($format === 'excel') {
        return Excel::download(new AthletesExport($athletes), 'athletes.xlsx');
    } elseif ($format === 'pdf') {
        $pdf = Pdf::loadView('admin.athletes.pdf', compact('athletes'));
        return $pdf->stream('athletes.pdf');
    }

    return back()->with('error', 'Invalid export format');
}

public function updateName(Request $request, $id)
{
    $athlete = Athlete::findOrFail($id);

    // Validate input
    $request->validate([
        'family_name' => 'required|string|max:255',
        'given_name' => 'required|string|max:255',
    ]);

    // Update only family_name and given_name
    $athlete->update([
        'family_name' => $request->family_name,
        'given_name' => $request->given_name,
    ]);

    // Check if the request is AJAX
    if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Athlete name updated successfully!']);
    }

    return redirect()->back()->with('success', 'Athlete name updated successfully!');
}


}
