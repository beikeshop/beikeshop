<?php

namespace Beike\Shop\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use League\Csv\CannotInsertRecord;

class Controller extends \App\Http\Controllers\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $filename
     * @param $header
     * @param $records
     * @return mixed
     * @throws CannotInsertRecord
     */
    public function downloadCsv($filename, $header, $records): mixed
    {
        if (! str_contains($filename, '.csv')) {
            $filename = $filename . '-' . date('YmdHis') . '.csv';
        }

        $headers = [
            'Content-Type'              => 'application/octet-stream',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition'       => 'attachment; filename=' . $filename,
        ];

        $csv = \League\Csv\Writer::createFromString('');
        $csv->insertOne($header);
        $csv->insertAll($records);

        return response($csv, 200, $headers);
    }
}
