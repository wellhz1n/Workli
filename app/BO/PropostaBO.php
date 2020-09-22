<?php
#region Requires
require_once("../DAO/PropostaDAO.php");
require_once("../Enums/UpgradesEnum.php");
require_once("../functions/Conexao.php");
require_once("../functions/Session.php");
require_once("../Enums/SecoesEnum.php");
require_once("../Enums/TipoNotificacaoEnum.php");
require_once("../functions/ImageUtils.php");
require_once("../BO/NotificacoesBO.php");
require_once("../Classes/BOGeneric.php");
require_once("../DAO/ProjetoDAO.php");
require_once("../BO/CarteiraBO.php");
require_once("../Enums/PlanosEnum.php");


#endregion


class PropostaBO extends BOGeneric
{
    private $PropostaDAO;
    private Proposta $Proposta;

    function __construct()
    {
        $this->PropostaDAO = new PropostaDAO();
        $this->Proposta = new Proposta();
    }
    public function SalvarProposta(Proposta $proposta)
    {
        if ($proposta->Id == -1) {
            $proposta->Id = GetNextID('proposta');
        }
        $this->PropostaDAO->Salvar($proposta);
        return true;
    }

    #region Aba Propostas
    public function BuscaPropostasPendentesClientes($idProjeto = null)
    {
        $UsuarioContexto = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        $resultado = $this->PropostaDAO->GetPropostaParaNotificacaoPendente($UsuarioContexto, $idProjeto);
        $listaP = [];
        $listaN = [];
        foreach ($resultado as $key => $value) {
            $resultado[$key]['imagem'] = ConvertBlobToBase64($value['imagem']);
            if ($resultado[$key]["patrocinado"] == 1)
                array_push($listaP, $resultado[$key]);
            else
                array_push($listaN, $resultado[$key]);
        }
        $listaP = array_reverse($listaP);
        $listaN = array_reverse($listaN);

        $saida = new stdClass;
        $saida->listaP = $listaP;
        $saida->listaN = $listaN;
        return $saida;
    }
    public function RecusarProposta($idProposta)
    {

        $Recusou = $this->PropostaDAO->RecusarProposta($idProposta);
        if ($Recusou) {

            $this->Proposta = GetByIdGeneric('proposta', Proposta::class, $idProposta);
            $_ProjetoDAO =  new ProjetoDAO();
            $Titulo = $_ProjetoDAO->GetTituloProjetoPorId($this->Proposta->IdServico);
            $_NotificacaoBO = new NotificacoesBO();
            $idUsuarioFuncionario = GetIdUsuarioComIdFuncionario($this->Proposta->IdFuncionario);
            $_NotificacaoBO->NovaNotificacao(
                "Proposta Recusada",
                "Sua Proposta do Projeto: <strong style='color: yellow;'>{$Titulo["nome"]}</strong> foi Recusada.",
                $idUsuarioFuncionario["id"],
                $this->GetUsuarioContexto(),
                TipoNotificacaoEnum::ERROR
            );
            return true;
        } else
            return false;
    }
    public function AprovarProposta($idProposta)
    {

        $idsParaRecusar = $this->PropostaDAO->GetPropostasParaoMesmoProjetoExecetoId($idProposta);
        foreach ($idsParaRecusar as $key => $value) {
            $this->RecusarProposta($value["id"]);
        }
        @$this->Proposta = GetByIdGeneric('proposta', Proposta::class, $idProposta);
        $CarteiraBo = new CarteiraBO();
        $Valor = $CarteiraBo->DeduzirCarteira($this->Proposta->Valor);
        if (explode("|", $Valor)[0] == "ERROR") {
            return $Valor;
        }
        $Prop = $this->PropostaDAO->AprovarProposta($idProposta);
        if ($Prop) {
            $_ProjetoDAO =  new ProjetoDAO();
            $Titulo = $_ProjetoDAO->GetTituloProjetoPorId($this->Proposta->IdServico);
            $_NotificacaoBO = new NotificacoesBO();
            $idUsuarioFuncionario = GetIdUsuarioComIdFuncionario($this->Proposta->IdFuncionario);
            $_NotificacaoBO->NovaNotificacao(
                "Proposta Aceita",
                "Sua Proposta do Projeto: <strong style='color: yellow;'>{$Titulo["nome"]}</strong> foi Aceita.",
                $idUsuarioFuncionario["id"],
                $this->GetUsuarioContexto(),
                TipoNotificacaoEnum::SUCCESS
            );
            $_ProjetoDAO->SetProjetoSituacao($this->Proposta->IdServico, 1);
            return true;
        } else
            return false;
    }
    function MudaSituacaoPropostaFuncionario($idproposta, $situacao, $titulo, $idcliente, $idservico)
    {
        $sucesso = true;
        @$this->Proposta = GetByIdGeneric('proposta', Proposta::class, $idproposta);
        switch ($situacao) {
            case 1:
            case "1":
                $sucesso = $this->PropostaDAO->SetSituacaoPropostaPorId($idproposta, 2);
                $_NotificacaoBO = new NotificacoesBO();
                $_NotificacaoBO->NovaNotificacao(
                    "Funcionário Iniciou o Projeto",
                    "Sua Proposta do Projeto: <strong style='color: yellow;'>{$titulo}</strong> foi Iniciada.",
                    $idcliente,
                    $this->GetUsuarioContexto(),
                    TipoNotificacaoEnum::
                    DEFAULT
                );
                $_ProjetoDAO =  new ProjetoDAO();
                $_ProjetoDAO->SetProjetoSituacao($idservico, 2);

                break;
            case 2:
            case "2":
                //TODO CALCULAR AS TAXAS
                $v = json_decode($this->Proposta->Valor);

                $Plano = json_decode(BuscaSecaoValor(SecoesEnum::PLANO));

                switch ($Plano) {
                    case PlanosEnum::PADRAO:
                        $v  = $v - (($v * PorcentagemPlanosEnum::PADRAO) / 100);
                        break;
                    case PlanosEnum::PLUS:
                        $v  = $v - (($v * PorcentagemPlanosEnum::PLUS) / 100);
                        break;
                    case PlanosEnum::PRIME:
                        $v  = $v - (($v * PorcentagemPlanosEnum::PRIME) / 100);
                        break;
                    case PlanosEnum::MASTER:
                        $v  = $v - (($v * PorcentagemPlanosEnum::MASTER) / 100);
                        break;
                }

                $CarteiraBo = new CarteiraBO();
                $Valor = $CarteiraBo->DepositarCarteira($v);
                $sucesso = $this->PropostaDAO->SetSituacaoPropostaPorId($idproposta, 4);
                $_NotificacaoBO = new NotificacoesBO();
                $_NotificacaoBO->NovaNotificacao(
                    "Funcionário Finalizou o Projeto",
                    "Seu Projeto: <strong style='color: yellow;'>{$titulo}</strong> foi Finalizado.",
                    $idcliente,
                    $this->GetUsuarioContexto(),
                    TipoNotificacaoEnum::SUCCESS
                );
                $_ProjetoDAO =  new ProjetoDAO();
                $_ProjetoDAO->SetProjetoSituacao($idservico, 4);
                break;
        }
        return $sucesso;
    }

