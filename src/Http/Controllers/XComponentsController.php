<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class XComponentsController extends Controller
{
    /**
     * Display the x-components demo page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('tyro-dashboard-components::x-components');
    }
}
