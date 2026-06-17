<?php
/**
 * REST API endpoint untuk tabel dim_prodi
 *
 * GET    /api/prodi.php              -> ambil semua data
 * GET    /api/prodi.php?id=1         -> ambil 1 data berdasarkan id
 * GET    /api/prodi.php?search=teknik -> cari data berdasarkan nama prodi
 * POST   /api/prodi.php              -> tambah data baru (body: {"prodi": "..."})
 * PUT    /api/prodi.php?id=1         -> update data (body: {"prodi": "..."})
 * DELETE /api/prodi.php?id=1         -> hapus data
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/crud_handler.php';

handleCrudRequest('dim_prodi', 'id_prodi', 'prodi');
