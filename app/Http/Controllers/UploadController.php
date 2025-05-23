<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        Log::debug('upload de imagem chegou aqui', ['dados' => $request->all()]);
        $request->validate([
            'imagem' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
    
        $path = $request->file('imagem')->store('imgs', 'public');
    
        return response()->json(['url' => asset('storage/' . $path)]);
    }
}
