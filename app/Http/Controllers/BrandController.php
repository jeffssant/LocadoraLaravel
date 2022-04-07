<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request->validate($this->brand->rules(), $this->brand->feedback());

        $image = $request->image;
        $image_urn =  $image->store('images', 'public');

        $this->brand->name = $request->name;
        $this->brand->image = $image_urn;
        $brand = $this->brand->save();


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
    public function update($id, Request $request)    {

        //Verifica se a marca existe
        $brand = $this->brand->find($id);


        if($brand === null){
            return response()->json(['error' => 'brand not found'], 404);
        }

        $this->brand->verifyPacth($request, $id);

        if($request->image){
            $image = $request->image;
            $image_urn =  $image->store('images', 'public');

            Storage::disk('public')->delete($brand->image);

            $brand->image = $image_urn;
        }

        if($request->name){
            $brand->name = $request->name;
        }

        $brand->update();
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

        if($brand->image){
            Storage::disk('public')->delete($brand->image);
        }


        $brand->delete();
        return response()->json($brand, 200);
    }

}
