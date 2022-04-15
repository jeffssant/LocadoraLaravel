<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Repositories\LocationRepository;

class LocationController extends Controller
{
    public function __construct(Location $location) {
        $this->location = $location;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientAtribute = new LocationRepository($this->location);

        if($request->has('car_atributes')) {
            $type_atribute = 'car:id,'.$request->car_atributes;
            $clientAtribute->selectRelationship($type_atribute);
        } else {
            $clientAtribute->selectRelationship('car');
        }

        if($request->has('client_atributes')) {
            $type_atribute = 'client:id,'.$request->client_atributes;
            $clientAtribute->selectRelationship($type_atribute);
        } else {
            $clientAtribute->selectRelationship('client');
        }

        if($request->has('filter')) {
            $clientAtribute->filter($request->filter);
        }

        if($request->has('atributes')) {
            $clientAtribute->selectAtributes($request->atributes.',car_id');
        }

        return response()->json($clientAtribute->getResult(), 200);
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
     * @param  \App\Http\Requests\StoreLocationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->location->rules());

        $location = $this->location->create([
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'expected_end_date' => $request->expected_end_date,
            'end_date' => $request->end_date,
            'daily_rate' => $request->daily_rate,
            'km_start' => $request->km_start,
            'km_end' => $request->km_end
        ]);

        return response()->json($location, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = $this->location->find($id);
        if($location === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        }

        return response()->json($location, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocationRequest  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $location = $this->location->find($id);

        if($location === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $dinamicsRules = array();

            //percorrendo todas as rules definidas no Model
            foreach($location->rules() as $input => $rule) {

                //coletar apenas as rules aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $dinamicsRules[$input] = $rule;
                }
            }

            $request->validate($dinamicsRules);

        } else {
            $request->validate($location->rules());
        }

        $location->fill($request->all());
        $location->save();

        return response()->json($location, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location = $this->location->find($id);

        if($location === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $location->delete();
        return response()->json(['msg' => 'O car foi removido com sucesso!'], 200);
    }
}
