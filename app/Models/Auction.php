<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Auction extends Model
{
    //
    use HasFactory;  

    protected $fillable = [  
        'car_name',
        'model',
        'description',
        'image_url',
        'passenger_capacity',
        'body_style',
        'cylinders',
        'color',
        'engine_type',
        'transmission',
        'vehicle_type',
        'fuel',
        'damage_description',
        'starting_price',
    ]; 
}
