<?php
require "../conexaoMysql.php";
$pdo = mysqlConnect();

$especialidade = $_GET["especialidade"] ?? "";

class Medico
{
    public $id;
    public $nome;
    function __construct($id, $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
    }
}

try {

    $sql = <<<SQL
      SELECT p.nome, p.id
      FROM Medico m
      inner join
      Pessoa p
      on m.id = p.id
      WHERE especialidade = ?
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$especialidade]);

    $medicos = [];
    while ($row = $stmt->fetch()) {
        $medico = new Medico(htmlspecialchars($row['id']), htmlspecialchars($row['nome']));
        array_push($medicos, $medico);
    }
    echo json_encode($medicos);
} catch (Exception $e) {
    exit('Ocorreu uma falha: ' . $e->getMessage());
}
