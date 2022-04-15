<?php

namespace App\Http\Controllers;

use App\Models\Car;

use App\Repositories\CarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class CarController extends Controller
{
    public function __construct(Car $car) {
        $this->car = $car;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $carRepository = new CarRepository($this->car);

        if($request->has('type_atributes')) {
            $type_atribute = 'type:id,'.$request->type_atributes;
            $carRepository->selectRelationship($type_atribute);
        } else {
            $carRepository->selectRelationship('type');
        }

        if($request->has('filter')) {
            $carRepository->filter($request->filter);
        }

        if($request->has('atributes')) {
            $carRepository->selectAtributes($request->atributes.',type_id');
        }

        return response()->json($carRepository->getResult(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->car->rules());

        $car = $this->car->create([
            'type_id' => $request->type_id,
            'license_plate' => $request->license_plate,
            'available' => $request->available,
            'km' => $request->km
        ]);

        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\car  $car
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = $this->car->with('type')->find($id);
        if($car === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        }

        return response()->json($car, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $car = $this->car->find($id);

        if($car === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $dinamicsRules = array();

            //percorrendo todas as rules definidas no Model
            foreach($car->rules() as $input => $rule) {

                //coletar apenas as rules aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $dinamicsRules[$input] = $rule;
                }
            }

            $request->validate($dinamicsRules);

        } else {
            $request->validate($car->rules());
        }

        $car->fill($request->all());
        $car->save();

        return response()->json($car, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);

        if($car === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $car->delete();
        return response()->json(['msg' => 'O car foi removido com sucesso!'], 200);

    }
}
