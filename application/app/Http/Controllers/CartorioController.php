<?php

namespace App\Http\Controllers;

use App\Cartorio;
use App\Exports\ClientsExport;
use App\Mail\SendMailUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Excel;
use App\Services\CartorioService;

class CartorioController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $serviceCartorio = new CartorioService();
        $cartorio = $serviceCartorio->listarCartorio();

        if (empty($cartorio)) {
            return response()->json('Cartório não encontrado', 400);
        }

        return response()->json($cartorio, 200);
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $serviceCartorio = new CartorioService();
            $serviceCartorio->salvarCartorio($input);

            return response()->json('Cadastrado com sucesso', 200);
        } catch (ValidatorException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function storeXML(Request $request)
    {
        try {
            $serviceCartorio = new CartorioService();
            $serviceCartorio->salvarCartoriosImportXML($request);

            return response()->json('Cadastrado com sucesso', 200);
        } catch (ValidatorException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id)
    {
        try {
            $serviceCartorio = new CartorioService();
            $serviceCartorio->editarCartorio($request, $id);

            return response()->json('Atualizado com sucesso', 200);
        } catch (ValidatorException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        $service = new CartorioService();
        $service->deletarCartorio($id);

    }

    public function export()
    {
        $file_name = \Carbon\Carbon::now()->format('d-m-Y') . '.xls';

        return (new ClientsExport())->download($file_name, Excel::XLS,
            [
                'Content-Description'=> 'File Transfer',
                'Accept' => 'application/xls',
                'Content-Type' =>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]);
    }


    public function mail()
    {
    }

    public function send(Request $request)
    {
        $clients = Cartorio::where('active', '=', 1)->where('email', '!=', '')->orWhere('email', '!=', null)->get();
        $arrTo = [];
        foreach ($clients as $client) {
            $arrTo[] = $client->email;
        }

        Mail::bcc($arrTo)->send(new SendMailUser($request));

        return redirect()->route('clients.index')->with('success',
            'Comunicado enviado para todos os torcedores ativos.');
    }
}
