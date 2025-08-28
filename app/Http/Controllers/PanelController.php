<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
    /**
     * Display the main panel view.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('panel', [
            'userApps' => Auth::user()->userApps()
        ]);
    }
}
