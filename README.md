# SISFOTA (Versi Cloning dari Repository SISFOTA milik Agus Triadi)

## About SISFOTA 

SISFOTA adalah singkatan dari sistem informasi manajemen tugas akhir. SISFOTA ini dibuat berdasarkan studi kasus pada Program Studi Sistem Informasi Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Tanjungpura Pontianak. Sistem ini dibuat untuk mengakomodir proses tugas akhir agar lebih efektif dan efisisan.

## Installation


1. Clone Project:
```bash
git clone git@github.com:tikmipa/sisfota.git
```
2. Install Paket Composer
```
composer install
```
3. Membauat file konfigurasi env
```
cp .env.example .env
```
4. Ganti permission dari /storage dan /bootstrap/cache agar bisa ditulis. Untuk hal ini anda dapat menggunakan chmod atau chown

5. Konfigurasikan database yang ada di <pre>.env</pre> sesuai dengan konfigurasi database anda. Jangan lupa juga untuk mengganti nama aplikasi yang ada

6. Install node module yang dibutuhkah
```bash
npm install && npm run dev
```
7. Autoload composernya
```
composer dump-autoload
```
8. Membuat key
```
php artisan key:generate
```
9. Melakukan migrasi database
```
php artisan migrate
```
10. Menghubungkan storage ke public
```
php artisan storage:link
```

Done!


