<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Booking::with(['user', 'room'])
            ->whereBetween('tanggal_mulai', [$this->startDate, $this->endDate])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Booking',
            'Nama Peminjam',
            'Email',
            'Ruangan',
            'Lokasi',
            'Keperluan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Jam Mulai',
            'Jam Selesai',
            'Jumlah Peserta',
            'Status',
            'Catatan Admin',
        ];
    }

    public function map($booking): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $booking->created_at->format('d/m/Y H:i'),
            $booking->user->nama ?? '-',
            $booking->user->email ?? '-',
            $booking->room->nama_room ?? '-',
            $booking->room->lokasi ?? '-',
            $booking->keperluan,
            $booking->tanggal_mulai->format('d/m/Y'),
            $booking->tanggal_selesai->format('d/m/Y'),
            substr($booking->jam_mulai, 0, 5),
            substr($booking->jam_selesai, 0, 5),
            $booking->jumlah_peserta,
            $this->getStatusText($booking->status),
            $booking->catatan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A90E2']
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 18,
            'C' => 20,
            'D' => 25,
            'E' => 20,
            'F' => 20,
            'G' => 30,
            'H' => 15,
            'I' => 15,
            'J' => 12,
            'K' => 12,
            'L' => 15,
            'M' => 15,
            'N' => 30,
        ];
    }

    private function getStatusText($status)
    {
        $statuses = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];

        return $statuses[$status] ?? $status;
    }
}
