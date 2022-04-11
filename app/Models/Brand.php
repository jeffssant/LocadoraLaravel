<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['image', 'name'];

    public function rules() {
        return [
            'name' => 'required|unique:brands,name,'.$this->id.'|min:3',
            'image' => 'required|file|mimes:png'
        ];


    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'image.mimes' => 'O arquivo deve ser uma imagem do tipo PNG',
            'name.unique' => 'O nome da marca já existe',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres'
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

            $request->validate($dinamicRules , $this->feedback());

        } else {
            $request->validate($this->rules(), $this->feedback());
        }
    }

    public function types() {
        //UMA marca POSSUI MUITOS modelos
        return $this->hasMany('App\Models\Type');
    }

}
