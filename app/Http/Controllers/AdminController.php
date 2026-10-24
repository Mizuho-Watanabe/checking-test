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
        /**検索機能
        *ユーザーが入力したものはバリデーションしなきゃいけないことは分かりますがやり方が分かりませんでした　未実装です
        */
        $keyword = $request->input('keyword');
    
        //データの取得
        $contacts = Contact::query()
            //キーワード検索
            ->when($keyword, function ($query, $keyword) {
            return $query->where('first_name', 'like', "%{$keyword}%")
                ->orwhere('last_name', 'like', "%{$keyword}%")
                ->orwhere('email', 'like', "%{$keyword}%");
            })
            //性別フィルター
            ->when($request->filled('gender'), function ($query) use ($request) {
                $query->where('gender', $request->input('gender'));
            })
            //カテゴリーフィルター
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->input('category_id'));
            })
            //日付フィルター
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->input('date'));
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
