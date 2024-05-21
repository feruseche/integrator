<?php

namespace Core\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //use HasFactory;

    protected $table     = 'products';

    protected $primaryKey = 'code';

    protected $perPage   = 10;
    protected $fillable  = [
        "code", "description", "sale_tax" 
    ];
    protected $appends   = [];
    public $timestamps   = false;
    public $incrementing = false;

    protected $hidden = [
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }
}

class Stock extends Model
{
    //use HasFactory;

    protected $table     = 'products_stock';

    protected $primaryKey = 'product_code';

    protected $perPage   = 10;
    protected $fillable  = [
        "product_code", "locations", "stock"
    ];
    protected $appends   = [];
    public $timestamps   = false;
    public $incrementing = false;

    protected $hidden = [
    ];

    public function getRouteKeyName(): string
    {
        return 'product_code';
    }
}
