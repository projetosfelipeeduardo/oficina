<?php

    // identificando dispositivo
    $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
    $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
    $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");

    $eMovel="N";
    if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true) {
        $eMovel="S";
    }

    // incluindo bibliotecas de apoio
    include "banco.php";
    include "util.php";

    $acao = $_GET["acao"];
    $chave = trim($_GET["chave"]);

    switch ($acao) {
    case 'ver':
        $titulo = "Consulta";
        break;
    case 'edita':
        $titulo = "Alteração";
        break;
    case 'apaga':
        $titulo = "Exclusão";
        break;
    default:
        header('Location: fichacadastral.php');
    }

    //codigo do usuario
    if (isset($_COOKIE['cdusua'])) {
        $cdusua = $_COOKIE['cdusua'];
    }

    // nome do usuario
    if (isset($_COOKIE['deusua'])) {
        $deusua = $_COOKIE['deusua'];
    }

    //localização da foto
    if (isset($_COOKIE['defoto'])) {
        $defoto = $_COOKIE['defoto'];
    }

    //tipo de usuario
    if (isset($_COOKIE['cdtipo'])) {
        $cdtipo = $_COOKIE['cdtipo'];
    }

    //email de usuario
    if (isset($_COOKIE['demail'])) {
        $demail = $_COOKIE['demail'];
    }

    $detipo="Tipo Não Identificado";
    if ($cdtipo == "A") {
        $detipo="Administrador";
    }
    if ($cdtipo == "F") {
        $detipo="Funcionário";
    }
    if ($cdtipo == "O") {
        $detipo="Oficina";
    }
    if ($cdtipo == "M") {
        $detipo="Mecânico";
    }
    if ($cdtipo == "C") {
        $detipo="Cliente";
    }

    // reduzir o tamanho do nome do usuario
    $deusua1=$deusua;
    $deusua = substr($deusua, 0,15);

    $aOrde = ConsultarDados("ordem", "cdorde", $chave);
    $aItem = ConsultarDados("ordemi", "cdorde", $chave);
    $aClie = ConsultarDados("", "", "","select * from clientes order by declie");
    $aPeca= ConsultarDados("", "", "","select * from pecas order by depeca");
    $aServ= ConsultarDados("", "", "","select * from servicos order by deserv");

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Demonstração Auto Mecânica&copy; | Principal </title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> <span>
                                <img alt="foto" width="80" height="80" class="img-circle" src="<?php echo $defoto; ?>" />
                                 </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $deusua; ?></strong>
                                 </span> <span class="text-muted text-xs block"><?php echo $detipo; ?><b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="meusdados.php">Atualizar Meus Dados</a></li>
                                <li><a href="minhasenha.php">Alterar Minha Senha</a></li>
                                <li class="divider"></li>
                                <li><a href="index.html">Sair</a></li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="index.php"><i class="fa fa-home"></i> <span class="nav-label">Menu Principal</span></a>
                    </li>                    

                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-warning " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-left">
                        <br>
                        <li>
                            <?php if (strlen($cdusua) == 14 ) {;?>
                                <span><?php echo  formatar($cdusua,"cnpj")." - ";?></span>
                            <?php } Else {?>
                                <span><?php echo  formatar($cdusua,"cpf")." - ";?></span>
                            <?php }?>
                        </li>
                        <li>
                            <span><?php echo  $deusua1 ;?></span>
                        </li>
                    </ul>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Benvindo a <strong>Demonstração Auto Mecânica&copy;</strong></span>
                        </li>
                        <li>
                            <a href="index.html">
                                <i class="fa fa-sign-out"></i> Sair
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="wrapper wrapper-content">
                <!--div class="col-lg-12"-->
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <button type="button" class="btn btn-warning btn-lg btn-block"><i
                                                        class="fa fa-user"></i> Agenda - <small><?php echo $titulo." da OS"; ?></small>
                            </button>
                        </div>

                        <div class="ibox-content">
                            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="ordemaa.php">

                                <div>
                                    <center>
                                        <?php if($acao == "edita") {?>
                                            <button class="btn btn-sm btn-primary" name = "edita" type="submit"><strong>Alterar</strong></button>
                                        <?php }?>
                                        <?php if($acao == "apaga") {?>
                                            <button class="btn btn-sm btn-danger" name = "apaga" type="submit"><strong>Apagar</strong></button>
                                        <?php }?>
                                        <button class="btn btn-sm btn-warning " type="button" onClick="history.go(-1)"><strong>Retornar</strong></button>
                                    </center>
                                </div>
                                <br>
                                <?php if($acao == "edita") {?>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Número da OS</label>
                                                <div class="col-md-4">
                                                    <input id="cdorde" name="cdorde" value="<?php echo $aOrde[0]["cdorde"];?>" type="text" placeholder="" class="form-control" maxlength = "10" readonly="">
                                                </div>
                                            </div>
                                            <!--center><h3><span class="text-warning"><strong>DADOS DO PEDIDO</strong></span></h3></center-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Cliente</label>
                                                <div class="col-md-4">
                                                    <select name="cdclie" id="cdclie" style="width:250%">
                                                        <option selected=""><?php echo $aOrde[0]["cdclie"];?></option>
                                                        <?php for($i=0;$i < count($aClie);$i++) { ?>
                                                          <option><?php echo str_pad($aClie[$i]["cdclie"],14," ",STR_PAD_LEFT)." - ".$aClie[$i]["declie"];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Situação</label>
                                                <div class="col-md-4">
                                                    <select name="cdsitu" id="cdsitu">
                                                        <?php if ($aOrde[0]["cdsitu"] == "") {?>
                                                            <option selected="">Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option>Andamento</option>
                                                            <option>Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Orçamento") {?>
                                                            <option selected="">Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option>Andamento</option>
                                                            <option>Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Pendente") {?>
                                                            <option>Orçamento</option>
                                                            <option selected="">Pendente</option>
                                                            <option>Andamento</option>
                                                            <option>Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Andamento") {?>
                                                            <option>Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option selected="">Andamento</option>
                                                            <option>Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Concluído") {?>
                                                            <option>Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option>Andamento</option>
                                                            <option selected="">Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Entregue") {?>
                                                            <option>Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option>Andamento</option>
                                                            <option>Concluído</option>
                                                            <option selected="">Entregue</option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Data</label>
                                                <div class="col-md-4">
                                                    <input id="dtorde" name="dtorde" value="<?php echo date("Y-m-d");?>" type="date" placeholder="" class="form-control" maxlength = "10">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Valor</label>
                                                <div class="col-md-4">
                                                    <input id="vlorde" name="vlorde" value="<?php echo number_format($aOrde[0]["vlorde"],2,",",".");?>" type="text" placeholder="" class="form-control" maxlength = "15">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Placa do Veículo</label>
                                                <div class="col-md-4">
                                                    <input id="veplac" name="veplac" value="<?php echo $aOrde[0]["veplac"];?>" type="text" placeholder="" class="form-control" maxlength = "7">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Marca do Veículo</label>
                                                <div class="col-md-4">
                                                    <input id="vemarc" name="vemarc" value="<?php echo $aOrde[0]["vemarc"];?>" type="text" placeholder="" class="form-control" maxlength = "50">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Modelo do Veículo</label>
                                                <div class="col-md-4">
                                                    <input id="vemode" name="vemode" value="<?php echo $aOrde[0]["vemode"];?>" type="text" placeholder="" class="form-control" maxlength = "50">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Cor do Veículo</label>
                                                <div class="col-md-4">
                                                    <input id="vecorv" name="vecorv" value="<?php echo $aOrde[0]["vecorv"];?>" type="text" placeholder="" class="form-control" maxlength = "50">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Ano Fabricação</label>
                                                <div class="col-md-2">
                                                    <input id="veanof" name="veanof" value="<?php echo $aOrde[0]["veanof"];?>" type="text" placeholder="" class="form-control" maxlength = "04">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Ano Modelo</label>
                                                <div class="col-md-2">
                                                    <input id="veanom" name="veanom" value="<?php echo $aOrde[0]["veanom"];?>" type="text" placeholder="" class="form-control" maxlength = "04">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Data de Pagamento</label>
                                                <div class="col-md-4">
                                                    <input id="dtpago" name="dtpago" value="<?php echo $aOrde[0]["dtpago"];?>" type="date" placeholder="" class="form-control" maxlength = "10">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Valor Pago</label>
                                                <div class="col-md-4">
                                                    <input id="vlpago" name="vlpago" value="<?php echo number_format($aOrde[0]["vlpago"],2,",",".");?>" type="text" placeholder="" class="form-control" maxlength = "10">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Forma de Pagamento</label>
                                                <div class="col-md-4">
                                                    <select name="cdform" id="cdform" style="width:50%">
                                                        <?php if ($aOrde[0]["cdform"] == ""){?>
                                                            <option selected="">Dinheiro</option>
                                                            <option>Débito</option>
                                                            <option>Crédito</option>
                                                            <option>Cheque</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdform"] == "Dinheiro"){?>
                                                            <option selected="">Dinheiro</option>
                                                            <option>Débito</option>
                                                            <option>Crédito</option>
                                                            <option>Cheque</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdform"] == "Débito"){?>
                                                            <option>Dinheiro</option>
                                                            <option selected="">Débito</option>
                                                            <option>Crédito</option>
                                                            <option>Cheque</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdform"] == "Crédito"){?>
                                                            <option>Dinheiro</option>
                                                            <option>Débito</option>
                                                            <option selected="">Crédito</option>
                                                            <option>Cheque</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdform"] == "Cheque"){?>
                                                            <option>Dinheiro</option>
                                                            <option>Débito</option>
                                                            <option>Crédito</option>
                                                            <option selected="">Cheque</option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Quantidade Parcelas</label>
                                                <div class="col-md-2">
                                                    <input id="qtform" name="qtform" value="<?php echo $aOrde[0]["qtform"];?>" type="number" placeholder="" class="form-control" maxlength = "15">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Observações</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="deobse" wrap="physical" cols=50 rows=3 name="deobse" placeholder=""><?php echo $aOrde[0]["deobse"];?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class ="col-lg-12">
                                            <div class="table responsive">
                                                <table class="table table-striped table-bordered ">
                                                    <thead>
                                                        <th style = "width:10%">Sequência</th>
                                                        <th style = "width:40%">Produto/Peça/Serviço</th>
                                                        <th style = "width:10%">Quantidade</th>
                                                        <th style = "width:10%">Valor Unitário</th>
                                                        <th style = "width:20%">Valor Total</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php for ($f =1; $f <= 10; $f++) { ?>
                                                            <?php $cditem = "cditem[".trim($f)."]"; ?>
                                                            <?php $qtitem = "qtitem[".trim($f)."]"; ?>
                                                            <?php $vlitem = "vlitem[".trim($f)."]"; ?>
                                                            <?php $vltota = "vltota[".trim($f)."]"; ?>
                                                            <tr>
                                                                <td><?php echo $f;?></td>
                                                                <?php if (isset($aItem[$f-1]["cdpeca"])) {?>
                                                                    <td>
                                                                        <center>
                                                                            <select id = "<?php echo $cditem;?>" name="<?php echo $cditem;?>" class="form-control" onclick="colocapreco();">
                                                                                <option value= "X|0|Serviços">SERVIÇOS</option>
                                                                                <option selected ="" value="<?php echo 'S|'.$aItem[$f-1]["vlpeca"].'|'.$aItem[$f-1]["cdpeca"];?>"><?php echo $aItem[$f-1]["cdpeca"];?></option>
                                                                                <?php for($i=0;$i < count($aServ);$i++) { ?>
                                                                                    <option value = "<?php echo 'S|'.$aServ[$i]["vlserv"].'|'.$aServ[$i]["cdserv"]." - ".$aServ[$i]["deserv"];?>"><?php echo $aServ[$i]["cdserv"]." - ".$aServ[$i]["deserv"];?></option>                                                                                    
                                                                                <?php }?>
                                                                                <option value="X|0|Peças">PEÇAS</option>
                                                                                <?php for($i=0;$i < count($aPeca);$i++) { ?>
                                                                                  <option value = "<?php echo 'P|'.$aPeca[$i]["vlpeca"].'|'.$aPeca[$i]["cdpeca"]." - ".$aPeca[$i]["depeca"];?>"><?php echo $aPeca[$i]["cdpeca"]." - ".$aPeca[$i]["depeca"];?></option>
                                                                                <?php }?>
                                                                            </select>
                                                                        </center>
                                                                    </td>
                                                                    <td><center><input id = "<?php echo $qtitem;?>" name="<?php echo $qtitem;?>" value = "<?php echo $aItem[$f-1]["qtpeca"] ;?>" onkeyup="mascara(this, 'soNumeros'); calcula();" type="text" class="form-control" placeholder="" maxlength = "15"></center></td>
                                                                    <td><center><input id = "<?php echo $vlitem;?>" name="<?php echo $vlitem;?>" value = "<?php echo $aItem[$f-1]["vlpeca"] ;?>" onkeyup="mascara(this, 'soNumeros'); calcula();" type="text" class="form-control" placeholder="" maxlength = "15"></center></td>
                                                                    <td><center><input id = "<?php echo $vltota;?>" name="<?php echo $vltota;?>" value = "<?php echo $aItem[$f-1]["vltota"] ;?>" type="text" class="form-control" placeholder="" maxlength = "15" readonly = ""></center></td>
                                                                <?php } Else {?>
                                                                    <td>
                                                                        <center>
                                                                            <select id = "<?php echo $cditem;?>" name="<?php echo $cditem;?>" class="form-control" onclick="colocapreco();">
                                                                                <option value= "X|0|Serviços" selected>SERVIÇOS</option>
                                                                                <?php for($i=0;$i < count($aServ);$i++) { ?>
                                                                                  <option value = "<?php echo 'S|'.$aServ[$i]["vlserv"].'|'.$aServ[$i]["cdserv"]." - ".$aServ[$i]["deserv"];?>"><?php echo $aServ[$i]["cdserv"]." - ".$aServ[$i]["deserv"];?></option>
                                                                                <?php }?>
                                                                                <option value="X|0|Peças" selected>PEÇAS</option>
                                                                                <?php for($i=0;$i < count($aPeca);$i++) { ?>
                                                                                  <option value = "<?php echo 'P|'.$aPeca[$i]["vlpeca"].'|'.$aPeca[$i]["cdpeca"]." - ".$aPeca[$i]["depeca"];?>"><?php echo $aPeca[$i]["cdpeca"]." - ".$aPeca[$i]["depeca"];?></option>
                                                                                <?php }?>
                                                                            </select>
                                                                        </center>
                                                                    </td>
                                                                    <td><center><input id = "<?php echo $qtitem;?>" name="<?php echo $qtitem;?>" value = "1" onkeyup="mascara(this, 'soNumeros'); calcula();" type="text" class="form-control" placeholder="" maxlength = "15"></center></td>
                                                                    <td><center><input id = "<?php echo $vlitem;?>" name="<?php echo $vlitem;?>" value = "0.00" onkeyup="mascara(this, 'soNumeros'); calcula();" type="text" class="form-control" placeholder="" maxlength = "15"></center></td>
                                                                    <td><center><input id = "<?php echo $vltota;?>" name="<?php echo $vltota;?>" value = "0.00" type="text" class="form-control" placeholder="" maxlength = "15" readonly = ""></center></td>
                                                                <?php }?>
                                                            </tr>
                                                        <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } Else {?>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Número da OS</label>
                                                <div class="col-md-4">
                                                    <input id="cdorde" name="cdorde" value="<?php echo $aOrde[0]["cdorde"];?>" type="text" placeholder="" class="form-control" maxlength = "10" readonly="">
                                                </div>
                                            </div>
                                            <!--center><h3><span class="text-warning"><strong>DADOS DO PEDIDO</strong></span></h3></center-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Cliente</label>
                                                <div class="col-md-4">
                                                    <select name="cdclie" id="cdclie" style="width:250%" disabled="">
                                                        <option selected=""><?php echo $aOrde[0]["cdclie"];?></option>
                                                        <?php for($i=0;$i < count($aClie);$i++) { ?>
                                                          <option><?php echo str_pad($aClie[$i]["cdclie"],14," ",STR_PAD_LEFT)." - ".$aClie[$i]["declie"];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Situação</label>
                                                <div class="col-md-4">
                                                    <select name="cdsitu" id="cdsitu" disabled="">
                                                        <?php if ($aOrde[0]["cdsitu"] == "") {?>
                                                            <option selected="">Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option>Andamento</option>
                                                            <option>Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Orçamento") {?>
                                                            <option selected="">Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option>Andamento</option>
                                                            <option>Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Pendente") {?>
                                                            <option>Orçamento</option>
                                                            <option selected="">Pendente</option>
                                                            <option>Andamento</option>
                                                            <option>Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Andamento") {?>
                                                            <option>Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option selected="">Andamento</option>
                                                            <option>Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Concluído") {?>
                                                            <option>Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option>Andamento</option>
                                                            <option selected="">Concluído</option>
                                                            <option>Entregue</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdsitu"] == "Entregue") {?>
                                                            <option>Orçamento</option>
                                                            <option>Pendente</option>
                                                            <option>Andamento</option>
                                                            <option>Concluído</option>
                                                            <option selected="">Entregue</option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Data</label>
                                                <div class="col-md-4">
                                                    <input id="dtorde" name="dtorde" value="<?php echo date("Y-m-d");?>" type="date" placeholder="" class="form-control" maxlength = "10" readonly="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Valor</label>
                                                <div class="col-md-4">
                                                    <input id="vlorde" name="vlorde" value="<?php echo number_format($aOrde[0]["vlorde"],2,",",".");?>" type="text" placeholder="" class="form-control" maxlength = "15" readonly="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Placa do Veículo</label>
                                                <div class="col-md-4">
                                                    <input id="veplac" name="veplac" value="<?php echo $aOrde[0]["veplac"];?>" type="text" placeholder="" class="form-control" maxlength = "7" readonly="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Marca do Veículo</label>
                                                <div class="col-md-4">
                                                    <input id="vemarc" name="vemarc" value="<?php echo $aOrde[0]["vemarc"];?>" type="text" placeholder="" class="form-control" maxlength = "50" readonly="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Modelo do Veículo</label>
                                                <div class="col-md-4">
                                                    <input id="vemode" name="vemode" value="<?php echo $aOrde[0]["vemode"];?>" type="text" placeholder="" class="form-control" maxlength = "50" readonly="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Cor do Veículo</label>
                                                <div class="col-md-4">
                                                    <input id="vecorv" name="vecorv" value="<?php echo $aOrde[0]["vecorv"];?>" type="text" placeholder="" class="form-control" maxlength = "50" readonly="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Ano Fabricação</label>
                                                <div class="col-md-2">
                                                    <input id="veanof" name="veanof" value="<?php echo $aOrde[0]["veanof"];?>" type="text" placeholder="" class="form-control" maxlength = "04" readonly="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Ano Modelo</label>
                                                <div class="col-md-2">
                                                    <input id="veanom" name="veanom" value="<?php echo $aOrde[0]["veanom"];?>" type="text" placeholder="" class="form-control" maxlength = "04" readonly="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Data de Pagamento</label>
                                                <div class="col-md-4">
                                                    <input id="dtpago" name="dtpago" value="<?php echo $aOrde[0]["dtpago"];?>" type="date" placeholder="" class="form-control" maxlength = "10" readonly="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Valor Pago</label>
                                                <div class="col-md-4">
                                                    <input id="vlpago" name="vlpago" value="<?php echo number_format($aOrde[0]["vlpago"],2,",",".");?>" type="text" placeholder="" class="form-control" maxlength = "10" readonly="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Forma de Pagamento</label>
                                                <div class="col-md-4">
                                                    <select name="cdform" id="cdform" style="width:50%" disabled="">
                                                        <?php if ($aOrde[0]["cdform"] == ""){?>
                                                            <option selected="">Dinheiro</option>
                                                            <option>Débito</option>
                                                            <option>Crédito</option>
                                                            <option>Cheque</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdform"] == "Dinheiro"){?>
                                                            <option selected="">Dinheiro</option>
                                                            <option>Débito</option>
                                                            <option>Crédito</option>
                                                            <option>Cheque</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdform"] == "Débito"){?>
                                                            <option>Dinheiro</option>
                                                            <option selected="">Débito</option>
                                                            <option>Crédito</option>
                                                            <option>Cheque</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdform"] == "Crédito"){?>
                                                            <option>Dinheiro</option>
                                                            <option>Débito</option>
                                                            <option selected="">Crédito</option>
                                                            <option>Cheque</option>
                                                        <?php }?>
                                                        <?php if ($aOrde[0]["cdform"] == "Cheque"){?>
                                                            <option>Dinheiro</option>
                                                            <option>Débito</option>
                                                            <option>Crédito</option>
                                                            <option selected="">Cheque</option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Quantidade Parcelas</label>
                                                <div class="col-md-2">
                                                    <input id="qtform" name="qtform" value="<?php echo $aOrde[0]["qtform"];?>" type="number" placeholder="" class="form-control" maxlength = "15" readonly="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="textinput">Observações</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="deobse" wrap="physical" cols=50 rows=3 name="deobse" placeholder="" readonly=""><?php echo $aOrde[0]["deobse"];?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class ="col-lg-12">
                                            <div class="table responsive">
                                                <table class="table table-striped table-bordered ">
                                                    <thead>
                                                        <th style = "width:10%">Sequência</th>
                                                        <th style = "width:40%">Produto/Peça/Serviço</th>
                                                        <th style = "width:10%">Quantidade</th>
                                                        <th style = "width:10%">Valor Unitário</th>
                                                        <th style = "width:20%">Valor Total</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php for ($f =1; $f <= 10; $f++) { ?>
                                                            <?php $cditem = "cditem[".trim($f)."]"; ?>
                                                            <?php $qtitem = "qtitem[".trim($f)."]"; ?>
                                                            <?php $vlitem = "vlitem[".trim($f)."]"; ?>
                                                            <?php $vltota = "vltota[".trim($f)."]"; ?>
                                                            <tr>
                                                                <td><?php echo $f;?></td>
                                                                <?php if (isset($aItem[$f-1]["cdpeca"])) {?>
                                                                    <td>
                                                                        <center>
                                                                            <select id = "<?php echo $cditem;?>" name="<?php echo $cditem;?>" class="form-control" onclick="colocapreco();" disabled="">
                                                                                <option value= "X|0|Serviços">SERVIÇOS</option>
                                                                                <option selected ="" value="<?php echo 'S|'.$aItem[$f-1]["vlpeca"].'|'.$aItem[$f-1]["cdpeca"];?>"><?php echo $aItem[$f-1]["cdpeca"];?></option>
                                                                                <?php for($i=0;$i < count($aServ);$i++) { ?>
                                                                                    <option value = "<?php echo 'S|'.$aServ[$i]["vlserv"].'|'.$aServ[$i]["cdserv"]." - ".$aServ[$i]["deserv"];?>"><?php echo $aServ[$i]["cdserv"]." - ".$aServ[$i]["deserv"];?></option>                                                                                    
                                                                                <?php }?>
                                                                                <option value="X|0|Peças">PEÇAS</option>
                                                                                <?php for($i=0;$i < count($aPeca);$i++) { ?>
                                                                                  <option value = "<?php echo 'P|'.$aPeca[$i]["vlpeca"].'|'.$aPeca[$i]["cdpeca"]." - ".$aPeca[$i]["depeca"];?>"><?php echo $aPeca[$i]["cdpeca"]." - ".$aPeca[$i]["depeca"];?></option>
                                                                                <?php }?>
                                                                            </select>
                                                                        </center>
                                                                    </td>
                                                                    <td><center><input id = "<?php echo $qtitem;?>" name="<?php echo $qtitem;?>" value = "<?php echo number_format($aItem[$f-1]["qtpeca"],0,",",".") ;?>" onkeyup="mascara(this, 'soNumeros'); calcula();" type="text" class="form-control" placeholder="" maxlength = "15" readonly=""></center></td>
                                                                    <td><center><input id = "<?php echo $vlitem;?>" name="<?php echo $vlitem;?>" value = "<?php echo number_format($aItem[$f-1]["vlpeca"],2,",",".") ;?>" onkeyup="mascara(this, 'soNumeros'); calcula();" type="text" class="form-control" placeholder="" maxlength = "15" readonly=""></center></td>
                                                                    <td><center><input id = "<?php echo $vltota;?>" name="<?php echo $vltota;?>" value = "<?php echo number_format($aItem[$f-1]["vltota"],2,",",".") ;?>" type="text" class="form-control" placeholder="" maxlength = "15" readonly = ""></center></td>
                                                                <?php } Else {?>
                                                                    <td>
                                                                        <center>
                                                                            <select id = "<?php echo $cditem;?>" name="<?php echo $cditem;?>" class="form-control" onclick="colocapreco();" disabled="">
                                                                                <option value= "X|0|Serviços" selected>SERVIÇOS</option>
                                                                                <?php for($i=0;$i < count($aServ);$i++) { ?>
                                                                                  <option value = "<?php echo 'S|'.$aServ[$i]["vlserv"].'|'.$aServ[$i]["cdserv"]." - ".$aServ[$i]["deserv"];?>"><?php echo $aServ[$i]["cdserv"]." - ".$aServ[$i]["deserv"];?></option>
                                                                                <?php }?>
                                                                                <option value="X|0|Peças" selected>PEÇAS</option>
                                                                                <?php for($i=0;$i < count($aPeca);$i++) { ?>
                                                                                  <option value = "<?php echo 'P|'.$aPeca[$i]["vlpeca"].'|'.$aPeca[$i]["cdpeca"]." - ".$aPeca[$i]["depeca"];?>"><?php echo $aPeca[$i]["cdpeca"]." - ".$aPeca[$i]["depeca"];?></option>
                                                                                <?php }?>
                                                                            </select>
                                                                        </center>
                                                                    </td>
                                                                    <td><center><input id = "<?php echo $qtitem;?>" name="<?php echo $qtitem;?>" value = "1" onkeyup="mascara(this, 'soNumeros'); calcula();" type="text" class="form-control" placeholder="" maxlength = "15" readonly=""></center></td>
                                                                    <td><center><input id = "<?php echo $vlitem;?>" name="<?php echo $vlitem;?>" value = "0.00" onkeyup="mascara(this, 'soNumeros'); calcula();" type="text" class="form-control" placeholder="" maxlength = "15" readonly=""></center></td>
                                                                    <td><center><input id = "<?php echo $vltota;?>" name="<?php echo $vltota;?>" value = "0.00" type="text" class="form-control" placeholder="" maxlength = "15" readonly = ""></center></td>
                                                                <?php }?>
                                                            </tr>
                                                        <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>

                                <div>
                                    <center>
                                        <?php if($acao == "edita") {?>
                                            <button class="btn btn-sm btn-primary" name = "edita" type="submit"><strong>Alterar</strong></button>
                                        <?php }?>
                                        <?php if($acao == "apaga") {?>
                                            <button class="btn btn-sm btn-danger" name = "apaga" type="submit"><strong>Apagar</strong></button>
                                        <?php }?>
                                        <button class="btn btn-sm btn-warning " type="button" onClick="history.go(-1)"><strong>Retornar</strong></button>
                                    </center>
                                </div>

                            </form>
                        </div>
                    </div>
                <!--/div-->
            </div>
        </div>
    </div>
    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="js/plugins/jeditable/jquery.jeditable.js"></script>
    <script src="js/plugins/dataTables/datatables.min.js"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="js/plugins/flot/jquery.flot.time.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- Jvectormap -->
    <script src="js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- EayPIE -->
    <script src="js/plugins/easypiechart/jquery.easypiechart.js"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="js/demo/sparkline-demo.js"></script>

    <script>

        function mascara(o,f){
            v_obj=o
            v_fun=f
            setTimeout("execmascara()",1)
        }

        function execmascara(){
            v_obj.value=v_fun(v_obj.value)
        }

        function leech(v){
            v=v.replace(/o/gi,"0")
            v=v.replace(/i/gi,"1")
            v=v.replace(/z/gi,"2")
            v=v.replace(/e/gi,"3")
            v=v.replace(/a/gi,"4")
            v=v.replace(/s/gi,"5")
            v=v.replace(/t/gi,"7")
            return v
        }

        function soNumeros(v){
            return v.replace(/\D/g,"")
        }

        function celular(v){
            v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
            v=v.replace(/^(\d{3})(\d)/,"$1-$2")             //Coloca ponto entre o segundo e o terceiro dígitos
            v=v.replace(/^(\d\d)(\d)/g,"($1) $2") //Coloca parênteses em volta dos dois primeiros dígitos
            v=v.replace(/(\d{4})(\d)/,"$1-$2")    //Coloca hífen entre o quarto e o quinto dígitos
            return v
        }

        function telefone(v){
            v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
            v=v.replace(/^(\d\d)(\d)/g,"($1) $2") //Coloca parênteses em volta dos dois primeiros dígitos
            v=v.replace(/(\d{4})(\d)/,"$1-$2")    //Coloca hífen entre o quarto e o quinto dígitos
            return v
        }

        function cpf(v){
            v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
            v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
            v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
                                                     //de novo (para o segundo bloco de números)
            v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
            return v
        }

        function cep(v){
            v=v.replace(/D/g,"")                //Remove tudo o que não é dígito
            v=v.replace(/^(\d{5})(\d)/,"$1-$2") //Esse é tão fácil que não merece explicações
            return v
        }

        function cnpj(v){
            v=v.replace(/\D/g,"")                           //Remove tudo o que não é dígito
            v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro dígitos
            v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") //Coloca ponto entre o quinto e o sexto dígitos
            v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           //Coloca uma barra entre o oitavo e o nono dígitos
            v=v.replace(/(\d{4})(\d)/,"$1-$2")              //Coloca um hífen depois do bloco de quatro dígitos
            return v
        }

        function romanos(v){
            v=v.toUpperCase()             //Maiúsculas
            v=v.replace(/[^IVXLCDM]/g,"") //Remove tudo o que não for I, V, X, L, C, D ou M
            //Essa é complicada! Copiei daqui: http://www.diveintopython.org/refactoring/refactoring.html
            while(v.replace(/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/,"")!="")
                v=v.replace(/.$/,"")
            return v
        }

        function site(v){
            //Esse sem comentarios para que você entenda sozinho ;-)
            v=v.replace(/^http:\/\/?/,"")
            dominio=v
            caminho=""
            if(v.indexOf("/")>-1)
                dominio=v.split("/")[0]
                caminho=v.replace(/[^\/]*/,"")
            dominio=dominio.replace(/[^\w\.\+-:@]/g,"")
            caminho=caminho.replace(/[^\w\d\+-@:\?&=%\(\)\.]/g,"")
            caminho=caminho.replace(/([\?&])=/,"$1")
            if(caminho!="")dominio=dominio.replace(/\.+$/,"")
            v="http://"+dominio+caminho
            return v
        }

    </script>

    <script language="javascript">

        function formatNumber(number)
        {
            number = number.toFixed(2) + '';
            x = number.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }

        function formatNumberP(number)
        {
            number = number.toFixed(2) + '';
            x = number.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        function calcula(){

            //item
            var qt1 = parseInt(document.getElementById('qtitem[1]').value);
            var vl1 = parseFloat(document.getElementById('vlitem[1]').value);
            var n1 = parseInt((qt1 * vl1)*100)/100;

            document.getElementById('vltota[1]').value = formatNumber(n1);

            //item
            var qt2 = parseInt(document.getElementById('qtitem[2]').value);
            var vl2 = parseFloat(document.getElementById('vlitem[2]').value);
            var n2 = parseInt((qt2 * vl2)*100)/100;

            document.getElementById('vltota[2]').value = formatNumber(n2);

            //item
            var qt3 = parseInt(document.getElementById('qtitem[3]').value);
            var vl3 = parseFloat(document.getElementById('vlitem[3]').value);
            var n3 = parseInt((qt3 * vl3)*100)/100;

            document.getElementById('vltota[3]').value = formatNumber(n3);

            //item
            var qt4 = parseInt(document.getElementById('qtitem[4]').value);
            var vl4 = parseFloat(document.getElementById('vlitem[4]').value);
            var n4 = parseInt((qt4 * vl4)*100)/100;

            document.getElementById('vltota[4]').value = formatNumber(n4);

            //item
            var qt5 = parseInt(document.getElementById('qtitem[5]').value);
            var vl5 = parseFloat(document.getElementById('vlitem[5]').value);
            var n5 = parseInt((qt5 * vl5)*100)/100;

            document.getElementById('vltota[5]').value = formatNumber(n5);

            //item
            var qt6 = parseInt(document.getElementById('qtitem[6]').value);
            var vl6 = parseFloat(document.getElementById('vlitem[6]').value);
            var n6 = parseInt((qt6 * vl6)*100)/100;

            document.getElementById('vltota[6]').value = formatNumber(n6);

            //item
            var qt7 = parseInt(document.getElementById('qtitem[7]').value);
            var vl7 = parseFloat(document.getElementById('vlitem[7]').value);
            var n7 = parseInt((qt7 * vl7)*100)/100;

            document.getElementById('vltota[7]').value = formatNumber(n7);

            //item
            var qt8 = parseInt(document.getElementById('qtitem[8]').value);
            var vl8 = parseFloat(document.getElementById('vlitem[8]').value);
            var n8 = parseInt((qt8 * vl8)*100)/100;

            document.getElementById('vltota[8]').value = formatNumber(n8);

            //item
            var qt9 = parseInt(document.getElementById('qtitem[9]').value);
            var vl9 = parseFloat(document.getElementById('vlitem[9]').value);
            var n9 = parseInt((qt9 * vl9)*100)/100;

            document.getElementById('vltota[9]').value = formatNumber(n9);

            //item
            var qt10 = parseInt(document.getElementById('qtitem[10]').value);
            var vl10 = parseFloat(document.getElementById('vlitem[10]').value);
            var n10 = parseInt((qt10 * vl10)*100)/100;

            document.getElementById('vltota[10]').value = formatNumber(n10);

            //total
            var nt = n1 + n2 + n3 + n4 + n5 + n6 + n7 + n8 + n9 + n10;
            var nt = parseInt(nt*100)/100;
            document.getElementById('vlorde').value = formatNumber(nt);

        }

        function colocapreco(){

            var n1 = document.getElementById('cditem[1]').value.split('|');
            var n2 = document.getElementById('cditem[2]').value.split('|');
            var n3 = document.getElementById('cditem[3]').value.split('|');
            var n4 = document.getElementById('cditem[4]').value.split('|');
            var n5 = document.getElementById('cditem[5]').value.split('|');
            var n6 = document.getElementById('cditem[6]').value.split('|');
            var n7 = document.getElementById('cditem[7]').value.split('|');
            var n8 = document.getElementById('cditem[8]').value.split('|');
            var n9 = document.getElementById('cditem[9]').value.split('|');
            var n10 = document.getElementById('cditem[10]').value.split('|');

            document.getElementById('vlitem[1]').value = n1[1];
            //document.getElementById('qtitem[1]').value = 1;

            document.getElementById('vlitem[2]').value = n2[1];
            //document.getElementById('qtitem[2]').value = 1;

            document.getElementById('vlitem[3]').value = n3[1];
            //document.getElementById('qtitem[3]').value = 1;

            document.getElementById('vlitem[4]').value = n4[1];
            //document.getElementById('qtitem[4]').value = 1;

            document.getElementById('vlitem[5]').value = n5[1];
            //document.getElementById('qtitem[5]').value = 1;

            document.getElementById('vlitem[6]').value = n6[1];
            //document.getElementById('qtitem[6]').value = 1;

            document.getElementById('vlitem[7]').value = n7[1];
            //document.getElementById('qtitem[7]').value = 1;

            document.getElementById('vlitem[8]').value = n8[1];
            //document.getElementById('qtitem[8]').value = 1;

            document.getElementById('vlitem[9]').value = n9[1];
            //document.getElementById('qtitem[9]').value = 1;

            document.getElementById('vlitem[10]').value = n10[1];
            //document.getElementById('qtitem[10]').value = 1;

            setTimeout("calcula()",1);
        }

    </script>
</body>
</html>
