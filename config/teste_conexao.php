
<?php

require_once __DIR__ . '/database.php';

try {
    $resultado = $pdo->query('SELECT NOW() AS horario')->fetch();

    echo 'Conexão com Supabase realizada com sucesso!';
    echo '<br>';
    echo 'Horário do banco: ' . $resultado['horario'];

} catch (PDOException $e) {
    echo 'Erro ao consultar o banco.';
}
