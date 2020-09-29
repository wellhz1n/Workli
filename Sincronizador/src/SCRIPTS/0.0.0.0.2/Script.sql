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



ALTER TABLE funcionario avaliacao_media,
ADD COLUMN tags TEXT DEFAULT ""
AFTER avaliacao_media,
ADD COLUMN profissao VARCHAR(50)
AFTER avaliacao_media;

ALTER TABLE funcionario
DROP curriculo,
DROP numero_telefone;

DROP TABLE servicos_funcionario;

ALTER TABLE funcionario
ADD COLUMN plano TINYINT DEFAULT 0
AFTER tags;


ALTER TABLE usuarios
ADD COLUMN valor_carteira decimal(15,2) DEFAULT 0
AFTER descricao;

ALTER TABLE funcionario
ADD COLUMN vales_patrocinios INT DEFAULT 0
AFTER tags;
-- Wellington.ramos em 08/09/2020 #22;
create view Projetos_view as 
  select
    S.id,
    S.nome,
    S.descricao,
    S.valor,
    S.situacao,
    S.id_usuario,
    S.id_tipo_servico,
    T.nome as categoria,
    U.nome as nome_usuario,
    IU.imagem as imagem_usuario,
    S.nivel_projeto,
    S.nivel_profissional,
    S.data_cadastro,
  	count(P.id) as propostas,
    floor(cast(time_format(TIMEDIFF(current_timestamp, S.data_cadastro), '%H') as int) / 24) as postado
  from
      servico S
    inner join tipo_servico as T on T.id = S.id_tipo_servico and T.ativo = 0 
    left join proposta as P on P.idServico = S.id
    left join usuarios as U on U.id = S.id_usuario
    left join imagem_usuario as IU on IU.id_usuario = U.id
  GROUP BY 1
  order by S.data_cadastro desc
 ;
-- WELLINGTON.RAMOS em 11/09/2020 #22;
alter view Projetos_view as 
  select
    S.id,
    S.nome,
    S.descricao,
    S.valor,
    S.situacao,
    S.id_usuario,
    S.id_tipo_servico,
    T.nome as categoria,
    U.nome as nome_usuario,
    IU.imagem as imagem_usuario,
    S.nivel_projeto,
    S.nivel_profissional,
    S.data_cadastro,
  	count(P.id) as propostas,
  	case p.situacao
  	when 1 then p.valor
  	when 2 then p.valor
  	when 4 then p.valor
  	when 0 then null
  	end as valorproposta,
    floor(cast(time_format(TIMEDIFF(current_timestamp, S.data_cadastro), '%H') as int) / 24) as postado
  from
      servico S
    inner join tipo_servico as T on T.id = S.id_tipo_servico and T.ativo = 0 
    left join proposta as P on P.idServico = S.id and p.situacao <> 3
    left join usuarios as U on U.id = S.id_usuario
    left join imagem_usuario as IU on IU.id_usuario = U.id
  GROUP BY 1
  order by S.data_cadastro desc;



  -- MATEUS.ARRUDA em 17/09/2020 #29;
  CREATE VIEW Usuarios_view AS 
  SELECT
    us.id,
    us.nome,
    us.descricao,
    us.nivel_usuario,
    func.avaliacao_media,
    func.plano,
    func.profissao,
    func.tags,
    img_us.imagem
  FROM
    usuarios AS us
    LEFT JOIN funcionario AS func ON func.id_usuario = us.id 
    LEFT JOIN imagem_usuario AS img_us ON img_us.id_usuario = us.id
  WHERE us.nivel_usuario <> 2;

  -- WELLINGTON.RAMOS em 22/09/2020 #36;
  ALTER TABLE CHAT_MENSAGENS ADD COLUMN AUTOMATICA INT(1) DEFAULT 0;


  -- MATEUS.ARRUDA em 25/09/2020 #29;
  ALTER TABLE notificacoes 
  ADD parametros VARCHAR(80);
  
  -- WELLINGTON.RAMOS em 29/09/2020 #34;
  
  alter table servico add column avaliou int(1) default 0 ;

  alter view Projetos_view as 
  select
    S.id,
    S.nome,
    S.descricao,
    S.valor,
    S.situacao,
    S.id_usuario,
    S.id_tipo_servico,
    T.nome as categoria,
    U.nome as nome_usuario,
    IU.imagem as imagem_usuario,
    S.nivel_projeto,
    S.nivel_profissional,
    S.data_cadastro,
    S.avaliou,
  	count(P.id) as propostas,
  	case p.situacao
  	when 1 then p.valor
  	when 2 then p.valor
  	when 4 then p.valor
  	when 0 then null
  	end as valorproposta,
    floor(cast(time_format(TIMEDIFF(current_timestamp, S.data_cadastro), '%H') as int) / 24) as postado
  from
      servico S
    inner join tipo_servico as T on T.id = S.id_tipo_servico and T.ativo = 0 
    left join proposta as P on P.idServico = S.id and p.situacao <> 3
    left join usuarios as U on U.id = S.id_usuario
    left join imagem_usuario as IU on IU.id_usuario = U.id
  GROUP BY 1
  order by S.data_cadastro desc;