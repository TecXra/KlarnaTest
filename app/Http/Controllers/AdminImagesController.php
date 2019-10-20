<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AdminImagesController extends Controller
{
    public function tires()
    {
    	$directory = public_path('images/product/media/tires');
        $images = File::files($directory);

        // dd($directory, $images, basename($images[0]));

        return view('admin/tire_media', compact('images'));
    }

    public function uploadTires(Request $request)
    {
    	$files = $request->file('file');
    	// dd($files);

        if($files) {  
            foreach ($files as $key => $file) {
                
                $fileName = $file->getClientOriginalName();
                // dd($fileName);
                $path = 'images/product/media/tires/'. $fileName;
                $thumbnail_path = 'images/product/media/tires/thumb/tn-'. $fileName;
                $absolute_path = public_path($path);
                $absolute_thumbnail_path = public_path($thumbnail_path);
                $file->move('images/product/media/tires/', $fileName);

                // dd($fileName, $path);

                Image::make($path)->resize(600, 600)->save();
                Image::make($path)->resize(170, 170)->save($thumbnail_path);
                // Image::make($path)->resize(600, 600)->save('../../dackline/public/'.$path);
                // Image::make($path)->resize(170, 170)->save('../../dackline/public/'.$thumbnail_path);
            }
        
        }

        $request->session()->flash('message', 'Bilderna är uppladdade.');
    }

    public function deleteTire($tire)
    {

    	$image = public_path("images/product/media/tires/$tire");
    	$imageThumb = public_path("images/product/media/tires/thumb/tn-$tire");

    	if(file_exists($image)) {
    		File::delete($image);
    	}

    	if(file_exists($imageThumb)) {
    		File::delete($imageThumb);
    	}
    	// dd($tire, $image, $imageThumb, file_exists($image));
        
        return redirect()->back();
    }

    public function rims()
    {
    	$directory = public_path('images/product/media/rims');
        $images = File::files($directory);

        // dd($directory, $images, basename($images[0]));

        return view('admin/rim_media', compact('images'));
    }

    public function uploadRims(Request $request)
    {
    	$files = $request->file('file');
    	// dd($files);

        if($files) {  
            foreach ($files as $key => $file) {
                
                $fileName = $file->getClientOriginalName();
                // dd($fileName);
                $path = 'images/product/media/rims/'. $fileName;
                $thumbnail_path = 'images/product/media/rims/thumb/tn-'. $fileName;
                $absolute_path = public_path($path);
                $absolute_thumbnail_path = public_path($thumbnail_path);
                $file->move('images/product/media/rims/', $fileName);

                // dd($fileName, $path);

                Image::make($path)->resize(600, 600)->save();
                Image::make($path)->resize(170, 170)->save($thumbnail_path);
                // Image::make($path)->resize(600, 600)->save('../../dackline/public/'.$path);
                // Image::make($path)->resize(170, 170)->save('../../dackline/public/'.$thumbnail_path);
            }
        
        }

        $request->session()->flash('message', 'Bilderna är uppladdade.');
    }

    public function deleteRim($rim)
    {

    	$image = public_path("images/product/media/rims/$rim");
    	$imageThumb = public_path("images/product/media/rims/thumb/tn-$rim");

    	if(file_exists($image)) {
    		File::delete($image);
    	}

    	if(file_exists($imageThumb)) {
    		File::delete($imageThumb);
    	}
    	// dd($rim, $image, $imageThumb, file_exists($image));
        
        return redirect()->back();
    }
}
