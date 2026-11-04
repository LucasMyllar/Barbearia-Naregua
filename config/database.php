
<?php

$host = 'aws-0-us-east-1.pooler.supabase.com';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres.pytnrzhzuihrritinhqf';
$password = 'lcsmll07...';

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

} catch (PDOException $e) {
    exit('Erro de conexão: ' . htmlspecialchars($e->getMessage()));
}
