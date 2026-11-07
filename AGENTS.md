<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

---

# ATURAN UTAMA PENGEMBANGAN DAN MODIFIKASI APLIKASI (AI AGENT GUIDELINES)

> **TUJUAN UTAMA: MENCEGAH PERUBAHAN DI LUAR SCOPE TUGAS YANG DIPERINTAHKAN.**
>
> Seluruh instruksi dalam dokumen ini bersifat **TEGAS, KETAT, DAN WAJIB DIPATUHI** setiap kali AI agent melakukan coding, memperbaiki bug, menambahkan fitur, atau mengubah tampilan aplikasi.

---

### 1. STRICT SCOPE CONTROL — WAJIB DIPATUHI
* AI hanya boleh mengubah file, komponen, halaman, menu, fungsi, dan fitur yang secara langsung diperlukan untuk menyelesaikan instruksi terbaru dari user.
* Jangan mengubah bagian aplikasi lain yang tidak berhubungan langsung dengan tugas yang sedang dikerjakan.
* Jangan melakukan refactoring, restrukturisasi folder, penggantian arsitektur, atau optimasi kode di luar scope tugas.
* Jangan mengubah kode hanya karena AI menganggapnya kurang bagus, kurang modern, atau perlu diperbaiki.
* Jangan menambahkan fitur, menghapus fitur, atau mengubah perilaku aplikasi tanpa instruksi eksplisit dari user.
* Jika perubahan membutuhkan modifikasi pada komponen bersama atau file global, pastikan perubahan tersebut benar-benar diperlukan dan tidak mengubah perilaku bagian aplikasi lain.

---

### 2. UI & DESIGN PRESERVATION — LINDUNGI TAMPILAN
* Pertahankan desain UI/UX yang sudah ada, termasuk layout, ukuran elemen, spacing, margin, padding, warna, font, ikon, border, shadow, animasi, dan responsivitas.
* Jangan mengubah tampilan dashboard, sidebar, navbar, header, footer, card, tabel, modal, form, tombol, dropdown, atau komponen lain yang tidak termasuk dalam instruksi user.
* Jangan mengubah tema, design system, CSS global, Tailwind configuration, variabel desain, atau style bersama kecuali benar-benar diperlukan oleh tugas.
* Jangan mengganti desain yang sudah ada dengan desain baru atas inisiatif sendiri.
* Jangan menghapus, mengganti nama, memindahkan, atau mengatur ulang menu navigasi yang tidak diperintahkan.
* Pertahankan tampilan desktop, tablet, dan mobile yang sudah berjalan.
* Jika sebuah komponen digunakan oleh banyak halaman, jangan mengubahnya secara global jika perubahan tersebut dapat memengaruhi halaman lain.

---

### 3. MENU & FEATURE ISOLATION
* Perlakukan setiap menu, halaman, modul, dan fitur sebagai bagian aplikasi yang harus tetap berfungsi secara independen selama memungkinkan.
* Ketika user memerintahkan perubahan pada satu menu, fokuskan seluruh perubahan pada menu tersebut beserta dependensi yang memang diperlukan.
* Jangan mengubah menu lain hanya karena memiliki nama, fungsi, atau komponen yang mirip.
* Jangan mengubah routing, permission, autentikasi, sidebar, atau struktur navigasi kecuali secara langsung diperlukan oleh tugas.
* Jika perubahan pada komponen bersama tidak dapat dihindari, analisis dampaknya terlebih dahulu dan pertahankan perilaku lama untuk semua penggunaannya di luar scope.
* Jangan menghapus atau mengganti fungsi lama yang masih digunakan oleh menu lain.

---

### 4. WAJIB INSPEKSI SEBELUM CODING
Sebelum melakukan perubahan:
1. Baca instruksi tugas terbaru dengan teliti.
2. Periksa struktur project dan identifikasi file yang terkait langsung dengan tugas.
3. Pelajari implementasi yang sudah ada sebelum membuat perubahan.
4. Identifikasi komponen bersama, dependensi, routing, state, API, dan style yang berpotensi terdampak.
5. Tentukan batas scope perubahan dan hindari menyentuh file yang tidak diperlukan.
6. Jika permintaan ambigu atau berpotensi memengaruhi menu lain, tanyakan klarifikasi sebelum melakukan perubahan berisiko.
7. **DILARANG** langsung menulis ulang file tanpa memahami implementasi yang sudah ada.

---

### 5. MINIMAL CHANGE POLICY
* Terapkan prinsip minimal changes: ubah sesedikit mungkin kode untuk menyelesaikan tugas dengan benar.
* Utamakan perubahan lokal daripada perubahan global.
* Pertahankan nama variabel, nama fungsi, struktur komponen, kontrak API, dan pola coding yang sudah ada jika tidak perlu diubah.
* Jangan mengganti seluruh file jika cukup memperbaiki beberapa baris.
* Jangan menginstal package baru jika tidak diperlukan.
* Jangan mengubah versi dependency, konfigurasi build, environment, database schema, atau file konfigurasi lain tanpa alasan teknis yang kuat dan relevan.
* Jangan menghapus kode yang tampaknya tidak digunakan sebelum memastikan kode tersebut benar-benar tidak diperlukan.

