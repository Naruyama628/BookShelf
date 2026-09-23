<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;
use App\Http\Requests\StoreReadingPlanRequest;

class ReadingPlanController extends Controller
{
    //

    public function index(Request $request) : View
    {
        $currentStatus = $request->status;
        
        $query = ReadingPlan::where('user_id', auth()->user()->id);
        if($request->status != null)
        {
            $query->where('status', $request->status);
        }
        $readingPlans = $query->get();

        return view('reading-plans.index', compact('currentStatus', 'readingPlans'));
    }

    public function create() : View
    {
        $books = Book::All();
        return view('reading-plans.create', compact('books'));
    }

    public function store(StoreReadingPlanRequest $request) : RedirectResponse
    {
        ReadingPlan::create([
            'user_id' => auth()->user()->id,
            'book_id' => $request->book_id,
            'target_date' => $request->target_date,
        ]);
        return redirect()->route('reading-plans.index');
    }

    public function edit(ReadingPlan $plan) : View
    {
        $this->authorize('update', $plan);

        $readingPlan = $plan;
        return view('reading-plans.edit', compact('readingPlan'));
    }

    public function update(ReadingPlan $plan, Request $request) : RedirectResponse
    {
        $this->authorize('update', $plan);
        
        $plan->update([
            'target_date' => $request->target_date,
        ]);

        return redirect()->route('reading-plans.index');
    }

    public function complete(ReadingPlan $plan) : RedirectResponse
    {
        $this->authorize('update', $plan);

        $plan->update([
            'status' => ReadingPlanStatus::Completed,
            'completed_at' => now(),
        ]);

        return redirect()->route('reading-plans.index');
    }

    public function destroy(ReadingPlan $plan) : RedirectResponse
    {
        $this->authorize('update', $plan);

        $plan->delete();

        return redirect()->route('reading-plans.index');
    }
}
