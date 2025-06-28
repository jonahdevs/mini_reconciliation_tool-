<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MismatchedTransactionsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $transactions;

    public function __construct(Collection $transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions->map(function ($pair) {
            return [
                'internal_reference' => $pair['internal']->transaction_reference,
                'internal_amount' => $pair['internal']->amount,
                'internal_status' => $pair['internal']->status,
                'provider_reference' => $pair['provider']->transaction_reference,
                'provider_amount' => $pair['provider']->amount,
                'provider_status' => $pair['provider']->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Internal Reference',
            'Internal Amount',
            'Internal Status',
            'Provider Reference',
            'Provider Amount',
            'Provider Status'
        ];
    }
}
