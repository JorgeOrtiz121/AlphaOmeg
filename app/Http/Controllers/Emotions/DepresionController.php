<?php

namespace App\Http\Controllers\Emotions;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepresionResource;
use App\Http\Resources\MusicaFourResource;
use App\Models\Ansiedad;
use Illuminate\Support\Facades\Gate;
use App\Models\Depresion;
use App\Models\MusicFour;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class DepresionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $depresion=Depresion::all();
        return $this->sendResponse(message: 'Lista de Emocion de Depresion desplegada', result: [
            'emociones' => DepresionResource::collection($depresion),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
      
         Depresion::create(
            [
                "Tema"=>$request->Tema,
                "descripcion"=>$request->descripcion,
                "video"=>$url
            ]
         );
         return $this->sendResponse('Emocion Depresion agregada',204);
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
    public function show(Depresion $depresion)
    {
        //
        $music=MusicFour::all();
        return $this->sendResponse(message: 'Detalle de Emocion Depresion', result: [
            'iras' => new DepresionResource($depresion),
            'music'=> MusicaFourResource::collection( $music),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Depresion $depresion)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $request->validate([
            'Tema' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            
        ]);

        $depre= $request -> validate([
            'video' => ['nullable','file','mimes:ppt,pptx','max:6000000'],
        ]);
        if($request->has('video')){
        $file = $depre['video'];
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'emotions']);
        $url = $uploadedFileUrl->getSecurePath();
        $depresion->update([
            "video"=>$url,
         ]);
        }
         $depresion->update([
            "Tema"=>$request->Tema,
            "descripcion"=>$request->descripcion,
            
         ]);
         return $this->sendResponse('Emocion Depresion actualizada',200);
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
    public function destroy(Depresion $depresion)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $depresion->delete();
        return $this->sendResponse("Emocion depresion eliminado", 200);
    }else{
        echo $response->message();
    }
    }
}
