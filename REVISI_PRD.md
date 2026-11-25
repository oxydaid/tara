<!-- File ini adalah dokumentasi catatan revisi yang diminta oleh client -->
<!-- Jadi file ini akan berubah-ubah sesuai revisi yang dikerjakan -->

# Revisi

Penjelasan revisi di bawah

## 1 - Halaman Profil `/profil`

### 1.1 - Deskripsi media

teks diganti

```
Hai, Salam kenal teman-teman! TARA (Lentera Eksplorasi Bagun Datar) merupakan media pembelajaran untuk kelas 4 Sekolah Dasar yang akan membantu kamu memahami jenis dan sifat bangun datar. Melalui kegiatan eksplorasi warisan budaya damar Kurung Khas Gresik, kamu diajak menerapkan pemahaman tentang karakteristik bagun datar untuk mengelompokkan jenis-jenis bagun datar yang ada pada motif damar kurung dengan baik dan benar!

Selamat bereksplorasi teman-teman!
```

### 1.2 - Capaian dan tujuan

CP

```
Pada materi ini murid dapat mendeskripsikan ciri-ciri berbagai
bentuk bangun datar segi banyak dan bukan segi banyak.
```

TP

```
1. Murid dapat menerapkan (C3) ciri-ciri
bentuk bangun datar (segiempat, berbagai bentuk bangun datar berdasarkan karakteristiknya pada motif damar kurung
dengan benar.

2. Murid dapat mengelompokkan (C4) bentuk
bangun datar pada motif damar kurung ke
dalam segi banyak dan bukan segi banyak
berdasarkan karakterıstık sısı dan sudutnya
dengan tepat.
```

## 2 - Bangun datar 

### 2.1 - Bangun Datar Dinamis

bangun datar yang awalnya berupa hardcode, ubah menjadi dinamis bisa diatur pada halaman admin.
admin bisa mengubah:

- warna bangun datar
- Gambar custom (jika user dirasa kurang cocok dengan gambar default)
- ciri-ciri
- bagian pada damar kurung (tulis manual, boleh kosong)
- urutan (agar urut di halaman `/ruang-eksplorasi/bangun-datar`)

### 2.2 - Bangun datar

Bangun datar yang ada masih belum lengkap maka bangun datar harus lengkap tidak hanya secara umum tapi tiap jenisnya juga harus ada

- persegi 
- persegi panjang
- lingkaran
- segitiga:

  - sama sisi
  - sama kaki
  - sembarang
  - lancip
  - siku-siku
  - tumpul

- jajar genjang
- layang-layang
- belah ketupat
- trapesium:
  
  - Sama Kaki
  - Siku-Siku
  - Sembarang
  - Lancip
  - Tumpul
  - Tiga Sisi Sama Panjang
 
dan khusus untuk segitiga dan trapesium harus ada tanda khusus pada gambar untuk menunjukkan jenis segitiga atau trapesium berdasarkan sifat sisi dan sudutnya `(tanda kesamaan sisi dan tanda kesamaan sudut dalam geometri)`.

gunakan seed saja biar langsung tergenerate bangun datarnya tanpa create satu persatu

## 3 - Halaman Ruang Ekplorasi `/ruang-eksplorasi/bangun-datar`

### 3.1 - Tabel bangun datar

Menyesuaikan dengan perubahan poin `2.2` sebelumnya maka:

- 1 tabel memuat 2 bangun datar, nanti buat tombol next dan previous untuk pagination, gunakan icon tombol pada asset
- karena memuat 2 maka ukuran bangun datar agak besarkan
- background rounded pada bangun datar hapus saja jadi langsung bangun datar tanpa background
- ciri-ciri tidak rapi ordered listnya
- di beberapa device mobile, tabel kepotong setengah

## 4 - Ruang Kolaborasi

### Misi 2

Menyesuaikan dengan perubahan poin `2.2` sebelumnya maka:

- dropdown pemilihan bangun datar diambil dari data master (revisi `2.2`)
- jika memilih bangun datar trapesium atau 
- pada tampilan desktop space y card gambar di kolom kiri terlalu lebar, cardnya kurangin space dan kotak nya gambar kurangi, gambar sedikit besarin
- sebaliknya pada gambar mobile gambar terlalu kecil karena space di ambil oleh pagination
- dari 2 poin di atas bagaimana jika tambah fitur zoom dan geser seperti map (untuk mobile wajib 2 jari karena 1 jari untuk drag shape)

### Misi 3

ada masalah pada tabel yaitu saat klik dropdown penelaian kerja sama, drop down tersebut tidak muncul keluar tapi malah di dalam tabel sehingga memperpanjang ukuran tabel, tapi karena tabel responsive jadinya ukuran tabel tetap, tapi menjadi scrollabel, jadi perbaiki dropdown baik di desktop maupun mobile

