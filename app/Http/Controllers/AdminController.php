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
        *
        */
        $validated = $request->validate([
            'keyword' => 'nullable|string',
            'gender' => 'nullable|int|in:0,1,2,3',
            'category_id' => 'nullable|int|exists:categories,id',
            'date' => 'nullable|date',
        ]);
        
    
        //データの取得
        $keyword = $request->input('keyword');
        $contacts = Contact::query()
            //キーワード検索
            ->when($validated['keyword'] ?? null, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                ->orwhere('last_name', 'like', "%{$keyword}%")
                ->orwhere('email', 'like', "%{$keyword}%");
                });
            })
            //性別フィルター
            ->when($validated['gender'] ?? null, function ($query, $gender) {
                $query->where('gender', $gender);
            })
            //カテゴリーフィルター
            ->when($validated['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            //日付フィルター
            ->when($validated['date'] ?? null, function ($query, $date) {
                $query->whereDate('created_at', $date);
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
