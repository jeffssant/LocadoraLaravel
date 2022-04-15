<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Repositories\TypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TypeController extends Controller
{
    public function __construct (Type $type){
        $this->type = $type;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $typeRepository = new typeRepository($this->type);

        if($request->has('brand_atributes')) {
            $type_atributes = 'brand:id,'.$request->brand_atributes;
            $typeRepository->selectRelationship($type_atributes);
        } else {
            $typeRepository->selectRelationship('brand');
        }

        if($request->has('filter')) {
            $typeRepository->filter($request->filter);
        }

        if($request->has('atributes')) {
            $typeRepository->selectAtributes($request->atributes);
        }

        return response()->json($typeRepository->getResult(), 200);
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
         //Verifica se a marca existe
         $request->validate($this->type->rules());

         $image = $request->image;
         $image_urn =  $image->store('images/types', 'public');

         $this->type->name = $request->name;
         $this->type->brand_id = $request->brand_id;
         $this->type->doors = $request->doors;
         $this->type->seats = $request->seats;
         $this->type->air_bag = $request->air_bag;
         $this->type->abs = $request->abs;
         $this->type->image = $image_urn;

         $type = $this->type->save();


         return response()->json($type, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $type = $this->type->with('brand')->find($id);

        if($type === null){
            return response()->json(['error' => 'Model not found'], 404);
        }

        return response()->json($type, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        //Verifica se a marca existe
        $type = $this->type->find($id);
        $oldImage = $type->image;


        if($type === null){
            return response()->json(['error' => 'Model not found'], 404);
        }

        $this->type->verifyPacth($request, $id);

        $type->fill($request->all());

        if($request->image){
            $image = $request->image;
            $image_urn =  $image->store('images/types', 'public');

            Storage::disk('public')->delete($oldImage);

            $type->image = $image_urn;
        }

        $type->save();
        return response()->json($type, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = $this->type->find($id);

        if($type === null){
            return response()->json(['error' => 'Model not found'], 404);
        }

        if($type->image){
            Storage::disk('public')->delete($type->image);
        }


        $type->delete();
        return response()->json($type, 200);
    }
}