---

### 6. BACKWARD COMPATIBILITY
* Semua fitur yang sudah berjalan harus tetap berfungsi setelah perubahan.
* Pertahankan integrasi API, format data, validasi, autentikasi, authorization, permission, dan alur bisnis yang sudah ada.
* Jangan mengubah nama field, format response, parameter, database column, atau kontrak antar-komponen tanpa kebutuhan eksplisit.
* Jika harus mengubah interface atau kontrak yang digunakan bagian lain, identifikasi seluruh pemakainya dan pastikan kompatibilitasnya tetap terjaga.
* Jangan menggunakan mock data untuk menggantikan integrasi asli kecuali diminta.
* Jangan menghapus validasi, error handling, atau security checks demi mempermudah implementasi.

---

### 7. VALIDASI SETELAH PERUBAHAN
Setelah menyelesaikan tugas:
1. Periksa kembali semua file yang diubah.
2. Pastikan perubahan tetap berada dalam scope yang diperintahkan.
3. Jalankan pemeriksaan atau test yang relevan jika tersedia.
4. Periksa error build, lint, TypeScript/PHP syntax, dan error lain yang relevan.
5. Pastikan menu, halaman, dan komponen lain yang berpotensi terdampak tidak mengalami regresi.
6. Bandingkan tampilan sebelum dan sesudah perubahan untuk memastikan UI yang tidak terkait tetap sama.
7. **JANGAN PERNAH** mengklaim pengujian berhasil jika pengujian belum dijalankan.
8. Jika ditemukan masalah pada bagian yang tidak terkait, jangan memperbaikinya secara otomatis. Laporkan temuan tersebut terlebih dahulu.

---

### 8. BATASAN PERUBAHAN FILE
* Jangan mengedit atau memformat ulang file yang tidak terkait.
* Jangan melakukan perubahan massal pada seluruh project.
* Jangan menghapus file, folder, konfigurasi, atau aset tanpa izin yang jelas.
* Jangan menimpa perubahan yang sudah dibuat oleh user atau developer lain.
* Sebelum mengedit file yang sudah memiliki perubahan lokal, periksa perubahan tersebut dan pertahankan pekerjaan yang sudah ada.
* Jangan menjalankan perintah destruktif, migrasi yang berisiko, atau operasi yang dapat menghilangkan data tanpa persetujuan eksplisit.

---

### 9. PRIORITAS INSTRUKSI
Urutan prioritas kerja:
1. **Instruksi terbaru yang secara eksplisit diberikan oleh user untuk tugas saat ini.**
2. **Aturan dan batasan dalam file `AGENTS.md`.**
3. **Pola implementasi, desain, dan arsitektur yang sudah ada di project.**
4. **Saran atau preferensi AI sendiri.**

*Catatan Penting:*
* AI tidak boleh menganggap bahwa permintaan untuk memperbaiki satu fitur merupakan izin untuk mendesain ulang seluruh aplikasi.
* Instruksi yang secara eksplisit meminta perubahan pada bagian tertentu hanya memberikan izin untuk mengubah bagian tersebut dan dependensi yang diperlukan, bukan bagian lain yang tidak terkait.

---

### 10. FORMAT LAPORAN SELESAI
Setelah menyelesaikan tugas, berikan laporan singkat yang mencakup:
* **Task:** tugas yang dikerjakan.
* **Files changed:** daftar file yang benar-benar diubah.
* **Changes made:** penjelasan perubahan pada setiap file.
* **Unrelated areas preserved:** menu, halaman, atau komponen lain yang sengaja tidak diubah.
* **Validation:** test atau pemeriksaan yang benar-benar dijalankan beserta hasilnya.
* **Risks or limitations:** risiko, masalah yang belum terselesaikan, atau pengujian yang belum dapat dilakukan.

*Jangan menyatakan bahwa bagian lain aman tanpa dasar pemeriksaan yang memadai.*

---

### 11. ATURAN FINAL — NO UNREQUESTED CHANGES
* Jika suatu perubahan tidak diperlukan secara langsung untuk menyelesaikan tugas, **JANGAN** lakukan perubahan tersebut.
* Jika solusi dapat diterapkan tanpa mengubah tampilan menu lain, gunakan solusi tersebut.
* Jika perubahan berpotensi merusak tampilan, fungsi, navigasi, atau perilaku fitur lain, hentikan perubahan berisiko dan minta persetujuan user terlebih dahulu.
* Jangan membuat asumsi bahwa user mengizinkan perubahan tambahan.

> **Prinsip Utama:**  
> **Ubah hanya yang diminta. Pertahankan semua yang tidak diminta. Jangan merusak fitur yang sudah berjalan.**
