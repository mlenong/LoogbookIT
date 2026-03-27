<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lookbook;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Default redirect
Route::get('/', function () {
    return redirect()->route('lookbook.index');
});

// Signature Endpoint - public (HP scans QR, no login needed)
Route::get('/lookbook/sign/{id}', [Lookbook::class, 'signForm'])->name('lookbook.sign');
Route::post('/lookbook/sign/{id}', [Lookbook::class, 'signSave'])->name('lookbook.sign.save');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('lookbook', Lookbook::class)->except(['show', 'create', 'edit']);
    Route::get('/lookbook/{id}/json', [Lookbook::class, 'getJson'])->name('lookbook.json');
    Route::get('/lookbook-data', [Lookbook::class, 'dataTable'])->name('lookbook.data');

    Route::resource('users', UserController::class);
    Route::resource('units', App\Http\Controllers\UnitController::class);

    // API Route for Unit dropdown - Proxy to external API (avoids CORS)
    Route::get('/api/units', function (\Illuminate\Http\Request $request) {
        try {
            // Fetch from external API
            $response = file_get_contents('http://172.16.15.25/api_reresik/get_units.php');
            $data = json_decode($response, true);
            
            $searchTerm = strtolower($request->get('q', ''));
            $formatted = [];
            
            foreach ($data as $item) {
                $unitName = $item['unit'];
                // Filter locally if search term is provided
                if ($searchTerm === '' || str_contains(strtolower($unitName), $searchTerm)) {
                    $formatted[] = ['id' => $unitName, 'text' => $unitName];
                }
            }
            
            return response()->json($formatted);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    });

    // PDF Export Route
    Route::get('/lookbook-report/pdf', [Lookbook::class, 'reportPdf'])->name('lookbook.pdf');
});
