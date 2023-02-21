<?php

namespace App\Http\Controllers\RutasPublicas;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactosPublicosResource;
use App\Models\ContactanosPublicos;
use Illuminate\Http\Request;

class ContactosPublicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $contacto=ContactanosPublicos::all();
        return $this->sendResponse(message: 'Contactos list generated successfully', result: [
            'contactanospublic' => ContactosPublicosResource::collection($contacto),
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
        $contactos= $request -> validate([
            'nombre' => ['required', 'string', 'min:3', 'max:45'],
            'apellido' => ['required', 'string', 'min:3', 'max:45'],
            'correo' => ['required', 'string', 'min:5', 'max:30', 'unique:contactanos'],
            'puesto' => ['required', 'string', 'min:5', 'max:100', 'unique:contactanos'],
            'contactanos' => ['required', 'numeric', 'digits:10'],
            
        ]);
         
         ContactanosPublicos::create($contactos);
         return $this->sendResponse('Contact created succesfully',204);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ContactanosPublicos $contactanos)
    {
        //
        return $this->sendResponse(message: 'Contactanos details', result: [
            'contactanos' => new ContactosPublicosResource($contactanos)
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
    public function update(Request $request, ContactanosPublicos $contactanos)
    {
        //
        $data=$request -> validate([
            'nombre' => ['required', 'string', 'min:3', 'max:45'],
            'apellido' => ['required', 'string', 'min:3', 'max:45'],
            'correo' => ['required', 'string', 'min:5', 'max:30' ],
            'puesto' => ['required', 'string', 'min:5', 'max:100'],
            'contactanos' => ['required', 'numeric', 'digits:10'],
        ]);
        $contactanos->fill($data);
        $contactanos->save();
        return $this->sendResponse('Contact update succesfully',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactanosPublicos $contactanos)
    {
        //
        $contactanos->delete();
        return $this->sendResponse("Contact delete succesfully", 200);
    }
}
