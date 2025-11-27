# Panduan Menyimpan Perubahan ke GitHub

## Masalah yang Terjadi
Perubahan kode yang dibuat di Cursor/VSCode tidak terlihat karena:
1. Perubahan tidak pernah di-commit ke Git
2. Saat pull dari GitHub atau reload editor, file kembali ke versi terakhir yang di-commit
3. Perubahan yang belum di-commit "hilang" dari view

## Solusi: Commit dan Push Perubahan

### Langkah 1: Tambahkan File ke Staging
```bash
git add .
```
Atau untuk file spesifik:
```bash
git add app/Http/Controllers/PermohonanController.php
```

### Langkah 2: Commit Perubahan
```bash
git commit -m "Update: Perubahan terbaru dari Cursor/VSCode"
```

### Langkah 3: Push ke GitHub
```bash
git push origin main
```

## Tips Pencegahan

### 1. Commit Secara Berkala
Jangan biarkan perubahan menumpuk terlalu lama. Commit setiap kali:
- Fitur baru selesai
- Bug fix selesai
- Perubahan penting lainnya

### 2. Gunakan Auto-Save
Aktifkan auto-save di editor untuk memastikan file tersimpan.

### 3. Cek Status Git Sebelum Pull
```bash
git status
```
Jika ada perubahan, commit dulu sebelum pull.

### 4. Gunakan Branch untuk Fitur Besar
```bash
git checkout -b fitur-baru
# ... buat perubahan ...
git commit -m "Add: Fitur baru"
git push origin fitur-baru
```

## Perintah Cepat

### Lihat Perubahan yang Belum di-Commit
```bash
git status
git diff
```

### Lihat History Commit
```bash
git log --oneline -10
```

### Kembalikan File ke Versi Terakhir yang di-Commit
```bash
git restore nama-file.php
```

### Backup Perubahan (Stash)
```bash
git stash push -m "Backup perubahan"
git stash list
git stash pop  # Kembalikan perubahan
```

