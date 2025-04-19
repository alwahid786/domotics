<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRoom extends Model
{
    protected $fillable = ['product_id','room_id'];
    protected $table = 'product_room';

    public function quotations()
    {
        return $this->belongsToMany(Quotation::class)->withPivot('quantity', 'price')->withTimestamps();
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
