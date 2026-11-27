<?php

namespace Database\Seeders;

use App\Models\BangunDatarShape;
use Illuminate\Database\Seeder;

class BangunDatarShapeSeeder extends Seeder
{
    /**
     * Seed the master bangun datar shapes including segitiga & trapesium variants.
     */
    public function run(): void
    {
        $shapes = [
            [
                'name' => 'Persegi',
                'type' => 'persegi',
                'subtype' => null,
                'color' => '#F59E0B',
                'bagian_damar_kurung' => 'Kotak Dagangan',
                'urutan' => 1,
                'ciri_ciri' => [
                    'Memiliki 4 sisi yang sama panjang',
                    'Memiliki 4 sudut siku-siku (90°)',
                    'Memiliki 2 diagonal yang sama panjang dan saling berpotongan tegak lurus',
                ],
            ],
            [
                'name' => 'Persegi Panjang',
                'type' => 'persegi_panjang',
                'subtype' => null,
                'color' => '#10B981',
                'bagian_damar_kurung' => 'Meja Jualan',
                'urutan' => 2,
                'ciri_ciri' => [
                    'Memiliki 2 pasang sisi berhadapan yang sejajar dan sama panjang',
                    'Memiliki 4 sudut siku-siku (90°)',
                    'Memiliki 2 diagonal yang sama panjang',
                ],
            ],
            [
                'name' => 'Lingkaran',
                'type' => 'lingkaran',
                'subtype' => null,
                'color' => '#0284C7',
                'bagian_damar_kurung' => 'Buah / Lentera Bulan',
                'urutan' => 3,
                'ciri_ciri' => [
                    'Memiliki 1 sisi lengkung tertutup',
                    'Tidak memiliki titik sudut (0 sudut)',
                    'Memiliki simetri lipat dan simetri putar tak terhingga',
                ],
            ],
            [
                'name' => 'Segitiga Sama Sisi',
                'type' => 'segitiga',
                'subtype' => 'sama_sisi',
                'color' => '#E57354',
                'bagian_damar_kurung' => 'Atap Menara',
                'urutan' => 4,
                'ciri_ciri' => [
                    'Ketiga sisinya sama panjang',
                    'Ketiga sudutnya sama besar, masing-masing 60°',
                    'Memiliki 3 simetri lipat dan 3 simetri putar',
                ],
            ],
            [
                'name' => 'Segitiga Sama Kaki',
                'type' => 'segitiga',
                'subtype' => 'sama_kaki',
                'color' => '#FB7185',
                'bagian_damar_kurung' => 'Atap Rumah',
                'urutan' => 5,
                'ciri_ciri' => [
                    'Memiliki 2 sisi (kaki) yang sama panjang',
                    'Memiliki 2 sudut alas yang sama besar',
                    'Memiliki 1 sumbu simetri lipat',
                ],
            ],
            [
                'name' => 'Segitiga Sembarang',
                'type' => 'segitiga',
                'subtype' => 'sembarang',
                'color' => '#F97316',
                'bagian_damar_kurung' => null,
                'urutan' => 6,
                'ciri_ciri' => [
                    'Ketiga sisinya memiliki panjang yang berbeda-beda',
                    'Ketiga sudutnya memiliki besar yang berbeda-beda',
                    'Tidak memiliki simetri lipat',
                ],
            ],
            [
                'name' => 'Segitiga Lancip',
                'type' => 'segitiga',
                'subtype' => 'lancip',
                'color' => '#14B8A6',
                'bagian_damar_kurung' => null,
                'urutan' => 7,
                'ciri_ciri' => [
                    'Ketiga sudutnya merupakan sudut lancip (kurang dari 90°)',
                    'Jumlah ketiga sudutnya adalah 180°',
                ],
            ],
            [
                'name' => 'Segitiga Siku-Siku',
                'type' => 'segitiga',
                'subtype' => 'siku_siku',
                'color' => '#EF4444',
                'bagian_damar_kurung' => 'Sudut Penyangga',
                'urutan' => 8,
                'ciri_ciri' => [
                    'Memiliki 1 sudut siku-siku (tepat 90°)',
                    'Memiliki 2 sudut lancip yang berjumlah 90°',
                    'Sisi di depan sudut siku-siku adalah sisi terpanjang',
                ],
            ],
            [
                'name' => 'Segitiga Tumpul',
                'type' => 'segitiga',
                'subtype' => 'tumpul',
                'color' => '#EA580C',
                'bagian_damar_kurung' => null,
                'urutan' => 9,
                'ciri_ciri' => [
                    'Memiliki 1 sudut tumpul (lebih dari 90° dan kurang dari 180°)',
                    'Memiliki 2 sudut lainnya berupa sudut lancip',
                    'Jumlah ketiga sudutnya adalah 180°',
                ],
            ],
            [
                'name' => 'Jajar Genjang',
                'type' => 'jajar_genjang',
                'subtype' => null,
                'color' => '#D97706',
                'bagian_damar_kurung' => 'Atap Penjual',
                'urutan' => 10,
                'ciri_ciri' => [
                    'Memiliki 2 pasang sisi yang sejajar dan sama panjang',
                    'Sudut yang berhadapan sama besar',
                    'Tidak memiliki simetri lipat, memiliki 2 simetri putar',
                ],
            ],
            [
                'name' => 'Layang-Layang',
                'type' => 'layang_layang',
                'subtype' => null,
                'color' => '#64748B',
                'bagian_damar_kurung' => 'Hiasan Gantung',
                'urutan' => 11,
                'ciri_ciri' => [
                    'Memiliki 2 pasang sisi berdekatan yang sama panjang',
                    'Memiliki 1 pasang sudut berhadapan yang sama besar',
                    'Kedua diagonalnya saling berpotongan tegak lurus',
                ],
            ],
            [
                'name' => 'Belah Ketupat',
                'type' => 'belah_ketupat',
                'subtype' => null,
                'color' => '#8B5CF6',
                'bagian_damar_kurung' => 'Ketupat',
                'urutan' => 12,
                'ciri_ciri' => [
                    'Memiliki 4 sisi yang sama panjang',
                    'Sudut yang berhadapan sama besar',
                    'Kedua diagonalnya saling berpotongan tegak lurus',
                ],
            ],
            [
                'name' => 'Trapesium Sama Kaki',
                'type' => 'trapesium',
                'subtype' => 'sama_kaki',
                'color' => '#EC4899',
                'bagian_damar_kurung' => 'Perahu / Bak',
                'urutan' => 13,
                'ciri_ciri' => [
                    'Memiliki sepasang sisi sejajar yang berbeda panjang',
                    'Sepasang sisi miring (kaki) sama panjang',
                    'Memiliki 2 pasang sudut alas yang berdekatan sama besar',
                ],
            ],
            [
                'name' => 'Trapesium Siku-Siku',
                'type' => 'trapesium',
                'subtype' => 'siku_siku',
                'color' => '#DB2777',
                'bagian_damar_kurung' => null,
                'urutan' => 14,
                'ciri_ciri' => [
                    'Memiliki sepasang sisi sejajar',
                    'Memiliki tepat 2 sudut siku-siku (90°) pada salah satu sisi',
                    'Memiliki 1 sudut lancip dan 1 sudut tumpul',
                ],
            ],
            [
                'name' => 'Trapesium Sembarang',
                'type' => 'trapesium',
                'subtype' => 'sembarang',
                'color' => '#A855F7',
                'bagian_damar_kurung' => null,
                'urutan' => 15,
                'ciri_ciri' => [
                    'Memiliki sepasang sisi sejajar',
                    'Keempat sisinya tidak sama panjang',
                    'Keempat sudutnya memiliki besar yang berbeda-beda',
                ],
            ],
            [
                'name' => 'Trapesium Lancip',
                'type' => 'trapesium',
                'subtype' => 'lancip',
                'color' => '#06B6D4',
                'bagian_damar_kurung' => null,
                'urutan' => 16,
                'ciri_ciri' => [
                    'Memiliki sepasang sisi sejajar',
                    'Kedua sudut pada sisi alasnya berupa sudut lancip (< 90°)',
                    'Keempat sudutnya berjumlah 360°',
                ],
            ],
            [
                'name' => 'Trapesium Tumpul',
                'type' => 'trapesium',
                'subtype' => 'tumpul',
                'color' => '#E11D48',
                'bagian_damar_kurung' => null,
                'urutan' => 17,
                'ciri_ciri' => [
                    'Memiliki sepasang sisi sejajar',
                    'Memiliki sudut tumpul (> 90°) yang menonjol pada sisi miringnya',
                    'Keempat sudutnya berjumlah 360°',
                ],
            ],
            [
                'name' => 'Trapesium Tiga Sisi Sama Panjang',
                'type' => 'trapesium',
                'subtype' => 'tiga_sisi_sama',
                'color' => '#7C3AED',
                'bagian_damar_kurung' => null,
                'urutan' => 18,
                'ciri_ciri' => [
                    'Memiliki sepasang sisi sejajar',
                    'Memiliki 3 sisi yang sama panjang (sisi atas dan kedua sisi miring)',
                    'Sudut alas sama besar karena kedua kakinya sama panjang',
                ],
            ],
        ];

        foreach ($shapes as $shape) {
            $svgInline = BangunDatarShape::generateDefaultSvg($shape['type'], $shape['subtype']);

            BangunDatarShape::updateOrCreate(
                [
                    'type' => $shape['type'],
                    'subtype' => $shape['subtype'],
                ],
                [
                    'name' => $shape['name'],
                    'color' => $shape['color'],
                    'ciri_ciri' => $shape['ciri_ciri'],
                    'bagian_damar_kurung' => $shape['bagian_damar_kurung'],
                    'urutan' => $shape['urutan'],
                    'svg_inline' => $svgInline,
                    'is_active' => true,
                ]
            );
        }

        BangunDatarShape::clearActiveCache();
    }
}
