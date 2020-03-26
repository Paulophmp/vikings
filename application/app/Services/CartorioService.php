<?php

namespace App\Services;

use App\Cartorio;

class CartorioService
{

    public function salvarCartoriosImportXML($request)
    {
        $clients = simplexml_load_file($request->files->get('file'));
        $clients = json_decode(json_encode((array) $clients), true);

        foreach ($clients['cartorio'] as $client) {
//        dd($client);
            $arrReplace = [
                'nome' => $client['nome'],
                'documento' => $client['documento'],
                'razao' => $client['razao'],
                'tipo_documento' => $client['tipo_documento'],
                'cep' => $client['cep'],
                'endereco' => $client['endereco'],
                'tabeliao' => $client['tabeliao'],
                'bairro' => $client['bairro'],
                'cidade' => $client['cidade'],
                'uf' => $client['uf'],
                'ativo' => $client['ativo'] ? 1 : 0,
//                'email' => $client['email'],
            ];
            // Verify if document is unique to save only different data
            $find = Cartorio::where('documento', '=', $client['documento'])->first();

            $client = new Cartorio();

            if (!empty($find)) {
                $client = $client::find($find->id);
            }

            $client->nome = $arrReplace['nome'];
            $client->documento = $arrReplace['documento'];
            $client->razao = $arrReplace['razao'];
            $client->tipo_documento = $arrReplace['tipo_documento'];
            $client->cep = $arrReplace['cep'];
            $client->logradouro = $arrReplace['endereco'];
            $client->nome_tabeliao = $arrReplace['tabeliao'];
            $client->bairro = $arrReplace['bairro'];
            $client->localidade = $arrReplace['cidade'];
            $client->ativo = $arrReplace['ativo'];
            $client->uf = $arrReplace['uf'];

            $client->save();
        }
    }

    public function salvarCartorio($request)
    {

            $client = new Cartorio();
            $client->name = $request['name'];
            $client->document = $request['document'];
            $client->postcode = $request['postcode'];
            $client->address = $request['address'];
            $client->district = $request['district'];
            $client->city = $request['city'];
            $client->active = $request['active'];
            $client->state = $request['state'];

            $client->save();
    }

    public function listarCartorio()
    {
        $clients = Cartorio::orderBy('nome')->get();

        return $clients;
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
