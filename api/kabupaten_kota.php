<?php
/**
 * REST API endpoint untuk tabel dim_kabupaten_kota
 *
 * GET    /api/kabupaten_kota.php              -> ambil semua data
 * GET    /api/kabupaten_kota.php?id=1         -> ambil 1 data berdasarkan id
 * GET    /api/kabupaten_kota.php?search=bone  -> cari data berdasarkan nama
 * POST   /api/kabupaten_kota.php              -> tambah data baru (body: {"kabupaten_kota": "..."})
 * PUT    /api/kabupaten_kota.php?id=1         -> update data (body: {"kabupaten_kota": "..."})
 * DELETE /api/kabupaten_kota.php?id=1         -> hapus data
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/crud_handler.php';

handleCrudRequest('dim_kabupaten_kota', 'id_kabupaten_kota', 'kabupaten_kota');
