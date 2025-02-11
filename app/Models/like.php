<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class like extends Model
{
    protected $table = 'likes';
    protected $fillable = ['id', 'user_id', 'item_id'];
    public function item():BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
