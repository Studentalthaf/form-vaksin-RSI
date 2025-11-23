# Script untuk memverifikasi sinkronisasi file antara Cursor dan VSCode
Write-Host "=== Pemeriksaan Sinkronisasi File ===" -ForegroundColor Cyan
Write-Host ""

# Cek file yang dimodifikasi dalam 15 menit terakhir
$recentFiles = Get-ChildItem -Path . -File -Recurse -Exclude "*.log","node_modules","vendor","storage/framework" | 
    Where-Object { $_.LastWriteTime -gt (Get-Date).AddMinutes(-15) } |
    Sort-Object LastWriteTime -Descending

if ($recentFiles) {
    Write-Host "File yang dimodifikasi dalam 15 menit terakhir:" -ForegroundColor Yellow
    $recentFiles | Select-Object Name, LastWriteTime, FullName | Format-Table -AutoSize
} else {
    Write-Host "Tidak ada file yang dimodifikasi dalam 15 menit terakhir." -ForegroundColor Green
}

Write-Host ""
Write-Host "=== Status Git ===" -ForegroundColor Cyan
git status --short

Write-Host ""
Write-Host "=== Tips ===" -ForegroundColor Cyan
Write-Host "1. Pastikan semua file sudah di-save di Cursor (Ctrl+S)" -ForegroundColor White
Write-Host "2. Di VSCode, tekan Ctrl+Shift+P lalu ketik 'Reload Window'" -ForegroundColor White
Write-Host "3. Atau tutup dan buka kembali VSCode" -ForegroundColor White
Write-Host "4. Pastikan VSCode membuka folder yang sama dengan Cursor" -ForegroundColor White

