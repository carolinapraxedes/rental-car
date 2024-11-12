<?php

namespace App\Http\Controllers;


use App\Models\Vehicle_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleModelController extends Controller
{
    protected $vehicle_model;
    public function __construct(Vehicle_model $vehicle_model){
        $this->vehicle_model = $vehicle_model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $models = array();

        if($request->has('atributos_brand')){
            $atributosBrand = $request->atributos_brand;
            $models = $this->vehicle_model->with('brand:id,'.$atributosBrand );
        }
        else{
            $models = $this->vehicle_model->with('brand');
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            //$models = $this->vehicle_model->select('id','name','image')->get();
            //$atributosBrand = $request->atributos_brand;
            //dd($atributosBrand);

            /*$models = $this->vehicle_model->selectRaw($atributos)->with('brand')->get();
                para conseguir recuperar a brand com o with, é necessário passar o brand_id na request
            ex: localhost:8000/api/vehicle_model?atributos=id,name,brand_id */


            $models = $models->selectRaw($atributos)->get();
            
            
            /* filtrando o atributo especifico da brand e smp recupere o id junto
                $models = $this->vehicle_model->selectRaw($atributos)->with('brand:id,name')->get();
            */ 

        }else{
            $models = $models->with('brand')->get();
        }

        //dd($request->get('atributos'));
        //return response()->json($this->vehicle_model->with('brand')->get(),200);
        return response()->json($models,200);

        //all()-> cria obj de consulta + get() = collection
        //get()-> modifica a consulta ->collection
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
        $vehicle_model = $this->vehicle_model->with('brand')->find($id);
        if($vehicle_model === null){
            return response()->json(['error' => 'not found item'],404);
        }

        return response()->json($vehicle_model,200);
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
        //dd($request->image);
        //dd("aqui");
         if($vehicle_model === null){
             return response()->json(['erro' => 'its not impossible to update'], 404);
         }
    
          //Validação dinâmica para PATCH ou completa para PUT
         if($request->method() === 'PATCH'){
             $regrasDinamicas = array();
    
              //Percorre todas as regras definidas no model
             foreach($vehicle_model->rules() as $input => $rule){
                  //Coleta apenas as regras aplicáveis aos parâmetros parciais da requisição
                 if(array_key_exists($input, $request->all())){
                     $regrasDinamicas[$input] = $rule;
                 }
             }
    
             //dd('teste');
             //dd($request->all());
            //Valida apenas os campos presentes no PATCH
             $request->validate($regrasDinamicas);
    
         } else {
              //Validação completa no PUT
             $request->validate($vehicle_model->rules());
         }
         if($request->file('image')){
             try {
                Storage::disk('public')->delete($vehicle_model->image);

                //Armazena a nova imagem
                 $image = $request->file('image');
                 $image_urn = $image->store('images/vehicle_model', 'public');
        
                  //Atualiza o campo 'image' no modelo
                 $vehicle_model->image = $image_urn;
             } catch (\Exception $e) {
                 return response()->json(['error' => 'Erro ao armazenar a imagem: '.$e->getMessage()], 500);
             }
         }
        
         //dd($request->name);
         $vehicle_model->update([
             'brand_id' => $request->brand_id, 
             'name' => $request->name,
             'number_doors' => $request->number_doors,
             'number_passenger' => $request->number_passenger,
             'air_bag' => $request->air_bag,
             'abs' => $request->abs,

             'image' => $image_urn,
              /*Apenas atualiza o campo 'image' se o $image_urn estiver definido
              Se a imagem não for enviada, o valor atual da base de dados será mantido*/

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
        //dd($vehicle_model->image);
        if($vehicle_model === null) {
            return response()->json(['error' => 'the model is not exist. delete is not possible'],404);
        }
        Storage::disk('public')->delete($vehicle_model->image);

        $vehicle_model->delete();
        return response()->json(['msg'=> 'vehicle model deleted with success'],200);
        
    }
}
