<?php
// db.php
$host = 'sistema_labor.mysql.dbaas.com.br';
$user = 'sistema_labor';
$password = 'xxxxxxxxx';
$dbname = 'sistema_labor';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>