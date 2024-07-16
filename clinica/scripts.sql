CREATE TABLE Pessoa (
  id int PRIMARY KEY AUTO INCREMENT,
  nome varchar(255),
  sexo varchar(30),
  email varchar(100),
  telefone varchar(11),
  cep varchar(8),
  logradouro varchar(100),
  cidade varchar(100),
  estado varchar(100)
);

CREATE TABLE Funcionario (
  id int,
  dataContrato date,
  salario float8,
  senhaHash varchar(100)
);

CREATE TABLE Paciete (
  id int,
  peso float8,
  altura float4,
  tipoSanguineo varchar(2)
);

CREATE TABLE Medico (
  id int,
  especialidade varchar(100),
  crm varchar(100)
);

CREATE TABLE Agenda (
  id int PRIMARY KEY AUTO INCREMENT,
  data date,
  horario datetime,
  pacienteNome varchar(255),
  sexo varchar(30),
  email varchar(100),
  medico_id int
);

CREATE TABLE BaseEnderecosAjax (
  cep varchar(8),
  logradouro varchar(100),
  cidade varchar(100),
  estado varchar(100)
);

ALTER TABLE Funcionario ADD FOREIGN KEY (id) REFERENCES Pessoa (id);

ALTER TABLE Paciete ADD FOREIGN KEY (id) REFERENCES Pessoa (id);

ALTER TABLE Medico ADD FOREIGN KEY (id) REFERENCES Funcionario (id);

ALTER TABLE Agenda ADD FOREIGN KEY (medico_id) REFERENCES Medico (id);
