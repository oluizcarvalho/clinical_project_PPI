<?php

require "../conexaoMysql.php";
$pdo = mysqlConnect();

$cep = $_POST["cep"] ?? "";
$logradouro = $_POST["logradouro"] ?? "";
$cidade = $_POST["cidade"] ?? "";
$estado = $_POST["estado"] ?? "";

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
    INSERT INTO Enderecos (cep, logradouro, cidade, estado)
    VALUES (?, ?, ?, ?)
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $cep, $logradouro, $cidade, $estado
    ]);

    echo json_encode(new RequestResponse(true, "Endereço cadastrado com sucesso"));
} catch (Exception $ex) {
    if ($ex->errorInfo[1] === 1062)
        echo json_encode(new RequestResponse(false, "Dados duplicados"));
    else
        echo json_encode(new RequestResponse(false, $ex->getMessage()));
}
