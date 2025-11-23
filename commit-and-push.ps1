# Script untuk commit dan push perubahan ke GitHub
Write-Host "=== Menyimpan Perubahan ke GitHub ===" -ForegroundColor Cyan
Write-Host ""

# Cek status
Write-Host "Status Git saat ini:" -ForegroundColor Yellow
git status --short

Write-Host ""
Write-Host "Perubahan yang akan di-commit:" -ForegroundColor Yellow
git diff --stat

Write-Host ""
$confirm = Read-Host "Apakah Anda yakin ingin commit dan push semua perubahan? (y/n)"

if ($confirm -eq "y" -or $confirm -eq "Y") {
    Write-Host ""
    Write-Host "Menambahkan semua perubahan ke staging..." -ForegroundColor Green
    git add .
    
    Write-Host "Membuat commit..." -ForegroundColor Green
    $commitMessage = Read-Host "Masukkan pesan commit (atau tekan Enter untuk menggunakan pesan default)"
    
    if ([string]::IsNullOrWhiteSpace($commitMessage)) {
        $commitMessage = "Update: Perubahan terbaru dari Cursor - $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
    }
    
    git commit -m $commitMessage
    
    Write-Host ""
    Write-Host "Push ke GitHub..." -ForegroundColor Green
    git push origin main
    
    Write-Host ""
    Write-Host "✓ Selesai! Perubahan sudah di-push ke GitHub" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "Operasi dibatalkan." -ForegroundColor Yellow
}

