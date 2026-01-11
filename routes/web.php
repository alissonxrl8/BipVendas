<?php

use Illuminate\Support\Facades\Route;

Route::get('/scanner', function () {
    return view('scanner');
});

Route::post('/scan', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'code' => $request->code
    ]);
});
