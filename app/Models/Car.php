<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['type_id', 'license_plate', 'available', 'km'];

    public function rules() {
        return [
            'type_id' => 'exists:types,id',
            'license_plate' => 'required',
            'available' => 'required',
            'km' => 'required'
        ];
    }

    public function type() {
        return $this->belongsTo('App\Models\Type');
    }
}
