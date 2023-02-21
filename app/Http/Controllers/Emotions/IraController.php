<?php

namespace App\Http\Controllers\Emotions;

use App\Http\Controllers\Controller;
use App\Http\Resources\IraResource;
use App\Http\Resources\MusicaOneResource;
use App\Models\Ira;
use App\Models\MusicaOne;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Facades\Gate;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class IraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ira=Ira::all();
        return $this->sendResponse(message: 'Lista de Emocion Ira desplegada', result: [
            'emociones' => IraResource::collection($ira),
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
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $request->validate([
            'Tema' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            
        ]);

        $ira= $request ->validate([
            'video' => ['file'],
        ]);
        $file = $ira['video'];
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'emotions']);
        $url = $uploadedFileUrl->getSecurePath();
       // $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(),['folder'=>'emotions','resource_type' => 'video',
        //'public_id' => 'myfolder/mysubfolder/dog_closeup',
        //'chunk_size' => 6000000,
        //'eager' => [
        //  ['width' => 300, 'height' => 300, 'crop' => 'pad'], 
         // ['width' => 160, 'height' => 100, 'crop' => 'crop', 'gravity' => 'south']], 
        //'eager_async' => true, ]);
        //$url = $uploadedFileUrl->getSecurePath();
        //dd($url);
       //$file = $ira['video'];
        //$url=(new UploadApi())->upload($file,['folder'=>'emotions','resource_type'=>'video','chunk_size'=>6000000]);
      
         Ira::create(
            [
                "Tema"=>$request->Tema,
                "descripcion"=>$request->descripcion,
                "video"=>$url
            ]
         );
         return $this->sendResponse('Emocion Ira agregada',204);
        }else{
            echo $response->message();
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ira $ira)
    {
        //
        $musica=MusicaOne::all();
        return $this->sendResponse(message: 'Detalle de Emocion Ira', result: [
            'iras' => new IraResource($ira),
            'music'=> MusicaOneResource::collection( $musica),

        ]);
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Ira $emotion)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $request->validate([
            'Tema' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            
        ]);

        $ira= $request -> validate([
            'video' => ['nullable','file','mimes:ppt,pptx','max:6000000'],
        ]);
        if($request->has('video')){
        $file = $ira['video'];
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'emotions']);
        $url = $uploadedFileUrl->getSecurePath();
        $emotion->update([
            "video"=>$url,
         ]);
        }
         $emotion->update([
            "Tema"=>$request->Tema,
            "descripcion"=>$request->descripcion,
            
         ]);
         return $this->sendResponse('Emocion Ira Actualizada',200);
        }else{
            echo $response->message();
        }
    }


       
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ira $ira)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $ira->delete();
        return $this->sendResponse("Emocion Ira eliminada", 200);
    }else{
        echo $response->message();
    }
    }
}
