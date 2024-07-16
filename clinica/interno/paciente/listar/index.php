<?php

require_once "../../../conexaoMysql.php";
require_once "../../../login/autenticacao.php";

session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);

try {
    $emailUsuario = $_SESSION["emailUsuario"];
    $sqlMed = <<<SQL
    SELECT pe.nome, pe.sexo, pe.email, pe.telefone, pe.cep, pe.logradouro, 
        pe.cidade, pe.estado, fu.dataContrato, fu.salario, me.especialidade, me.crm
    FROM Funcionario fu 
    INNER JOIN Pessoa pe 
    on fu.id = pe.id 
    INNER JOIN Medico me on fu.id = me.id
    WHERE pe.email = ?
    SQL;
    $stmtMed = $pdo->prepare($sqlMed);
    $stmtMed->execute([$emailUsuario]);

    $sql = <<<SQL
    SELECT pe.nome, pe.sexo, pe.email, pe.telefone, pe.cep, pe.logradouro, 
        pe.cidade, pe.estado, pa.peso, pa.altura, pa.tipoSanguineo 
    FROM Paciente pa INNER JOIN Pessoa pe on pa.id = pe.id
    SQL;
    $stmt = $pdo->query($sql);
} 
catch (Exception $e) {
  exit('Ocorreu uma falha: ' . $e->getMessage());
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lista de Paciente</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="../../index.css">
  <link rel="stylesheet" href="../../global.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div class="logo">
                    <a class="navbar-brand d-flex text-align-bottom logoIcone" href="#">
                        <img src="../../../image/logo2.png">
                    </a>
                    <a class="navbar-brand marca" href="#">Biazonne</a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="../../">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../funcionario/cadastro/">Novo Funcionário</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../cadastro/">Novo Paciente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../funcionario/listar/">Listar Funcionários</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Listar Pacientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../endereco/listar">Listar Endereços</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../agendamento/listar">Listar Agendamentos</a>
                        </li>
                        <li class="nav-item" style="display: none" id="meus_agendamentos">
                            <a class="nav-link" href="../../meu_agendamento/listar">Listar meus Agendamentos</a>
                        </li>
                    </ul>
                    <a href="../../../logout" class="d-flex text-decoration-none">
                        <button class="btn btn-primary" type="submit">Logout</button>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div class="sub-Nav"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous">
    </script>

    <main class="container main-content">
    <section class="container mt-3">
        <h3>Clientes</h3>
        <table class="table table-striped table-hover">
        <tr>
            <th>Nome</th>
            <th>Sexo</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>CEP</th>
            <th>Logradouro</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Peso</th>
            <th>Altura</th>
            <th>Tipo Sanguíneo</th>
        </tr>

        <?php
        while ($row = $stmt->fetch()) {

            $nome = htmlspecialchars($row['nome']);
            $sexo = htmlspecialchars($row['sexo']);
            $email = htmlspecialchars($row['email']);
            $telefone = htmlspecialchars($row['telefone']);
            $cep = htmlspecialchars($row['cep']);
            $logradouro = htmlspecialchars($row['logradouro']);
            $cidade = htmlspecialchars($row['cidade']);
            $estado = htmlspecialchars($row['estado']);
            $peso = htmlspecialchars($row['peso']);
            $altura = htmlspecialchars($row['altura']);
            $tipoSanguíneo = htmlspecialchars($row['tipoSanguineo']);

            echo <<<HTML
            <tr>
                <td>$nome</td>
                <td>$sexo</td>
                <td>$email</td>
                <td>$telefone</td>
                <td>$cep</td>
                <td>$logradouro</td>
                <td>$cidade</td>
                <td>$estado</td>
                <td>$peso</td>
                <td>$altura</td>
                <td>$tipoSanguíneo</td>
            </tr>
            HTML;

        }
        ?>

        </table>
    </section>
    </main>
    <footer class="d-flex justify-content-center copyRight">&copy; Copyright 2022 - Todos os direitos reservados</footer>
    <?php
        $rowMed = $stmtMed->fetch();
        if($rowMed['especialidade'] != null){
            echo <<<HTML
                <script>
                    window.onload = function () {
                        const navMeusAgendamentos = document.getElementById('meus_agendamentos');
                        navMeusAgendamentos.style.display = 'block';
                    }
                </script>
            HTML;
        }
    ?>
</body>

</html>