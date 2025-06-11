<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\Athlete;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CompetitionAthletesExport;
use App\Exports\AthletesExport;

class CompetitionController extends Controller
{
    // Show competition creation form (Admin only)
    public function create()
    {
        abort(500, 'This method is being executed!');
        return view('competitions.create');
    }
    
    // Store competition in the database (Admin only)
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'date' => 'required|date',
                'location' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = [
                'name' => $request->name,
                'date' => $request->date,
                'location' => $request->location,
            ];

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('competition_images', 'public');
                $data['image'] = $path;
            }

            Competition::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Competition created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Show all competitions
    public function index()
    {
        $competitions = Competition::all();
        $userRegion = auth()->user()->region;
    
        // Fetch athletes from the user's region
        $athletes = Athlete::where('region', $userRegion)->get();
    
        $registeredAthletes = []; // Initialize empty array

        // If there's a specific competition being viewed, get its registered athletes
        if (request()->has('competition_id')) {
            $competition = Competition::find(request('competition_id'));
            if ($competition) {
                // Only get athletes registered for this specific competition
                $registeredAthletes = $competition->athletes()
                    ->where('region', $userRegion)
                    ->where('competition_id', request('competition_id'))
                    ->pluck('athletes.id')
                    ->toArray();
            }
        }
    
        return view('competitions.index', compact('competitions', 'athletes', 'registeredAthletes'));
    }
    

    // Show a single competition with registered athletes
    public function show($competitionId)
    {
        $competition = Competition::findOrFail($competitionId);
        $userRegion = auth()->user()->region;
        $isAdmin = auth()->user()->roles->contains('name', 'admin');

        // Get all athletes from the user's region
        $athletes = Athlete::where('region', $userRegion)->get();

        // Get registered athletes based on user role
        if ($isAdmin) {
            // Admin can see all registered athletes
            $registeredAthletes = $competition->athletes()->get();
        } else {
            // Regional users can only see their region's athletes
            $registeredAthletes = $competition->athletes()
                ->where('region', $userRegion)
                ->get();
        }

        return view('competitions.show', compact('competition', 'athletes', 'registeredAthletes', 'isAdmin'));
    }

   
    // Register an athlete to a competition (Regional accounts only)
    public function registerAthlete(Request $request, $competitionId)
    {
        $competition = Competition::findOrFail($competitionId);
        $userRegion = auth()->user()->region;
    
        // Validate request
        $request->validate([
            'athlete_id' => 'required|exists:athletes,id',
            'category' => 'required|string',
            'entry_total' => 'required|integer',
            'reserve' => 'required|string'
        ]);
    
        // Check if athlete belongs to user's region
        $athlete = Athlete::findOrFail($request->athlete_id);
        if ($athlete->region !== $userRegion) {
            return back()->with('error', 'You can only register athletes from your region.');
        }
    
        // Check if athlete is already registered
        if ($competition->athletes()->where('athletes.id', $request->athlete_id)->exists()) {
            return back()->with('error', 'Athlete is already registered in this competition.');
        }
    
        // Register athlete with extra details
        $competition->athletes()->attach($request->athlete_id, [
            'category' => $request->category,
            'entry_total' => $request->entry_total,
            'reserve' => $request->reserve
        ]);
    
        return back()->with('success', 'Athlete registered successfully!');
    }
    
    
    
public function update(Request $request, Competition $competition)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'date' => 'required|date',
        'location' => 'nullable|string|max:255',
    ]);

    $competition->update([
        'name' => $request->name,
        'date' => $request->date,
        'location' => $request->location,
    ]);

    return redirect()->route('competitions.index')->with('success', 'Competition updated successfully!');
}

public function destroy(Competition $competition)
{
    $competition->delete();
    return redirect()->route('competitions.index')->with('success', 'Competition deleted successfully!');
}


public function removeAthlete($competitionId, $athleteId)
{
    $competition = Competition::findOrFail($competitionId);

    // Detach the athlete from the competition
    $competition->athletes()->detach($athleteId);

    return redirect()->back()->with('success', 'Athlete removed successfully.');
}
public function editAthlete(Request $request, $competitionId, $athleteId)
{
    $competition = Competition::findOrFail($competitionId);
    $athlete = Athlete::findOrFail($athleteId);

    // Update athlete details
    $athlete->update([
        'family_name' => $request->family_name,
        'given_name' => $request->given_name,
        'region' => $request->region,   
    ]);

    return redirect()->back()->with('success', 'Athlete details updated successfully.');
}
public function deleteAthlete(Competition $competition, Athlete $athlete)
{
    // Remove the athlete from the competition
    $competition->athletes()->detach($athlete->id);

    return redirect()->back()->with('success', 'Athlete removed successfully from this competition.');
}

public function export(Competition $competition, $format)
{
    $athletes = $competition->athletes()->get();
    
    if ($format === 'excel') {
        return Excel::download(new CompetitionAthletesExport($athletes, $competition), 'competition-athletes.xlsx');
    }
    
    // Set timezone to Uzbekistan
    date_default_timezone_set('Asia/Tashkent');
    
    // Get the absolute path to the image
    $imagePath = null;
    if ($competition->image) {
        $imagePath = storage_path('app/public/' . $competition->image);
    }
    
    // For PDF or other formats
    $data = [
        'competition' => $competition,
        'imagePath' => $imagePath,
        'athletes' => $athletes->map(function($athlete) {
            return [
                'name' => $athlete->given_name . ' ' . $athlete->family_name,
                'adams_id' => strtoupper($athlete->adams_id),
                'category' => $athlete->pivot->category,
                'entry_total' => $athlete->pivot->entry_total,
                'reserve' => $athlete->pivot->reserve ? 'Yes' : 'No',
                'exported_at' => now()->format('Y-m-d H:i:s')
            ];
        })
    ];
    
    $pdf = PDF::loadView('exports.competition-athletes', $data);
    return $pdf->download('competition-athletes.pdf');
}
 
}


