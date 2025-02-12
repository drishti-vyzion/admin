<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class ItemVarient extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = 'item_varients';
    protected $primarykey = 'id';
    protected $fillable = ['color', 'size', 'fabric', 'price'];
    public function item():BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

