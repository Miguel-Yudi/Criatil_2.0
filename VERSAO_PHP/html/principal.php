<?php
require_once("../controller/conexao.php");
require_once("../controller/global.php");

$stmt = $conn->prepare("SELECT * FROM brinquedo WHERE Status <> 1");
$stmt->execute();
$brinquedos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// coloca os dados da tabela em um vetor

$stmt_recentes = $conn->prepare("SELECT * FROM brinquedo WHERE Status <> 1 ORDER BY Codigo_Brinq DESC LIMIT 6");
$stmt_recentes->execute();
$brinquedos_recentes = $stmt_recentes->fetchAll(PDO::FETCH_ASSOC);

$stmt_populares1 = $conn->prepare("SELECT Codigo_Brinq, COUNT(*) AS quantidade_vendida
FROM brinqvendido GROUP BY Codigo_Brinq ORDER BY quantidade_vendida DESC LIMIT 6");
$stmt_populares1->execute();
$populares1 = $stmt_populares1->fetchAll(PDO::FETCH_ASSOC);
$brinquedos_populares = []; // inicializa vetor

foreach($populares1 as $popular) {
   $stmt_populares2 = $conn->prepare("SELECT * FROM brinquedo WHERE Codigo_Brinq = :codigo
    AND Status <> 1 ORDER BY Codigo_Brinq"); // faz o select dos brinquedos usando o código retornado do $stmt_populares1
    $stmt_populares2->bindParam(":codigo", $popular['Codigo_Brinq']);
    $stmt_populares2->execute();
    $resultados = $stmt_populares2->fetchAll(PDO::FETCH_ASSOC); 
    $brinquedos_populares = array_merge($brinquedos_populares, $resultados);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../imagens/Logo/LogoAba32x32.png" type="image/x-icon">
  <title>Criatil - Home</title>

  <link rel="stylesheet" href="../css/card.css">

  <!--CSS dos carrosseis e da pagina respectivamente-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>

<body>

  <!--Inicio Carrossel-->
  <section class="slider_carrossel">  


    <!-- Swiper -->
    <div class="swiper carrossel" id="carrossel">
    <?php include("header.php")?>
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img src="../imagens/Carrossel/imagem1.png" alt="Barbie carrossel" id="grande" class="imagem_carrossel1">
        </div>

        <div class="swiper-slide">
          <img src="../imagens/Carrossel/imagem2.png" alt="Hotwhells carrossel" id="grande" class="imagem_carrossel2">
        </div>

        <div class="swiper-slide">
          <img src="../imagens/Carrossel/imagem3.png" alt="Squishmallows carrossel" id="grande" class="imagem_carrossel3">
        </div>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </section>

  <!--Fim carrossel-->

  <!--Classe container para responsividade-->
  <div class="container">
    
<!--Inicio product slider 1-->
<h1 class="titulo">Novidades</h1>
    <div class="slider">
      <div class="swiper-button-prev seta prev-product"></div>
      <div class="swiper product">
        <div class="swiper-wrapper">
          <?php
            foreach ($brinquedos_recentes as $brinquedo) {
              // seleciona a img do brinquedo atual
              $stmt = $conn->query("SELECT Imagem FROM imagem WHERE Codigo_Brinq = " . $brinquedo['Codigo_Brinq'] . " ORDER BY Num_Imagem LIMIT 1");
              $imagem = $stmt->fetch(PDO::FETCH_ASSOC);

              $stmt2 = $conn->query("SELECT Imagem_Selo FROM selo WHERE Codigo_Selo = " . $brinquedo['Codigo_Selo']);
              $selo = $stmt2->fetch(PDO::FETCH_ASSOC);
          ?>
          <!--Div que contem os elementos do card-->
          <div class="card swiper-slide">
            <div class="imagem_card">
              <img src=<?php echo("../imagens/Produtos/".$imagem['Imagem'].".jpeg"); ?> class="foto_card">
              <?php  if ($selo['Imagem_Selo'] != null) { ?>
                <img src="<?php echo "../imagens/Selo/".$selo['Imagem_Selo'].".jpeg"; ?>" class="selo">
                <?php } ?>
            </div>
            <h4 class="titulo_card"><?php echo $brinquedo['Nome_Brinq']; ?></h4>
            <h3 class="preco">R$<?php echo number_format($brinquedo['Preco_Brinq'], 2, ',', '.'); ?></h3>
            <a href=<?php print_r("produto.php?codigo=" . $brinquedo['Codigo_Brinq'] )?>>
              <button class="card">
              <img src="../imagens/Icons/carrinho.png" alt="Carrinho" class="botao_card">
              <p class="botao_card"> Comprar!</p>
              </button>
            </a>
            </div>
          <!--Fim card-->
          <?php } ?>
        </div>
        <div class="swiper-pagination"></div>
      </div>
      <div class="swiper-button-next seta next-product"></div>
    </div>
    <!--Fim product slider 1-->
    <!--Inicio imagens promocionais-->
    <div class="promocionais">
      <img src="../imagens/Promocional/tamagotchi.png" alt="Tamagotchi" class="imagem_promocional img1">
      <img src="../imagens/Promocional/curlimals.png" alt="Curlimals" class="imagem_promocional img2">
      <img src="../imagens/Promocional/pkxd.png" alt="PKXD" class="imagem_promocional img3">
    </div>
    <!--FIm imagens promocionais-->

    <!--Inicio product slider 2-->
    <h1 class="titulo">Populares</h1>
    <div class="slider">
      <div class="swiper-button-prev seta prev-product"></div>
      <div class="swiper product">
        <div class="swiper-wrapper">
          <?php
            // Loop através dos brinquedos e exibição na página
            foreach ($brinquedos_populares as $brinquedo) {
              // Seleciona as imagens do brinquedo atual
              $stmt = $conn->query("SELECT Imagem FROM imagem WHERE Codigo_Brinq = " . $brinquedo['Codigo_Brinq'] . " ORDER BY Num_Imagem LIMIT 1");
              $imagem = $stmt->fetch(PDO::FETCH_ASSOC);

              $stmt2 = $conn->query("SELECT Imagem_Selo FROM selo WHERE Codigo_Selo = " . $brinquedo['Codigo_Selo']);
              $selo = $stmt2->fetch(PDO::FETCH_ASSOC);
          ?>
          <!--Div que contem os elementos do card-->
          <div class="card swiper-slide">
            <div class="imagem_card">
            <img src=<?php echo("../imagens/Produtos/".$imagem['Imagem'].".jpeg"); ?> class="foto_card">
              <?php  if ($selo['Imagem_Selo'] != null) { ?>
                <img src="<?php echo "../imagens/Selo/".$selo['Imagem_Selo'].".jpeg"; ?>" class="selo">
                <?php } ?>
            </div>
            <h4 class="titulo_card"><?php echo $brinquedo['Nome_Brinq']; ?></h4>
            <h3 class="preco">R$<?php echo number_format($brinquedo['Preco_Brinq'], 2, ',', '.'); ?></h3>
            <a href=<?php print_r("produto.php?codigo=" . $brinquedo['Codigo_Brinq'] )?>>
              <button class="card">
              <img src="../imagens/Icons/carrinho.png" alt="Carrinho" class="botao_card">
              <p class="botao_card"> Comprar!</p>
              </button>
            </a>
            </div>
          <!--Fim card-->
          <?php } ?>
        </div>
        <div class="swiper-pagination"></div>
      </div>
      <div class="swiper-button-next seta next-product"></div>
    </div>
    <!--Fim product slider 2-->

  </div>
  <!--Fim Container-->

  


  <!--JS dos carrosseis e da pagina respectivamente-->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script src="../js/index.js"> </script>

<?php include("footer.php") ?>
</body>

<head>
<link rel="stylesheet" href="../css/index.css">
</head>
</html>