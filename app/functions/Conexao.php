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
        $this->campos = $campos;
        $this->resultados = $resultados;
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
        if (count($resultado) > 0){

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
// function GetCampos($resultado)
// {
//     $campos = mysqli_fetch_fields($resultado);
//     $saida = array();
//     foreach ($campos as $key => $value) {
//         array_push($saida, $value->name);
//     }
//     return $saida;
// }
// function GetRows($resultado)
// {
//     $saida = array();
//     while ($arr = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
//         array_push($saida, $arr);
//     }
//     return $saida;
// }

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
