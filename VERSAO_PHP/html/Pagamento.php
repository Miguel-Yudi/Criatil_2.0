<?php
require_once("../controller/global.php");
require_once("../controller/conexao.php");
require_once("../Dao/cupomDAO.php");
require_once("../Dao/carrinhoDAO.php");
require_once("../models/message.php");
require_once("../Dao/produtoDAO.php");
require_once("../models/brinquedo.php");
require_once("../Dao/usuarioDAO.php");
require_once("../models/usuario.php");
require_once("../models/cupom.php");

ini_set('display_errors','Off'); ini_set('error_reporting', E_ALL ); define('WP_DEBUG', false); define('WP_DEBUG_DISPLAY', false);

    $userDao = new UsuarioDAO($conn,$BASE_URL);

    $usuarioData = $userDao->verificarToken(true);


    $prodDAO = new ProdutoDAO($conn,$BASE_URL);

    $cupomDao = new cupomDao($conn,$BASE_URL);
    $carrinhoDao = new carrinhoDao($conn,$BASE_URL);
    $carrinho = $carrinhoDao->getCarrinho();
    $contador = 0;//Variavel que irá servir para percorrer arrays mais a frente no código
    $precoTotal = 0;
    $quantidadeArray = json_decode($_COOKIE["quantidade"], true); // Decodifica o JSON em array associativo

    date_default_timezone_set('America/Sao_Paulo');
    $agora = date('Y-m-d\TH:i:s'); //Variavel que guar a data e hora atual

    $porcentagem = 0;
    
    $cupom = $_SESSION['cupom'];

    if($cupom !== null && $cupom !== ""){
        $cupom_nome = $_SESSION["cupom"];
        $cupom = $cupomDao->getCupomPorNome($cupom_nome);
        $porcentagem = $cupom->getPorcentagemCupom();
    }else{
        $cupom = $cupomDao->getCupomPorCodigo(1);
    }

    foreach ($carrinho as $produto) {
        $brinquedo = $prodDAO->pesquisarPorCodigo($produto);
        $precoTotal += $brinquedo->getPrecoBrinq()*$quantidadeArray[$contador];
        $contador++;
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../imagens/Logo/LogoAba32x32.png" type="image/x-icon">
    <title>Criatil - Pagamento</title>
    <link rel="stylesheet" href="../css/Pagamento.css">
    <script src="../js/Pagamento.js" defer></script>
</head>
<body>
<?php include("header.php") ?>
    <div class="main-container">
        <div class="secaoPagamento">
            <h2>Selecione um método de pagamento</h2>
            <div class="opcoesPagamento">
                
                <div class="option" id="credito" onclick="selectPayment('Credito');mudarForma('Credito')">
                    <img src="../imagens/pagamento/Cartão-Visa-Gold.webp" alt="Crédito">
                    <p>Crédito</p>
                </div>
                <div class="option" id="debito" onclick="selectPayment('Debito');mudarForma('Debito')">
                    <img src="../imagens/pagamento/debito.png" alt="Débito">
                    <p>Débito</p>
                </div>
                <div class="option" id="boleto" onclick="selectPayment('Boleto');mudarForma('Boleto')">
                    <img src="../imagens/pagamento/boleto.png" alt="Boleto">
                    <p>Boleto</p>
                </div>
                <div class="option" id="pix" onclick="selectPayment('Pix');mudarForma('Pix')"> 
                    <img src="../imagens/pagamento/logo-pix.png" alt="Pix">
                    <p>Pix</p>
                </div>
            </div>
        </div>
        <div class="resumo">

        <div class="sumario">
            <h3 class="resumo">Resumo</h3>
            <p>Subtotal: R$<?php echo(number_format($precoTotal, 2, ',', '.')) ?></p>            <p>Desconto: <?php print_r($porcentagem); ?>%</p>
            <p>Total: R$<?php 
            $total =($precoTotal - ($precoTotal*($porcentagem/100)));
            echo(number_format($total, 2, ',', '.')) ?></p>
            <p id="pagamentoSelecionado">Pagamento:</p>
            <button id="back-button">Voltar</button>
            <form method="POST" action="../controller/compraProccess.php">
            <input type="hidden" name="formaPagamento" value="vazio" id="forma"> <!--Input que armazenará a tipo de pagamento-->
            <input type="hidden" name="precoTotal" value=<?php print_r($total) ?>> 
            <input type="hidden" name="statusPedido" value="Finalizado"> 
            <input type="hidden" name="cupom" value="<?php
            if(isset($_SESSION["cupom"])){
                print_r($cupom->getCodigoCupom());
                }else{
                print_r(1);
                }
            ?>" id="cupom"> 
            <input type="hidden" name="dataPedido" value=<?php print_r($agora) ?>> 
            <input type="hidden" name="codigoUsu" value=<?php print_r($usuarioData->getCodigo()) ?>> 
            <button id="continue-button">Continuar para o pagamento</button>
            </form>
        </div>

        <div class="cupom" id="cupom">
            <form id="form_cupom" action="../controller/cupomProccess.php" method="POST">
            <h4 class="cupom">Insira seu cupom</h4>
            <input type="text" class="cupom" name="cupomCliente" placeholder="Insira o cupom promocional" id="CupomCliente" value="">
            <button class="cupom">Confirmar</button>
            </form>
        </div>
        </div>
    </div>
    <script src="../js/cupom.js"></script>

<?php include("footer.php") ?>
</body>
</html>