<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- fonte roboto -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> <!-- css do bootstrap para ícone de mostrar senha -->
    <link rel="stylesheet" href="../css/cadastro.css"> <!-- css do cadastro -->
    <script src="../js/cadastro.js"></script>
    <title>Criatil - Cadastro</title>
</head>
<body>
    <?php include("../html/header.php")?>
    <div class="cadastrinho">
        <img src="../imagens/Logo/criatillogo2.png">


            <form class="form-login" method="POST" action="../controller/cadastroProcess.php">
           
            <div class="input-container">
                <input type="text"  id="nome" name="Nome_Usu" placeholder="Nome completo" class="input-login">
                </div>

                <div class="input-container">  
                <input type="date" id="nascimento" name="Nasc_Usu" placeholder="Data de nascimento" class="input-login">
                </div>

                <div class="input-container">
                <input type="email" id="email" name="Email_Usu" placeholder="E-mail" class="input-login">
                </div>
                
                <div class="input-container">
                    <input type="password" id="senha" name="Senha_Usu" placeholder="Senha" class="input-login">
                    <i class="bi bi-eye" id="botao-senha" onclick="mostrarSenha('senha', 'botao-senha')"></i>
                </div>

                <div class="input-container">
                    <input type="password" id="cof_senha" name="Senha_Usu_Confirm" placeholder="Confirmar senha" class="input-login">
                    <i class="bi bi-eye" id="botao-conf-senha" onclick="mostrarSenha('cof_senha', 'botao-conf-senha')"></i>
                </div>

                <div class="input-container">
                <input type="text" id="celular" name="Celular_Usu" placeholder="Digite o celular" class="input-login">
                </div>

                <input type="hidden" id="tipo" name="Tipo_Usu" class="input-login" value="Cliente">
                <input type="hidden" name="Tipo" value="Cadastro">
                <input type="hidden" name="Imagem" value="vazio">

                <button type="submit" class="botao-entrar">Cadastrar</button>
                <a href="login.php" class="login">Já tem uma conta?</a>
            </form>
        </div>
</body>
</html>