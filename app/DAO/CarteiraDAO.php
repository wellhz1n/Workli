<?php
 class CarteiraDAO{
    
    #region Carteira

    public function GetValorUsuarioCarteira($idUsuario){
       
        $valor = Sql("SELECT VALOR_CARTEIRA FROM USUARIOS WHERE ID = ?",[$idUsuario])->resultados[0];
        return json_decode($valor["VALOR_CARTEIRA"]);
    }

    #endregion

}