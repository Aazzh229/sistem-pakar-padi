<?php

use Illuminate\Support\Facades\Route;
use App\Services\ExpertSystemService;

Route::get('/next-test', function () {

    $service =
        new ExpertSystemService();

    return $service
        ->getNextSymptoms([
            'G01'
        ]);
});