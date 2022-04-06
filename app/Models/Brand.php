<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['image', 'name'];

    public function validBrandUpdateRules ($request, $id){

        if(array_key_exists('name', $request->all()) and $request->name === null){
            return response()->json(['error' => 'Name is required'], 422);
        }

        if(array_key_exists('image', $request->all()) and $request->image === null){
            return response()->json(['error' => 'Image is required'], 422);
        }

        return false;

    }

    public function validBrandStoreRules($request){

        if($request->name === null or $request->image === null){
            return response()->json(['error' => 'Name and image are required'], 422);
        }

        return false;

    }

}
