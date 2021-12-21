<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conteudo;

class ConteudoController extends Controller
{
    public function adicionar(Request $request)
    {
        $data = $request->all();
        $user = $request->user();

        //validar

        $conteudo = new Conteudo();

        $conteudo->titulo = $data['titulo'];
        $conteudo->texto = $data['texto'];
        $conteudo->link = $data['link'] ? $data['link'] : '#';
        $conteudo->imagem = $data['imagem'] ? $data['imagem'] : '#';
        $conteudo->data = date("Y-m-d H:i:s");

        $user->conteudos()->save($conteudo);
        return ['status' => true, 'conteudos' => $user->conteudos];
    }
}
