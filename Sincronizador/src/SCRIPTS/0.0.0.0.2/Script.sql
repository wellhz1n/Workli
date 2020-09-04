-- Wellington.Ramos em 15/07/2020 Tarefa #6;
alter table servico change Ativo situacao int(1) not null default 0 ;

CREATE TABLE IF NOT EXISTS proposta (
	id INT AUTO_INCREMENT,
    idFuncionario INT,
    idCliente INT,
    idServico INT,
    valor FLOAT,
    descricao TEXT,
    upgrades TINYINT,
    PRIMARY KEY (id),
    FOREIGN KEY (idFuncionario) REFERENCES funcionario(id),
    FOREIGN KEY (idCliente) REFERENCES usuarios(id),
    FOREIGN KEY (idServico) REFERENCES servico(id)
);
alter table proposta add column if not exists data_criacao timestamp default current_timestamp;
alter table proposta add column if not exists situacao numeric(1) default 0;
ALTER TABLE proposta MODIFY COLUMN idFuncionario int(11) NOT NULL;
ALTER TABLE proposta MODIFY COLUMN idCliente int(11)  NOT NULL;
ALTER TABLE proposta MODIFY COLUMN idServico int(11) NOT NULL;

-- Wellington.Ramos em 2/08/2020 Tarefa #7;
create table IF NOT EXISTS notificacoes(
		id bigint auto_increment primary key,
		descricao varchar(255),
		titulo varchar(100),
		id_projeto int  null,
		id_chat bigint  null,
		id_usuario int,
		id_usuario_criacao int,
		data_hora timestamp default current_timestamp,
        tipo int(1)default 0,
		visto int(1) default 0,
		constraint FK_PROJETO_NOTIFICACAO foreign key (id_projeto) references servico(id),
		constraint FK_CHAT_NOTIFICACAO foreign key (id_chat) references chat(id_chat),
		constraint FK_USUARIO_NOTIFICACAO foreign key (id_usuario) references usuarios(id),
		constraint FK_USUARIO_CRIACAO_NOTIFICACAO foreign key (id_usuario_criacao) references usuarios(id)
);
ALTER TABLE notificacoes MODIFY COLUMN descricao TEXT DEFAULT NULL NULL;

ALTER TABLE imagem_usuario
CHANGE extensao_imagem imagem_banner longblob;


ALTER TABLE usuarios
ADD COLUMN descricao TEXT
AFTER nome;

ALTER TABLE funcionario
ADD COLUMN plano TINYINT DEFAULT 0
AFTER avaliacao_media,
ADD COLUMN tags TEXT
AFTER avaliacao_media,
ADD COLUMN profissao VARCHAR(50)
AFTER avaliacao_media;

ALTER TABLE funcionario
DROP curriculo,
DROP numero_telefone;


