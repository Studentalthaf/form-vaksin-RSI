# Panduan Testing Signature Pad di iPhone/Safari

## Perbaikan yang Telah Dilakukan

### 1. Canvas DPI Scaling
- Menambahkan proper handling untuk high DPI displays (Retina display di iPhone)
- Canvas sekarang otomatis menyesuaikan dengan `devicePixelRatio`

### 2. Touch Event Handling
- Memperbaiki touch event handling untuk Safari/iPhone
- Menambahkan `preventDefault()` dan `stopPropagation()` yang lebih robust
- Menambahkan handling untuk `changedTouches` sebagai fallback

### 3. Smooth Drawing
- Menggunakan teknik drawing yang lebih smooth dengan `beginPath()` dan `moveTo()`
- Menyimpan posisi terakhir untuk garis yang lebih halus

### 4. Viewport Settings
- Menambahkan `user-scalable=no` di halaman screening untuk mencegah zoom tidak sengaja saat menandatangani

## Cara Testing di iPhone/Safari

### Metode 1: Testing di iPhone Fisik (Recommended)

1. **Deploy ke Production/Staging**
   ```bash
   # Pastikan semua perubahan sudah di-commit dan di-deploy
   git add .
   git commit -m "Fix signature pad untuk iPhone/Safari"
   git push
   ```

2. **Akses dari iPhone**
   - Buka Safari di iPhone
   - Akses URL production/staging
   - Login sebagai user yang memiliki permohonan
   - Navigasi ke halaman screening atau halaman tanda tangan dokter

3. **Test Signature Pad**
   - Coba tanda tangan dengan jari di canvas
   - Pastikan:
     ✓ Tanda tangan muncul saat jari menyentuh canvas
     ✓ Tanda tangan mengikuti gerakan jari dengan smooth
     ✓ Tidak ada scroll atau zoom yang tidak diinginkan
     ✓ Tanda tangan tersimpan dengan benar saat submit form

### Metode 2: Testing dengan Safari Desktop (Simulator)

1. **Buka Safari Desktop**
   - Buka Safari di Mac
   - Tekan `Cmd + Option + I` untuk membuka Developer Tools
   - Klik ikon device di toolbar (atau `Cmd + Shift + M`)

2. **Pilih iPhone Device**
   - Pilih device: iPhone 12, iPhone 13, iPhone 14, atau iPhone 15
   - Pastikan orientation: Portrait atau Landscape

3. **Akses Website**
   - Masukkan URL localhost atau production
   - Pastikan responsive mode aktif

4. **Test Touch Events**
   - Klik dan drag di canvas signature pad
   - Simulasikan touch dengan mouse (Safari akan convert ke touch events)

### Metode 3: Testing dengan BrowserStack/Sauce Labs (Cloud Testing)

1. **Daftar ke BrowserStack atau Sauce Labs**
   - Buat akun gratis di browserstack.com atau saucelabs.com

2. **Pilih Real iPhone Device**
   - Pilih iPhone dengan iOS terbaru
   - Pilih Safari browser

3. **Test Signature Pad**
   - Akses website Anda
   - Test semua fungsi signature pad

### Metode 4: Testing dengan Xcode Simulator (Mac Only)

1. **Install Xcode**
   - Download Xcode dari App Store (gratis)

2. **Buka Simulator**
   - Buka Xcode
   - Pilih `Xcode > Open Developer Tool > Simulator`
   - Pilih device iPhone

3. **Buka Safari di Simulator**
   - Buka Safari di simulator
   - Akses website Anda
   - Test signature pad dengan mouse (akan di-convert ke touch)

## Checklist Testing

### ✅ Functional Testing
- [ ] Signature pad bisa di-draw dengan jari di iPhone
- [ ] Signature pad tidak menyebabkan scroll halaman
- [ ] Signature pad tidak menyebabkan zoom tidak sengaja
- [ ] Tanda tangan tersimpan dengan benar saat submit
- [ ] Tombol "Hapus Tanda Tangan" berfungsi
- [ ] Validasi tanda tangan kosong berfungsi

### ✅ Visual Testing
- [ ] Tanda tangan terlihat jelas dan tidak blur
- [ ] Ukuran canvas sesuai dengan layar iPhone
- [ ] Border dan styling terlihat dengan benar
- [ ] Responsive di berbagai ukuran iPhone (SE, 12, 13, 14, 15, Pro Max)

### ✅ Browser Compatibility
- [ ] Safari iOS (latest version)
- [ ] Chrome iOS (jika digunakan)
- [ ] Firefox iOS (jika digunakan)

### ✅ Device Testing
- [ ] iPhone SE (small screen)
- [ ] iPhone 12/13/14 (standard)
- [ ] iPhone 14 Pro Max (large)
- [ ] iPad (jika perlu support)

## Troubleshooting

### Masalah: Tanda tangan tidak muncul saat di-draw
**Solusi:**
- Pastikan `touch-action: none` ada di style canvas
- Pastikan `preventDefault()` dipanggil di touch events
- Check console browser untuk error JavaScript

### Masalah: Tanda tangan blur atau tidak jelas
**Solusi:**
- Pastikan DPI scaling sudah benar (sudah diperbaiki)
- Pastikan canvas width/height di-set dengan benar

### Masalah: Halaman scroll saat menandatangani
**Solusi:**
- Pastikan `touch-action: none` ada di canvas
- Pastikan `preventDefault()` dipanggil di `touchmove`
- Pastikan viewport memiliki `user-scalable=no` (sudah ditambahkan)

### Masalah: Tanda tangan tidak tersimpan
**Solusi:**
- Check apakah `toDataURL()` berfungsi (bisa ada CORS issue)
- Check network tab di browser untuk melihat apakah data dikirim
- Check server logs untuk error

## Tips Testing

1. **Test di Production/Staging**
   - Jangan hanya test di localhost
   - Test di environment yang mirip production (HTTPS, dll)

2. **Test dengan Jaringan Berbeda**
   - Test dengan WiFi
   - Test dengan 4G/5G
   - Test dengan jaringan lambat

3. **Test dengan Berbagai Kondisi**
   - Test dengan iPhone dalam mode portrait
   - Test dengan iPhone dalam mode landscape
   - Test dengan iPhone dalam mode low power mode

4. **Test Edge Cases**
   - Test dengan tanda tangan sangat cepat
   - Test dengan tanda tangan sangat lambat
   - Test dengan tanda tangan sangat kecil
   - Test dengan tanda tangan sangat besar

## Catatan Penting

- **Pastikan HTTPS**: Safari di iOS memerlukan HTTPS untuk beberapa fitur
- **Pastikan CORS**: Jika ada CORS issue, pastikan server mengizinkan origin yang benar
- **Pastikan Storage**: Pastikan storage quota tidak penuh di iPhone

## Kontak Support

Jika masih ada masalah setelah testing, silakan:
1. Screenshot error message
2. Screenshot console browser (jika ada error JavaScript)
3. Catat device dan iOS version
4. Catat Safari version

---

**Last Updated:** {{ date('Y-m-d') }}
**Version:** 1.0

