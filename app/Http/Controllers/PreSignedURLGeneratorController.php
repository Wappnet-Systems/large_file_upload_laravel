<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Lib\S3PresignedUrlGenerator;

class PreSignedURLGeneratorController extends Controller
{
    
    
    /*
     * This function will generate presigned put object url using which file can be upload from client side
     * Note: make sure this function route will have auth middleware applied in production project
     * Also make sure that proper security checked added in functions are per project scope to block unauthorize access
     * 
     */
    public function generatePresignedPutObjectURL(S3PresignedUrlGenerator $s3PresignedurlObj,Request $request): JsonResponse {
        $fileName= time().$request->file_name; // make this dynamic in production project
        $urlData = $s3PresignedurlObj->putObjectUrlGenerate($fileName);
        
        if($urlData['status']){
            return response()->json(['status'=>true,'pre_signed_url'=>$urlData['presigned_url']]);
        }
        
    }
}
