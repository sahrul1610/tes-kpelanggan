<?php

namespace App\Exports;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\KeluhanPelanggan;
use App\Models\KeluhanStatusHis;
//use Illuminate\Support\Collection;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View as LaravelView;

class KeluhanPelangganExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        // Sheet untuk Keluhan Pelanggan
        $sheets[] = new class implements FromCollection, WithHeadings {
            public function collection()
            {
                return KeluhanPelanggan::all();
            }

            public function headings(): array
            {
                return [
                    'ID',
                    'Nama',
                    'Email',
                    'Nomor HP',
                    'Status Keluhan',
                    'Keluhan',
                    'Created At',
                    'Updated At',
                ];
            }
        };

        // Sheet untuk Keluhan Status History
        $sheets[] = new class implements FromCollection, WithHeadings {
            public function collection()
            {
                return KeluhanStatusHis::all();
            }

            public function headings(): array
            {
                return [
                    'ID',
                    'Keluhan ID',
                    'Status Keluhan',
                    'Created At',
                    'Updated At',
                ];
            }
        };

        return $sheets;
    }

    public function exportPdf()
    {
        $fileName = 'keluhan_pelanggan_export.pdf';

        $dompdf = new Dompdf();

        // Load the HTML template
        $html = LaravelView::make('exports.keluhan_pelanggan_export', [
            'data' => $this->collection()->toArray(),
            'headings' => $this->headings(),
        ])->render();

        $dompdf->loadHtml($html);

        // Set options for PDF rendering
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf->setOptions($options);

        // Render PDF (output to browser or file)
        $dompdf->render();

        // Output PDF to the browser
        $dompdf->stream($fileName);
    }
}
