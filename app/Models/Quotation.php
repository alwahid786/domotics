<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = ['user_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price', 'product_room_id','product_id', 'note')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function total()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });
    }

    public function send()
    {
        Mail::to($this->user)->send(new SendQuotation($this));
    }

    public function pdf()
    {
        $pdf = PDF::loadView('quotations.pdf', ['quotation' => $this]);
        return $pdf->download('quotation.pdf');
    }

    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function priceByRole($role)
    {
        return $this->products->sum(function ($product) use ($role) {
            return $product->pivot->price * $product->pivot->quantity;
        });
    }
}
