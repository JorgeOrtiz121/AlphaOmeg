<?php

namespace App\Http\Controllers\Publicidad;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicidadResource;
use App\Models\Publicidad;
use Illuminate\Support\Facades\Gate;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class PublicidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $publicidad=Publicidad::all();
        return $this->sendResponse(message: 'Publish list generated successfully', result: [
            'publ' => PublicidadResource::collection($publicidad),
       
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
            'titulo' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            'evento' => ['required', 'date'],
            
        ]);

        $publicidad= $request -> validate([
            'imagen' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
              
        ]);
        $file = $publicidad['imagen'];
        $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),['folder'=>'publicidad']);
        $url = $uploadedFileUrl->getSecurePath(); 

         Publicidad::create([
            "titulo"=>$request->titulo,
            "imagen"=>$url,
            "descripcion"=>$request->descripcion,
            "evento"=>$request->evento

         ]);
         return $this->sendResponse('Publicidad created succesfully',204);
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
    public function show(Publicidad $publicidad)
    {
        //
        return $this->sendResponse(message: 'Publish details', result: [
            'publ' => new PublicidadResource($publicidad)
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
    public function update(Request $request, Publicidad $publicidad)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $request->validate([
            'titulo' => ['required', 'string', 'min:3', 'max:45'],
            'descripcion' => ['required', 'string', 'min:3', 'max:600'],
            'evento' => ['required', 'date'],
            
        ]);

        $publi= $request -> validate([
            'imagen' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
              
        ]);
        if($request->has('imagen')){
        $file = $publi['imagen'];
        $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),['folder'=>'publicidad']);
        $url = $uploadedFileUrl->getSecurePath();
        $publicidad->update([
            "imagen"=>$url,
        ]);    
    } 

         $publicidad->update([
            "titulo"=>$request->titulo,
            "descripcion"=>$request->descripcion,
            "evento"=>$request->evento

         ]);
         return $this->sendResponse('Publicidad update succesfully',204);
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
    public function destroy(Publicidad $publicidad)
    {
        //
        $response = Gate::inspect('gestion-alphao-admin');

        if($response->allowed())
        {   
        $publicidad->delete();
        return $this->sendResponse("Publish delete succesfully", 200);
    }else{
        echo $response->message();
    }
    }
}
