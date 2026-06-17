<?php
/**
 * Helper generik untuk menangani request CRUD (GET, POST, PUT, DELETE)
 * pada satu tabel dimensi. Dipakai oleh setiap file di folder /api.
 *
 * Cara pakai di file endpoint:
 *   require_once __DIR__ . '/../config/db.php';
 *   require_once __DIR__ . '/../config/crud_handler.php';
 *   handleCrudRequest('dim_jabatan', 'id_jabatan', 'jabatan');
 */

function sendJson($data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function getJsonBody(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * @param string $table     Nama tabel di Postgres, contoh: dim_jabatan
 * @param string $idColumn  Nama kolom primary key, contoh: id_jabatan
 * @param string $nameColumn Nama kolom nilai/teks, contoh: jabatan
 */
function handleCrudRequest(string $table, string $idColumn, string $nameColumn): void
{
    // Header standar untuk REST API + izinkan diakses dari Postman/Pentaho/browser manapun (CORS)
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    $method = $_SERVER['REQUEST_METHOD'];

    // Preflight request dari browser, langsung balas OK
    if ($method === 'OPTIONS') {
        sendJson(['success' => true]);
    }

    $pdo = getDbConnection();

    switch ($method) {
        case 'GET':
            handleGet($pdo, $table, $idColumn, $nameColumn);
            break;
        case 'POST':
            handlePost($pdo, $table, $idColumn, $nameColumn);
            break;
        case 'PUT':
            handlePut($pdo, $table, $idColumn, $nameColumn);
            break;
        case 'DELETE':
            handleDelete($pdo, $table, $idColumn);
            break;
        default:
            sendJson(['success' => false, 'message' => 'Method tidak didukung.'], 405);
    }
}

function handleGet(PDO $pdo, string $table, string $idColumn, string $nameColumn): void
{
    $id = $_GET['id'] ?? null;
    $search = $_GET['search'] ?? null;

    if ($id !== null) {
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$idColumn} = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            sendJson(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        sendJson(['success' => true, 'data' => $row]);
    }

    if ($search !== null && $search !== '') {
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$nameColumn} ILIKE :search ORDER BY {$idColumn} ASC");
        $stmt->execute(['search' => '%' . $search . '%']);
        $rows = $stmt->fetchAll();
        sendJson(['success' => true, 'count' => count($rows), 'data' => $rows]);
    }

    // Ambil semua data, dengan dukungan paging opsional (?limit=&offset=)
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : null;
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

    $sql = "SELECT * FROM {$table} ORDER BY {$idColumn} ASC";
    if ($limit !== null) {
        $sql .= " LIMIT :limit OFFSET :offset";
    }

    $stmt = $pdo->prepare($sql);
    if ($limit !== null) {
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    $rows = $stmt->fetchAll();

    sendJson(['success' => true, 'count' => count($rows), 'data' => $rows]);
}

function handlePost(PDO $pdo, string $table, string $idColumn, string $nameColumn): void
{
    $body = getJsonBody();

    if (!array_key_exists($nameColumn, $body)) {
        sendJson(['success' => false, 'message' => "Field '{$nameColumn}' wajib diisi di body request."], 400);
    }

    $value = $body[$nameColumn];

    // Jika id disediakan manual, pakai itu. Jika tidak, biarkan sequence Postgres yang generate.
    if (array_key_exists($idColumn, $body) && $body[$idColumn] !== null && $body[$idColumn] !== '') {
        $stmt = $pdo->prepare("INSERT INTO {$table} ({$idColumn}, {$nameColumn}) VALUES (:id, :value) RETURNING *");
        $stmt->execute(['id' => $body[$idColumn], 'value' => $value]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO {$table} ({$nameColumn}) VALUES (:value) RETURNING *");
        $stmt->execute(['value' => $value]);
    }

    $newRow = $stmt->fetch();
    sendJson(['success' => true, 'message' => 'Data berhasil ditambahkan.', 'data' => $newRow], 201);
}

function handlePut(PDO $pdo, string $table, string $idColumn, string $nameColumn): void
{
    $id = $_GET['id'] ?? null;
    $body = getJsonBody();

    if ($id === null) {
        sendJson(['success' => false, 'message' => "Parameter '?id=' wajib disertakan di URL untuk update."], 400);
    }

    if (!array_key_exists($nameColumn, $body)) {
        sendJson(['success' => false, 'message' => "Field '{$nameColumn}' wajib diisi di body request."], 400);
    }

    $stmt = $pdo->prepare("UPDATE {$table} SET {$nameColumn} = :value WHERE {$idColumn} = :id RETURNING *");
    $stmt->execute(['value' => $body[$nameColumn], 'id' => $id]);
    $updatedRow = $stmt->fetch();

    if (!$updatedRow) {
        sendJson(['success' => false, 'message' => 'Data dengan id tersebut tidak ditemukan.'], 404);
    }

    sendJson(['success' => true, 'message' => 'Data berhasil diperbarui.', 'data' => $updatedRow]);
}

function handleDelete(PDO $pdo, string $table, string $idColumn): void
{
    $id = $_GET['id'] ?? null;

    if ($id === null) {
        sendJson(['success' => false, 'message' => "Parameter '?id=' wajib disertakan di URL untuk menghapus data."], 400);
    }

    $stmt = $pdo->prepare("DELETE FROM {$table} WHERE {$idColumn} = :id RETURNING *");
    $stmt->execute(['id' => $id]);
    $deletedRow = $stmt->fetch();

    if (!$deletedRow) {
        sendJson(['success' => false, 'message' => 'Data dengan id tersebut tidak ditemukan.'], 404);
    }

    sendJson(['success' => true, 'message' => 'Data berhasil dihapus.', 'data' => $deletedRow]);
}
