    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\AthleteController;
    use App\Http\Controllers\CompetitionController;
    use App\Http\Controllers\MonitoringController;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Http\Request;
    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |32
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "web" middleware group. Make something great!
    |
    */

    Route::view('/', 'welcome')->name('welcome');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    // Custom admin routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AthleteController::class, 'index'])->name('dashboard');
        Route::post('/athletes', [AthleteController::class, 'store'])->name('athletes.store');
        Route::get('/admin/athletes/{id}/edit', [AthleteController::class, 'edit'])->name('athletes.edit');

        Route::put('/athletes/{id}', [AthleteController::class, 'update'])->name('athletes.update');
        Route::delete('/athletes/{id}', [AthleteController::class, 'destroy'])->name('athletes.destroy');
        Route::get('/athletes/export/{format}', [AthleteController::class, 'export'])->name('athletes.export');
       

    });
    

    Route::middleware(['auth'])->group(function () {
        // General competition routes (All authenticated users)
        Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');
        Route::get('/competitions/{id}', [CompetitionController::class, 'show'])->name('competitions.show');
        Route::post('/competitions/{id}/register', [CompetitionController::class, 'registerAthlete'])->name('competitions.register');
        Route::get('/competitions/{competition}/export/{format}', [CompetitionController::class, 'export'])
            ->name('competitions.export');
        
        // Monitoring route
        Route::middleware(['admin'])->group(function () {
            Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        });
    
        // Admin-only routes
        Route::middleware(['admin'])->group(function () {
            Route::get('/competitions/create', [CompetitionController::class, 'create'])->name('competitions.create');
            Route::post('/competitions', [CompetitionController::class, 'store'])->name('competitions.store');
            Route::get('/competitions/{competition}/edit', [CompetitionController::class, 'edit'])->name('competitions.edit');
            Route::put('/competitions/{competition}', [CompetitionController::class, 'update'])->name('competitions.update');
            Route::delete('/competitions/{competition}', [CompetitionController::class, 'destroy'])->name('competitions.destroy');
            Route::delete('/competitions/{competition}/athlete/{athlete}', [CompetitionController::class, 'removeAthlete'])->name('competitions.athlete.delete');
            Route::post('/competitions/{competition}/athlete/{athlete}/edit', [CompetitionController::class, 'editAthlete'])->name('competitions.athlete.edit');
            Route::put('/athletes/{id}/update-name', [AthleteController::class, 'updateName'])->name('athletes.updateName');
            Route::delete('/competitions/{competition}/athletes/{athlete}/delete', [CompetitionController::class, 'deleteAthlete'])
                ->name('competitions.athlete.delete');
        });
    });
    Route::get('lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'ru', 'uz'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
        return redirect()->back();
    })->name('switchLang');
    // Include Breeze authentication routes
    require __DIR__.'/auth.php';