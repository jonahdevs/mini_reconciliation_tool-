<?php

namespace App\Imports;

use App\Models\ProviderTransaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProviderTransactionImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ProviderTransaction([
            'transaction_reference' => $row['transaction_reference'],
            'amount' => $row['amount'],
            'status' => $row['status'],
        ]);
    }
}
