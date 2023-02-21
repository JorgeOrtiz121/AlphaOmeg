<?php

namespace App\Http\Controllers\RutasPublicas;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerPublicosResource;
use App\Models\BannerPublicos;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class BannerPublicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $banner=BannerPublicos::all();
        return $this->sendResponse(message: 'Banner-public  list generated successfully', result: [
            'bannerspublic' => BannerPublicosResource::collection($banner),
        ]);
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $img=$request -> validate([
            'fotografias' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
            // https://laravel.com/docs/9.x/validation#rule-alpha-dash
        
           
        ]);
        $file = $img['fotografias'];
        $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),['folder'=>'fotografiaspublic']);
        $url = $uploadedFileUrl->getSecurePath();
        BannerPublicos::create(["fotografias"=>$url]);
        // https://laravel.com/docs/9.x/eloquent#inserts
       
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Banner-public stored successfully');
    }

   
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BannerPublicos $banner)
    {
        //
        $banner->delete();

        return $this->sendResponse(message: 'Banner-public delete successfully');
    }
}
