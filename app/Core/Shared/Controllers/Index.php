<?php

namespace Core\Shared\Controllers;

// use Illuminate\Http\Request;
// use Core\Enterprises\Controllers\EnterpriseController;
use Core\Helpers\{ApiResponse, Messages};

trait Index
{
    public function index()
    { 
        $model = $this->model->Where('status', true)->paginate(100)->toArray();        

        $message  = $model['total'] . ' ' . Messages::$recordsFound;

        return (new ApiResponse(true, $message, $model))->execute();
    }
}
