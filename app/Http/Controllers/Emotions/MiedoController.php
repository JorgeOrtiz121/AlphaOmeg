<?php

namespace App\Http\Controllers\Emotions;

use App\Http\Controllers\Controller;
use App\Http\Resources\MiedoResource;
use App\Http\Resources\MusicThreeResource;
use App\Models\Miedo;
use App\Models\MusicaThree;
use Illuminate\Support\Facades\Gate;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class MiedoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $miedo=Miedo::all();
        return $this->sendResponse(message: 'Lista de Emocion de Miedo desplegada', result: [
            'emociones' => MiedoResource::collection($miedo),
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

        $miedo= $request ->validate([
            'video' => ['file'],
        ]);
        $file = $miedo['video'];
        $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(),['folder'=>'emotions']);
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
      
         Miedo::create(
            [
                "Tema"=>$request->Tema,
                "descripcion"=>$request->descripcion,
                "video"=>$url
            ]
         );
         return $this->sendResponse('Emocion Miedo agregada',204);
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
    public function show(Miedo $miedo)
    {
        //
        $musica=MusicaThree::all();
        return $this->sendResponse(message: 'Detalles de Emocion Miedo', result: [
            'iras' => new MiedoResource($miedo),
            'music'=> MusicThreeResource::collection( $musica),
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
    public function update(Request $request, Miedo $miedo)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $request->validate([
            'Tema' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            
        ]);

        $miedin= $request -> validate([
            'video' => ['nullable','file','mimes:ppt,pptx','max:6000000'],
        ]);
        if($request->has('video')){
        $file = $miedin['video'];
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'emotions']);
        $url = $uploadedFileUrl->getSecurePath();
        $miedo->update([
            "video"=>$url,
         ]);
        }
         $miedo->update([
            "Tema"=>$request->Tema,
            "descripcion"=>$request->descripcion,
            
         ]);
         return $this->sendResponse('Emocion Miedo actualizada',200);
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
    public function destroy(Miedo $miedo)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $miedo->delete();
        return $this->sendResponse("Emocion Miedo eliminada", 200);
    }else{
        echo $response->message();
    }
    }
}
