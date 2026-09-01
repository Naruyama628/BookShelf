<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Genre;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;

class GenreController extends Controller
{
    // ジャンル一覧画面
    public function index()
    {
        $genres = Genre::all();
        $genres->loadCount('books');
        return view('genres.index', compact('genres'));
    }

    // ジャンル登録画面
    public function create()
    {
        //
        return view('genres.create');
    }

    // ジャンル登録処理
    public function store(StoreGenreRequest $request)
    {
        //
        Genre::create([
            'name' => $request->name,
        ]);

        return redirect()->route('genres.index')
            ->with('success', 'ジャンルを登録しました');
    }

    // ジャンルに紐づけられた書籍一覧
    public function show(Genre $genre)
    {
        //
        $books = $genre->books()->paginate(10);
        return view('genres.show', compact('genre', 'books'));
    }

    // ジャンル更新画面
    public function edit(Genre $genre)
    {
        //
        return view('genres.edit', compact('genre'));
    }

    // ジャンル更新処理
    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        //
        $genre->update([
            'name' => $request->name,
        ]);

        return redirect()->route('genres.index')
            ->with('success', 'ジャンルを更新しました');
    }

    // ジャンル削除処理
    public function destroy(Genre $genre)
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
