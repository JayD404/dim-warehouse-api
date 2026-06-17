# REST API Data Warehouse — Tabel Dimensi

REST API CRUD (Create, Read, Update, Delete) untuk 5 tabel dimensi dari data warehouse Pentaho, dibangun dengan PHP, database PostgreSQL (Supabase), dan dideploy ke Vercel.

## Tabel yang disediakan

| Tabel asli (MySQL) | Endpoint | Kolom nilai | Kolom id |
|---|---|---|---|
| dim_jabatan | `/api/jabatan.php` | `jabatan` | `id_jabatan` |
| dim_kabupaten_kota | `/api/kabupaten_kota.php` | `kabupaten_kota` | `id_kabupaten_kota` |
| dim_lama_dapat_kerja | `/api/lama_dapat_kerja.php` | `lama_dapat_kerja` | `id_lama_dapat_kerja` |
| dim_sekolah | `/api/sekolah.php` | `nama_sekolah` | `id_sekolah` |
| dim_prodi | `/api/prodi.php` | `prodi` | `id_prodi` |

> Catatan: nama kolom yang sebelumnya mengandung spasi (misal `KABUPATEN KOTA`, `NAMA SEKOLAH`) diubah ke format snake_case tanpa spasi (`kabupaten_kota`, `nama_sekolah`) agar lebih aman digunakan di URL, JSON, dan query SQL.

## Struktur project

```
project/
├── api/
│   ├── index.php              # info dasar API
│   ├── jabatan.php
│   ├── kabupaten_kota.php
│   ├── lama_dapat_kerja.php
│   ├── sekolah.php
│   └── prodi.php
├── config/
│   ├── db.php                 # koneksi PDO ke Supabase
│   └── crud_handler.php       # logika CRUD generik dipakai semua endpoint
├── vercel.json                # konfigurasi runtime PHP untuk Vercel
└── .gitignore
```

## Environment Variables yang dibutuhkan

Diset di dashboard Vercel (Project Settings → Environment Variables), BUKAN di dalam kode:

- `DB_HOST` — host Supabase, contoh: `db.xxxxxxxxxxxx.supabase.co`
- `DB_PORT` — biasanya `5432` (direct connection) atau `6543` (pooler)
- `DB_NAME` — biasanya `postgres`
- `DB_USER` — biasanya `postgres`
- `DB_PASSWORD` — password database Supabase kamu

## Format response

Semua endpoint mengembalikan JSON dengan format konsisten:

```json
{
  "success": true,
  "count": 3,
  "data": [...]
}
```

Jika error:

```json
{
  "success": false,
  "message": "Pesan error di sini"
}
```

## Contoh penggunaan setiap endpoint (pakai dim_jabatan sebagai contoh)

### GET — ambil semua data
```
GET /api/jabatan.php
```

### GET — ambil satu data oleh id
```
GET /api/jabatan.php?id=1
```

### GET — cari berdasarkan nama (case-insensitive)
```
GET /api/jabatan.php?search=ketua
```

### GET — paging (khusus dim_sekolah yang datanya besar, ~2000 baris)
```
GET /api/sekolah.php?limit=50&offset=0
```

### POST — tambah data baru
```
POST /api/jabatan.php
Content-Type: application/json

{
  "jabatan": "Staf Kementerian Baru"
}
```

### PUT — update data
```
PUT /api/jabatan.php?id=213
Content-Type: application/json

{
  "jabatan": "Staf Kementerian Baru (Revisi)"
}
```

### DELETE — hapus data
```
DELETE /api/jabatan.php?id=213
```

## Lisensi
Internal project — bebas dimodifikasi sesuai kebutuhan.
