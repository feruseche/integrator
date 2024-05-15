<?php

namespace Core\Shared\Controllers;

use Illuminate\Http\Request;
// use Core\Enterprises\Controllers\EnterpriseController;
use Core\Helpers\{ApiResponse, Messages};
use Exception;


trait Update
{
    public function update(Request $request, $uuid)
    {
        try {
            $modelUpdate = $this->model->findOrFail($uuid)->update($request->toArray());
        } catch (Exception $e) {
            $message = Messages::$errorServer;
            return (new ApiResponse(false, $message))->execute();
        }

        try {
            if ($modelUpdate) $model = $this->model->findOrFail($uuid);
        } catch (Exception $e) {
            $message = Messages::$notRecordFound;
            return (new ApiResponse(false, $message))->execute();
        }

        $message  = Messages::$updatedRecord;

        return (new ApiResponse(true, $message, $model->toArray()))->execute();
    }
}
