<?php
require "../conexaoMysql.php";
$pdo = mysqlConnect();

$medico = $_GET["medico"] ?? "";
$data = $_GET["data"] ?? "";

try {

    $sql = <<<SQL
      SELECT a.horario
      FROM Agenda a
      inner join
      Pessoa p
      on a.medico_id = p.id
      WHERE p.id = ? and a.data = ?
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$medico, $data]);

    $horarios = [];
    while ($row = $stmt->fetch()) {
        $horario = htmlspecialchars($row['horario']);
        array_push($horarios, $horario);
    }
    echo json_encode($horarios);
} catch (Exception $e) {
    exit('Ocorreu uma falha: ' . $e->getMessage());
}