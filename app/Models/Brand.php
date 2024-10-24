<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicle_model;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brands';
    protected $fillable = ['name','image'];

    public function rules(){
        return [
            'name'=> 'required|unique:brands,name,'.$this->id.'|min:3',
            'image' => 'required|file|mimes:png'
        ];
    }

    public function feedback(){
        return [
            'required' => 'O campo é obrigatório',
            'name.unique' => 'O nome da marca já existe',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres',
            'image.mimes' => 'O arquivo deve ser uma imagem'
        ];
    }

    public function vehicle_model(){
        //uma marca POSSUI MUITOS modelos
        return $this->hasMany(Vehicle_model::class);
    }

}
