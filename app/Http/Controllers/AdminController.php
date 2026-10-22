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
    public function index(Request $request)
    {
        //検索機能
        $keyword = $request->input('keyword');
    
        //データの取得
        $contacts = Contact::when($keyword, function ($query, $keyword) {
            return $query->where('first_name', 'like', "%{$keyword}%")
                ->orwhere('last_name', 'like', "%{$keyword}%")
                ->orwhere('email', 'like', "%{$keyword}%");
        })
        ->orderBy('id')
        ->paginate(7);
        
        $categories = Category::orderBy('id')->get();
        $tags = Tag::orderBy('id')->get();

        return view('admin.index', compact('contacts', 'categories', 'tags', 'keyword'));

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
