<?php
/**
 * REST API endpoint untuk tabel dim_jabatan
 *
 * GET    /api/jabatan.php              -> ambil semua data
 * GET    /api/jabatan.php?id=1         -> ambil 1 data berdasarkan id
 * GET    /api/jabatan.php?search=ketua -> cari data berdasarkan nama jabatan
 * POST   /api/jabatan.php              -> tambah data baru (body: {"jabatan": "..."})
 * PUT    /api/jabatan.php?id=1         -> update data (body: {"jabatan": "..."})
 * DELETE /api/jabatan.php?id=1         -> hapus data
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/crud_handler.php';

handleCrudRequest('dim_jabatan', 'id_jabatan', 'jabatan');
