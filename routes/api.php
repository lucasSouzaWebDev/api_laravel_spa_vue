<?php
use App\User;
use App\Conteudo;
use App\Comentario;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/cadastro', "UsuarioController@cadastro");
Route::post('/login', "UsuarioController@login");
Route::middleware('auth:api')->put('/perfil', "UsuarioController@perfil");
Route::middleware('auth:api')->post('/conteudo/adicionar', "ConteudoController@adicionar");
Route::middleware('auth:api')->get('/conteudo/lista', "ConteudoController@lista");
Route::middleware('auth:api')->put('/conteudo/curtir/{id}', "ConteudoController@curtir");
Route::middleware('auth:api')->put('/conteudo/comentar/{id}', "ConteudoController@comentar");
Route::middleware('auth:api')->get('/conteudo/pagina/lista/{id}', "ConteudoController@pagina");

Route::middleware('auth:api')->post('/usuario/amigo', "UsuarioController@amigo");

Route::get('/testes', function (){
    $user = User::find(1);
    $user2 = User::find(2);

    /* $conteudos = Conteudo::all();
    foreach ($conteudos as $conteudo) {
        $conteudo->delete();
    } */
    /* 
    $user->conteudos()->create([
        'titulo' => 'Conteudo 3', 
        'texto' => 'Aqui é o texto', 
        'imagem' => 'url da imagem', 
        'link' => 'link', 
        'data' => '2021-05-15',
    ]);
    return $user->conteudos; 
    */

    // add amigo:
    //$user->curtidas()->toggle($conteudo->id);

    // add curtidas
    /* $conteudo = Conteudo::find(1);
    //$user->curtidas()->toggle($conteudo->id);

    // add comentarios

    $user->comentarios()->create([
        'conteudo_id' => $conteudo->id, 
        'texto' => 'Aqui é o texto', 
        'data' => '2021-05-15',
    ]);
    $user2->comentarios()->create([
        'conteudo_id' => $conteudo->id, 
        'texto' => 'Brabo', 
        'data' => '2021-05-15',
    ]);
    return $conteudo->comentarios;  */
});