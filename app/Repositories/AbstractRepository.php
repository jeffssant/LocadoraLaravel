<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectRelationship($atributes) {
        $this->model = $this->model->with($atributes);
        //a query está sendo montada
    }

    public function filter($filters) {
        $filters = explode(';', $filters);

        foreach($filters as $key => $condition) {

            $c = explode(':', $condition);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
            //a query está sendo montada
        }
    }

    public function selectAtributes($atributes) {
        $this->model = $this->model->selectRaw($atributes.',id');
    }

    public function getResult() {
        return $this->model->get();
    }

    public function getPaginateResult($page) {
        return $this->model->paginate($page);
    }

}

?>
