<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Album;
use App\ImageModel;
use Illuminate\Support\Facades\Response;
use Image;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function addImage(Request $request)
    {
        $album = Album::find($request->get('album_id'));
        if ($album->userId != $request->get('userId')){
            return response()->json([
                'OPERATION_MESSAGE' => "YOU DO NOT OWN THIS ALBUM",
                'status' => 'failed',
            ]);
        }
        else {
            $file = $request->file('image');
            // echo $file;
            $random_name = Str::random(8);
            $destinationPath = 'images/';
            $extension = $file->getClientOriginalExtension();
            $filename=$random_name.'_imageFile.'.$extension;
            $uploadSuccess = $request->file('image')
            ->move($destinationPath, $filename);
            $image = ImageModel::create(array(
            'album_id' => $request->get('album_id'),
            'description' => $request->get('description'),
            'userId' => $request->get('userId'),
            'image' => $filename,
            ));
    
            return response()->json([
                'OPERATION_MESSAGE' => "uploaded",
                'status' => 'successful',
            ]);
        }
        
    }

    public function deleteImage(Request $request, $id)
    {
        $image = ImageModel::find($id);
        if ($image->userId != $request->get('userId')){
            return response()->json([
                'OPERATION_MESSAGE' => "YOU DO NOT OWN THIS ALBUM",
                'status' => "failed",
            ]);
        }
        else {
            $image->delete();

            return response()->json([
                'OPERATION_MESSAGE' => "deleted",
                'status' => "successful",
            ]);
        }
    }
}
