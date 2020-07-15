<?php
require("../../bootstrap.php");

class TipoServicoDAO{
    public function GetTipoServicos()
    {
        $resultado = Sql("select id,nome,descricao,Ativo,imagem FROM tipo_servico");
        foreach ($resultado->resultados as $key => $value) {
            $resultado->resultados[$key]['descricao'] = stripslashes($resultado->resultados[$key]['descricao']);
        }
        return $resultado;
    }
    public function GetTipoServicoCategoria()
    {
        $resultado = Sql("select id,nome,descricao FROM tipo_servico where ATIVO = 1 order by nome asc");
        foreach ($resultado->resultados as $key => $value) {
            $resultado->resultados[$key]['descricao'] = stripslashes($resultado->resultados[$key]['descricao']);
        }
        return $resultado;
    }
    public function GetTipoServicosCompletosAtivos($ord)
    {
        $ord = $ord == 'true'? 'ASC':'DESC';
        $resultado = Sql("select ts.id , ts.nome , ts.descricao , ts.imagem FROM tipo_servico as ts
                        where ts.Ativo = ? Order by nome {$ord} ",[1]);
        foreach ($resultado->resultados as $key => $value) {
            $resultado->resultados[$key]['descricao'] = stripslashes($resultado->resultados[$key]['descricao']);
            $resultado->resultados[$key]['servicos'] = $this->GetServicosNumeroPorTipo_Servico( $resultado->resultados[$key]['id']);
        }
        return $resultado;
    }
    private function GetServicosNumeroPorTipo_Servico($id){
        $sql = Sql("select count(id) as servicos from servico where id_tipo_servico = ? and situacao = 0",[$id]);
        return $sql->resultados[0]["servicos"];
    }
    public function GetImagemTipoServico($id)
    {
        $resultado = Sql("select imagem FROM tipo_servico where id = ?",[$id]);
        return $resultado;
    }
    public function InsertTipoServico($TipoServico){
                $TipoServico['descricao'] = addslashes($TipoServico['descricao']);      
        $resultado = Insert("insert into tipo_servico(nome,descricao,Ativo,imagem) 
        values(?,?,?,?)",[$TipoServico['nome'],$TipoServico['descricao'],$TipoServico['Ativo'],$TipoServico['imagem']]);
        return $resultado; 
    } 
    public function UpdateTipoServico($TipoServico){
        $TipoServico['descricao'] = addslashes($TipoServico['descricao']); 
        $resultado = Update("update  tipo_servico set nome = ?,
                                                descricao = ?,
                                                Ativo = ?,
                                                imagem = ?
                                                where id = ?",
                                                [$TipoServico['nome'],$TipoServico['descricao'],$TipoServico['Ativo'],$TipoServico['imagem'],$TipoServico['id']]);
        return $resultado; 
    }
    public function RemoverTipoServico($id){
        $resultado = DeleteGenerico("tipo_servico",$id);
        return $resultado;
    }
    public function GetMaisUtilizados(){
            $resultado = Sql("select ts.id,ts.nome,s.servicos,ts.imagem from tipo_servico ts 
            inner join(
                select count(id) as servicos, id_tipo_servico from servico 
                where servico .Ativo  = 1
                group by 
                id_tipo_servico 
            )as s on s.id_tipo_servico = ts.id
            where ts.ativo  =1 
            order  by s.servicos desc, ts.nome  asc
            limit 3
        ");
        return $resultado->resultados;
    }
}