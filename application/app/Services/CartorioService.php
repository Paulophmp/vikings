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
            $cartorio->documento = $this->limpaCpfCnpjCep($request['documento']);
            $cartorio->razao = $request['razao'];
            $cartorio->tipo_documento = $request['tipo_documento'];
            $cartorio->cep = $this->limpaCpfCnpjCep($request['cep']);
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

        $dadosArray = $this->montaArrayDados($cartorios);
        $resultArray['dados'] = $dadosArray;
        return $resultArray;
    }

    private function montaArrayDados($itensDados) {
        $dadosArray = [];
        foreach ($itensDados as $item) {
            $dadosArray[] = [
                'documento' => $this->formatCpfCnpjCep($item['documento']),
                'cep' => $this->formatCpfCnpjCep($item['cep']),
                'nome' => $item['nome'],
                'nome_tabeliao' => $item['nome_tabeliao'],
                'razao' => $item['razao'],
                'tipo_documento' => $item['tipo_documento'],
                'logradouro' => $item['logradouro'],
                'bairro' => $item['bairro'],
                'email' => $item['email'],
                'localidade' => $item['localidade'],
                'uf' => $item['uf'],
                'ativo' => $item['ativo'],
                'id' => $item['id'],
            ];
        }
        return $dadosArray;
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public function editarCartorio($request, $id)
    {
        $data = $request->all();
        $data['cep'] = $this->limpaCpfCnpjCep($data['cep']);
        $data['documento'] = $this->limpaCpfCnpjCep($data['documento']);

        $atualizar = Cartorio::find($id)
            ->update($data);

        return $atualizar;
    }

    public function deletarCartorio($id)
    {
        $deletarCliente = Cartorio::find($id);
        $deletarCliente->delete();
    }

    public function limpaCpfCnpjCep($valor){
        $valor = preg_replace('/[^0-9]/', '', $valor);
        return $valor;
    }

    function formatCpfCnpjCep($value)
    {
        $cnpj_cpf = preg_replace("/\D/", '', $value);

        if (strlen($cnpj_cpf) === 8) {
            return preg_replace("/(\d{5})(\d{3})/", "\$1-\$2", $cnpj_cpf);
        }elseif (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
}
