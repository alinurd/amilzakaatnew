<?php

namespace App\Exports;

use App\Models\Muzakki;
use App\Models\MuzakkiHeader;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MuzakkiReportExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new MuzakkiSheetRp('Rupiah', 'Rupiah');
        $sheets[] = new MuzakkiSheetLt('Liter', 'Liter');
        $sheets[] = new MuzakkiSheetKg('Kg', 'Kilogram');

        return $sheets;
    }
}

class MuzakkiSheetRp implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $satuan;
    private $title;

    public function __construct($satuan, $title)
    {
        $this->satuan = $satuan;
        $this->title = $title; // Menggunakan judul yang diberikan
    }

    public function collection()
    {
        $detail = Muzakki::with('user', 'kategori')->get();
        $header = MuzakkiHeader::with('user')->get();

        $dataRupiah = [];
        $dataLiter = [];
        $dataKg = [];

        $totalsRupiah = 0;
        $totalsLiter = 0;
        $totalsKg = 0;

        $no = 1;
        foreach ($detail as $item) {
            foreach ($header as $h) {
                if ($item->code == $h->code) {
                    $jumlah_bayar = str_replace(',', '.', $item->jumlah_bayar);
                    $jumlah_bayar = floatval($jumlah_bayar);

                    if (is_numeric($jumlah_bayar)) {
                        $satuan = $item->satuan;

                        switch ($satuan) {
                            case 'Rupiah':
                                $totalsRupiah += $jumlah_bayar;
                                $dataRupiah[] = [
                                    'No' => $no++,
                                    'Code Invoice' => $item->code,
                                    'Tanggal' => $h->created_at,
                                    'Dibayarkan Oleh' => $h->user->nama_lengkap,
                                    'Nama' => $item->user->nama_lengkap,
                                    'Kategori' => $item->kategori->nama_kategori,
                                    'Type' => $item->type,
                                    'Satuan' => $satuan,
                                    'Jumlah' => $jumlah_bayar,
                                ];
                                break;
                          
                        }
                    } else {
                        // Lakukan penanganan jika $jumlah_bayar bukan numerik 
                        // Contoh: set nilai $jumlah_bayar ke 0 atau lakukan tindakan lain
                        $jumlah_bayar = 0;
                    }
                }
            }
        }

        // Tambahkan total berdasarkan satuan ke data
        $dataRupiah[] = [
            'No' => '',
            'Code Invoice' => '',
            'Tanggal' => '',
            'Dibayarkan Oleh' => '',
            'Nama' => '',
            'Kategori' => '',
            'Type' => 'Total',
            'Satuan' => 'Rupiah',
            'Jumlah' => $totalsRupiah,
        ];
        
      
        

        return collect(array_merge($dataRupiah, $dataLiter, $dataKg));
    }

    public function headings(): array
    {
        return [
            'No',
            'Code Invoice',
            'Tanggal',
            'Dibayarkan Oleh',
            'Nama',
            'Kategori',
            'Type',
            'Satuan',
            'Jumlah',
        ];
    }

    public function title(): string
    {
        return $this->title; // Mengembalikan judul sheet
    }
}



