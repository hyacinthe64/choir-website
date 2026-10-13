<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $pdo->prepare("DELETE FROM members WHERE id = ?")->execute([$id]);
}
header('Location: members.php?deleted=1');
exit;
