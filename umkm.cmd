@echo off
title Sistem Informasi Pemasaran UMKM Dusun 01 Purwodadi
color 0A
cls

echo ======================================================================
echo    SISTEM INFORMASI PEMASARAN PADA UMKM DUSUN 01 PURWODADI
echo    Kecamatan Trimurjo, Kabupaten Lampung Tengah
echo ======================================================================
echo.

:: 1. Memeriksa status database MySQL (Port 3306)
echo [*] Memeriksa status database MySQL...
netstat -ano | findstr 127.0.0.1:3306 >nul
if %ERRORLEVEL% equ 0 (
    echo [OK] MySQL Database telah aktif di Port 3306.
) else (
    echo [!] MySQL belum aktif, mencoba mengaktifkan MySQL XAMPP otomatis...
    if exist "C:\xampp\mysql\bin\mysqld.exe" (
        start /b "" "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
        timeout /t 3 /nobreak >nul
        echo [OK] MySQL berhasil diaktifkan.
    ) else (
        echo [PERINGATAN] Silakan pastikan MySQL di XAMPP Control Panel telah berjalan.
    )
)

echo.
:: 2. Berpindah ke direktori proyek Laravel
cd /d "c:\Project\Sistem_informasi_pemasaran\umkm-purwodadi"

:: 3. Jalankan pembuka browser otomatis setelah 2 detik di background
start "" cmd /c "timeout /t 2 /nobreak >nul && start http://127.0.0.1:8000"

echo [*] Server Laravel sedang berjalan di http://127.0.0.1:8000
echo [*] Membuka browser default secara otomatis...
echo.
echo ======================================================================
echo   Aplikasi UMKM Purwodadi Siap Digunakan!
echo   Tekan tombol Ctrl + C di jendela ini untuk mematikan server.
echo ======================================================================
echo.

:: 4. Menjalankan server Laravel
php artisan serve --port=8000

