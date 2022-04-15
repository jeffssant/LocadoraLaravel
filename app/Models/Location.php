<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'car_id',
        'start_date',
        'expected_end_date',
        'end_date',
        'daily_rate',
        'km_start',
        'km_end' ,
    ];

    public function rules() {
        return [];
    }

    public function car() {
        return $this->belongsTo('App\Models\Car');
    }

    public function client() {
        return $this->belongsTo('App\Models\Client');
    }
}
