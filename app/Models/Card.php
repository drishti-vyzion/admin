<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $table = 'addcards';
    protected $fillable = ['id', 'user_id', 'item_id', 'quantity'];
    public function item():BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
