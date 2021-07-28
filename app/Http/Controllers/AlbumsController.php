<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Album;
use Illuminate\Support\Facades\Response;
use Image;
use Illuminate\Support\Str;

class AlbumsController extends Controller
{

    public function getAlbumsByUserId(Request $request)
    {
        $albums = Album::with('Images')->where('userId', $request->get('userId'))->get();
        return response()->json([
            'albums' => $albums,
        ]);
    }
    public function getAlbum($id, Request $request)
    {
        $album = Album::with('Images')->find($id);
        if ($album->userId != $request->get('userId')){
            return response()->json([
                'OPERATION_MESSAGE' => "YOU DO NOT OWN THIS ALBUM.",
                'status' => "failed",
            ]);
        }
        else {
            return response()->json([
                'album' => $album,
                'status' => 'successful',
            ]);
        }
        
    }

    public function addAlbum(Request $request)
    {
        $file = $request->file('cover_image');
        // echo $file;
        $random_name = Str::random(8);
        $destinationPath = 'albums/';
        $extension = $file->getClientOriginalExtension();
        $filename=$random_name.'_cover.'.$extension;
        $uploadSuccess = $request->file('cover_image')
        ->move($destinationPath, $filename);
        $album = Album::create(array(
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'userId' => $request->get('userId'),
        'cover_image' => $filename,
        ));

        return response()->json([
            'OPERATION_MESSAGE' => "uploaded",
            'status' => "successful",
        ]);
    }

    public function deleteAlbum(Request $request, $id)
    {
        $album = Album::find($id);
        if ($album->userId != $request->get('userId')){
            return response()->json([
                'OPERATION_MESSAGE' => "YOU DO NOT OWN THIS ALBUM",
                'status' => "failed",
            ]);
        }
        else {
            $album->delete();

            return response()->json([
                'OPERATION_MESSAGE' => "deleted",
                'status' => "successful",
            ]);
        }
    }
}
