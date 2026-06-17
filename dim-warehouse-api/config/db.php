<?php
/**
 * Koneksi database ke Supabase (PostgreSQL) menggunakan PDO.
 * Kredensial diambil dari Environment Variables (diset di dashboard Vercel),
 * supaya tidak ada password yang ter-hardcode / ke-commit ke GitHub.
 */

function getDbConnection(): PDO
{
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT') ?: '5432';
    $dbname = getenv('DB_NAME') ?: 'postgres';
    $user = getenv('DB_USER') ?: 'postgres';
    $password = getenv('DB_PASSWORD');

    if (!$host || !$password) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Konfigurasi database belum lengkap. Pastikan environment variables DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD sudah diset.'
        ]);
        exit;
    }

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";

    try {
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Koneksi ke database gagal.',
            'error' => $e->getMessage()
        ]);
        exit;
    }
}
