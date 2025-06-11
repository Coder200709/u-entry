<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index()
    {
        // Get total athletes count
        $totalAthletes = Athlete::count();
        
        // Get athletes by region
        $athletesByRegion = Athlete::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->get();
            
        // Get athletes by gender
        $athletesByGender = Athlete::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();
            
        // Get registrations by day
        $registrationsByDay = DB::table('athlete_competition')
            ->join('competitions', 'athlete_competition.competition_id', '=', 'competitions.id')
            ->select(DB::raw('DATE(competitions.date) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Get registrations by year
        $registrationsByYear = DB::table('athlete_competition')
            ->join('competitions', 'athlete_competition.competition_id', '=', 'competitions.id')
            ->select(DB::raw('YEAR(competitions.date) as year'), DB::raw('count(*) as total'))
            ->groupBy('year')
            ->orderBy('year')
            ->get();
            
        // Get registrations by category
        $registrationsByCategory = DB::table('athlete_competition')
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
            
        // Get top regions by registrations
        $topRegions = DB::table('athlete_competition')
            ->join('athletes', 'athlete_competition.athlete_id', '=', 'athletes.id')
            ->select('athletes.region', DB::raw('count(*) as total'))
            ->groupBy('athletes.region')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
            
        // Get competitions by year
        $competitionsByYear = Competition::select(DB::raw('YEAR(date) as year'), DB::raw('count(*) as total'))
            ->groupBy('year')
            ->orderBy('year')
            ->get();
            
        // Get average entry totals by category
        $avgEntryTotals = DB::table('athlete_competition')
            ->select('category', DB::raw('AVG(entry_total) as average'))
            ->groupBy('category')
            ->get();

        return view('monitoring.index', compact(
            'totalAthletes',
            'athletesByRegion',
            'athletesByGender',
            'registrationsByDay',
            'registrationsByYear',
            'registrationsByCategory',
            'topRegions',
            'competitionsByYear',
            'avgEntryTotals'
        ));
    }
} 