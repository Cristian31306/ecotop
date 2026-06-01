<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/vps_database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "--- Users ---\n";
$users = $pdo->query("SELECT id, name, email, created_at FROM users")->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $u) {
    echo "ID: {$u['id']} | Email: {$u['email']} | Created: {$u['created_at']}\n";
}

echo "\n--- Scores ---\n";
$scores = $pdo->query("SELECT id, user_id, ecosystem_id, score, created_at FROM user_scores")->fetchAll(PDO::FETCH_ASSOC);
foreach ($scores as $s) {
    echo "ID: {$s['id']} | UserID: {$s['user_id']} | Score: {$s['score']} | Created: {$s['created_at']}\n";
}
