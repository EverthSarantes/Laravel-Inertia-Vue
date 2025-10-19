<?php

namespace App\Http\Controllers\AdministrationApp\Reports;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ReportsController extends Controller
{
    public function index()
    {
        return Inertia::render('administration_app.reports.index', [
            'config' => config('reporting.models'),
        ]);
    }
}
