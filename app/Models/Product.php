<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'image'];

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('price');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class)->withPivot('quantity','product_room_id')->withTimestamps();
    }

    public function priceByRole($user)
    {
        $role = $user->roles()->first();
        // dd($role);
        return $this->roles()->where('role_id', $role->id)->first()?->pivot->price;
    }
    /*{dd($role);
        dd($this->roles()->where('role_id', $role->id)->first()?->price);
        return $this->roles()->where('role_id', $role->id)->first()?->pivot->price;
    }*/


}
