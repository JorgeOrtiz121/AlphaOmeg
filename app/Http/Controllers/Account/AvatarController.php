<?php

namespace App\Http\Controllers\Account;

use App\File;
use App\Http\Requests;
use Dropbox\WriteMode;
//use Cloudinary\Cloudinary;
use Spatie\Dropbox\Client;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AvatarController extends Controller
{
    //
    

    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request -> validate([
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
        ]);

        // Se obtiene el usario que esta haciendo el Request
        $user = $request->user();
        // Se invoca la función del helper
        // Pasando a la función la imagen del request
        /*$uploaded_image_path = ImageHelper::getLoadedImagePath(
            $request['image'],
            // https://styde.net/nuevo-operador-nullsafe-en-php-8/
            $user->image?->path,
            'avatars'
        );*/
        //Storage::disk('dropbox')->put("avatars",$request['image']);
        //$url = Storage::disk('dropbox')->url($uploaded_image_path);
        $file = $request['image'];
        $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),['folder'=>'avatars']);
        $url = $uploadedFileUrl->getSecurePath();
        //$dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
        //$url='https://dropbox';
        //$linkdisk=ImageHelper::getDiskImageUrl($uploaded_image_path);
         
       
        // Se hace uso del Trait para asociar esta imagen con el modelo user
        $user->attachImage($url,$url);
        // Uso de la función padre
        return $this->sendResponse('Avatar actualizado satisfactoriamente');

    }


}