<?php
require_once("../Classes/Projeto.php");
require_once("../DAO/ProjetoDAO.php");
require_once("../functions/ImageUtils.php");
require_once("../Enums/SecoesEnum.php");
require_once("../functions/Session.php");
require_once("../functions/Conexao.php");
require_once("../Classes/BOGeneric.php");
require_once("../BO/NotificacoesBO.php");
require_once("../Enums/TipoNotificacaoEnum.php");


class ProjetoBO extends BOGeneric
{
    private $ProjetoDAO;
    private $Projeto;
    function __construct()
    {
        $this->Projeto = new Projeto();
        $this->ProjetoDAO = new ProjetoDAO();
    }
    #region CRUD
    public function SalvarProjeto(Projeto $proj)
    {
        $Novo = false;
        $this->ValidaProjeto($proj);
        $proj->idusuario = $proj->idusuario == null ? BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO) : $proj->idusuario;
        if ($proj->id == 0) {
            $proj->id = GetNextID("servico");
            $Novo = true;
        }
        if (count($proj->imagens) > 0) {
            foreach ($proj->imagens as $key => $value) {
                $proj->imagens[$key]["img"] = ConvertBase64ToBlob($value["img"]);
            }
        }

        if ($this->ProjetoDAO->SalvarOuAtulizarProjeto($proj, $Novo)) {
            echo "OK|" . $proj->id;
        } else {
            throw new Exception("Algo Deu Errado.");
        }
    }
    private function ValidaProjeto(Projeto $p)
    {

        if ($p->Nome == null || $p->Nome == "")
            throw new Exception("Titulo Do Projeto é Nescessario.");
        if ($p->Descricao == null || $p->Descricao == "")
            throw new Exception("Descriçao Do Projeto é Nescessario.");
        if ($p->Categoria == null || $p->Categoria == "")
            throw new Exception("Categoria Do Projeto é Nescessario.");
        if ($p->NivelDoProfissional == null || $p->NivelDoProfissional == "")
            throw new Exception("Nivel Do Profissional Do Projeto é Nescessario.");
        if ($p->NivelDoProjeto == null || $p->NivelDoProjeto == "")
            throw new Exception("Nivel Do Projeto Do Projeto é Nescessario.");
        if ($p->Valor == null || $p->Valor == "")
            throw new Exception("Valor Do Projeto é Nescessario.");
        if (strlen(utf8_decode($p->Descricao)) < 50)
            throw new Exception("Numero minimo de caracteres para a Descrição Do Projeto é 50.");
    }
    public function GetTituloProjetoPorId($idProjeto)
    {
        return $this->ProjetoDAO->GetTituloProjetoPorId($idProjeto);
    }
    #endregion
    #region UTILS    
    private function AssociaProjetoCampo($proj)
    {
        foreach ($proj as $key => $value) {
            $this->Projeto->$key = $value;
        }
    }
    public function CriaProjetoCampo($proj)
    {
        $p = new Projeto();
        foreach ($proj as $key => $value) {
            $p->$key = $value;
        }
        return $p;
    }
    #endregion
    #region BuscaProjetos
    public function BuscarProjeto($C = [], $Q = "", $P = 1)
    {
        $dados = $this->ProjetoDAO->BuscaProjeto($C, $Q, $P);
        $dt = $dados[0];
        foreach ($dt as $key => $value) {
            $dt[$key]["img"] = ConvertBlobToBase64($value["img"]);
            if ($dt[$key]["postado"] > 24)
                $dt[$key]["postado"] = "Há " . floor($dt[$key]["postado"] / 24) . " dias";
            else if ($dt[$key]["postado"] > 1)
                $dt[$key]["postado"] = "Há " . $dt[$key]["postado"] . " horas";
            else
                $dt[$key]["postado"] = "Há  menos de uma hora";
        }
        $obj = new stdClass();
        $obj->lista = $dt;
        $obj->pagina = $dados[1];
        $obj->paginaAtual = $P;
        echo json_encode($obj);
    }
    public function BuscaDependenciasProjetoModal($id)
    {
        $imagens = $this->ProjetoDAO->BuscaDependenciasModal($id);
        foreach ($imagens as $key => $value) {
            $imagens[$key]["imagem"] = ConvertBlobToBase64($imagens[$key]["imagem"]);
        }
        echo json_encode($imagens);
    }

    public function BuscaNumeroProjetos()
    {
        echo json_encode($this->ProjetoDAO->BuscaNumeroProjetos());
    }
    public function GetProjetosPorUsuarioContexto()
    {
        $idUsuarioContexto = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        $resultados = $this->ProjetoDAO->GetProjetosPorUsuario($idUsuarioContexto);
        return $resultados;
    }
    public function SetProjetoSituacao($idProjeto, $Situacao)
    {
        return $this->ProjetoDAO->SetProjetoSituacao($idProjeto, $Situacao);
    }

    #endregion
    #region MeusProjetos
    public function BuscaMeusProjetos($q = null, $p = 1, $categoria = null, $situacao = null)
    {

        $dados = $this->ProjetoDAO->BuscaProjetosPorIdUsuario($this->GetUsuarioContexto(), $q, $p, $categoria, $situacao);
        $dt = $dados[0];
        foreach ($dt as $key => $value) {
            $dt[$key]["imagem_usuario"] = ConvertBlobToBase64($value["imagem_usuario"]);
            if ($dt[$key]["postado"] == 0)
                $dt[$key]["postado"] = "Hoje";
            else {
                if ($dt[$key]["postado"] > 1 && $dt[$key]["postado"] != 0)
                    $dt[$key]["postado"] = "Há " . $dt[$key]["postado"] . " dias";
                else
                    $dt[$key]["postado"] = "Há 1 dia";
            }
        }
        $obj = new stdClass();
        $obj->lista = $dt;
        $obj->pagina = $dados[1];
        $obj->paginaAtual = $p;
        return $obj;
    }
    public function Cancela($id)
    {
        return $this->ProjetoDAO->CancelaProjeto($id);
    }
    public function GetProjetoPorIDModal($id)
    {
        $saida =  $this->ProjetoDAO->GetProjetoByIdComView($id, $this->GetUsuarioContexto());
        if ($saida != null) {

            $saida["imagem_usuario"] = ConvertBlobToBase64($saida["imagem_usuario"]);
            if ($saida["postado"] == 0)
                $saida["postado"] = "Hoje";
            else {
                if ($saida["postado"] > 1 &&  $saida["postado"] != 0)
                    $saida["postado"] = "Há " . $saida["postado"] . " dias";
                else
                    $saida["postado"]["postado"] = "Há 1 dia";
            }
            return $saida;
        } else
            throw new Exception("Projeto não encontrado");
    }


    public function BuscaMeusProjetosAtribuirP($idDestinatario)
    {

        $dados = $this->ProjetoDAO->BuscaMeusProjetosAtribuirP($this->GetUsuarioContexto(), $idDestinatario);
        foreach ($dados as $key => $value) {
            if ($dados[$key]["postado"] == 0)
                $dados[$key]["postado"] = "Hoje";
            else {
                if ($dados[$key]["postado"] > 1 && $dados[$key]["postado"] != 0)
                    $dados[$key]["postado"] = "Há " . $dados[$key]["postado"] . " dias";
                else
                    $dados[$key]["postado"] = "Há 1 dia";
            }
        }
        $obj = new stdClass();
        $obj->lista = $dados;
        return $obj;
    }

    public function MandaMensagemFunc($informacoes)
    {
        
        $_NotificacaoBO = new NotificacoesBO();

        $_NotificacaoBO->NovaNotificacao(
            "O cliente <strong style='color: yellow;'>{$informacoes['nomeCliente']}</strong> requisitou seus serviços.",
            "Clique para acessar o projeto <strong style='color: yellow; opacity: 1;'>{$informacoes['projetoTitulo']}</strong>.",
            $informacoes["idFuncionario"],
            $this->GetUsuarioContexto(),
            TipoNotificacaoEnum::PROPOSTA_RECEBIDA,
            null,
            null,
            "page=buscaservicos;idProjeto=" . $informacoes["idProjeto"]

        );

        return true;
    }


    public function BuscaProjetoPorIdBuscaServico($id)
    {
        $saida = $this->ProjetoDAO->BuscaProjetoPorIdBuscaServico($id);
        $saida = $saida[0];
        if ($saida != null) {

            $saida["imagem_usuario"] = ConvertBlobToBase64($saida["imagem_usuario"]);
            if ($saida["postado"] == 0)
                $saida["postado"] = "Hoje";
            else {
                if ($saida["postado"] > 1 &&  $saida["postado"] != 0)
                    $saida["postado"] = "Há " . $saida["postado"] . " dias";
                else
                    $saida["postado"]["postado"] = "Há 1 dia";
            }
            return $saida;
        } else
            throw new Exception("Projeto não encontrado");
    }

    #endregion

}

