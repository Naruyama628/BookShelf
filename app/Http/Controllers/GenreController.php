<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Genre;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;

class GenreController extends Controller
{
    /**
     * ジャンル一覧表示
     *
     * @return View ジャンル一覧画面
     */
    public function index(): View
    {
        $genres = Genre::all();
        $genres->loadCount('books');
        return view('genres.index', compact('genres'));
    }

    /**
     * ジャンル登録画面表示
     *
     * @return View ジャンル登録画面
     */
    public function create(): View
    {
        //
        return view('genres.create');
    }

    /**
     * ジャンル登録処理
     *
     * @param StoreGenreRequest $request ジャンル登録の為のデータ
     * @return RedirectResponse ジャンル一覧画面
     */
    public function store(StoreGenreRequest $request): RedirectResponse
    {
        //
        Genre::create([
            'name' => $request->name,
        ]);

        return redirect()->route('genres.index')
            ->with('success', 'ジャンルを登録しました');
    }

    /**
     * ジャンルに紐づけられた書籍一覧を表示
     *
     * @param Genre $genre 
     * @return View ジャンルに紐づけられた書籍一覧画面
     */
    public function show(Genre $genre): View
    {
        //
        $books = $genre->books()->paginate(10);
        return view('genres.show', compact('genre', 'books'));
    }

    /**
     * ジャンル更新画面を表示
     *
     * @param Genre $genre 更新するジャンル
     * @return View ジャンル更新画面
     */
    public function edit(Genre $genre): View
    {
        //
        return view('genres.edit', compact('genre'));
    }

    /**
     * ジャンル更新処理
     *
     * @param UpdateGenreRequest $request 更新後のデータ
     * @param Genre $genre 更新するジャンル
     * @return RedirectResponse ジャンル一覧画面
     */
    public function update(UpdateGenreRequest $request, Genre $genre): RedirectResponse
    {
        //
        $genre->update([
            'name' => $request->name,
        ]);

        return redirect()->route('genres.index')
            ->with('success', 'ジャンルを更新しました');
    }

    /**
     * ジャンル削除処理
     *
     * @param Genre $genre 削除するジャンル
     * @return RedirectResponse ジャンル一覧画面
     */
    public function destroy(Genre $genre): RedirectResponse
    {
        //
        if ($genre->books()->exists()) {
            return redirect()->route('genres.index')
                ->with('error', 'このジャンルに紐づく書籍が存在するため、削除できません');
        }

        $genre->delete();
        return redirect()->route('genres.index')
            ->with('success', 'ジャンルを削除しました');
    }
}
