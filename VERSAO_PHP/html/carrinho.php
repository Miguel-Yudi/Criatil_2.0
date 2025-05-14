<?php
require_once("../controller/global.php");
require_once("../controller/conexao.php");
require_once("../Dao/carrinhoDAO.php");
require_once("../models/message.php");
require_once("../Dao/produtoDAO.php");
require_once("../models/brinquedo.php");

ini_set('display_errors','Off'); ini_set('error_reporting', E_ALL ); define('WP_DEBUG', false); define('WP_DEBUG_DISPLAY', false);

    $prodDAO = new ProdutoDAO($conn,$BASE_URL);

    $carrinhoDao = new carrinhoDao($conn,$BASE_URL);
    $carrinho = $carrinhoDao->getCarrinho();
    $contador = 0;//Variavel que irá servir para percorrer arrays mais a frente no código
    $precoTotal = 0;
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- link da fonte pro css saber que fonte usar -->
        <link rel="stylesheet" href="../css/carrinho.css">
        <script src="../js/carrinhoResumo.js" defer></script>
        <script src="../js/carrinhoQuantidade.js" defer></script>
        <link rel="shortcut icon" href="../imagens/Logo/LogoAba32x32.png" type="image/x-icon">
        <title>Criatil - Carrinho</title>
    </head>
    <body>
    <?php include("header.php") ?>
    <!-- página do carrinho -->
    <div class="container-pag">
        <div class="container-car">
                <div id="carrinho-legenda">
                            <p class="legenda-img">Foto</p>
                            <p class="legenda-nome">Nome</p>
                            <p class="legenda-qntd">Quantidade</p>
                            <p class="legenda-valor">Valor</p>
                            <p class="legenda-valor">Excluir</p>
                        </div>
                <div id="carrinho" class="carrinhobox">
                <?php 
                if(empty($carrinho)){
                ?>
                <div class="produto" style="cursor: not-allowed;">
                <div class="produto-info" style="color: gray;">
                    <img src="../imagens/Produtos/Miku/Imagem1.png" class="produto-imagem" style="filter: grayscale(100%);">
                    <div class="produto-nome">Nenhum produto adicionado</div>
                    <div class="produto-quantidade">
                        <button class="quantidade-botao" name="botao_menos" style="color: gray; cursor: not-allowed;">-</button>
                        <span class="quantidade-numero" style="display: flex;">0</span>
                        <button class="quantidade-botao"  name="botao_mais" style="color: gray; cursor: not-allowed;">+</button>
                    </div>
                    <div class="produto-valor">
                        <div class="valor_flex">
                            <div class="valor-unidade">R$0,00/un.</div>
                            <div class="valor-total">R$0,00 total</div>
                        </div>
                    </div>
                    <div class="excluir-item">
                        <button class="excluir">
                            <img src="../imagens/Icons/x.png" alt="Excluir item" class="excluir" style="cursor: not-allowed;">
                        </button>
                    </div>
                </div>
                </div>
                <?php 
                }
                ?>
                        <?php
                            foreach ($carrinho as $produto) {
                                $brinquedo = $prodDAO->pesquisarPorCodigo($produto);
                                $imagens[] = $prodDAO->pesquisarImagemPorCodigoBrinq($brinquedo->getCodigoBrinq());
                                $imagem = $imagens[$contador];
                                ?>
                            <div class="produto">
                            <?php 
                                $quantidade = 1;
                                ?>
                            <div class="produto-info">
                                <img src=<?php echo("../imagens/Produtos/".$imagem[0]->getImagem().".jpeg") ?> class="produto-imagem">
                                <div class="produto-nome"><?php echo($brinquedo->getNomeBrinq()); ?></div>
                                <div class="produto-quantidade">

                                <form method="POST" action="../controller/carrinhoProccess.php">
                                <input type="hidden" name="Contador" value=<?php echo($contador)?>>
                                <input type="hidden" name="Operacao" value="Diminuir">
                                <button class="quantidade-botao" name="botao_menos">-</button>
                                </form>

                                    <span class="quantidade-numero" style="display: flex;"><?php
                                    $quantidadeArray = json_decode($_COOKIE["quantidade"], true); // Decodifica o JSON em array associativo
            
                                    echo($quantidadeArray[$contador]); 
                                    ?></span>
                                    
                                    <form method="POST" action="../controller/carrinhoProccess.php">
                                    <input type="hidden" name="Contador" value=<?php echo($contador)?>>
                                    <input type="hidden" name="Operacao" value="AdicionarQuant">
                                    <button class="quantidade-botao"  name="botao_mais">+</button>
                                    </form>
                                </div>
                                <div class="produto-valor">
                                    <div class="valor_flex">
                                        <div class="valor-unidade">R$<?php echo(number_format($brinquedo->getPrecoBrinq(), 2, ',', '.')); ?>/un.</div>
                                        <div class="valor-total">R$<?php echo(number_format(($brinquedo->getPrecoBrinq()*$quantidadeArray[$contador]), 2, ',', '.')); ?> total</div>
                                    </div>
                                </div>
                                <div class="excluir-item">
                                <form method="POST" action="../controller/carrinhoProccess.php">
                                <input type="hidden" name="Operacao" value="Excluir">
                                <input type="hidden" name="Contador" value=<?php echo($contador)?>>
                                    <button class="excluir">
                                        <img src="../imagens/Icons/x.png" alt="Excluir item" class="excluir">
                                    </button>
                                </form>
                                </div>
                            </div>
                        </div>

                        <?php
                            $precoTotal += $brinquedo->getPrecoBrinq()*$quantidadeArray[$contador];
                            $contador++;

                            }
                        ?>

                        <p class="aviso-tamanho" style="display: none; font-size: 14px;">* Tamanho de tela não recomendado para acesso ao site.</p>
                </div>
                <div class="botao-deletar">
                    <form method="POST" action="../controller/carrinhoProccess.php">
                    <input type="hidden" name="Operacao" value="Deletar">
                    <button class="deletar">
                        <img src="../imagens/Icons/lixeira.png" alt="Lixeira" class="deletar">
                        <p class="deletar">Deletar todos os itens</p>
                    </button>
                    </form>
                </div>
            </div>

                <div id="resumo" class="resumobox">
                    <div class="conteudoresumo">
                        <h1>Resumo</h1>
                        <div class="resumopreco">
                            <div class="legendas">
                                <div class="legenda">Subtotal:</div>
                                <div class="legenda">Total:</div>
                            </div>
                            <div class="precos">
                                <div class="precos">
                                    <div class="preco" id="subtotal">R$<?php echo(number_format($precoTotal, 2, ',', '.')); ?></div>
                                    <div class="preco" id="total">R$<?php echo(number_format($precoTotal, 2, ',', '.')); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="botoes-resumo">
                        <a href="./principal.php" class="botao-continuar">Continuar Comprando</a>
                        <form method="POST" action="../controller/carrinhoProccess.php">
                        <input type="hidden" name="Operacao" value="Compra">
                        <button class="botao-pagamento">Escolher método de pagamento</button>
                        </form>
                    </div>
                </div>
        
                <button id="botao-resumo">Resumo da Compra</button>
    <!-- fim da página do carrinho -->
     </div>

     <?php include("footer.php") ?>
    </body>
    </html>