try {

    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $ProjetoBO = new ProjetoBO();
        if ($metodo == "SalvarProjeto") {
            if (isset($_POST['Projeto'])) {
                $projeto = $_POST['Projeto'];
                $projeto = $ProjetoBO->CriaProjetoCampo($projeto);
                $ProjetoBO->SalvarProjeto($projeto);
            } else {
                throw new Exception("Parametro Projeto Ausente");
            }
        }
        if ($metodo == "BuscarProjetos") {
            $C = empty($_POST["C"]) ? [] : $_POST["C"];
            $Q = empty($_POST["Q"]) ? "" : $_POST["Q"];
            $P = empty($_POST["P"]) ? 1 : $_POST["P"];
            $ProjetoBO->BuscarProjeto($C, $Q, $P);
        }
        if ($metodo == "BuscaDependeciasModal") {
            if (isset($_POST['id'])) {
                $ProjetoBO->BuscaDependenciasProjetoModal($_POST['id']);
            } else
                throw new Exception("Parametro Projeto Ausente");
        }
        if ($metodo == "GETPROJETOSPORUSUARIOCONTEXTO") {
            $saida = $ProjetoBO->GetProjetosPorUsuarioContexto();
            echo json_encode($saida);
        }

        if ($metodo == "BuscaNumeroProjetos") {
            $ProjetoBO->BuscaNumeroProjetos();
        }
        if ($metodo == "BuscarMeusProjetos") {
            $C = empty($_POST["C"]) ? null : $_POST["C"];
            $S = empty($_POST["S"]) ? null : $_POST["S"];
            $Q = empty($_POST["Q"]) ? null : $_POST["Q"];
            $P = empty($_POST["P"]) ? 1 : $_POST["P"];
            echo json_encode($ProjetoBO->BuscaMeusProjetos($Q, $P, $C, $S));
        }
        if ($metodo == "CANCELA") {
            $ID = empty($_POST["ID"]) ? null : $_POST["ID"];
            if ($ID !== null)
                echo json_encode($ProjetoBO->Cancela($ID));
            else
                throw new Exception("O Metodo " . $metodo . " Precisa de Uma Propriedade [ID]");
        }
        if ($metodo == "BuscaProjetoPorIdModal") {
            $ID = empty($_POST["ID"]) ? null : $_POST["ID"];
            if ($ID !== null)
                echo json_encode($ProjetoBO->GetProjetoPorIDModal($ID));
            else
                throw new Exception("O Metodo " . $metodo . " Precisa de Uma Propriedade [ID]");
        }

        if ($metodo == "BuscaMeusProjetosAtribuirP") {
            $idDestinatario = empty($_POST["id_destinatario"]) ? null : $_POST["id_destinatario"];
            echo json_encode($ProjetoBO->BuscaMeusProjetosAtribuirP($idDestinatario));
        }

        if ($metodo == "MandaMensagemFunc") {
            if (isset($_POST['informacoes'])) {
                $informacoes = $_POST['informacoes'];
                
                echo json_encode($ProjetoBO->MandaMensagemFunc($informacoes));
            } else {
                throw new Exception("Informações necessárias.");
            }
        }

        if ($metodo == "BuscaProjetoPorIdBuscaServico") {
            if (isset($_POST['ID'])) {
                $id = $_POST['ID'];
                echo json_encode($ProjetoBO->BuscaProjetoPorIdBuscaServico($id));
            } else {
                throw new Exception("Informações necessárias.");
            }
        }

        if ($metodo == "GetTituloProjetoPorId") {
            if (isset($_POST['idProjeto'])) {
                $id = $_POST['idProjeto'];
                echo json_encode($ProjetoBO->GetTituloProjetoPorId($id));
            } else {
                throw new Exception("Id necessário");
            }
        }
    }
} catch (Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}
