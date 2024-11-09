<?php

namespace App\Traits;

trait GeneralTrait
{
    public function returnSuccessData ($data, $message,$status_code){
        $response['data']=$data;
        $response['message']=$message;
        $response['status_code']=$status_code;
        return response()->json($response,200);
    }

    public function returnSuccessMessage($message, $status_code){
        $response['message']=$message;
        $response['status_code']=$status_code;
        return response()->json($response,200);
    }

    public function returnValidationError($message,$status_code){
        $response['message']=$message;
        $response['status_code']=$status_code;
        return response()->json($response,400);
    }

    public function returnErrorMessage($message,$status_code){
        $response['message']=$message;
        $response['status_code']=$status_code;
        return response()->json($response,400);
    }

    public function returnData($data)
    {
        return response()->json([
            'data' => $data
        ]);
    }

}
