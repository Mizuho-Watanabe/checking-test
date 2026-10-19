<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * フォーム画面
     */
    public function index()
    {
        $categories = Category::orderBy('id')->get();
        return view('contact.index', compact('categories'));
    }

    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        Contact::create($validated);

        return redirect()->route('contact.thanks');

    }
}
