<?php

namespace Core\Shared\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
// use Core\Enterprises\Controllers\EnterpriseController;
use Core\Helpers\{ApiResponse, Messages};
use Exception;

trait Store
{
    public function store(Request $request)
    {
        // $enterprise = $request->input('enterprise');
        // $verify = (new EnterpriseController())->verify($enterprise);
        // if (!$verify) return (new EnterpriseController())->invalidSerial();

        $user = Auth()->user();

        try {

            $newModel = $request->all();

            $newUuid = Str::uuid()->toString();
            $newModel = Arr::add($newModel, 'uuid', $newUuid);

/*            if(!empty($request->file('image')))
            {
                $file = $request->file('image');
                $filename = $request->newUuid . ".jpg";
                Storage::disk('public')->put($filename, File::get($file));
            }
*/
/*           
            if(!empty($request->file('image')))
            {
                $imageContent = $this->imageBase64Content($request->image);
                $uuidImg = Str::uuid()->toString();
                //$fullPath = public_path('/storage/' . $uuidImg);
                //File::put($fullPath, $imageContent);
                $filename = $uuidImg . ".jpg";
                Storage::disk('public')->put($filename, File::get($imageContent));
            }

            if ($user->type == "CORPORATE CAMBIAR ESTO FERNANDO") {
                $newModel = Arr::add($newModel, 'enterprise', $user->enterprise);
            }
*/
            $createModel = $this->model->create($newModel);
            
        } catch (Exception $e) {
            $message = Messages::$canNotCreate;
            return (new ApiResponse(false, $e->getMessage()))->execute();
        }

        $message  = Messages::$createRecord;

        return (new ApiResponse(true, $message, $createModel->toArray()))->execute();
    }

    
}
