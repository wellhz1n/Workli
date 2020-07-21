<?php
@require_once("../DAO/PropostaDAO.php");

try {
    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $PropostaBO = new PropostaBO();
        if (isset($_POST['proposta'])) {
            if($metodo == "SALVAR") {
                $proposta = $_POST['proposta'];
                $proposta = $PropostaBO->CriaPropostaCampo($proposta);
                $PropostaBO->SalvarProposta($proposta);
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
class PropostaBO {
    private $PropostaDAO;
    private $Proposta;

    function __construct()
    {
        $this->PropostaDAO = new PropostaDAO();
        $this->Proposta = new Proposta();
    }
    public function SalvarProposta(Proposta $proposta)
    {
        if($proposta->Id == -1){
               $proposta->Id = GetNextID('proposta');
        }
        $this->PropostaDAO->Salvar($proposta);
    }



    #region UTILS    
    public function CriaPropostaCampo($proposta)
    {
        $p = new Proposta();
        foreach ($proposta as $key => $value) {
            if(isset($p->$key))
                 $p->$key = $value;
        }
        return $p;
    }
    #endregion
}


