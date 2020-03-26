<?php

namespace App\Services;

use App\Cartorio;

class CartorioService
{

    public function salvarCartoriosImportXML($request)
    {
        $cartorios = simplexml_load_file($request->files->get('file'));
        $cartorios = json_decode(json_encode((array) $cartorios), true);

        foreach ($cartorios['cartorio'] as $cartorio) {
//        dd($cartorio);
            $arrReplace = [
                'nome' => $cartorio['nome'],
                'documento' => $cartorio['documento'],
                'razao' => $cartorio['razao'],
                'tipo_documento' => $cartorio['tipo_documento'],
                'cep' => $cartorio['cep'],
                'endereco' => $cartorio['endereco'],
                'tabeliao' => $cartorio['tabeliao'],
                'bairro' => $cartorio['bairro'],
                'cidade' => $cartorio['cidade'],
                'uf' => $cartorio['uf'],
                'ativo' => $cartorio['ativo'] ? 1 : 0,
//                'email' => $cartorio['email'],
            ];
            // Verify if document is unique to save only different data
            $find = Cartorio::where('documento', '=', $cartorio['documento'])->first();
//dd($find);
            $cartorio = new Cartorio();

            if (!empty($find)) {
                $cartorio = $cartorio::find($find->id);
            }

            $cartorio->nome = $arrReplace['nome'];
            $cartorio->documento = $arrReplace['documento'];
            $cartorio->razao = $arrReplace['razao'];
            $cartorio->tipo_documento = $arrReplace['tipo_documento'];
            $cartorio->cep = $arrReplace['cep'];
            $cartorio->logradouro = $arrReplace['endereco'];
            $cartorio->nome_tabeliao = $arrReplace['tabeliao'];
            $cartorio->bairro = $arrReplace['bairro'];
            $cartorio->localidade = $arrReplace['cidade'];
            $cartorio->ativo = $arrReplace['ativo'];
            $cartorio->uf = $arrReplace['uf'];

            $cartorio->save();
        }
    }

    public function salvarCartorio($request)
    {

            $cartorio = new Cartorio();
            $cartorio->nome = $request['nome'];
            $cartorio->documento = $request['documento'];
            $cartorio->razao = $request['razao'];
            $cartorio->tipo_documento = $request['tipo_documento'];
            $cartorio->cep = $request['cep'];
            $cartorio->logradouro = $request['logradouro'];
            $cartorio->nome_tabeliao = $request['nome_tabeliao'];
            $cartorio->bairro = $request['bairro'];
            $cartorio->localidade = $request['localidade'];
            $cartorio->ativo = $request['ativo'];
            $cartorio->uf = $request['uf'];

            $cartorio->save();
    }

    public function listarCartorio()
    {
        $cartorios = Cartorio::orderBy('nome')->get();

        return $cartorios;
    }

    public function editarCartorio($request, $id)
    {
        $atualizar = Cartorio::find($id)
            ->update($request->all());

        return $atualizar;
    }

    public function deletarCartorio($id)
    {
        $deletarCliente = Cartorio::find($id);
        $deletarCliente->delete();
    }
}
