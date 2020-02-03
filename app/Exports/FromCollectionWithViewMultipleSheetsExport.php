<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\FromCollectionWithViewExport;

class FromCollectionWithViewMultipleSheetsExport implements WithMultipleSheets
{
    use Exportable;

    protected $excelData;

    public function __construct($excelData)
    {
        $this->excelData = $excelData;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->excelData as $sheetdata) {
            $sheets[] = new FromCollectionWithViewExport($sheetdata);
        }

        return $sheets;
    }
}
