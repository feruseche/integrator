<?php

namespace Core\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    //use HasFactory;

    protected $table     = 'products_ally';

    //protected $primaryKey = 'barcode';

    protected $perPage   = 10;
    protected $fillable  = [
        "barcode", "name", "stock", "price" 
    ];
    protected $appends   = [];
    public $timestamps   = false;
    public $incrementing = false;

    protected $hidden = [
    ];

    public function getRouteKeyName(): string
    {
        return 'barcode';
    }
}

class ProductUltimate extends Model
{
    //use HasFactory;

    protected $table     = 'products_ultimate';

    protected $primaryKey = 'barcode';

    protected $perPage   = 10;
    protected $fillable  = [
        "barcode", "name", "stock", "price" 
    ];
    protected $appends   = [];
    public $timestamps   = false;
    public $incrementing = false;

    protected $hidden = [
    ];

    public function getRouteKeyName(): string
    {
        return 'barcode';
    }
}

class ProductPenultimate extends Model
{
    //use HasFactory;

    protected $table     = 'products_penultimate';

    protected $primaryKey = 'barcode';

    protected $perPage   = 10;
    protected $fillable  = [
        "barcode", "name", "stock", "price" 
    ];
    protected $appends   = [];
    public $timestamps   = false;
    public $incrementing = false;

    protected $hidden = [
    ];

    public function getRouteKeyName(): string
    {
        return 'barcode';
    }
}

class ProductUpload extends Model
{
    //use HasFactory;

    protected $table     = 'products_upload';

    protected $primaryKey = 'barcode';

    protected $perPage   = 10;
    protected $fillable  = [
        "barcode", "name", "stock", "price" 
    ];
    protected $appends   = [];
    public $timestamps   = false;
    public $incrementing = false;

    protected $hidden = [
    ];

    public function getRouteKeyName(): string
    {
        return 'barcode';
    }
}