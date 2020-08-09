<?php
class Banco
{

    // function CriaConnexao()
    // {
    //     $config = file_get_contents('../../STARTUP.json', true);
    //     $config = json_decode($config, true);
    //     $banco = $config["database"];
    //     $username = $config["username"];
    //     $senha = $config["password"];
    //     $url = $config["databasehost"];
    //     $conn = new mysqli($url, $username, $senha);
    //     $db = mysqli_select_db($conn, $banco) or die();
    //     $conn->set_charset('utf8');
    //     return $conn;
    // }
    function CriaConnexaoPDO()
    {
        $config = file_get_contents('../../STARTUP.json', true);
        $config = json_decode($config, true);
        $banco = $config["database"];
        $username = $config["username"];
        $senha = $config["password"];
        $url = "mysql:host=" . $config["databasehost"] . ";dbname=" . $banco . ";charset=utf8mb4";
        $conn = new PDO($url, $username, $senha);
        return $conn;
    }
}

class Conexao
{
    private static $intance;
    public static function CriaConnexao()
    {
        if (self::$intance == null) {
            $bd = new Banco();
            self::$intance =  $bd->CriaConnexaoPDO();
        }
        return self::$intance;
    }
}


class ResultadoSql
{
    public $campos = array();
    public $resultados = array();
    public $erros = null;
    function __construct($campos, $resultados, $erros)
    {
        $this->campos = count($campos) > 0 ? $campos : [];
        $this->resultados = count($resultados) > 0 ? $resultados : [];
        $this->erros = $erros;
    }
}

function Sql($sql, $parans = [])
{
    $parans ?? [];
    try {

        $pd = Conexao::CriaConnexao();
        $sqlPrepare = $pd->prepare($sql);
        if (count($parans) > 0)
            foreach ($parans as $key => $value) {
                $sqlPrepare->bindValue(($key + 1), $value, PDO::PARAM_LOB);
            }
        if (!$sqlPrepare->execute())
            throw new Exception("Sql Error: " . $sqlPrepare->queryString);
        $resultado = $sqlPrepare->fetchAll(PDO::FETCH_ASSOC);
        $colunas = [];
        if (count($resultado) > 0) {

            array_push($colunas, array_keys($resultado[0]));
            $colunas = $colunas[0];
        }
        $saida = new ResultadoSql($colunas, $resultado, []);
    } catch (PDOException $ex) {
        $saida = new ResultadoSql([], [], $ex->getMessage());
    }

    return $saida;
}
function Insert($sql, $parans = [])
{
    $parans ?? [];
    try {

        $pd = Conexao::CriaConnexao();
        $sqlPrepare = $pd->prepare($sql);
        if (count($parans) > 0)
            foreach ($parans as $key => $value) {
                $sqlPrepare->bindValue(($key + 1), $value, PDO::PARAM_LOB);
            }
        if (!$sqlPrepare->execute())
            throw new Exception("Sql Error: " . $sqlPrepare->queryString);
    } catch (PDOException $ex) {
        throw new Exception($ex->getMessage());
    }
    return true;
}

function Update($sql, $parans = [])
{
    $parans ?? [];
    try {

        $pd = Conexao::CriaConnexao();
        $sqlPrepare = $pd->prepare($sql);
        if (count($parans) > 0)
            foreach ($parans as $key => $value) {
                $sqlPrepare->bindValue(($key + 1), $value, PDO::PARAM_LOB);
            }
        if (!$sqlPrepare->execute())
            throw new Exception("Sql Error: " . $sqlPrepare->queryString);
    } catch (PDOException $ex) {
        throw new Exception($ex->getMessage());
    }
    return true;
}
function Delete($sql, $parans = [])
{
    $parans ?? [];
    try {

        $pd = Conexao::CriaConnexao();
        $sqlPrepare = $pd->prepare($sql);
        if (count($parans) > 0)
            foreach ($parans as $key => $value) {
                $sqlPrepare->bindValue(($key + 1), $value);
            }
        if (!$sqlPrepare->execute())
            throw new Exception("Sql Error: " . $sqlPrepare->queryString);
    } catch (PDOException $ex) {
        throw new Exception($ex->getMessage());
    }
    return true;
}
function DeleteGenerico($tabela, $id)
{

    try {
        $sql = "delete from " . $tabela . " where id = ?";

        $pd = Conexao::CriaConnexao();
        $sqlPrepare = $pd->prepare($sql);
        $sqlPrepare->bindValue(1, $id);
        if (!$sqlPrepare->execute())
            throw new Exception("Sql Error: " . $sqlPrepare->queryString);
    } catch (PDOException $ex) {
        throw new Exception($ex->getMessage());
    }
    return true;
}
function GetNextID($tablename)
{
    $saida = Sql("select `auto_increment` FROM INFORMATION_SCHEMA.TABLES
    WHERE table_name = ? order by `auto_increment` desc limit 1", [$tablename]);
    return $saida->resultados[0]["auto_increment"];
}
//|---------------------------------------------------------------------------------------------------|
//|----Para a Funcao FUncionar corretamente a Entidade e a Tabela devem possuir os nomes de Atributos |
//|-----------------------------------------------Iguais----------------------------------------------|
function GetByIdGeneric($tabela, $classe, $id)
{
    $c = new ReflectionClass($classe);
    $classeArr = $c->getProperties();
    $busca  = Sql(" show columns from {$tabela}");
    $TabelaArr = $busca->resultados;
    $KeyClass = [];
    foreach ($classeArr as $key => $value) {
        array_push($KeyClass, strtolower($value->name));
    }
    $TabelaSqlKey = [];
    foreach ($TabelaArr as $key => $value) {
        array_push($TabelaSqlKey, strtolower($value["Field"]));
    }
    $colunasMatcheds = array_intersect($KeyClass, $TabelaSqlKey);
    $campoPrimario = "";
    foreach ($TabelaArr as $key => $value) {
        if ($value["Key"] == "PRI") {
            $item = array_search(strtolower($value["Field"]), $colunasMatcheds);
            if ($item !== false)
                $campoPrimario = $colunasMatcheds[$item];
        }
    }
    $sql = "select " . join(',', $colunasMatcheds) . " from " . $tabela . " where {$campoPrimario} = ?";
    $buscaTabela = Sql($sql, [$id]);
    // $a = 
    if (count($buscaTabela->resultados) != 0) {

        $classeNova = new $classe();
        foreach ($classeNova as $key => $value) {
            $classeNova->$key = $buscaTabela->resultados[0][$key];
        }
        return $classeNova;
    }
    return null;
}

function GetColunsgeneric($tabela, $classe, $Apelido = "", $Execao = [])
{
    $c = new ReflectionClass($classe);
    $classeArr = $c->getProperties();
    $busca  = Sql(" show columns from {$tabela}");
    $TabelaArr = $busca->resultados;
    $KeyClass = [];
    foreach ($classeArr as $key => $value) {
        array_push($KeyClass, strtolower($value->name));
    }
    $TabelaSqlKey = [];
    foreach ($TabelaArr as $key => $value) {
        array_push($TabelaSqlKey, strtolower($value["Field"]));
    }
    $colunasMatcheds = array_intersect($KeyClass, $TabelaSqlKey);
    if (count($Execao) != 0) {
        $colunasMatcheds = array_diff($colunasMatcheds, $Execao);
    }
    foreach ($colunasMatcheds as $key => $value) {
        $colunasMatcheds[$key] = $Apelido . '.' . $value;
    }
    return join(',', $colunasMatcheds);
}

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}
