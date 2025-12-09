# Fix Error Upload File HEIC

## Masalah
Error: "File KTP harus berupa gambar" saat upload file HEIC dari iPhone.

## Penyebab
Rule validasi `image` di Laravel tidak mengenali format HEIC sebagai image. Rule `image` hanya mengenali format standar seperti JPEG, PNG, GIF, BMP, SVG, WEBP.

## Solusi
Mengubah rule `image` menjadi `file` karena:
- `file` menerima semua jenis file (termasuk HEIC)
- Validasi format tetap dilakukan oleh rule `mimes`
- HEIC sudah ada di list `mimes:jpeg,jpg,png,pdf,heic,heif`

## Perubahan yang Dilakukan

### 1. Controller (`app/Http/Controllers/PermohonanController.php`)

**Sebelum:**
```php
'foto_ktp' => 'required|image|mimes:jpeg,jpg,png,pdf,heic,heif|max:5120',
```

**Sesudah:**
```php
'foto_ktp' => 'required|file|mimes:jpeg,jpg,png,pdf,heic,heif|max:5120',
```

### 2. Error Messages
- `foto_ktp.image` → `foto_ktp.file`
- `passport_halaman_pertama.image` → `passport_halaman_pertama.file`
- `passport_halaman_kedua.image` → `passport_halaman_kedua.file`

## File yang Diubah
- ✅ `app/Http/Controllers/PermohonanController.php`

## Testing
Setelah deploy, test upload file HEIC:
1. Upload file `IMG_2052.HEIC` dari iPhone
2. Seharusnya tidak ada error "File harus berupa gambar"
3. File akan tersimpan dengan extension `.heic`

## Catatan
- File HEIC akan tersimpan apa adanya (tidak di-convert)
- Untuk menampilkan HEIC di browser, mungkin perlu library tambahan atau convert ke JPG/PNG
- Validasi format tetap dilakukan oleh `mimes`, jadi hanya file dengan extension yang diizinkan yang bisa diupload

