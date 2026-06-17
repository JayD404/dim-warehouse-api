<?php
/**
 * REST API endpoint untuk tabel dim_lama_dapat_kerja
 *
 * GET    /api/lama_dapat_kerja.php              -> ambil semua data
 * GET    /api/lama_dapat_kerja.php?id=1         -> ambil 1 data berdasarkan id
 * GET    /api/lama_dapat_kerja.php?search=bulan -> cari data
 * POST   /api/lama_dapat_kerja.php              -> tambah data baru (body: {"lama_dapat_kerja": "..."})
 * PUT    /api/lama_dapat_kerja.php?id=1         -> update data (body: {"lama_dapat_kerja": "..."})
 * DELETE /api/lama_dapat_kerja.php?id=1         -> hapus data
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/crud_handler.php';

handleCrudRequest('dim_lama_dapat_kerja', 'id_lama_dapat_kerja', 'lama_dapat_kerja');
