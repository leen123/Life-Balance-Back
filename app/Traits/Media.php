<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait Media {

    // upload file
    
    public function upload_file($request,$request_file_name,$directory){

        $path = $request->file($request_file_name)->store('public/'.$directory);

        $path = substr($path, strlen('public/'));

        return 'storage/'.$path;
    }



    // upload multiple images

    public function upload_mulitple_files($request,$request_files_name,$directory){
      
        $paths = [];

        if($request->hasfile($request_files_name))
        {
           foreach($request->file($request_files_name) as $key => $file)
           {
               $path = $file->store('public/'.$directory);

               $path = substr($path, strlen('public/'));

               $paths [] = 'storage/'.$path;
           }
        }

        return $paths;
    }


    // delete image file 

    public function delete_file($path){

        $file = substr($path, strlen('storage/'));
        
        Storage::delete('public/'.$file);
      
    }


}