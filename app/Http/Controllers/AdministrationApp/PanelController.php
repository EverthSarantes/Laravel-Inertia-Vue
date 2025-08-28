<?php

namespace App\Http\Controllers\AdministrationApp;

use Inertia\Inertia;
use App\Http\Controllers\Controller;

class PanelController extends Controller
{
    /**
     * Display the administration panel view.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('administration_app.panel');
    }
}
