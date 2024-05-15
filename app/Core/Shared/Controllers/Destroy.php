<?php

namespace Core\Shared\Controllers;

// use Illuminate\Http\Request;
// use Core\Enterprises\Controllers\EnterpriseController;
use Core\Helpers\{ApiResponse, Messages};
use Exception;

trait Destroy
{
    public function destroy($uuid)
    {
        // $enterprise = $request->input('enterprise');
        // $verify = (new EnterpriseController())->verify($enterprise);
        // if (!$verify) return (new EnterpriseController())->invalidSerial();

        try {
            $model = $this->model->findOrFail($uuid);
        } catch (Exception $e) {
            $message = Messages::$notRecordFound;
            return (new ApiResponse(false, $message))->execute();
        }
        $modelArray = $model->toArray();
        $model->delete();
        $message  = '1 ' . Messages::$deletedRecord;
        return (new ApiResponse(true, $message, $modelArray))->execute();
    }
}
