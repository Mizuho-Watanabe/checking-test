<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
    ];

    /**
     * このcategoryに属するcontactを取得
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
