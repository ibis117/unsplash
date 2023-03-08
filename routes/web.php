<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/', function (\Illuminate\Http\Request $request) {
    $title = $request->get('title', 'furniture');
    $color = $request->get('color', null);
    $response = Http::withHeaders([
        'Authorization' => 'Client-ID eFZ5_ObExjM2ys0sGZWdbLmpka8YdEDqeKEMqwBCXGw'
    ])
    ->get('https://api.unsplash.com/search/photos', [
        'query' => $title,
        'color' => $color
    ]);

    $data = $response->object();
    $colors = [
        'black_and_white',
        'black',
        'white',
        'yellow',
        'orange',
        'red',
        'purple',
        'magenta',
        'teal',
        'blue',
    ];

    return inertia('Unsplash', compact('data', 'title', 'color', 'colors'));
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
