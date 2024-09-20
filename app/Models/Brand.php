<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brands';
    protected $fillable = ['name','image'];

    public function rules(){
        return [
            'name'=> 'required|unique:brands,name|min:3',
            'image' => 'required'
        ];
    }

    public function feedback(){
        return [
            'required' => 'O campo é obrigatório',
            'name.unique' => 'O nome da marca já existe',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres'
        ];
    }
}
