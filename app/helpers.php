<?php
/**
 * make json response.
 *
 * @param  bool  $success
 * @param  mixed $data
 * @return \Illuminate\Http\JsonResponse
 */
function apiResponse(bool $success, $data){
    if($success)
    {
        return response()->json([
            'success' => true,
            'data'    =>$data
        ], 200);
    }else
    {
        return response()->json([
            'success' => false,
            'message' =>$data
        ], 422);
    }
}