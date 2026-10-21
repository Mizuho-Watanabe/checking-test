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
        $contacts = Contact::orderBy('id')->paginate(7);
        $categories = Category::orderBy('id')->get();
        $tags = Tag::orderBy('id')->get();

        return view('admin.index', compact('contacts', 'categories', 'tags'));
    }

    /**
     * 詳細表示
     */
    public function show(Contact $contact)
    {
        $contact->load('category', 'tags');

        return view('admin.show', compact('contact'));
    }

    /**
     * 削除
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect('/admin')
            ->with('success', 'お問い合わせを削除しました');
    }
}
