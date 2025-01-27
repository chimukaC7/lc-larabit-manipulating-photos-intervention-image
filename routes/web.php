<?php

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome', [
        'photos' => Photo::pluck('name')->toArray()
    ]);
});

Route::post('/save', function (Request $request) {
    // $name = basename($request->photo->store('public'));

    $name = Str::random(32) . '.jpg';//name of the image
//     Image::make($request->photo)
//         ->resize(200,300,function ($constraint){
//             $constraint->aspectRatio();//image will be resized to the highest of the wid length while maintaining the aspect ratio of the image
//         })
//         //->crop(200, 200)
//         ->save(storage_path('app/public/' . $name));

    // Image::make($request->photo)
    //     ->widen(200)
    //     ->crop(200, 200)
    //     ->save(storage_path('app/public/' . $name));

    $watermark = Image::make(storage_path('app/watermark.png'))
        ->widen(100)
        ->opacity(25);

    Image::make($request->photo)
        ->widen(800)
        ->insert($watermark, 'bottom-right', 20, 20)//inserts one image into another,how far
        ->save(storage_path('app/public/' . $name));

    Photo::create([
        'name' => $name
    ]);

    return redirect()->back();
});
