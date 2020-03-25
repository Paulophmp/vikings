<?php

namespace App\Exports;

use App\Cartorio;
use Maatwebsite\Excel\Cell;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ClientsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;

    public function headings(): array
    {
        return [
            'NOME',
            'RAZÃO',
            'DOCUMENTO',
            'CEP',
            'ENDEREÇO',
            'BAIRRO',
            'CIDADE',
            'UF',
            'TELEFONE',
            'E-MAIL',
            'TABELIÃO',
            'ATIVO'
        ];
    }


    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }
        if (is_string($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        if (is_bool($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_BOOL);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function map($invoice): array
    {
        return [
            $invoice->nome,
            $invoice->razao,
            $invoice->tipo_documento,
            $invoice->documento,
            $invoice->cep,
            $invoice->logradouro,
            $invoice->bairro,
            $invoice->email,
            $invoice->localidade,
            $invoice->uf,
            $invoice->nome_tabeliao,
            $invoice->ativo ? 'SIM' : 'NÃO',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Cartorio::all();
    }
}
