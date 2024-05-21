<?php
namespace Core\Shared\Controllers;

use Core\Helpers\{ApiResponse, Messages};
use Exception;

trait Show
{
    public function show($code)
    {
        try {

            $model = $this->model->findOrFail($code)
            ->select('products.code', 'products.description', 'ps.stock')
            ->leftjoin('products_stock as ps', 'ps.product_code', 'products.code')
            ->where('products.code', $code)
            ->where('ps.locations', '00')
            ->paginate(10)            
            ->toArray();

        } catch (Exception $e) {
            $message = Messages::$notRecordFound;
            return (new ApiResponse(false, 'Error: ' . $e->getMessage()))->execute();
        }

        $message  = '1 ' . Messages::$recordsFound;

        return (new ApiResponse(true, $message, $model))->execute();
    }
}
