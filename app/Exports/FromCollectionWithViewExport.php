<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class FromCollectionWithViewExport implements FromView, WithTitle
{
    public $view = 'exports.no-data';
    public $data = [];
    public $sheetname = 'Worksheet';

    public function __construct($excelData)
    {
        if (isset($excelData['view'])) {
            $this->view = $excelData['view'];
        }

        if (isset($excelData['data'])) {
            $this->data = $excelData['data'];
        }

        if (isset($excelData['sheetname'])) {
            $this->sheetname = $excelData['sheetname'];
        }
    }

    public function view(): View
    {
        return view($this->view, $this->data);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->sheetname;
    }

}
