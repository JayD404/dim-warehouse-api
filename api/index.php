<?php
header('Content-Type: application/json');

echo json_encode([
    'message' => 'REST API Data Warehouse - Tabel Dimensi',
    'status' => 'online',
    'endpoints' => [
        'jabatan' => '/api/jabatan.php',
        'kabupaten_kota' => '/api/kabupaten_kota.php',
        'lama_dapat_kerja' => '/api/lama_dapat_kerja.php',
        'sekolah' => '/api/sekolah.php',
        'prodi' => '/api/prodi.php',
    ],
    'methods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'contoh' => [
        'ambil_semua' => 'GET /api/jabatan.php',
        'ambil_satu' => 'GET /api/jabatan.php?id=1',
        'cari' => 'GET /api/jabatan.php?search=ketua',
        'tambah' => 'POST /api/jabatan.php  body: {"jabatan": "Staf Baru"}',
        'update' => 'PUT /api/jabatan.php?id=1  body: {"jabatan": "Ketua Umum Baru"}',
        'hapus' => 'DELETE /api/jabatan.php?id=1',
    ]
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
