<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PanelController extends Controller
{
    /**
     * Display the main panel view.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('panel');
    }
}
