<?php

namespace App\Imports;

use App\Models\InternalTransaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InternalTransactionImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new InternalTransaction([
            'transaction_reference' => $row['transaction_reference'],
            'amount' => $row['amount'],
            'status' => $row['status'],
        ]);
    }
}
