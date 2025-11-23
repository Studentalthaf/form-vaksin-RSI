# Panduan Sinkronisasi File antara Cursor dan VSCode

## Masalah yang Sering Terjadi
Ketika menggunakan dua editor berbeda (Cursor dan VSCode) pada proyek yang sama, sering terjadi masalah sinkronisasi file.

## Solusi Cepat

### Di Cursor:
1. **Reload Window**
   - Tekan `Ctrl+Shift+P` (atau `Cmd+Shift+P` di Mac)
   - Ketik "Reload Window" atau "Developer: Reload Window"
   - Tekan Enter

2. **Tutup dan Buka Kembali File**
   - Tutup tab file yang bermasalah
   - Buka kembali dari Explorer

3. **Tutup dan Buka Kembali Cursor**
   - Tutup semua window Cursor
   - Buka kembali folder proyek

### Di VSCode:
1. **Reload Window**
   - Tekan `Ctrl+Shift+P`
   - Ketik "Reload Window"
   - Tekan Enter

2. **Tutup dan Buka Kembali VSCode**

## Tips Pencegahan

### 1. Selalu Simpan File Sebelum Pindah Editor
- Di Cursor: `Ctrl+K S` (Save All) atau `Ctrl+S` (Save)
- Di VSCode: `Ctrl+K S` (Save All) atau `Ctrl+S` (Save)

### 2. Aktifkan Auto-Save
**Di Cursor:**
- Buka Settings (`Ctrl+,`)
- Cari "files.autoSave"
- Pilih "afterDelay" atau "onFocusChange"

**Di VSCode:**
- Buka Settings (`Ctrl+,`)
- Cari "files.autoSave"
- Pilih "afterDelay" atau "onFocusChange"

### 3. Gunakan Satu Editor Utama
Untuk menghindari konflik, gunakan satu editor sebagai editor utama.

### 4. Commit Perubahan ke Git Secara Berkala
Ini akan membantu jika terjadi masalah sinkronisasi:
```bash
git add .
git commit -m "Update changes"
```

## Verifikasi File Sudah Tersimpan

Jalankan script PowerShell:
```powershell
.\check-sync.ps1
```

Atau cek manual:
```powershell
Get-Item docker-compose.yml | Select-Object LastWriteTime
```

## Jika Masalah Masih Terjadi

1. **Cek Apakah File Terkunci**
   - Tutup kedua editor
   - Cek apakah ada proses yang masih menggunakan file

2. **Cek File Watcher**
   - Pastikan tidak ada file watcher yang dikecualikan
   - Di Cursor: Settings → "files.watcherExclude"
   - Di VSCode: Settings → "files.watcherExclude"

3. **Restart File Watcher**
   - Di Cursor: `Ctrl+Shift+P` → "Developer: Restart Extension Host"
   - Di VSCode: `Ctrl+Shift+P` → "Developer: Restart Extension Host"



