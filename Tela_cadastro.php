
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<?php
require_once 'conecta_banco.php';
$conexao = conexao();
$sql ='SELECT nomeCategoria FROM categoria';
$resultado = $conexao->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$categoria = [];
for($i = 0; $i < count($resultado); $i++){
    foreach($resultado[$i] as $res => $valor){
        if($res != 'codigo') {
            array_push($categoria, $valor);
        }
    }
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    
    li {
       
        list-style: none; /*retirando as bolinhas*/
        
    }
   *{
       margin: 0px;
       padding: 0px;
   }
  
   
   label, input {
       display: block;
       height: 40px;
       padding: 5px;
       width: 100%;
   }
   form {
    background-color: rgba(0, 0, 0, .2);
       width: 350px;
       margin: 30px 0px;
       padding: 20px;
   }

   input[type="submit"]{
       margin-top: 10px;
   }

   .esquerda {
       width: 35%;
       float: left;
   }

   .direita{
    width: 65%;
    float: left;
   }

   table {
       background-color: rgba(0, 0, 0, .2);
       width: 90%;
       margin: 30px auto;

   }
   tr{
       line-height: 30px;
   }
   tr#titulo{
       font-weight: bold;
       background-color: rgba(0, 0, 0, .6);
       color: snow;
   }
   a{
       margin: 0px 5px;
       background-color: snow;
       color: black;
       padding: 3px;
   }
   h3 {
       color: crimson;
   }
 
   
    

</style>   
<body>
<section class="esquerda">
<form action="" method="post">
  <div class="gastos" >

    <ul>
        <h4>Cadastro de gastos</h4>
        <label for="">Usuario</label>
        <li> <input class="campo" type="text" name="campousuario"></li>
        <label for="">Valor</label>
        <li><input class="campo" type="text" name="campoValor"></li>
        <label for="">Data</label>
        <input class="data" type="date" name="data">
        <label for="">Categoria</label>
        <li>
                <select name="listaCategoria">
                <?php
                        for($i = 0; $i < count($categoria); $i++){
                            ?>
                            <option value="<?= $i; ?>"> <?= $categoria[$i] ?> </option>
                            <?php
                        }
                        ?>
                </select>        
        </li> 
        <li> <input class="botao" type="submit" name="btncadastrar" value="Cadastrar"></li>
    </ul>
  </div>
 
    <?php
     if(isset($_POST["campousuario"]) && isset($_POST["campoValor"]) && isset($_POST["listaCategoria"]) ){
        $usu = $_POST["campousuario"];
        $val = (int) $_POST["campoValor"];
        $dat = $_POST["data"];
        $cat = $categoria[$_POST["listaCategoria"]];
        cadastrarGastos($usu, $val, $dat, $cat);
    }
      
    ?>
</form>


<form action="" method="post">
    <h4>Cadastro de Categorias</h4>
    <div class="categorias">
        <label for="">Categoria</label>
        <input class="campo" type="text" name="campoCategoria"
         value="<?php
             /*Codigo para o EDITAR categoria */
             if(isset($_GET["id"])){
            $res = array();
            $codigoUpdate = $_GET["id"];
            $res = consultaCategoriaEditar($codigoUpdate);
            echo $res['nomeCategoria'];
        }
    ?>">

        <label for="">Valor Maximo a ser gasto</label>
        <input class="campo" type="text" name="campoCategoriaValor"
        value="<?php
             /*Codigo para o EDITAR categoria */
             if(isset($_GET["id"])){
                $res = array();
                $codigoUpdate = $_GET["id"];
                $res = consultaCategoriaEditar($codigoUpdate);
                echo $res['valorMaximo'];
             }   
        ?>" >
        <label for="">Data de Inicio</label>
        <input class="data" type="date" name="datainicio">
        <label for="">Data de Final</label>
        <input class="data" type="date" name="datafinal">


        <input class type="submit" name="btncadastrar"
         value="<?php
            /*Codigo para o EDITAR categoria */
            if(isset($_GET["id"])){
                $res = array();
                $codigoUpdate = $_GET["id"];
                $res = consultaCategoriaEditar($codigoUpdate);
                echo "Atualizar";
             } else {
                 echo "Cadastrar";
             }
         ?>
         ">
    </div>
        
    <?php
        if(isset($_POST["campoCategoria"])){
            //-----------------Editar----------------
            if(isset($_GET['id'])) {
                $codigoUpdate = $_GET["id"];
                $atualizaCate = $_POST["campoCategoria"];
                $atualizaValor =(int) $_POST["campoCategoriaValor"];
                $atualizaDataInicio = $_POST["datainicio"];
                $atualizaDataFinal = $_POST["datafinal"];
                atualizarDadosEditar($codigoUpdate, $atualizaCate, $atualizaValor, $atualizaDataInicio, $atualizaDataFinal);
                header("location: Tela_cadastro.php");

            } else {
                $novoCate = $_POST["campoCategoria"];
                $novoValor =(int) $_POST["campoCategoriaValor"];
                $novoDataInicio = $_POST["datainicio"];
                $novoDataFinal = $_POST["datafinal"];
                cadastraCategoria($novoCate, $novoValor, $novoDataInicio, $novoDataFinal);
            }
           
        }
    ?>
