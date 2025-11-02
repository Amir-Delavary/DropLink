<?php

namespace App\Models;

use App\Enums\FileTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $fillable = ['privacy'];

    protected $casts = [
        "privacy" => FileTypes::class,
    ];
    protected $hidden = [
        "id",
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
