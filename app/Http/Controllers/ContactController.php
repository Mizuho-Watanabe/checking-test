<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * フォーム画面の表示
     */
    public function index()
    {
        $categories = Category::orderBy('id')->get();
        return view('contact.index', compact('categories'));
    }

    //お問い合わせの新規作成
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        Contact::create($validated);

        return redirect('/thanks');
    }

    //確認画面の表示
    public function show(StoreContactRequest $request, Contact $contact, Category $category)
    {
        $validated = $request->validated();

        return view('contact.confirm', compact('validated', 'category'));
    }
}
