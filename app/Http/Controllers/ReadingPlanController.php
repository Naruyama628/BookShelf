<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadingPlanController extends Controller
{
    //

    public function index(Request $request) : View
    {
        $currentStatus = 'reading';
        $readingPlans = collect([

        ]);
        return view('reading-plans.index', compact('currentStatus', 'readingPlans'));
    }

    public function create(Request $request) : View
    {
        $currentStatus = 'reading';
        return view('reading-plans.index', compact('currentStatus'));
    }
}
