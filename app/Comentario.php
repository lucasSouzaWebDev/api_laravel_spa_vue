<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = [
        'conteudo_id', 'texto', 'data',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function conteudo()
    {
        return $this->belongsTo('App\Conteudo');
    }

    public function getDataAttribute($value)
    {
        return date('H\hi d/m/Y', strtotime($value));
    }
}
