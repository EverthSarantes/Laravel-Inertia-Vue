<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GeneralExcelExport implements FromView, WithEvents
{
    public $table_html;
    public $file_name;
    public $classStyles;

    public function __construct($table_html, $file_name = null)
    {
        $this->table_html = $table_html;
        $this->file_name = $file_name;
        $this->classStyles = config('excelstyles');
    }

    public function view(): View
    {
        $html = $this->table_html;
        $file_name = $this->file_name;

        return view('exports.generalexcelexport', compact('html', 'file_name'));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
        
                $firstRowStyle = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                        ],
                    ],
                ];
        
                $generalStyle = [
                    'font' => [
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
        
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
        
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray($generalStyle);
                $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray($firstRowStyle);

                for ($row = 1; $row <= $highestRow; $row++) {
                    for ($col = 'A'; $col <= $highestColumn; $col++) {

                        $cell = $sheet->getCell("{$col}{$row}");
                        $cellValue = $cell->getValue();

                        if (preg_match('/\{(.+?)\}(.*)/', $cellValue, $matches)) {
                            $classes = explode(' ', $matches[1]);
                            $value = $matches[2];

                            $sheet->getStyle("{$col}{$row}")->applyFromArray($styleArray);
                            foreach ($classes as $class) {
                                if (isset($this->classStyles[$class])) {
                                    $sheet->getStyle("{$col}{$row}")->applyFromArray($this->classStyles[$class]);
                                }
                            }

                            $cell->setValue($value);
                        }
                    }
                }

                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}