<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;

class Vehicle_model extends Model
{
    use HasFactory;
    protected $table = 'vehicle_models';

    protected $fillable = [
        'brand_id',
        'name',
        'image',
        'number_doors',
        'number_passenger',
        'air_bag',
        'abs',
    
    ];

    public function rules(){
        return [
            'brand_id' => 'exists:brands,id',
            'name' => 'required|unique:brands,name,'.$this->id.'|min:3',
            'image' => 'required|file|mimes:png,jpeg,jpg',
            'number_doors' => 'required|integer|digits_between:1,5',
            'number_passenger' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',

        ];
    }

    public function brand(){
        //um modelo pretence a uma marca
        return $this->belongsTo(brand);


    }
}
