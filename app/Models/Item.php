<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Item extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = 'items';
    protected $primarykey = 'id';
    protected $fillable = ['created_by', 'update_by', 'name', 'description', 'category_id', 'image', 'item_varients'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function like(): HasMany
    {
        return $this->hasMany(like::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    protected function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }
    protected function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }
    public function Orderitem()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function ItemVarient(): HasMany
    {
        return $this->hasMany(ItemVarient::class);
    }
}
