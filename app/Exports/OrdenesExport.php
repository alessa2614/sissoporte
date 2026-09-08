<?php

namespace App\Exports;

use App\Models\Orden;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;


class OrdenesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    WithColumnWidths,
    WithEvents,
    WithCustomStartCell
{
    protected $desde;
    protected $hasta;
    protected $estado;
    protected $totalRows;

    public function __construct($desde, $hasta, $estado = null)
    {
        $this->desde  = $desde;
        $this->hasta  = $hasta;
        $this->estado = $estado;
    }
    public function startCell(): string
    {
        return 'A2';
    }
    public function collection()
    {
        $query = Orden::with(['cliente', 'tipoEquipo', 'tecnico', 'servicios', 'adicionales'])
            ->whereBetween('created_at', [
                $this->desde . ' 00:00:00',
                $this->hasta . ' 23:59:59'
            ]);

        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        $result = $query->orderBy('created_at', 'desc')->get();
        $this->totalRows = $result->count();
        return $result;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Cliente',
            'Celular',
            'Equipo',
            'Marca / Modelo',
            'Problema',
            'Técnico',
            'Estado',
            'Servicios (S/.)',
            'Adicionales (S/.)',
            'Total (S/.)',
            'Fecha',
        ];
    }

    public function map($orden): array
    {
        return [
            $orden->codigo ?? '',
            $orden->cliente->nombre ?? '—',
            $orden->cliente->celular ?? '—',
            $orden->tipoEquipo->nombre ?? '—',
            trim(($orden->marca ?? '') . ' ' . ($orden->modelo ?? '')),
            $orden->descripcion ?? '',
            $orden->tecnico->name ?? '—',
            strtoupper(str_replace('_', ' ', $orden->estado ?? '')),
            $orden->servicios->sum('precio'),
            $orden->adicionales->where('estado', 'aprobado')->sum('costo'),
            $orden->total_final ?? 0,
            optional($orden->created_at)->format('d/m/Y'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 22,
            'C' => 14,
            'D' => 16,
            'E' => 18,
            'F' => 30,
            'G' => 22,
            'H' => 18,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Cabecera
            2 => [
                'font' => [
                    'bold'  => true,
                    'size'  => 11,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF435EBE'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $dataStartRow = 3;
                $dataEndRow   = $this->totalRows > 0
                    ? $this->totalRows + 2
                    : 3;

                $totalRow = $dataEndRow + 1;

                // ─── TÍTULO ─────────────────────────
                $sheet->mergeCells('A1:L1');
                $sheet->setCellValue(
                    'A1',
                    'NEXOS TIENDAS — Reporte de Órdenes | '
                        . $this->desde . ' al ' . $this->hasta
                );

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'size'  => 14,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF2C3E8C'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(22);

                // ─── BORDES TABLA ───────────────────
                if ($this->totalRows > 0) {
                    $sheet->getStyle('A2:L' . $dataEndRow)
                        ->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['argb' => 'FFD0D0D0'],
                                ],
                            ],
                        ]);
                }

                // ─── FILAS ALTERNAS ─────────────────
                for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:L{$i}")
                            ->applyFromArray([
                                'fill' => [
                                    'fillType'   => Fill::FILL_SOLID,
                                    'startColor' => ['argb' => 'FFF0F4FF'],
                                ],
                            ]);
                    }
                }

                // ─── ALINEACIÓN NUMÉRICA ─────────────
                $sheet->getStyle("I{$dataStartRow}:L{$dataEndRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // ─── FILA TOTAL ─────────────────────
                if ($this->totalRows > 0) {

                    $sheet->mergeCells("A{$totalRow}:H{$totalRow}");
                    $sheet->setCellValue("A{$totalRow}", 'TOTALES DEL PERÍODO');

                    $sheet->setCellValue("I{$totalRow}", "=SUM(I{$dataStartRow}:I{$dataEndRow})");
                    $sheet->setCellValue("J{$totalRow}", "=SUM(J{$dataStartRow}:J{$dataEndRow})");
                    $sheet->setCellValue("K{$totalRow}", "=SUM(K{$dataStartRow}:K{$dataEndRow})");
                    $sheet->setCellValue("L{$totalRow}", "=SUM(L{$dataStartRow}:L{$dataEndRow})");

                    $sheet->getStyle("A{$totalRow}:L{$totalRow}")
                        ->applyFromArray([
                            'font' => [
                                'bold'  => true,
                                'size'  => 11,
                                'color' => ['argb' => 'FFFFFFFF'],
                            ],
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FF28A745'],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);

                    $sheet->getRowDimension($totalRow)->setRowHeight(22);
                }

                // ─── WRAP PROBLEMA ──────────────────
                $sheet->getStyle("F{$dataStartRow}:F{$dataEndRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                // ─── CONGELAR PANEL ─────────────────
                $sheet->freezePane('A3');
            },
        ];
    }
    public function title(): string
    {
        return 'Órdenes ' . $this->desde . ' al ' . $this->hasta;
    }
}
