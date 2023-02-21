<?php

namespace App\Http\Controllers\Emotions;

use App\Http\Controllers\Controller;
use App\Http\Resources\MusicThreeResource;
use App\Http\Resources\MusicTwoResource;
use App\Http\Resources\SoledadResource;
use App\Models\MusicaTwo;
use App\Models\Soledad;
use Illuminate\Support\Facades\Gate;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class SoledadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $soledad=Soledad::all();
        return $this->sendResponse(message: 'Lista de Emocion de Soledad desplegada', result: [
            'emociones' => SoledadResource::collection($soledad),

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
      
         Soledad::create(
            [
                "Tema"=>$request->Tema,
                "descripcion"=>$request->descripcion,
                "video"=>$url
            ]
         );
         return $this->sendResponse('Emocion Soledad agregada ',204);
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
    public function show(Soledad $soledad)
    {
        //
        $musica=MusicaTwo::all();

        return $this->sendResponse(message: 'Detalle de Emocion Soledad', result: [
            'iras' => new SoledadResource($soledad),
            'music'=> MusicTwoResource::collection( $musica),

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
    public function update(Request $request, Soledad $soledad)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $request->validate([
            'Tema' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            
        ]);

        $sol= $request -> validate([
            'video' => ['nullable','file','mimes:ppt,pptx','max:6000000'],
        ]);
        if($request->has('video')){
        $file = $sol['video'];
        $uploadedFileUrl = Cloudinary::uploadFile($file->getRealPath(),['folder'=>'emotions']);
        $url = $uploadedFileUrl->getSecurePath();
        $soledad->update([
            "video"=>$url,
         ]);
        }
         $soledad->update([
            "Tema"=>$request->Tema,
            "descripcion"=>$request->descripcion,
           
         ]);
         return $this->sendResponse('Emocion Soledad actualizada',200);
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
    public function destroy(Soledad $soledad)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $soledad->delete();
        return $this->sendResponse("Emocion Soledad eliminada", 200);
    }else{
        echo $response->message();
    }
    }
}
