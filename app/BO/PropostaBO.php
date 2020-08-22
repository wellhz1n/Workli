<?php
@require_once("../DAO/PropostaDAO.php");
@require_once("../Enums/UpgradesEnum.php");
@require_once("../functions/Conexao.php");
@require_once("../functions/Session.php");
@require_once("../Enums/SecoesEnum.php");
@require_once("../functions/ImageUtils.php");


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
            $resultado = json_encode($resultado,JSON_UNESCAPED_UNICODE);
            echo $resultado;
        }
    }
} catch (\Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}
class PropostaBO
{
    private $PropostaDAO;
    private $Proposta;

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
}
