# 🛡️ Panduan Melindungi Code dari Kehilangan

Dokumen ini menjelaskan cara mencegah kehilangan code saat menggunakan Cursor/VS Code.

## ✅ Fitur yang Sudah Diaktifkan

### 1. **Auto-Save Otomatis**
- Code akan otomatis tersimpan setiap 1 detik setelah mengetik
- Tidak perlu manual save (Ctrl+S)
- File akan tersimpan sebelum Cursor/VS Code ditutup

### 2. **Git Pre-Commit Hook**
- Akan memperingatkan jika ada file yang dihapus
- Mencegah penghapusan file secara tidak sengaja

### 3. **Backup Script**
- Script `backup-changes.sh` untuk backup otomatis sebelum close editor

## 📋 Cara Menggunakan

### Sebelum Menutup Cursor/VS Code:

1. **Cek Status Git:**
   ```bash
   git status
   ```

2. **Jika Ada Perubahan, Commit:**
   ```bash
   git add .
   git commit -m "Deskripsi perubahan"
   git push origin main
   ```

3. **Atau Gunakan Backup Script:**
   ```bash
   ./backup-changes.sh
   ```
   Script ini akan:
   - Cek apakah ada perubahan
   - Buat backup branch otomatis
   - Simpan semua perubahan ke branch backup

### Restore dari Backup:

Jika code hilang, restore dari backup branch:
```bash
# Lihat semua backup branch
git branch | grep backup

# Restore dari backup terbaru
git checkout backup-YYYYMMDD_HHMMSS
git checkout main
git cherry-pick backup-YYYYMMDD_HHMMSS
```

## ⚙️ Konfigurasi Cursor/VS Code

File `.vscode/settings.json` sudah dikonfigurasi dengan:
- ✅ Auto-save setiap 1 detik
- ✅ Restore window sebelumnya saat buka
- ✅ Konfirmasi sebelum hapus file
- ✅ Git auto-fetch

## 🔍 Tips Tambahan

### 1. **Commit Sering-sering**
   - Commit setiap kali selesai fitur kecil
   - Jangan tunggu sampai banyak perubahan

### 2. **Gunakan Git Stash**
   Jika perlu switch branch tapi belum siap commit:
   ```bash
   git stash save "Deskripsi perubahan"
   git checkout branch-lain
   # ... kerja di branch lain ...
   git checkout main
   git stash pop
   ```

### 3. **Cek Git Log Sebelum Close**
   ```bash
   git log --oneline -5
   ```
   Pastikan commit terakhir sudah sesuai

### 4. **Push ke GitHub Secara Berkala**
   ```bash
   git push origin main
   ```
   Ini akan backup code ke cloud

## 🚨 Jika Code Hilang

### Opsi 1: Cek Git Reflog
```bash
git reflog
git checkout HEAD@{n}  # n = nomor commit yang hilang
```

### Opsi 2: Cek Backup Branch
```bash
git branch -a | grep backup
git checkout backup-YYYYMMDD_HHMMSS
```

### Opsi 3: Restore dari GitHub
```bash
git fetch origin
git checkout origin/main
```

## 📝 Checklist Sebelum Close Editor

- [ ] `git status` - cek apakah ada perubahan
- [ ] `git add .` - tambahkan semua perubahan
- [ ] `git commit -m "..."` - commit perubahan
- [ ] `git push origin main` - push ke GitHub
- [ ] `git log --oneline -3` - verifikasi commit terakhir

## 🔧 Troubleshooting

### Code hilang setelah close?
1. Cek `git reflog` untuk melihat history
2. Cek backup branch dengan `git branch | grep backup`
3. Restore dari commit terakhir: `git checkout HEAD@{1}`

### Auto-save tidak bekerja?
1. Cek `.vscode/settings.json` ada dan benar
2. Restart Cursor/VS Code
3. Cek apakah ada error di Output panel

### File terhapus tidak sengaja?
1. Cek `git status` untuk melihat file yang dihapus
2. Restore: `git checkout HEAD -- path/to/file`
3. Atau restore semua: `git checkout HEAD -- .`

---

**Ingat:** Selalu commit dan push sebelum close editor untuk memastikan code aman! 🛡️

