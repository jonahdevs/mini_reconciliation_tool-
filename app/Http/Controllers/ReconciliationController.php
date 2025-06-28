<?php

namespace App\Http\Controllers;

use App\Exports\InternalOnlyTransactionsExport;
use App\Exports\MatchedTransactionsExport;
use App\Exports\MismatchedTransactionsExport;
use App\Exports\ProviderOnlyTransactionsExport;
use App\Imports\InternalTransactionImport;
use App\Imports\ProviderTransactionImport;
use App\Models\InternalTransaction;
use App\Models\ProviderTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReconciliationController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Reconciliation/Index');
    }

    // public function reconcile(Request $request)
    // {

    //     $request->validate([
    //         'internal_file' => 'required|file|mimes:csv,txt',
    //         'provider_file' => 'required|file|mimes:csv,txt',
    //     ]);


    //     try {
    //         //code...


    //         $internal = $this->parseCSV($request->file('internal_file'));
    //         $provider = $this->parseCSV($request->file('provider_file'));

    //         $matched = [];
    //         $internalOnly = [];
    //         $providerOnly = [];
    //         $mismatched = [];

    //         $providerMap = $provider->keyBy('transaction_reference');


    //         foreach ($internal as $tx) {
    //             $ref = $tx['transaction_reference'];
    //             if (isset($providerMap[$ref])) {
    //                 $providerTx = $providerMap[$ref];
    //                 if (
    //                     $tx['amount'] == $providerTx['amount'] &&
    //                     strtolower($tx['status']) === strtolower($providerTx['status'])
    //                 ) {
    //                     $matched[] = $tx;
    //                 } else {
    //                     $mismatched[] = [
    //                         'internal' => $tx,
    //                         'provider' => $providerTx,
    //                     ];
    //                 }
    //                 $providerMap->forget($ref);
    //             } else {
    //                 $internalOnly[] = $tx;
    //             }
    //         }

    //         $providerOnly = $providerMap->values()->all();

    //         session([
    //             'reconciliation' => compact('matched', 'internalOnly', 'providerOnly', 'mismatched')
    //         ]);

    //         return redirect()->route('reconciliation.index')->with([
    //             'matched' => $matched,
    //             'internalOnly' => $internalOnly,
    //             'providerOnly' => $providerOnly,
    //             'mismatched' => $mismatched,
    //         ]);
    //     } catch (\Throwable $th) {
    //         dd($th->getMessage());
    //     }
    // }

    // public function export(string $type): StreamedResponse
    // {
    //     $data = session('reconciliation')[$type] ?? [];

    //     $headers = ['Content-Type' => 'text/csv'];
    //     $callback = function () use ($data, $type) {
    //         $handle = fopen('php://output', 'w');
    //         if ($type === 'mismatched') {
    //             fputcsv($handle, ['Field', 'Internal', 'Provider']);
    //             foreach ($data as $row) {
    //                 foreach (['transaction_reference', 'amount', 'status'] as $field) {
    //                     fputcsv($handle, [
    //                         $field,
    //                         $row['internal'][$field] ?? '',
    //                         $row['provider'][$field] ?? ''
    //                     ]);
    //                 }
    //                 fputcsv($handle, ['---', '', '']);
    //             }
    //         } else {
    //             fputcsv($handle, array_keys($data[0] ?? []));
    //             foreach ($data as $row) {
    //                 fputcsv($handle, $row);
    //             }
    //         }
    //         fclose($handle);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }


    // protected function parseCSV($file): Collection
    // {
    //     $data = [];
    //     $csv = fopen($file, 'r');
    //     $headers = fgetcsv($csv);
    //     while ($row = fgetcsv($csv)) {
    //         $data[] = array_combine($headers, $row);
    //     }
    //     fclose($csv);
    //     return collect($data);
    // }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'internal_file' => 'required|file|mimes:csv,txt',
            'provider_file' => 'required|file|mimes:csv,txt',
            'overwrite' => 'boolean',
        ]);


        // Wipe old data if needed
        if ($validated['overwrite']) {
            InternalTransaction::truncate();
            ProviderTransaction::truncate();
        }

        Excel::import(new InternalTransactionImport, $validated['internal_file'], null, ExcelFormat::CSV);
        Excel::import(new ProviderTransactionImport, $validated['provider_file'], null, ExcelFormat::CSV);

        return redirect()->route('reconciliation');
    }

    public function compare()
    {
        $internal = InternalTransaction::all();
        $provider = ProviderTransaction::all();

        $matched = [];
        $internalOnly = [];
        $providerOnly = [];
        $mismatched = [];

        $providerMap = $provider->keyBy('transaction_reference');

        foreach ($internal as $tx) {
            $ref = $tx->transaction_reference;
            if ($providerMap->has($ref)) {
                $providerTx = $providerMap[$ref];
                if (
                    $tx->amount == $providerTx->amount &&
                    strtolower($tx->status) === strtolower($providerTx->status)
                ) {
                    $matched[] = $tx;
                } else {
                    $mismatched[] = [
                        'internal' => $tx,
                        'provider' => $providerTx,
                    ];
                }
                $providerMap->forget($ref);
            } else {
                $internalOnly[] = $tx;
            }
        }

        $providerOnly = $providerMap->values()->all();

        return Inertia::render('Reconciliation/Results', [
            'matched' => $matched,
            'internalOnly' => $internalOnly,
            'providerOnly' => $providerOnly,
            'mismatched' => $mismatched,
        ]);
    }

    public function export(string $type)
    {

        switch ($type) {
            case 'matched':
                $data = InternalTransaction::all()->filter(function ($tx) {
                    $provider = ProviderTransaction::where('transaction_reference', $tx->transaction_reference)->first();
                    return $provider &&
                        $tx->amount == $provider->amount &&
                        strtolower($tx->status) == strtolower($provider->status);
                });



                return Excel::download(new MatchedTransactionsExport($data), 'matched_transactions.csv');

            case 'internal':
                $providerRefs = ProviderTransaction::pluck('transaction_reference')->toArray();
                $data = InternalTransaction::whereNotIn('transaction_reference', $providerRefs)->get();
                return Excel::download(new InternalOnlyTransactionsExport($data), 'internal_only_transactions.csv');

            case 'provider':
                $internalRefs = InternalTransaction::pluck('transaction_reference')->toArray();
                $data = ProviderTransaction::whereNotIn('transaction_reference', $internalRefs)->get();
                return Excel::download(new ProviderOnlyTransactionsExport($data), 'provider_only_transactions.csv');

            case 'mismatched':
                $internal = InternalTransaction::all();
                $provider = ProviderTransaction::all()->keyBy('transaction_reference');

                $mismatched = collect();

                foreach ($internal as $tx) {
                    $ref = $tx->transaction_reference;
                    if ($provider->has($ref)) {
                        $providerTx = $provider[$ref];
                        if (
                            $tx->amount != $providerTx->amount ||
                            strtolower($tx->status) != strtolower($providerTx->status)
                        ) {
                            $mismatched->push([
                                'internal' => $tx,
                                'provider' => $providerTx,
                            ]);
                        }
                    }
                }

                return Excel::download(new MismatchedTransactionsExport($mismatched), 'mismatched_transactions.csv');

            default:
                abort(404);
        }
    }
}
