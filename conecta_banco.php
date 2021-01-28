<?php
function conexao($banco = 'registro'){
    $servidor = '127.0.0.1:3306';
    $usuario = 'root';
    $senha = '12345678';

    try {
        $conexao = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha);
        return $conexao;
    }catch(PDOException $e){
        die('erro: '. $e->getMessage());
    }
}

function cadastrarGastos($usuario, $valor ,$data, $categoria){
    $conexao = conexao();

    
    //-----------------PEGANDO A DATA INICIAL DA CATEGORIA--------------
    $cmdData = $conexao-> query("SELECT data_inicio FROM categoria WHERE nomeCategoria = '{$categoria}';");
    $resData = $cmdData->fetch();
    $dataInicio = '';
    foreach($resData as $d){
        $dataInicio = $d;
    }

    //-----------------------PEGANDO A DATA FINAL DA CATEGORIA-------------------------
    $cmdData = $conexao-> query("SELECT data_final FROM categoria WHERE nomeCategoria = '{$categoria}';");
    $resData = $cmdData->fetch();
    $dataFinal = '';
    foreach($resData as $d){
        $dataFinal = $d;
    }

    //---------------------Soma de todos da tabela--------------------
    $cmd1 = $conexao->query("SELECT valor FROM gastos WHERE codigo_categoria = '{$categoria}'
                            and data between '{$dataInicio}' and '{$dataFinal}'");
    $res = $cmd1->fetchAll(PDO::FETCH_ASSOC);
    $resultadoSUM = $valor;
    for($i = 0; $i < count($res); $i++){
        foreach($res[$i] as $soma){
            $resultadoSUM += $soma;
        }
    }
  //---------------------------------------Valor maximo da categoria-----------------------------
    $valorMaximo = 0;
    $cmd2 = $conexao->query("SELECT valorMaximo FROM categoria WHERE nomeCategoria =  '{$categoria}' 
                            and data_inicio = '{$dataInicio}' and data_final= '{$dataFinal}'");
    $res2 = $cmd2->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0; $i < count($res2); $i++){
        foreach($res2[$i] as $valor1){
            $valorMaximo += $valor1;
        }
    }
    if( $data < $dataInicio || $data > $dataFinal){
        echo "<h3>data diferente do configurado</h3>";
    } else if($resultadoSUM > $valorMaximo   ){
        echo "<h3>Valor Maximo da Categoria Atingido</h3>";
   } else {
        $cmd = $conexao->prepare("INSERT INTO gastos (nomeUsuario, valor,data, codigo_categoria)
        VALUES (:n, :v, :d, :c)");
        $cmd->bindValue(":n", $usuario);
        $cmd->bindValue(":v", $valor);
        $cmd->bindValue(":d", $data);
        $cmd->bindValue(":c", $categoria);
        $cmd->execute();
        echo $resultadoSUM, "<br>";
        echo $valorMaximo;
       
        
    }
}

function cadastraCategoria($categoria, $valor, $dataInicio, $dataFinal){
    $conexao = conexao();

    $cmd = $conexao->prepare("SELECT codigo FROM categoria WHERE nomecategoria = '{$categoria}'");
    $cmd->execute();
    if($cmd->rowCount() > 0){
        echo("<h3>Categoria ja cadastrada</h3>");
        
    } else {
        //$cmd = $conexao->prepare("INSERT INTO categoria (nomeCategoria, valorMaximo)
        $cmd = $conexao->prepare("INSERT INTO categoria (nomeCategoria, valorMaximo, data_inicio,data_final)
        VALUES(:c, :v, :di, :df)");
         $cmd->bindValue(":c", $categoria);
         $cmd->bindValue(":v", $valor);
         $cmd->bindValue(":di", $dataInicio);
         $cmd->bindValue(":df", $dataFinal);
        $cmd->execute();
    }


    
}



function consultaGastos(){
    $conexao = conexao();
    $cmd = $conexao->query("SELECT * FROM gastos");
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function consultaCategoria(){
    $conexao = conexao();
    $cmd = $conexao->query("SELECT * FROM categoria");
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function filtroData(){
    $conexao = conexao();
    $cmd = $conexao->query("SELECT distinct data FROM gastos");
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}


function filtroCategoria(){
    $conexao = conexao();
    $cmd = $conexao->query("select nomeCategoria from categoria");
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function consultagastosWhere($retorno){
    $conexao = conexao();
    $cmd = $conexao->query("SELECT * FROM gastos WHERE codigo_categoria = '{$retorno}'");
    $cmd->execute();
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function consultadataWhere($data){
    $conexao = conexao();
    $cmd = $conexao->query("SELECT * FROM gastos WHERE data = '{$data}'");
    $cmd->execute();
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function consultaDataCategoria($categoria, $data){
    $conexao = conexao();
    $cmd = $conexao->query("SELECT * FROM gastos WHERE data = '{$data}' AND codigo_categoria = '{$categoria}'");
    $cmd->execute();
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function consultaCategoriaEditar($id){
    $conexao = conexao();
    $cmd = $conexao->query("SELECT * FROM categoria WHERE codigo = '{$id}'");
    $cmd->execute();
    $res = $cmd->fetch(PDO::FETCH_ASSOC);
    return $res;
}

function atualizarDadosEditar($id, $categoria, $valor, $DI, $DF){
    $conexao = conexao();
    $cmd = $conexao->prepare("UPDATE categoria SET nomeCategoria = '{$categoria}', valorMaximo = '{$valor}',data_inicio = '{$DI}', data_final = '{$DF}'
                            WHERE codigo = {$id}");
    $cmd->execute();

}