-- Wellington.Ramos em 15/07/2020 Tarefa #6
alter table servico change Ativo situacao int(1) not null default 0 ;

CREATE TABLE proposta (
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