</form>

<table>
<h4>Lista de Categorias</h4>
    <tr id="titulo">
        <td>ID</td>
        <td>N.Cat</td>
        <td>V.Est</td>
        <td>D.I</td>
        <td colspan="2">D.F</td>
    </tr>
    <?php
    $dadosCategoria = consultaCategoria();
    for($i = 0; $i < count($dadosCategoria); $i++){
        echo "<tr>";
        foreach($dadosCategoria[$i] as $dados){
            echo "<td>". $dados . "</td>";
        }
        ?>
        <td><a href="Tela_cadastro.php?id=<?php echo $dadosCategoria[$i]['codigo']  ?>">Editar</a></td>
        <?php
        echo "</tr>";
    }
    ?>
</table>
    


</section>



<section class="direita">
   <div class="tabela1">
       <form action="" method="POST">   
       <select name="filtrocategoria" >
                <option value="Todos" >Todos</option>
                <?php
                     $filtrocategoria = filtroCategoria();
                     for($i = 0; $i < count($filtrocategoria); $i++){
                         foreach($filtrocategoria[$i] as $cat){
                        ?>
                            <option ><?=$cat?></option>
                        <?php
                         }
                     }
                    
                ?>
        </select>

        <select name="filtrodata" >
                <option value="Todos" >Todos</option>
                <?php
                     $filtrodata = filtroData();
                     for($i = 0; $i < count($filtrodata); $i++){
                         foreach($filtrodata[$i] as $data){
                        ?>
                            <option ><?=$data?></option>
                        <?php
                         }
                     }
                    
                ?>
        </select>

            <input type="submit" value="pesquisa"> 
            <?php
                // Consulta Categoria
                $resultadoCategoria= '';
                $resultadoCategoria= filter_input(INPUT_POST, "filtrocategoria", FILTER_DEFAULT);
                //echo $teste;
                //$teste1 = consultaCategoriaWhere($resultadoCategoria);
                //print_r($teste1);
                    
                // consulta data
                $resultadoData = '';
                $resultadoData = filter_input(INPUT_POST, "filtrodata",FILTER_DEFAULT);
               // $teste = consultadataWhere($resultadoData);
               // print_r($teste);
                
               
            ?>
       </form>
       <h4>Lista de Gastos</h4>
        <table>
            <tr id="titulo">
                <td>ID</td>
                <td>Usuario</td>
                <td>Valor</td>
                <td>Data</td> 
                <td colspan="2">Categoria</td><!--colspan: ocupa 2 colunas-->
                
                
            </tr>
            <?php
                
            if($resultadoCategoria == 'Todos' && $resultadoData =='Todos' ||$resultadoCategoria == '' && $resultadoData =='' ) {
                $dadosGastos = consultaGastos();
                

                for($i = 0; $i < count($dadosGastos); $i++){
                    echo "<tr>";
                    foreach($dadosGastos[$i] as $dados){
                        echo "<td>". $dados ."</td>";
                    }
                    ?>
                   
                    <?php
                    echo "</tr>";
                }
            } else if($resultadoCategoria != 'Todos' && $resultadoData == 'Todos') {
                $dadosGastos = consultagastosWhere($resultadoCategoria);
                for($i = 0; $i < count($dadosGastos); $i++) {
                    echo "<tr>";
                    foreach($dadosGastos[$i] as $dados){
                        echo "<td>". $dados ."</td>";
                    }
                    echo "</tr>";
                }
            } else if($resultadoCategoria == 'Todos' && $resultadoData != 'Todos'){
                $dadosGastos = consultadataWhere($resultadoData);
                for($i = 0; $i < count($dadosGastos); $i++) {
                    echo "<tr>";
                    foreach($dadosGastos[$i] as $dados){
                        echo "<td>". $dados ."</td>";
                    }
                    echo "</tr>";
                }
            } else if($resultadoCategoria != 'Todos' && $resultadoData != 'Todos'){
                $dadosGastos = consultaDataCategoria($resultadoCategoria, $resultadoData);
                for ($i = 0; $i < count($dadosGastos); $i++){
                    echo "<tr>";
                    foreach($dadosGastos[$i] as $dados){
                        echo "<td>". $dados ."</td>";
                    }
                    echo "</tr>";
                }
            }
             ?>
                
            </tr>
        </table>
    </div>   
    
</section>

</body>
</html>

