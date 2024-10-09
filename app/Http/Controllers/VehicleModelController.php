<?php

namespace App\Http\Controllers;

use App\Models\Vehicle_model as Model;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    protected $vehicle_model;
    public function __construct(Model $model){
        $this->vehicle_model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json($this->vehicle_model->all(),200);
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
        $request->validate(
            $this->vehicle_model->rules(),
           
        );

        $image = $request->file('image');
        $image_urn = $image->store('imagens/vehicle_model','public');

        $vehicle_model = $this->vehicle_model->create([
            'brand_id'=> $request->brand_id,
            'name'=> $request->name,
            'image' => $image_urn,
            'number_doors' => $request->number_doors,
            'number_passenger' => $request->number_passenger,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,

        ]);
        
        return response()->json($vehicle_model, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vehicle_model = $this->vehicle_model->find($id);
        if($vehicle_model === null){
            return response()->json(['error' => 'not found item'],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle_model $vehicle_model)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vehicle_model = $this->vehicle_model->find($id);

        if($vehicle_model === null){
            return response()->json(['erro'=> 'its not impossible to update'],404);
        }
        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            //percorre todas as regras definidas no model
            foreach($vehicle_model->rules() as $input => $rule){
                //coleta apenas as regras aplicaveis aos parametros parciais da requisicao
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $rule;
                }
            }
            $request->validate($regrasDinamicas);

        }else{
            $request->validate($vehicle_model->rules());
        }
        if($request->file('image')){
            Storage::disk('public')->delete($vehicle_model->image);
        }

        $image = $request->file('image');
        $image_urn = $image->store('images','public');

        $vehicle_model->update([
            'brand_id'=> $request->brand_id,
            'name'=> $request->name,
            'image' => $image_urn,
            'number_doors' => $request->number_doors,
            'number_passenger' => $request->number_passenger,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);

        return response()->json($vehicle_model,200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle_model  $vehicle_model
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicle_model = $this->vehicle_model->find($id);
        if($vehicle_model === null) {
            return response()->json(['error' => 'the brand is not exist. delete is not possible'],404);
        }
        Storage::disk('public')->delete($vehicle_model->image);

        $vehicle_model->delete();
        return response()->json(['msg'=> 'vehicle model deleted with success'],200);
        
    }
}
