<?php

namespace App\Http\Controllers\Event;

use App\Event\EventBasicInfo;
use App\Event\EventPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

class EventPhotoController extends Controller
{
    protected $path = '../public/storage/Event/Photos/';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Counting the photos
        $countPhotos = count($_FILES['images']['name']);
        // Looping the files
        for ($i=0; $i < $countPhotos ; $i++) {
                // File Loop
                $file_name = $_FILES['images']["tmp_name"][$i];
                // image extension extraction
                $extension = explode("/", $_FILES["images"]["type"][$i]);
                $name = time() .$i. '.' . $extension[1];
                $thumb = time() .$i. '-thumb.' . $extension[1];
            // Original
                \Image::make($file_name)->save($this->path. $name);
                $Original =  \Image::make($file_name)->save($this->path. $name);
            // thumb
                $Original->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                \Image::make($Original)->save($this->path. $thumb);
            // Inserting
            // Recording in Database
                $restaurant = EventPhoto::create([
                    'event_basic_info_id' => $request->id,
                    // 'path' => $image->store(''),
                    'path' => $name,
                    'thumb' => $thumb,
                    'user_id' => Auth::user()->id,
                ]);
            }
        return response()->json([
            "message" => "Done"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $photo = EventPhoto::find($id);
        // return $photo->path;
        $unlink = $this->path. $photo->path;
        $thumb = $this->path. $photo->thumb;
        // Unlinking all the photos
        unlink($unlink);
        unlink($thumb);
        // Deleting records
        $photo->delete();
    }

    /**
     * 
     * Custom api functions
     * 
     *  */
    public function photo(EventBasicInfo $eventBasicInfo)
    {
        return $eventBasicInfo->event_photos()->orderBy('created_at', 'desc')->get();
    }
}
