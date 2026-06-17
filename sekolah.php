<?php
/**
 * REST API endpoint untuk tabel dim_sekolah
 *
 * GET    /api/sekolah.php              -> ambil semua data
 * GET    /api/sekolah.php?id=1         -> ambil 1 data berdasarkan id
 * GET    /api/sekolah.php?search=man+1 -> cari data berdasarkan nama sekolah
 * GET    /api/sekolah.php?limit=50&offset=0 -> ambil data dengan paging (tabel ini besar, ~2000 baris)
 * POST   /api/sekolah.php              -> tambah data baru (body: {"nama_sekolah": "..."})
 * PUT    /api/sekolah.php?id=1         -> update data (body: {"nama_sekolah": "..."})
 * DELETE /api/sekolah.php?id=1         -> hapus data
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/crud_handler.php';

handleCrudRequest('dim_sekolah', 'id_sekolah', 'nama_sekolah');
