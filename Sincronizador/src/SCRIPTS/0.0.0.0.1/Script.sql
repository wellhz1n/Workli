CREATE TABLE IF NOT EXISTS usuarios(
  id int(11) auto_increment NOT NULL,
  nome varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  senha varchar(255) NOT NULL,
  constraint PK_USUARIO primary key (id)  
);

ALTER TABLE usuarios MODIFY COLUMN email VARCHAR(255) UNIQUE KEY;

ALTER TABLE usuarios ADD if not EXISTS nivel_usuario INT(1) NOT NULL DEFAULT 0;

ALTER TABLE usuarios ADD cpf VARCHAR(11) NOT NULL UNIQUE KEY AFTER email;

INSERT IGNORE INTO usuarios (id, nome, email,senha,nivel_usuario,cpf) VALUES(-1, 'Root', 'root','202cb962ac59075b964b07152d234b70',2,'11111111111');

CREATE TABLE IF NOT EXISTS imagem_usuario(
  id INT(16) auto_increment NOT NULL,
  imagem LONGTEXT CHARACTER  SET BINARY,
  extensao_imagem varchar(255),
  id_usuario INT(16) NOT NULL,

  constraint PK_IMAGEM_USUARIO PRIMARY KEY (id),

  constraint FK_USUARIOIMAGEMID FOREIGN KEY (id_usuario)
  REFERENCES usuarios(id)
);

ALTER TABLE imagem_usuario MODIFY COLUMN if EXISTS imagem LONGTEXT CHARACTER  SET BINARY;


CREATE TABLE IF NOT EXISTS funcionario(
  id INT(16) auto_increment NOT NULL,
  curriculo TEXT,
  numero_telefone varchar(16),
  avaliacao_media DECIMAL(4,3) NOT NULL DEFAULT 0,
  id_usuario INT(16) NOT NULL,
  PRIMARY KEY (id),
  constraint FK_USUARIOFUNCID FOREIGN KEY (id_usuario)
  REFERENCES usuarios(id)
);
CREATE TABLE IF NOT EXISTS tipo_servico(
  id INT auto_increment NOT NULL,
  nome varchar(255) NOT NULL,
  descricao TEXT,
  ativo int(1),
  PRIMARY KEY (id)
);

alter table tipo_servico MODIFY COLUMN descricao LONGTEXT;


CREATE TABLE IF NOT EXISTS servico(
  id INT auto_increment NOT NULL,
  id_usuario INT not Null,
  nome varchar(255) NOT NULL,
  id_tipo_servico int not null,
  descricao TEXT,
  valor DECIMAL(8,3) NOT NULL,
  Ativo TINYINT NOT NULL,

  PRIMARY KEY (id),
  constraint FK_ID_USUARIOSERVICO FOREIGN KEY (id_usuario)
  REFERENCES usuarios(id),
  constraint FK_ID_TIPOSERVICO FOREIGN KEY (id_tipo_servico)
  REFERENCES tipo_servico(id)
);
CREATE TABLE if not EXISTS  servicos_funcionario(
  id INT auto_increment NOT NULL,
  horas_trabalhadas INT,
  id_funcionario int not null,
  id_servico int not null,
  Situacao TINYINT NOT NULL DEFAULT 0,
  Porcentagem INT,
  PRIMARY KEY (id),
  constraint FK_ID_FUNCIONARIOSERVICO FOREIGN key (id_funcionario)
  REFERENCES funcionario(id) ,
	constraint FK_ID_SERVICOSERVICO FOREIGN key (id_servico)
  REFERENCES servico(id)
);

CREATE TABLE IF NOT EXISTS foto_servico (
  id INT auto_increment NOT NULL,
  imagem MEDIUMBLOB,
  extensao_imagem varchar(15),
  id_servico INT NOT NULL,
  PRIMARY KEY (id),
  constraint FK_ID_SERVICOFOTO
  FOREIGN KEY (id_servico)
  REFERENCES servico(id)
);
ALTER TABLE funcionario MODIFY COLUMN curriculo mediumblob NULL;
ALTER TABLE funcionario MODIFY COLUMN numero_telefone varchar(16) NULL;
set global net_buffer_length=1000000; 
set global max_allowed_packet=1000000000;
ALTER TABLE tipo_servico add  COLUMN imagem LONGTEXT CHARACTER  SET BINARY;

alter table foto_servico add COLUMN if not EXISTS principal int(1) DEFAULT 0 ;
alter table servico add COLUMN if not EXISTS nivel_projeto int(1);
alter table servico modify COLUMN if  EXISTS valor int(2);
alter table servico add COLUMN if not EXISTS nivel_profissional int(1);
alter table servico add COLUMN if not EXISTS data_cadastro   timestamp DEFAULT CURRENT_TIMESTAMP;
alter table servico modify COLUMN if EXISTS Ativo int(1) DEFAULT 1;

-- Wellington 14/06/2020 Tarefa #1 

 create table IF NOT EXISTS chat(
	id_chat bigint auto_increment primary key,
	id_servico int,
	constraint CHAT_ID_SERVICO foreign key(id_servico) references servico(id) 
);
create table IF NOT EXISTS chat_mensagens(
	id_chat_mensagens bigint auto_increment primary key,
	id_chat bigint,
	id_usuario_remetente int,
	id_usuario_destinatario int,
	msg text,
	data_hora timestamp default current_timestamp,
	visualizado int(1) default 0,
	constraint FK_ID_CHAT_MENSAGENS_CHAT foreign key (id_chat) references chat(id_chat),
	constraint FK_ID_USUARIO_REMETENTE foreign key(id_usuario_remetente) references usuarios(id),
	constraint FK_ID_USUARIO_DESTINATARIO foreign key(id_usuario_destinatario) references usuarios(id)
);
	ALTER TABLE chat_mensagens CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
  	ALTER DATABASE worklidb CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- TRIGGER RODAR MANUALMENTE
-- delimiter $$
-- create trigger set_Ativo_servico after update  
-- on servico
-- for each row 
-- begin 
-- 	if(NEW.Ativo = 0) then
-- 		update servicos_funcionario 
-- 		set servicos_funcionario.Ativo = 0 where servicos_funcionario.Situacao = 0 and 
-- 		servicos_funcionario.id_servico = NEW.id;
-- 	end if;
-- end $$
-- delimiter ;