class MuzakkiSheetLt implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $satuan;
    private $title;

    public function __construct($satuan, $title)
    {
        $this->satuan = $satuan;
        $this->title = $title; // Menggunakan judul yang diberikan
    }

    public function collection()
    {
        $detail = Muzakki::with('user', 'kategori')->get();
        $header = MuzakkiHeader::with('user')->get();

        $dataRupiah = [];
        $dataLiter = [];
        $dataKg = [];

        $totalsRupiah = 0;
        $totalsLiter = 0;
        $totalsKg = 0;

        $no = 1;
        foreach ($detail as $item) {
            foreach ($header as $h) {
                if ($item->code == $h->code) {
                    $jumlah_bayar = str_replace(',', '.', $item->jumlah_bayar);
                    $jumlah_bayar = floatval($jumlah_bayar);

                    if (is_numeric($jumlah_bayar)) {
                        $satuan = $item->satuan;

                        switch ($satuan) {
                            case 'Liter':
                                $totalsLiter += $jumlah_bayar;
                                $dataLiter[] = [
                                    'No' => $no++,
                                    'Code Invoice' => $item->code,
                                    'Tanggal' => $h->created_at,
                                    'Dibayarkan Oleh' => $h->user->nama_lengkap,
                                    'Nama' => $item->user->nama_lengkap,
                                    'Kategori' => $item->kategori->nama_kategori,
                                    'Type' => $item->type,
                                    'Satuan' => $satuan,
                                    'Jumlah' => $jumlah_bayar,
                                ];
                                break;
                        }
                    } else {
                        // Lakukan penanganan jika $jumlah_bayar bukan numerik 
                        // Contoh: set nilai $jumlah_bayar ke 0 atau lakukan tindakan lain
                        $jumlah_bayar = 0;
                    }
                }
            }
        }

    
        
        $dataLiter[] = [
            'No' => '',
            'Code Invoice' => '',
            'Tanggal' => '',
            'Dibayarkan Oleh' => '',
            'Nama' => '',
            'Kategori' => '',
            'Type' => 'Total',
            'Satuan' => 'Liter',
            'Jumlah' => $totalsLiter,
      ];
  

        return collect(array_merge($dataRupiah, $dataLiter, $dataKg));
    }

    public function headings(): array
    {
        return [
            'No',
            'Code Invoice',
            'Tanggal',
            'Dibayarkan Oleh',
            'Nama',
            'Kategori',
            'Type',
            'Satuan',
            'Jumlah',
        ];
    }

    public function title(): string
    {
        return $this->title; // Mengembalikan judul sheet
    }
}



class MuzakkiSheetKg implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $satuan;
    private $title;

    public function __construct($satuan, $title)
    {
        $this->satuan = $satuan;
        $this->title = $title;
    }

    public function collection()
    {
        $detail = Muzakki::with('user', 'kategori')->get();
        $header = MuzakkiHeader::with('user')->get();

        $dataRupiah = [];
        $dataLiter = [];
        $dataKg = [];

        $totalsRupiah = 0;
        $totalsLiter = 0;
        $totalsKg = 0;

        $no = 1;
        foreach ($detail as $item) {
            foreach ($header as $h) {
                if ($item->code == $h->code) {
                    $jumlah_bayar = str_replace(',', '.', $item->jumlah_bayar);
                    $jumlah_bayar = floatval($jumlah_bayar);

                    if (is_numeric($jumlah_bayar)) {
                        $satuan = $item->satuan;

                        switch ($satuan) {
                            case 'Kg':
                                $totalsKg += $jumlah_bayar;
                                $dataKg[] = [
                                    'No' => $no++,
                                    'Code Invoice' => $item->code,
                                    'Tanggal' => $h->created_at,
                                    'Dibayarkan Oleh' => $h->user->nama_lengkap,
                                    'Nama' => $item->user->nama_lengkap,
                                    'Kategori' => $item->kategori->nama_kategori,
                                    'Type' => $item->type,
                                    'Satuan' => $satuan,
                                    'Jumlah' => $jumlah_bayar,
                                ];
                                break;
                        }
                    } else {
                        // Lakukan penanganan jika $jumlah_bayar bukan numerik 
                        // Contoh: set nilai $jumlah_bayar ke 0 atau lakukan tindakan lain
                        $jumlah_bayar = 0;
                    }
                }
            }
        }

        // Tambahkan total berdasarkan satuan ke data
       
        
        $dataKg[] = [
            'No' => '',
            'Code Invoice' => '',
            'Tanggal' => '',
            'Dibayarkan Oleh' => '',
            'Nama' => '',
            'Kategori' => '',
            'Type' => 'Total',
            'Satuan' => 'Kg',
            'Jumlah' => $totalsKg,
      ];
        

        return collect(array_merge($dataRupiah, $dataLiter, $dataKg));
    }

    public function headings(): array
    {
        return [
            'No',
            'Code Invoice',
            'Tanggal',
            'Dibayarkan Oleh',
            'Nama',
            'Kategori',
            'Type',
            'Satuan',
            'Jumlah',
        ];
    }
    public function title(): string
    {
        return $this->title; // Mengembalikan judul sheet
    }
}
