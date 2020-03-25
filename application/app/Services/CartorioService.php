<?php

namespace App\Services;

use App\Cartorio;

class CartorioService
{

    public function salvarCartoriosImportXML($request)
    {
        $clients = simplexml_load_file($request->files->get('file'));
        $clients = json_decode(json_encode((array) $clients), true);

        foreach ($clients['torcedor'] as $client) {
            $arrReplace = [
                'name' => $client['@attributes']['nome'],
                'document' => $client['@attributes']['documento'],
                'postcode' => $client['@attributes']['cep'],
                'address' => $client['@attributes']['endereco'],
                'district' => $client['@attributes']['bairro'],
                'city' => $client['@attributes']['cidade'],
                'state' => $client['@attributes']['uf'],
                'telephone' => $client['@attributes']['telefone'],
                'email' => $client['@attributes']['email'],
                'active' => $client['@attributes']['ativo'] ? 1 : 0,
            ];
            // Verify if document is unique to save only different data
            $find = Cartorio::where('document', '=', $client['@attributes']['documento'])->first();

            $client = new Cartorio();

            if (!empty($find)) {
                $client = $client::find($find->id);
            }

            $client->name = $arrReplace['name'];
            $client->document = $arrReplace['document'];
            $client->postcode = $arrReplace['postcode'];
            $client->address = $arrReplace['address'];
            $client->district = $arrReplace['district'];
            $client->city = $arrReplace['city'];
            $client->email = $arrReplace['email'];
            $client->telephone = $arrReplace['telephone'];
            $client->active = $arrReplace['active'];
            $client->state = $arrReplace['state'];

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
