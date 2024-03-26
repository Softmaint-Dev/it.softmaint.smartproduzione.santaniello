<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ExcelExport implements FromView, WithStyles
{

    // Excel data
    private $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }


    public function view(): View
    {
        $righe = $this->rows;
        return view('exports.ftp', compact('righe'));
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('A23:I24')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E25:E60')->getAlignment()->setWrapText(true);
        //$sheet->getStyle('A1:P1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);

    }


}

