<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $memos = Auth::user()->memos;
        return view('memo.index', compact('memos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content'  => ['required', 'string'],
            'priority' => ['required', 'integer', 'in:1,2,3'],
            'deadline' => ['required', 'date'],
            'file'     => ['nullable', 'file'],
        ]);

        // 画像アップロード
        $path = $request->file('file')->store('memo_images', 'public');

        $request->merge([
            'user_id' => Auth::id(),
            'path'    => $path,
        ]);


        Memo::create($request->all());
        return redirect()->route('memo.index')->with('status', 'メモを作成しました！');
    }

    /**
     * Display the specified resource.
     */
    public function show(Memo $memo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Memo $memo)
    {
        return view('memo.edit', compact('memo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Memo $memo)
    {
        // バリデーション
        $request->validate([
            'content'  => ['required', 'string'],
            'priority' => ['required', 'integer', 'in:1,2,3'],
            'deadline' => ['required', 'date'],
        ]);

        $memo->content = $request->input('content'); // $request->contentは予約語で使用できないためinput()を使用
        $memo->priority = $request->priority;
        $memo->deadline = $request->deadline;
        $memo->save();
        return redirect()->route('memo.index')->with('status', 'メモを更新しました！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Memo $memo)
    {
        $memo->delete();
        return redirect()->route('memo.index')->with('status', 'メモを削除しました！');
    }
}
