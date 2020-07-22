<?php
@require_once("../DAO/PropostaDAO.php");
@require_once("../Enums/UpgradesEnum.php");
@require_once("../functions/Conexao.php");
try {
    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $PropostaBO = new PropostaBO();
        if (isset($_POST['proposta'])) {
            if ($metodo == "SalvarProposta") {
                $proposta = $_POST['proposta'];
                $proposta = $PropostaBO->CriaPropostaCampo($proposta);
                echo json_encode($PropostaBO->SalvarProposta($proposta));
            }
        } else {
            throw new Exception("O parâmetro Proposta está em falta.");
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
            $p->$key = $key == "Descricao"?$value:json_decode($value);
        }
        return $p;
    }
    #endregion
}