    function BuscaPropostasFuncionarioTab($filtros = [], $pagina = 1)
    {
        $pagina = json_decode($pagina);
        $resultado =  $this->PropostaDAO->BuscaPropostaFuncionarioTab($this->GetIdFuncionarioCOntexto(), $filtros, $pagina);
        $Plano = json_decode(BuscaSecaoValor(SecoesEnum::PLANO));
        foreach ($resultado[1] as $key => $value) {
            $resultado[1][$key]["IMAGEM"] = ConvertBlobToBase64($value["IMAGEM"]);
            switch ($Plano) {
                case PlanosEnum::PADRAO:
                    $resultado[1][$key]["VALOR"]  = $resultado[1][$key]["VALOR"]  - ($resultado[1][$key]["VALOR"]  * PorcentagemPlanosEnum::PADRAO) / 100;
                    break;
                case PlanosEnum::PLUS:
                    $resultado[1][$key]["VALOR"]   = $resultado[1][$key]["VALOR"]  - ($resultado[1][$key]["VALOR"]  * PorcentagemPlanosEnum::PLUS) / 100;
                    break;
                case PlanosEnum::PRIME:
                    $resultado[1][$key]["VALOR"]   = $resultado[1][$key]["VALOR"]  - ($resultado[1][$key]["VALOR"]  * PorcentagemPlanosEnum::PRIME) / 100;
                    break;
                case PlanosEnum::MASTER:
                    $resultado[1][$key]["VALOR"]  = $resultado[1][$key]["VALOR"]  - ($resultado[1][$key]["VALOR"] * PorcentagemPlanosEnum::MASTER) / 100;
                    break;
            }
        }
        $obj = new stdClass();
        $obj->paginas = $resultado[0];
        $obj->lista = $resultado[1];
        $obj->possuiAprovada = $resultado[2];

        return $obj;
    }
    #endregion


