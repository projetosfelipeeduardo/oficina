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
    case 'imprime':
        $titulo = "Impressão";
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

    $aItem = ConsultarDados("ordemi", "cdorde", $chave);

    $aOrde = ConsultarDados("ordem", "cdorde", $chave);
    $pos = strpos($aOrde[0]["cdclie"], "-");
    $cdclie = substr($aOrde[0]["cdclie"], 0, $pos-1);

    $aClie = ConsultarDados("clientes", "cdclie", $cdclie);
    $declie = $aClie[0]["declie"];

    $dtorde = $aOrde[0]["dtorde"];
    $dtorde = strtotime($dtorde);
    $dtorde = date("d-m-Y", $dtorde);

    $dtpago = $aOrde[0]["dtpago"];
    $dtpago = strtotime($dtpago);
    $dtpago = date("d-m-Y", $dtpago);

    if (strtotime("1969-12-31") == strtotime($dtpago)){
        $dtpago="  ABERTA  ";
    }

    if (strtotime("0000-00-00") == "") {
        $dtpago="  ABERTA  ";
    }

    if (strtotime("") == "") {
        $dtpago="  ABERTA  ";
    }

    $aPara = ConsultarDados("", "", "", "select * from parametros");
    $cdprop=$aPara[0]["cdprop"];

    if (strlen($cdclie) > 11 ) {
        $cdclie=formata($cdclie,"cnpj");
    } Else {
        $cdclie=formata($cdclie,"cpf");
    }

    if (strlen($cdprop) > 11 ) {
        $cdprop=formata($cdprop,"cnpj");
    } Else {
        $cdprop=formata($cdprop,"cpf");
    }

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>&nbsp;&nbsp;&nbsp;&nbsp;</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="white-bg">
    <center><img src="img/logoalianca.png" alt="aliança logo" width="160px" heigth="160px"></center>
    <center><strong><?php echo $aPara[0]["deprop"]; ?></strong></center>
    <center><h2 class="text-navy"><?php echo 'Ordem de Serviço No. '.$aOrde[0]["cdorde"]; ?></h2></center>

    <!--div class="wrapper wrapper-content p-xl"-->

        <div class="ibox-content p-xl">

            <div class = "row">
                <div class="col-sm-8">
                    <!--h5>From:</h5-->
                    <address>
                        <span><?php echo $cdprop; ?></span><br>
                        <?php echo $aPara[0]["deende"].", ".$aPara[0]["nrende"].", ".$aPara[0]["debair"]; ?><br>
                        <?php echo $aPara[0]["decida"].", ".$aPara[0]["cdesta"].", ".$aPara[0]["nrcepi"]; ?><br>
                        <span title="Telefone"></span> <?php echo $aPara[0]["nrtele"]; ?><br>
                        <span title="Celular"></span> <?php echo $aPara[0]["nrcelu"]; ?><br>
                        <span title="E-mail"></span> <?php echo $aPara[0]["demail"]; ?>                        
                    </address>
                </div>
                <div class="col-sm-6 text-right">
                    <span><strong>CLIENTE</strong></span>
                    <address>
                        <?php echo $aClie[0]["declie"]; ?><br>
                        <?php echo $cdclie; ?> <br>
                        <?php echo $aClie[0]["deende"].", ".$aClie[0]["nrende"].", ".$aClie[0]["debair"]; ?><br>
                        <?php echo $aClie[0]["decida"].", ".$aClie[0]["cdesta"].", ".$aClie[0]["nrcepi"]; ?><br>
                        <span title="Telefone"></span> <?php echo $aClie[0]["nrtele"]; ?><br>
                        <span title="Celular"></span> <?php echo $aClie[0]["nrcelu"]; ?><br>
                        <span title="E-mail"></span> <?php echo $aClie[0]["demail"]; ?><br>
                        <span><strong>VEÍCULO : </strong>
                            <?php echo $aOrde[0]["veplac"].", ".$aOrde[0]["vemarc"].", ".$aOrde[0]["vemode"]; ?>
                            <?php echo $aOrde[0]["vecorv"].", ".$aOrde[0]["veanom"].", ".$aOrde[0]["veanof"]; ?>
                        </span>
                    </address>
                    <p>
                        <span><strong>Data da Abertura da O.S.  :  </strong><?php echo $dtorde; ?></span><br/>
                        <span><strong>Data de Fechamento da O.S.:  </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dtpago; ?></span>
                    </p>
                </div>
            </div>

            <div class="table-responsive m-t">
                <table class="table invoice-table">
                    <thead>
                        <th>Sequência</th>
                        <th>Produto/Peça/Serviço</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário</th>
                        <th>Valor Total</th>
                    </thead>
                    <tbody>
                        <?php for ($f =1; $f <= 20; $f++) { ?>
                            <?php $cditem = "cditem[".trim($f)."]"; ?>
                            <?php $qtitem = "qtitem[".trim($f)."]"; ?>
                            <?php $vlitem = "vlitem[".trim($f)."]"; ?>
                            <?php $vltota = "vltota[".trim($f)."]"; ?>
                            <tr>
                                <?php if (isset($aItem[$f-1]["cdpeca"])) {?>
                                    <td>
                                        <div>
                                            <strong><?php echo $f;?></strong>
                                        </div>
                                    </td>
                                    <td><div><strong><small><?php echo $aItem[$f-1]["cdpeca"]; ?></small></strong></div></td>
                                    <td><?php echo number_format($aItem[$f-1]["qtpeca"],0,",",".") ;?></td>
                                    <td><?php echo number_format($aItem[$f-1]["vlpeca"],2,",",".") ;?></td>
                                    <td><?php echo number_format($aItem[$f-1]["vltota"],2,",",".") ;?></td>
                                <?php }?>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>                
            </div>
            <table class="table invoice-total">
                <tbody>
                    <tr>
                        <td>TOTAL   :</td>
                        <td><?php echo number_format($aOrde[0]["vlorde"],2,",","."); ?></td>
                    </tr>
                </tbody>
            </table>
            <table class="table">
                <tbody>
                    <tr>
                        <td><strong>OBSERVAÇÕES:</strong></td>
                        <td><?php echo $aOrde[0]["deobse"]; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <!--/div-->
    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>

    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>

