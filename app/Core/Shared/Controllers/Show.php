<?php
namespace Core\Shared\Controllers;

// use Illuminate\Http\Request;
// use Core\Enterprises\Controllers\EnterpriseController;
use Core\Helpers\{ApiResponse, Messages};
use Exception;

trait Show
{
    public function show($uuid)
    {
        // $enterprise = $request->input('enterprise');
        // $verify = (new EnterpriseController())->verify($enterprise);
        // if (!$verify) return (new EnterpriseController())->invalidSerial();

        try {
            $model = $this->model->findOrFail($uuid)->toArray();

            // $model = $this->model->findOrFail($request->input('uuid'))->toArray();
        } catch (Exception $e) {
            $message = Messages::$notRecordFound;
            return (new ApiResponse(false, $message))->execute();
        }

        $message  = '1 ' . Messages::$recordsFound;

        return (new ApiResponse(true, $message, $model))->execute();
    }
}
