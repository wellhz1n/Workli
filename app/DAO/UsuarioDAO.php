<?php
@require_once("../../bootstrap.php");
class UsuarioDAO
{


    public function GetUsuarios()
    {
        $resultado = Sql("select id,nome,email,cpf,nivel_usuario FROM usuarios");
        return $resultado;
    }
    public function VerificaEmail($email)
    {
        $resultado = Sql("select id FROM usuarios where email = ? limit 1", [$email]);
        if (count($resultado->resultados) == 1)
            return true;
        return false;
    }
    public function GetUsuarioIdPorEmail($email)
    {
        $resultado = Sql("select id FROM usuarios where email = ? limit 1", [$email]);
        return $resultado->resultados[0]['id'];
    }
    public function CadastraUsuario($usuario)
    {
        $cpfExistente = Sql("SELECT cpf FROM usuarios WHERE cpf = ?", [$usuario['cpf']]);
        $emailExistente = Sql("SELECT email FROM usuarios WHERE email = ?", [$usuario['email']]);
        if ($cpfExistente->resultados) {
            $resultado = "Este cpf já está cadastrado.";
        } else if ($emailExistente->resultados) {
            $resultado = "Este email já está cadastrado.";
        } else {
            $resultado = Insert(
                "INSERT INTO usuarios (nome, email, cpf, senha, nivel_usuario)
                VALUES (?,?,?,?,?)",
                [
                    $usuario['nome'], $usuario['email'], $usuario['cpf'], $usuario['senha'],
                    $usuario['nivel']
                ]
            );
            if ($usuario["nivel"] == "1") {
                $id = Sql("SELECT id FROM usuarios WHERE cpf = ?", [$usuario['cpf']]);
                if (count($id->resultados) > 0) {
                    $id = $id->resultados[0]['id'];
                    $resultado = Insert(
                        "INSERT INTO funcionario (id_usuario, avaliacao_media) VALUES(?, ?)",
                        [$id, $usuario['avaliacaoMedia']]
                    );
                }
            }
        }
        return $resultado;
    }
    public function UpdateUsuario($usuario)
    {
        $resultado = Update(
            "update usuarios set
            nome = ?,
            email = ?,
            nivel_usuario = ?
            where id = ? ",
            [$usuario["nome"], $usuario["email"], $usuario["nivel"], $usuario["id"]]
        );
        return $resultado;
    }
    public function DeleteUser($id)
    {
        $temImagem = Sql("select id from imagem_usuario where id_usuario = ?", [$id]);
        $temImagem = count($temImagem->resultados) > 0 ? $temImagem->resultados[0]["id"] : false;
        if ($temImagem != false)
            DeleteGenerico("imagem_usuario", $temImagem);

        $resultado = DeleteGenerico("usuarios", $id);
        return $resultado;
    }
    public function EditaUsuario($usuarioDados)
    {
        $resultado = "";

        /* Checa para ver se é um funcionario */
        $funcionario = Sql("SELECT nivel_usuario FROM usuarios WHERE id = ?", [$usuarioDados["ID"]]);

        /*Checa para ver se existe a row na tabela de funcionarios*/
        $existe = Sql("SELECT EXISTS(SELECT * FROM funcionario WHERE id_usuario = ?)", [$usuarioDados["ID"]]);

        if ($funcionario->resultados[0]["nivel_usuario"] == 1) {
            if ($existe->resultados) { /*Se a row ja existe na tabela de func*/
                $resultado = Update(
                    "UPDATE usuarios AS US
                    INNER JOIN funcionario AS FUNC
                    ON US.id = FUNC.id_usuario 
                    SET
                    US.nome = ?,
                    US.descricao = ?,
                    FUNC.profissao = ?,
                    FUNC.tags = ?
                    where US.id = ?",
                    [$usuarioDados["nome"], $usuarioDados["descricao"], $usuarioDados["profissao"], $usuarioDados["tags"], $usuarioDados["ID"]]
                );
            } else { /*Se a row não existir na tabela de func (da update em usuarios e insert em funcionario)*/
                $resultado = Insert(
                    "BEGIN;
                UPDATE usuarios AS US set US.nome = ?, US.descricao = ? WHERE US.id = ?;
                INSERT INTO funcionario(profissao, tags, id_usuario) 
                VALUES(?, ?, ?);
                COMMIT;
                ",
                    [$usuarioDados["nome"], $usuarioDados["descricao"], $usuarioDados["ID"], $usuarioDados["profissao"], $usuarioDados["tags"], $usuarioDados["ID"]]
                );
            }
        } else { /*Se o usuário for um cliente*/
            $resultado = Update(
                "UPDATE usuarios AS US SET US.nome = ?, US.descricao = ? WHERE US.id = ?
                ",
                [$usuarioDados["nome"], $usuarioDados["descricao"], $usuarioDados["ID"]]
            );
        }

        return $resultado;
        // return $funcionario->resultados[0];
    }
    public function SalvarOuAtualizarImagem($img, $idUsuario)
    {
        $Busca = Sql("select id from imagem_usuario where id_usuario =?", [$idUsuario]);
        if (count($Busca->resultados) == 1)
            $resultado = Insert("update  imagem_usuario set imagem = ? where id_usuario = ? ", [$img, $idUsuario]);
        else
            $resultado = Insert("insert into imagem_usuario(imagem, id_usuario)
        values(?, ?)", [$img, $idUsuario]);

        return $resultado;
    }

    public function SalvarOuAtualizarImagemBanner($img, $idUsuario)
    {
        $Busca = Sql("SELECT id FROM imagem_usuario WHERE id_usuario =?", [$idUsuario]);
        if (count($Busca->resultados) == 1)
            $resultado = Insert("UPDATE imagem_usuario SET imagem_banner = ? WHERE id_usuario = ? ", [$img, $idUsuario]);
        else
            $resultado = Insert("INSERT INTO imagem_usuario(imagem_banner, id_usuario)
        VALUES(?, ?)", [$img, $idUsuario]);

        return $resultado;
    }


    public function GetUsuarioCompletobyId($id)
    {
        $retorno  = Sql("select u.id,u.nome,u.email,u.cpf,u.nivel_usuario,im.imagem from usuarios as u
                        left join imagem_usuario as im on im.id_usuario = u.id  
                        where u.id = ? ", [$id]);
        return $retorno;
    }

    public function GetFuncionarioCompletobyId($id)
    {
        $retorno = Sql("SELECT u.id, u.nome, u.email, u.cpf, u.nivel_usuario, im.imagem, func.avaliacao_media, func.plano FROM usuarios AS u
                        LEFT JOIN imagem_usuario AS im ON im.id_usuario = u.id  
                        LEFT JOIN funcionario AS func ON func.id_usuario = u.id
                        where u.id = ? ", [$id]);
        return $retorno;
    }

    public function GetFuncionarioDataEdit($id)
    {
        $retorno = Sql("SELECT u.id, u.nome, u.descricao, func.profissao, func.tags,func.avaliacao_media FROM usuarios AS u
                        LEFT JOIN funcionario AS func ON func.id_usuario = u.id
                        where u.id = ? ", [$id]);
        return $retorno;
    }

    public function GetImagemBannerbyId($id)
    {
        $retorno = Sql("SELECT img.imagem_banner FROM usuarios as u
                        LEFT JOIN imagem_usuario AS img ON img.id_usuario = u.id
                        WHERE u.id = ? ", [$id]);
        return $retorno;
    }
    public function GetImagemUserById($id)
    {
        $retorno = Sql("SELECT img.imagem FROM usuarios as u
                        LEFT JOIN imagem_usuario AS img ON img.id_usuario = u.id
                        WHERE u.id = ? ", [$id]);
        return $retorno;
    }
    public function GetNivelUsuarioById($id)
    {
        $retorno = Sql("SELECT us.nivel_usuario FROM usuarios as us
                        WHERE us.id = ? ", [$id]);
        return $retorno;
    }
    public function GetPlanoById($id)
    {
        $retorno = Sql("SELECT func.plano FROM funcionario as func
                        WHERE func.id_usuario = ? ", [$id]);
        return $retorno;
    }
    public function BuscaNumeroUsuarios()
    {
        $retorno = Sql("SELECT COUNT(id) FROM usuarios");
        return $retorno->resultados[0];
    }

    public function SetDadoUsuario($id, $coluna, $dado, $tabela)
    {

        if ($tabela == "funcionario") {
            $coluna = "FUNC." . $coluna;
            $resultado = Update(
                "UPDATE funcionario AS FUNC
                INNER JOIN usuarios AS US
                ON FUNC.id_usuario = US.id
                SET
                $coluna = ?
                WHERE FUNC.id_usuario = ?",
                [
                    $dado,
                    $id
                ]
            );
        } else if ($tabela == "usuarios") {
            $coluna = "US." . $coluna;
            $resultado = Update(
                "UPDATE usuarios AS US
                SET
                $coluna = ?
                WHERE US.id = ?",
                [
                    $dado,
                    $id
                ]
            );
        }
    }


    public function BuscaUsuarios($pg = 1, $filtro)
    {

        $groupby = "ORDER BY nome";

        $exc = "WHERE id != " . BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO) . " AND nivel_usuario != 2";

        $tagsFiltroArray = explode(",", $filtro["tags"]);
        if ($filtro["tags"]) {
            $exc .= " AND ";
            foreach ($tagsFiltroArray as $key => $value) {
                if ($key != 0) {
                    $exc .= " OR ";
                }
                $exc .= "tags LIKE '%{$value}%'";
            }
        }

        $exc .= $filtro["avaliacao"] != 0 ? " AND avaliacao_media >= " . ($filtro["avaliacao"]) : "";

        if ($filtro["tipo_usuario"] == 1) {
            $exc .= " AND nivel_usuario = 0";
        } else if ($filtro["tipo_usuario"] == 2) {
            $exc .= " AND nivel_usuario = 1";
            $groupby = "ORDER BY avaliacao_media desc";


            $profissao = $filtro["profissao"];
            if ($profissao) {
                $exc .= " AND profissao LIKE '%$profissao%'";
            }
        }

        $queryBusca = $filtro["queryBusca"];
        $exc .= " AND nome LIKE '%$queryBusca%'";



        $paginas = Sql("
            SELECT CEIL(COUNT(id)/5) AS paginas FROM Usuarios_view
            {$exc}
            
        ");
        if (count($paginas->resultados) > 0) {
            if (json_decode($paginas->resultados[0]['paginas']) == 1)
                $pg = 1;
        }

        $pg = (json_decode($pg) - 1) * 5;

        $retorno = Sql("
            SELECT  id,
                    nome,
                    descricao,
                    nivel_usuario,
                    avaliacao_media,
                    plano,
                    profissao,
                    tags,
                    imagem,
                    COUNT(id) AS usuariosC
                    FROM ( 
                        SELECT * FROM Usuarios_view {$exc} {$groupby} LIMIT 5 OFFSET {$pg}
                    ) AS us
                    GROUP BY 1 ${groupby};
        ");
        foreach ($retorno->resultados as $key => $value) {
            $retorno->resultados[$key]["descricao"] = nl2br($retorno->resultados[$key]["descricao"]);
        }
        return [$retorno->resultados, $paginas->resultados[0]["paginas"]];
    }


    public function BuscaProfissoes($query)
    {

        $like = " AND profissao LIKE '%$query%'";
        $retorno = Sql("
            SELECT profissao FROM funcionario WHERE profissao != '' {$like} GROUP BY profissao;
        ");
        return [$retorno->resultados];
    }

    //GET USUARIO(FUNCIONARIO) POR ID PROJETO, ELE VAI OLHAR PARA A PROPOSTA A FIM DE BUSCAR O IDFUNCIONARIO
    public function GetFuncionarioPorIdProjeto($idProjeto)
    {
        $sql = "SELECT F.id,U.nome,UM.imagem FROM SERVICO S
        inner join PROPOSTA P on P.idServico = S.id
        inner join funcionario F on F.id = P.idFuncionario
        inner join usuarios U on U.id = F.id_usuario
        left join imagem_usuario UM on UM.id_usuario = U.id
        WHERE S.id = ? AND P.SITUACAO = 4 ";
        $result = Sql($sql, [$idProjeto]);
        return count($result->resultados) > 0 ? $result->resultados[0] : null;
    }

    public function GetDadosDeCima($id, $tipo)
    {
        $sql = "";
        if ($tipo == "1") {

            $idFuncionario = Sql("SELECT id FROM funcionario WHERE id_usuario = ?", [$id])->resultados[0]["id"];

            $sql = "SELECT  COUNT(id) AS p_enviadas,
                            (SELECT COUNT(id) FROM proposta WHERE (situacao = 1 OR situacao = 2 OR situacao = 4) and  idFuncionario = $idFuncionario) AS p_aceitas,
                            (SELECT COUNT(id) FROM proposta WHERE  situacao = 4 and idFuncionario = $idFuncionario ) AS p_concluidas
                    FROM proposta
                    WHERE idFuncionario = (SELECT id FROM funcionario WHERE id_usuario = ?)
                ";
        } else if ($tipo == "0") {
            $sql = "SELECT  count(id) AS p_publicados,
                            (SELECT COUNT(id) FROM servico WHERE situacao = 3 and  id_usuario = $id) AS p_cancelados,
                            (SELECT COUNT(id) FROM servico WHERE situacao = 4 and  id_usuario = $id) AS p_concluidos
                    FROM servico
                    WHERE id_usuario = ?";
        }

        $result = Sql($sql, [$id]);
        return count($result->resultados) > 0 ? $result->resultados[0] : null;
    }
}
// $USR->example();
