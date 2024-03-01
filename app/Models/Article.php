<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;
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
}