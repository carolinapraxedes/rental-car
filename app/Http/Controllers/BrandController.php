<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $brand;

    public function __construct(Brand $brand){
         $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand = Brand::all();
        return response()->json($brand,200);
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
            $this->brand->rules(),
            $this->brand->feedback()
        );

        dd($request->file('image'));

        $brand = Brand::create($request->all());
        
        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::find($id);
        //dd($brand);

        if ($brand === null){
            return response()->json(['error' => 'brand not found'],404);
        } else {
            return response()->json($brand,200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        if($brand ===null) {
            return response()->json(['error' => 'update is not possible. the brand is not exist'],404);
        }
        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            //percorre todas as regras definidas no model
            foreach($brand->rules() as $input => $rule){
                //coleta apenas as regras aplicaveis aos parametros parciais da requisicao
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $rule;
                }
            }
        }else{
            $request->validate($brand->rules(),$brand->feedback());
        }
        
        $brand->update($request->all());
        return response()->json($brand,200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);
        if($brand === null) {
            return ['error' => 'the brand is not exist. delete is not possible. '];
        }else{
            $brand->delete();
            return response()->json(['msg'=> 'brand deleted with success'],200);
        }
    }
}
