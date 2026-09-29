<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;
use App\Http\Requests\StoreReadingPlanRequest;
use App\Http\Requests\UpdateReadingPlanRequest;

class ReadingPlanController extends Controller
{
    /**
     * 読書計画一覧画面表示
     *
     * @param Request $request 絞り込み用のステータス
     * @return View 読書計画一覧画面
     */
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

    /**
     * 読書計画登録画面表示
     *
     * @return View 読書計画作成画面
     */
    public function create() : View
    {
        $books = Book::All();
        return view('reading-plans.create', compact('books'));
    }

    /**
     * 読書計画登録処理
     *
     * @param StoreReadingPlanRequest $request 読書計画登録データ
     * @return RedirectResponse 読書計画一覧画面
     */
    public function store(StoreReadingPlanRequest $request) : RedirectResponse
    {
        ReadingPlan::create([
            'user_id' => auth()->user()->id,
            'book_id' => $request->book_id,
            'target_date' => $request->target_date,
        ]);
        return redirect()->route('reading-plans.index');
    }

    /**
     * 読書計画編集画面表示
     *
     * @param ReadingPlan $plan 編集する読書計画
     * @return RedirectResponse 読書計画編集画面
     */
    public function edit(ReadingPlan $plan) : View
    {
        $this->authorize('update', $plan);

        $readingPlan = $plan;
        return view('reading-plans.edit', compact('readingPlan'));
    }

    /**
     * 読書計画編集処理
     *
     * @param ReadingPlan $plan 編集する読書計画
     * @param UpdateReadingPlanRequest $request 編集後の読書計画のデータ
     * @return RedirectResponse 読書計画一覧画面
     */
    public function update(ReadingPlan $plan, UpdateReadingPlanRequest $request) : RedirectResponse
    {
        $this->authorize('update', $plan);
        
        $plan->update([
            'target_date' => $request->target_date,
        ]);

        return redirect()->route('reading-plans.index');
    }

    /**
     * 読書計画読了処理
     *
     * @param ReadingPlan $plan 読了した読書計画
     * @return RedirectResponse 読書計画一覧画面
     */
    public function complete(ReadingPlan $plan) : RedirectResponse
    {
        $this->authorize('update', $plan);

        $plan->update([
            'status' => ReadingPlanStatus::Completed,
            'completed_at' => now(),
        ]);

        return redirect()->route('reading-plans.index');
    }

    /**
     * 読書計画削除処理
     *
     * @param ReadingPlan $plan 削除する読書計画
     * @return RedirectResponse 読書計画一覧画面
     */
    public function destroy(ReadingPlan $plan) : RedirectResponse
    {
        $this->authorize('delete', $plan);

        $plan->delete();

        return redirect()->route('reading-plans.index');
    }
}
