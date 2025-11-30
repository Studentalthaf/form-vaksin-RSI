#!/bin/bash
# Script untuk backup perubahan sebelum close Cursor/VS Code
# Jalankan: chmod +x backup-changes.sh && ./backup-changes.sh

echo "=== Backup Changes Script ==="
echo ""

# Cek apakah ada perubahan
if [ -n "$(git status --porcelain)" ]; then
    echo "⚠️  Ada perubahan yang belum di-commit!"
    echo ""
    git status --short
    echo ""
    
    # Buat backup branch
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    BACKUP_BRANCH="backup-${TIMESTAMP}"
    
    echo "📦 Membuat backup branch: ${BACKUP_BRANCH}"
    git checkout -b "${BACKUP_BRANCH}"
    git add -A
    git commit -m "Backup: Auto backup sebelum close editor - ${TIMESTAMP}"
    git checkout main
    
    echo "✅ Backup berhasil dibuat di branch: ${BACKUP_BRANCH}"
    echo ""
    echo "Untuk restore backup:"
    echo "  git checkout ${BACKUP_BRANCH}"
    echo "  git checkout main"
    echo "  git cherry-pick ${BACKUP_BRANCH}"
else
    echo "✅ Tidak ada perubahan, semua sudah di-commit"
fi

echo ""
echo "=== Selesai ==="

