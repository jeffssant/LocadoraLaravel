<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable = ['brand_id', 'name', 'image', 'doors', 'seats', 'air_bag', 'abs'];

    public function rules() {
        return [
            'brand_id' => 'exists:brands,id',
            'name' => 'required|unique:types,name,'.$this->id.'|min:3',
            'image' => 'required|file|mimes:png,jpeg,jpg',
            'doors' => 'required|integer|digits_between:1,5', //(1,2,3,4,5)
            'seats' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean' //true, false, 1, 0, "1", "0"
        ];
    }

    public function verifyPacth($request, $id) {
        $this->id = $id;
        if($request->method() === 'PATCH') {

            $dinamicRules = array();

            //percorrendo todas as regras definidas no Model
            foreach($this->rules() as $input => $rule) {
                //coletar apenas as rules aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $dinamicRules [$input] = $rule;
                }
            }

            $request->validate($dinamicRules);

        } else {
            $request->validate($this->rules());
        }
    }

    public function brand() {

        return $this->belongsTo('App\Models\Brand');
    }
}
