<?php
require_once('db.php');
require_once('session_check.php');

error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

$this_user_id = $_SESSION['ID'];

$response = [
    'success' => false,
    'error' => null,
    'url' => null,
];

try {
    if (!isset($_FILES['profile_picture'])) {
        throw new RuntimeException('No file sent.');
    }

    $file = $_FILES['profile_picture'];

    // Check for upload errors.
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('File upload error code: ' . $file['error']);
    }

    // Validate file size (5 MiB limit)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // Validate file type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowed = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
    ];

    $ext = array_search($mimeType, $allowed, true);
    if ($ext === false) {
        throw new RuntimeException('Invalid file format.');
    }

    // Upload directory
    $targetDir = __DIR__ . '/uploads/profile_pictures';
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
        throw new RuntimeException('Failed to create upload directory.');
    }

    $newFilename = sprintf('%d_%s.%s', $this_user_id, bin2hex(random_bytes(8)), $ext);
    $targetPath = $targetDir . '/' . $newFilename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    $publicUrl = '/uploads/profile_pictures/' . $newFilename;

    // Remove previous picture if exists
    $stmt = $pdo->prepare('SELECT avatar_url FROM nutzer WHERE user_id = :uid');
    $stmt->execute([':uid' => $this_user_id]);
    $previous = $stmt->fetchColumn();

    $stmtUpdate = $pdo->prepare('UPDATE nutzer SET avatar_url = :pp WHERE user_id = :uid');
    $stmtUpdate->execute([
        ':pp' => $publicUrl,
        ':uid' => $this_user_id,
    ]);

    if ($previous && $previous !== $publicUrl) {
        $oldPath = $_SERVER['DOCUMENT_ROOT'] . $previous;
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    $response['success'] = true;
    $response['url'] = $publicUrl;

} catch (RuntimeException $e) {
    $response['error'] = $e->getMessage();
} catch (Exception $e) {
    $response['error'] = 'Unexpected server error.';
}

echo json_encode($response);
