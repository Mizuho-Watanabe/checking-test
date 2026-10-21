<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Models\Contact;
use App\Models\Tag;

class TagController extends Controller
{
    //タグの新規作成
    public function store(TagRequest $request)
    {
        Tag::create($request->validated());

        return redirect('/admin');
    }

    //タグの編集フォームを表示
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    //タグの更新
    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        return redirect('/admin');
    }

    //タグの削除
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect('/admin');

    }
}

