# 🔐 PANDUAN SETUP GITHUB SECRETS

## Link Setup Secrets
Buka: https://github.com/Studentalthaf/form-vaksin-RSI/settings/secrets/actions

---

## ✅ CHECKLIST SECRETS YANG HARUS DIISI

### 1️⃣ VPS Connection (4 secrets)

#### VPS_HOST
```
Name: VPS_HOST
Value: [IP ADDRESS VPS ANDA]
Contoh: 103.123.45.67
```

#### VPS_USER
```
Name: VPS_USER
Value: [USERNAME SSH]
Contoh: root
atau: ubuntu
```

#### VPS_SSH_KEY
```
Name: VPS_SSH_KEY
Value: [ISI LENGKAP PRIVATE KEY]

Cara mendapatkan (jalankan di VPS):
cat ~/.ssh/id_rsa

Copy semua termasuk:
-----BEGIN OPENSSH PRIVATE KEY-----
...isi key...
-----END OPENSSH PRIVATE KEY-----
```
✅ **SUDAH SELESAI** (Anda sudah isi ini)

#### VPS_PATH
```
Name: VPS_PATH
Value: /var/www
```

---

### 2️⃣ GitHub Token (1 secret)

#### GIT_TOKEN
```
Name: GIT_TOKEN
Value: [GITHUB PERSONAL ACCESS TOKEN]

Cara membuat:
1. Buka: https://github.com/settings/tokens
2. Click: Generate new token → Generate new token (classic)
3. Note: "VPS Deployment"
4. Expiration: No expiration (atau pilih waktu)
5. Select scopes: ✓ repo (centang semua repo)
6. Click: Generate token
7. COPY TOKEN (hanya muncul 1x!)
8. Paste ke Secret Value
```

---

### 3️⃣ Application (2 secrets)

#### APP_PORT
```
Name: APP_PORT
Value: 8080
```

#### LARAVEL_ENV
```
Name: LARAVEL_ENV
Value: (Copy isi di bawah, GANTI yang perlu diganti)
```

**ISI LARAVEL_ENV:**
```env
APP_NAME="Form Vaksin RSI"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://GANTI_DENGAN_IP_VPS_ANDA:8080

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=form_vaksin
DB_USERNAME=laravel_user
DB_PASSWORD=RahAsiaDatabase123!

SESSION_DRIVER=database
SESSION_LIFETIME=120

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@formvaksin.com"
MAIL_FROM_NAME="Form Vaksin RSI"
```

⚠️ **PENTING - GANTI INI:**
- `http://GANTI_DENGAN_IP_VPS_ANDA:8080` → `http://103.123.45.67:8080` (ganti dengan IP VPS Anda)
- `DB_PASSWORD=RahAsiaDatabase123!` → Ganti dengan password yang kuat

---

### 4️⃣ Database (5 secrets)

#### DB_ROOT_PASSWORD
```
Name: DB_ROOT_PASSWORD
Value: PasswordRootMySQL123!

⚠️ Ganti dengan password kuat untuk MySQL root
```

#### DB_HOST
```
Name: DB_HOST
Value: db
```
(Jangan diganti, ini nama container)

#### DB_NAME
```
Name: DB_NAME
Value: form_vaksin
```

#### DB_USERNAME
```
Name: DB_USERNAME
Value: laravel_user
```

#### DB_PASSWORD
```
Name: DB_PASSWORD
Value: RahAsiaDatabase123!

⚠️ HARUS SAMA dengan yang di LARAVEL_ENV
```

---

## 📋 RINGKASAN SEMUA SECRETS (Total: 12)

| No | Secret Name | Contoh Value | Status |
|----|-------------|--------------|--------|
| 1  | VPS_HOST | 103.123.45.67 | ⏳ |
| 2  | VPS_USER | root | ⏳ |
| 3  | VPS_SSH_KEY | -----BEGIN... | ✅ DONE |
| 4  | VPS_PATH | /var/www | ⏳ |
| 5  | GIT_TOKEN | ghp_xxxx... | ⏳ |
| 6  | APP_PORT | 8080 | ⏳ |
| 7  | LARAVEL_ENV | APP_NAME=... | ⏳ |
| 8  | DB_ROOT_PASSWORD | PasswordRootMySQL123! | ⏳ |
| 9  | DB_HOST | db | ⏳ |
| 10 | DB_NAME | form_vaksin | ⏳ |
| 11 | DB_USERNAME | laravel_user | ⏳ |
| 12 | DB_PASSWORD | RahAsiaDatabase123! | ⏳ |

---

## 🎯 CARA ISI SECRETS DI GITHUB

### Step by Step:

1. **Buka link:** https://github.com/Studentalthaf/form-vaksin-RSI/settings/secrets/actions

2. **Untuk setiap secret:**
   - Klik tombol **"New repository secret"**
   - **Name:** Isi dengan nama secret (contoh: `VPS_HOST`)
   - **Value:** Isi dengan nilai secret (contoh: `103.123.45.67`)
   - Klik **"Add secret"**

3. **Ulangi** untuk semua 12 secrets

4. **Verifikasi:** Setelah selesai, Anda akan lihat 12 secrets di list

---

## ✅ SETELAH SEMUA SECRETS TERISI

### Test Deployment:

**Option 1: Tunggu Otomatis**
- GitHub Actions sudah berjalan karena Anda push tadi
- Buka: https://github.com/Studentalthaf/form-vaksin-RSI/actions
- Lihat workflow yang sedang running

**Option 2: Trigger Manual**
Jalankan di terminal:
```powershell
git commit --allow-empty -m "Test deployment to VPS"
git push origin main
```

---

## 🔍 CEK HASIL DEPLOYMENT

### 1. Lihat Progress di GitHub Actions
- Buka: https://github.com/Studentalthaf/form-vaksin-RSI/actions
- Klik workflow terakhir
- Lihat log setiap step

### 2. Akses Aplikasi
- Buka browser: `http://IP_VPS_ANDA:8080`
- Contoh: `http://103.123.45.67:8080`

### 3. Cek di VPS (jika ada masalah)
```bash
ssh root@IP_VPS_ANDA
cd /var/www/form-vaksin
docker ps                    # Lihat container running
docker logs laravel_app      # Lihat log aplikasi
docker logs laravel_mysql    # Lihat log database
```

---

## 🆘 TROUBLESHOOTING

### Error: Permission denied
- Pastikan VPS_SSH_KEY sudah benar
- Cek di VPS: `cat ~/.ssh/authorized_keys`

### Error: Cannot connect to VPS
- Pastikan VPS_HOST benar (IP atau domain)
- Pastikan VPS_USER benar (root/ubuntu)
- Test manual: `ssh VPS_USER@VPS_HOST`

### Error: Database connection failed
- Pastikan DB_PASSWORD di LARAVEL_ENV sama dengan DB_PASSWORD secret
- Cek di VPS: `docker logs laravel_mysql`

### Error: Port 8080 already in use
- Ganti APP_PORT dengan port lain (misal: 8081)
- Update APP_URL di LARAVEL_ENV juga

---

## 📞 NEED HELP?

Jika ada error, screenshot:
1. GitHub Actions log
2. Error message
3. Output `docker ps` dan `docker logs`

Good luck! 🚀
