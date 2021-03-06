<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Conteudo;
use App\User;

class ConteudoController extends Controller
{
    public function lista(Request $request)
    {
        $user = $request->user();
        $amigos = $user->amigos()->pluck('id');
        $amigos->push($user->id);
        $conteudos = Conteudo::whereIn('user_id', $amigos)->with('user')->orderBy('data', 'DESC')->paginate(5);

        foreach($conteudos as $key => $conteudo){
            $conteudo->total_curtidas = $conteudo->curtidas()->count();
            $conteudo->comentarios = $conteudo->comentarios()->with('user')->get();
            $curtiu = $user->curtidas()->find($conteudo->id);
            if($curtiu){
                $conteudo->curtiu_conteudo = true;
            }else{
                $conteudo->curtiu_conteudo = false;
            }
        }
        return ['status' => true, 'conteudos' => $conteudos];
    }

    public function pagina($id, Request $request)
    {
        $donoDaPagina = User::find($id);
        if(!$donoDaPagina){
            return ['status' => false, 'erro' => 'Usuário não existe!'];
        }
        $conteudos = $donoDaPagina->conteudos()->with('user')->orderBy('data', 'DESC')->paginate(5);
        $user = $request->user();

        foreach($conteudos as $key => $conteudo){
            $conteudo->total_curtidas = $conteudo->curtidas()->count();
            $conteudo->comentarios = $conteudo->comentarios()->with('user')->get();
            $curtiu = $user->curtidas()->find($conteudo->id);
            if($curtiu){
                $conteudo->curtiu_conteudo = true;
            }else{
                $conteudo->curtiu_conteudo = false;
            }
        }
        return ['status' => true, 'conteudos' => $conteudos, 'dono' => $donoDaPagina];
    }

    public function adicionar(Request $request)
    {
        $data = $request->all();
        $user = $request->user();

        //validar
        $validacao = Validator::make($data, [
            'titulo' => 'required',
            'texto' => 'required',
        ]);

        if ($validacao->fails()) {
            return ['status' => false, 'validacao' => true, 'erros' => $validacao->errors()];
        }

        $conteudo = new Conteudo();

        $conteudo->titulo = $data['titulo'];
        $conteudo->texto = $data['texto'];
        $conteudo->link = $data['link'] ? $data['link'] : '#';
        $conteudo->imagem = $data['imagem'] ? $data['imagem'] : '#';
        $conteudo->data = date("Y-m-d H:i:s");

        $user->conteudos()->save($conteudo);
        $conteudos = Conteudo::with('user')->orderBy('data', 'DESC')->paginate(5);
        return ['status' => true, 'conteudos' => $conteudos];
    }

    public function curtir($id, Request $request)
    {
        $conteudo = Conteudo::find($id);
        if(!$conteudo){
            return ['status' => false, 'erro' => 'Conteudo não existe.'];
        }
        $user = $request->user();
        $user->curtidas()->toggle($conteudo->id);
        return ['status' => true, 'curtidas' => $conteudo->curtidas()->count(), 'lista' => $this->lista($request)];
    }

    public function curtirpagina($id, Request $request)
    {
        $conteudo = Conteudo::find($id);
        if(!$conteudo){
            return ['status' => false, 'erro' => 'Conteudo não existe.'];
        }
        $user = $request->user();
        $user->curtidas()->toggle($conteudo->id);
        return ['status' => true, 'curtidas' => $conteudo->curtidas()->count(), 'lista' => $this->pagina($conteudo->user_id, $request)];
    }

    public function comentar($id, Request $request)
    {
        $conteudo = Conteudo::find($id);
        if(!$conteudo){
            return ['status' => false, 'erro' => 'Conteudo não existe.'];
        }
        $user = $request->user();
        $user->comentarios()->create([
            'conteudo_id' => $conteudo->id, 
            'texto' => $request->texto, 
            'data' => '2021-05-15',
        ]);
        return ['status' => true, 'lista' => $this->lista($request)];
    }

    public function comentarpagina($id, Request $request)
    {
        $conteudo = Conteudo::find($id);
        if(!$conteudo){
            return ['status' => false, 'erro' => 'Conteudo não existe.'];
        }
        $user = $request->user();
        $user->comentarios()->create([
            'conteudo_id' => $conteudo->id, 
            'texto' => $request->texto, 
            'data' => '2021-05-15',
        ]);
        return ['status' => true, 'lista' => $this->pagina($conteudo->user_id, $request)];
    }
}
