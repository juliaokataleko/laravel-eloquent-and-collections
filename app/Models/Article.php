<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use HasFactory, Searchable;
    protected $fillable = [
        "user_id",
        "title",
        "excerpt",
        "slug",
        "description",
        "min_to_read"
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function toSearchableArray()
    {
        return [
            "title" => $this->title,
            "slug" => $this->slug,
            "excerpt" => $this->excerpt,
            "description" => $this->description,
            "created_at" => $this->created_at
        ];
    }
}
