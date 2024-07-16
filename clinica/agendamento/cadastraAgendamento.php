<?php

require "../conexaoMysql.php";
$pdo = mysqlConnect();

$data = $_POST["data"] ?? "";
$hora = $_POST["hora"] ?? "";
$nome = $_POST["nome"] ?? "";
$sexo = $_POST["sexo"] ?? "";
$email = $_POST["email"] ?? "";
$medico = $_POST["medico"] ?? "";

class RequestResponse
{
    public $success;
    public $message;
    function __construct($success, $message)
    {
        $this->success = $success;
        $this->message = $message;
    }
}

try {
    $sql = <<<SQL
    INSERT INTO Agenda (data, horario, pacienteNome, sexo, email, medico_id)
    VALUES (?, ?, ?, ?, ?, ?)
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data, $hora, $nome, $sexo, $email, $medico
    ]);

    echo json_encode(new RequestResponse(true, "Agendamento cadastrado com sucesso"));
} catch (Exception $ex) {
    if ($ex->errorInfo[1] === 1062)
        echo json_encode(new RequestResponse(false, "Dados duplicados"));
    else
        echo json_encode(new RequestResponse(false, $ex->getMessage()));
}
