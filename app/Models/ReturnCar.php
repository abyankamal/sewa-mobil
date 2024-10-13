<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnCar extends Model
{
    use HasFactory;

    protected $table = 'return_cars';
    protected $fillable = ['rental_id', 'return_date', 'total_days', 'total_cost'];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
