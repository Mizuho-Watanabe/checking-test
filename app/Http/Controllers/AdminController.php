<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Tag;
use App\Models\Category;

class AdminController extends Controller
{
    /**
     * 管理者画面の表示(一覧表示)
     */
    public function index()
    {
        $contacts = Contact::orderBy('created_at')->paginate(7);
        $categories = Category::orderBy('id')->get();
        $tags = Tag::orderBy('id')->get();

        return view('admin.index', compact('contacts', 'categories', 'tags'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact->load('category', 'tag');

        return view('admin.show', compact('comtact', 'category', 'tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
