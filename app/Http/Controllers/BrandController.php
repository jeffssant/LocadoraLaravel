<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct (Brand $brand){
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->brand->all();
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
        $brandExists = $this->brand->where('name', $request->name)->get()->first();

        if($brandExists ){
            return response()->json(['error' => 'Brand already exists'], 422);
        }

        //Valida os campos
        $valid = $this->brand->validBrandStoreRules($request);

        if($valid){ return $valid; }

        $brand = $this->brand->create($request->all());

        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brand->find($id);

        if($brand === null){
            return response()->json(['error' => 'Brand not found'], 404);
        }

        return response()->json($brand, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        //Verifica se a marca existe
        $brand = $this->brand->find($id);

        if($brand === null){
            return response()->json(['error' => 'brand not found'], 404);
        }

        //Verifica se existe uma marca com o mesmo nome proposto na atualização
        $brandExists = $this->brand->where('name', $request->name)->where('id', '!=', $id)->get()->first();

        if($brandExists ){
            return response()->json(['error' => "$request->name already exists"], 422);
        }

        //Valida os campos
        $valid = $this->brand->validBrandUpdateRules($request,$id);

        if($valid){ return $valid; }

        $brand->update($request->all());
        return response()->json($brand, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = $this->brand->find($id);

        if($brand === null){
            return response()->json(['error' => 'brand not found'], 404);
        }

        $brand->delete();
        return response()->json($brand, 200);
    }

}