    #region UTILS    
    public function CriaPropostaCampo($proposta)
    {
        $p = new Proposta();
        foreach ($proposta as $key => $value) {
            if (isset($p->$key))
                if ($key == "Upgrades") {
                    if (json_decode($value["upgrade1"]) == true) {
                        if (json_decode($value["upgrade2"]) == true) {
                            $p->Upgrades = UpgradeEnum::EVERYTHING;
                        } else {
                            $p->Upgrades = UpgradeEnum::UPGRADE1;
                        }
                    } else {
                        if (json_decode($value["upgrade2"]) == true) {
                            $p->Upgrades = UpgradeEnum::UPGRADE2;
                        } else {
                            $p->Upgrades = UpgradeEnum::NDA;
                        }
                    }
                    continue;
                }
            $p->$key = $key == "Descricao" ? $value : json_decode($value);
        }
        return $p;
    }
    #endregion

    #region retorna o valor de proposta média
    public function RetornaValorPropostaMedia($idServico)
    {
        try {
            $resultado = $this->PropostaDAO->RetornaValorPropostaMedia($idServico);
            // echo BuscaSecaoValor(SecoesEnum::PLANO);
            echo json_encode($resultado);
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }

        // return $resultado[0]["soma"];
    }
    #endregion
}



try {
    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $PropostaBO = new PropostaBO();
        if ($metodo == "SalvarProposta") {
            if (isset($_POST['proposta'])) {
                $proposta = $_POST['proposta'];
                $proposta = $PropostaBO->CriaPropostaCampo($proposta);
                echo json_encode($PropostaBO->SalvarProposta($proposta));
            } else {
                throw new Exception("O parâmetro Proposta está em falta.");
            }
        }

        if ($metodo == "BuscaPropostasPendentesClientes") {
            $projeto = isset($_POST['IDPROJETO']) ? $_POST['IDPROJETO'] : null;
            $resultado = $PropostaBO->BuscaPropostasPendentesClientes($projeto);
            $resultado = json_encode($resultado, JSON_UNESCAPED_UNICODE);
            echo $resultado;
        }
        if ($metodo == "RECUSARPROPOSTA") {
            if (isset($_POST['IDPROPOSTA'])) {
                $proposta = $_POST['IDPROPOSTA'];
                $proposta = $PropostaBO->RecusarProposta($proposta);
                echo json_encode($proposta);
            } else {
                throw new Exception("O parâmetro IDPROPOSTA está em falta.");
            }
        }
        if ($metodo == "APROVARPROPOSTA") {
            if (isset($_POST['IDPROPOSTA'])) {
                $proposta = $_POST['IDPROPOSTA'];
                $proposta = $PropostaBO->AprovarProposta($proposta);
                echo json_encode($proposta);
            } else {
                throw new Exception("O parâmetro IDPROPOSTA está em falta.");
            }
        }
        if ($metodo == "BUSCAPROPOSTASFUNCIONARIOTAB") {
            echo json_encode($PropostaBO->BuscaPropostasFuncionarioTab(isset($_POST["FILTROS"]) ? $_POST["FILTROS"] : [], $_POST["PAGINA"]));
        }

        if ($metodo == "RetornaValorPropostaMedia") {
            $resultado = $PropostaBO->RetornaValorPropostaMedia($_POST["ID_SERVICO"]);
        }
        if ($metodo == "MudaSituacaoPropostaFuncionario") {
            if (isset($_POST['IDPROPOSTA']) && isset($_POST['SITUACAO']) && isset($_POST['TITULO']) && isset($_POST['IDCLIENTE']) && isset($_POST['IDSERVICO'])) {
                $idproposta = $_POST['IDPROPOSTA'];
                $proposta = $PropostaBO->MudaSituacaoPropostaFuncionario($idproposta, $_POST['SITUACAO'], $_POST['TITULO'], $_POST['IDCLIENTE'], $_POST['IDSERVICO']);
                echo json_encode($proposta);
            } else {
                throw new Exception("parâmetros [IDPROPOSTA,SITUACAO,TITULO,IDCLIENTE,IDSERVICO] estão em falta.");
            }
        }
    }
} catch (\Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}
