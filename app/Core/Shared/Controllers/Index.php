<?php

namespace Core\Shared\Controllers;

use Core\Helpers\{ApiResponse, Messages};

trait Index
{
    public function index()
    { 
        try 
        {

            $model = $this->model
                ->select('products.code', 'products.description', 'ps.stock',
                    'pu.minimum_price as minprice','pu.offer_price as ofertprice','pu.higher_price as hiprice','pu.maximum_price as maxprice')
                ->leftjoin('products_stock as ps', 'ps.product_code', 'products.code')
                ->leftjoin('products_units as pu', 'pu.product_code', 'products.code')
                ->where('ps.locations', '00')
                ->where('pu.unit', '00')
                ->paginate(10000)
                ->toArray();

        } catch (Exception $e) {
            $message = Messages::$notRecordFound;
            return (new ApiResponse(false, 'Error: ' . $e->getMessage()))->execute();
        }

        $message  = $model['total'] . ' ' . Messages::$recordsFound;

        return (new ApiResponse(true, $message, $model))->execute();
    }
}
