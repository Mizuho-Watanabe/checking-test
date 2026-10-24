<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactController extends Controller
{
    /**
     * フォーム画面の表示
     */
    public function index()
    {
        $categories = Category::orderBy('id')->get();
        $tags = Tag::orderBy('id')->get();
        return view('contact.index', compact('categories', 'tags'));
    }

    //お問い合わせの新規作成
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        //contactテーブルにはtagを直接入力できないため、tagのデータを除外する
        $contactData = collect($validated)->except('tag_ids')->toArray();
        $contact = Contact::create($contactData);

        //除外したtagデータを紐づける
        if ($request->has('tag_ids')) {
            $contact->tags()->sync($request->input('tag_ids'));
        }

        return redirect('/thanks');
    }

    //確認画面の表示
    public function show(StoreContactRequest $request)
    {
        $validated = $request->validated();

        //送られたcategory_idやtag_idからそれぞれカテゴリー・タグを取得
        $category = Category::find($validated['category_id']);
        $tags = collect();
        if (!empty($validated['tag_ids'])) {
            $tags = Tag::whereIn('id', $validated['tag_ids'])->get();
        }

        return view('contact.confirm', compact('validated', 'category', 'tags'));
    }
}
