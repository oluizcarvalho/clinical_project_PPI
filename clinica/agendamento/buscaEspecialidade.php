<?php
require "../conexaoMysql.php";
$pdo = mysqlConnect();

try {

    $sql = <<<SQL
      SELECT especialidade FROM Medico
    SQL;

    $stmt = $pdo->query($sql);
    $especialidades = [];
    while ($row = $stmt->fetch()) {
        $especialidade = htmlspecialchars($row['especialidade']);
        array_push($especialidades, $especialidade);
    }
    echo json_encode($especialidades);
} catch (Exception $e) {
    exit('Ocorreu uma falha: ' . $e->getMessage());
}
