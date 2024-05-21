<?php

namespace Core\Shared\Controllers;

use Illuminate\Http\Request;
use Core\Helpers\{ApiResponse, Messages};
use Exception;

trait Update
{
    public function update(Request $request, $code)
    {
        try {

            $new_stock = $request->input('stock');

            $modelUpdate = $this->stock->findOrFail($code);

/*            $modelUpdate = $this->stock::updateOrCreate(
                ['product_code' => $code], 
                ['stock' => $new_stock], 
                ['locations' => '00'])
            ->where('locations', '00');
*/        
            $modelUpdate->stock = $new_stock;
            $modelUpdate->save();

        } catch (Exception $e) {

            $message = Messages::$errorServer;
            return (new ApiResponse(false, $e->getMessage()))->execute();

        }

        $message  = Messages::$updatedRecord;

        return (new ApiResponse(true, $message, $modelUpdate->toArray()))->execute();
    }
}
