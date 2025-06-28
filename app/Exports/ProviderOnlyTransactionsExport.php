<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProviderOnlyTransactionsExport implements FromCollection, WithHeadings
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
        return $this->transactions->map(function ($tx) {
            return [
                'id' => $tx->id,
                'transaction_reference' => $tx->transaction_reference,
                'amount' => $tx->amount,
                'status' => $tx->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Transaction Reference', 'Amount', 'Status'];
    }
}
