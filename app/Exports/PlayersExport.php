<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlayersExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function collection()
    {
        return collect($this->data);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            'Role',
            'Name',
            'Email',
            'Phone Number',
            'Grad Year',
            'GPA',
            'College/School Name',
            'Travel/Club',
            'Sports',
            'Positions',
            'Twitter',
            'Instagram',
            'SAT Score',
//            'Scanned By',
            'Scanned at'
        ];
    }
}
