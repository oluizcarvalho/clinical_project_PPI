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
}
catch (Exception $e) {
    exit('Ocorreu uma falha: ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Clínica Biazonne, atendimento diferenciado.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <title>Clínica</title>
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
                        <a class="nav-link" href="#">Novo Funcionário</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../paciente/cadastro/">Novo Paciente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../listar/">Listar Funcionários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../paciente/listar/">Listar Pacientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../endereco/listar/">Listar Endereços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../agendamento/listar/">Listar Agendamentos</a>
                    </li>
                    <li class="nav-item" style="display: none" id="meus_agendamentos">
                        <a class="nav-link" href="../../meu_agendamento/listar/">Listar meus Agendamentos</a>
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
        crossorigin="anonymous"></script>
<main class="container main-content">
    <section class="container mt-3">
        <h2>Cadastro de Funcionário</h2>
            <form action="action.php" method="post" class="row g-2">
                <div class="col-sm-6 mb-2 form-floating">
                    <input type="text" id="nome" name="nome" class="form-control" placeholder=" ">
                    <label for="nome">Nome</label>
                </div>
                <div class="col-sm-6 mb-2 form-floating">
                    <input type="email" name="email" id="email" class="form-control" placeholder=" ">
                    <label for="email">Email</label>
                </div>
                <div class="col-sm-4 mb-2 form-floating">
                    <input type="telefone" name="telefone" id="telefone" class="form-control" placeholder=" ">
                    <label for="telefone">Telefone</label>
                </div>
                <div class="col-sm-2 mb-2 form-floating">
                    <input type="text" id="sexo" name="sexo" class="form-control" placeholder=" ">
                    <label for="sexo">Sexo</label>
                </div>
                <div class="col-sm-2 mb-2 form-floating">
                    <input type="text" id="cep" name="cep" class="form-control" placeholder=" ">
                    <label for="cep">CEP</label>
                </div>
                <div class="col-sm-4 mb-2 form-floating">
                    <input type="text"  id="cidade" name="cidade" class="form-control" placeholder=" ">
                    <label for="cidade">Cidade</label>
                </div>
                <div class="col-sm-8 mb-2 form-floating">
                    <input type="text" id="logradouro" name="logradouro" class="form-control" placeholder=" ">
                    <label for="logradouro">Logradouro</label>
                </div>
                <div class="col-sm-4 mb-2 form-floating">
                    <select class="form-select" id="estado" name="estado" placeholder=" ">
                        <option selected></option>
                        <option>MG</option>
                    </select>
                    <label for="estado">Estado</label>
                </div>
                <div class="col-sm-4 mb-2 form-floating">
                    <input class="form-control" type="date" name="data" id="data">
                    <label for="data">Início do Contrato</label>
                </div>
                <div class="col-sm-4 mb-2 form-floating">
                    <input type="text"  id="salario" name="salario" class="form-control" placeholder=" ">
                    <label for="salario">Salario</label>
                </div>
                <div class="col-sm-4 mb-2 form-floating">
                    <input type="password"  id="senha" name="senha" class="form-control" placeholder=" ">
                    <label for="senha">Senha</label>
                </div>
                <div class="col-md-12 mb-2 form-floating">
                    <div class="form-check">
                        <input type="checkbox" onchange="abrirEspecialidade()" id="eMedico" class="form-check-input">
                        <label for="eMedico" class="form-check-label">É Médico ?</label>
                    </div>
                </div>
                <div class="container-fluid" id="especialidades" style="display: none;">
                    <div class="col-sm-6 mb-2 ml-2 form-floating especialidade">
                        <input type="text"  id="especialidade" name="especialidade" class="form-control" placeholder=" ">
                        <label for="especialidade">Especialidade</label>
                    </div>
                    <div class="col-sm-6 mb-2 form-floating">
                        <input type="text"  id="crm" name="crm" class="form-control" placeholder=" ">
                        <label for="crm">CRM</label>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <button type="submit" class="btn btn-primary">
                        Cadastrar 
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#FFFFFF"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/></svg>
                    </button>
                </div>
            </form>
    </section>
</main>
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
<script>
    function abrirEspecialidade(){
        var check = document.getElementById("eMedico").value;
        var divEspecialidade = document.getElementById("especialidades")
        if(check){
            divEspecialidade.style.display = 'flex';
        }else{
            divEspecialidade.style.display = 'none';
        }
        console.log(check);
    }
</script>
<footer class="d-flex justify-content-center copyRight">&copy; Copyright 2022 - Todos os direitos reservados</footer>
</body>
</html>