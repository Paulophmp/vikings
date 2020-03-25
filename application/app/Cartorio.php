<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cartorio extends Model
{
    protected $fillable = [
        'nome',
        'razao',
        'tipo_documento',
        'documento',
        'cep',
        'logradouro',
        'bairro',
        'email',
        'localidade',
        'uf',
        'nome_tabeliao',
        'ativo'
    ];
}
