<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DocsController extends Controller
{
    /**
     * Show the documentation index page.
     */
    public function index(): View
    {
        return view('docs.index');
    }

    /**
     * Show the quick start guide.
     */
    public function quickstart(): View
    {
        return view('docs.quickstart');
    }

    /**
     * Show the full documentation.
     */
    public function documentation(): View
    {
        return view('docs.documentation');
    }
}
