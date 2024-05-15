<?php
namespace Core\Shared\Controllers;

use Core\Helpers\{ApiResponse, Messages};
use Exception;

trait Search
{
    public function search($search)
    {
        try {
        
            $model = $this->model
            ->select(['products_mercarapid.category','products_mercarapid..barcode','products_mercarapid..name','products_mercarapid..uuidimg', 'c.category as category_name'])
            ->join('categories as c', 'c.uuid', 'products_mercarapid.category')
            ->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('barcode', $search)
            ->orderby('uuidimg', 'desc')
            ->paginate(100)
            ->toArray();

        } catch (Exception $e) {
            $message = Messages::$notRecordFound;
            return (new ApiResponse(false, $message))->execute();
        }

        $message  =  $model['total'] . ' ' . Messages::$recordsFound;

        return (new ApiResponse(true, $message, $model))->execute();
    }
}
