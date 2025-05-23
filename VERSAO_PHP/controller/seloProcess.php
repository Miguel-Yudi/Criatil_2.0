<?php
require_once("global.php");
require_once("conexao.php");
require_once("../Dao/seloDAO.php");
require_once("../models/selo.php");
require_once("../models/message.php");

$message = new Message($BASE_URL); //Criação de uma objeto de mansagem

$seloDao = new SeloDAO($conn,$BASE_URL);

$tipo = filter_input(INPUT_POST, "Tipo");

if($tipo === "Inserir") {
$nome = filter_input(INPUT_POST, 'Nome');

$selo = new Selo();

$selo->setNomeSelo($nome);

if(isset($_FILES["Imagem"]) && !empty($_FILES["Imagem"]["tmp_name"])){
    $image = $_FILES["Imagem"];
    $tiposImagem = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpeg","image/jpg"];

    if(in_array($image["type"],$tiposImagem)){
        if(in_array($image["type"],$jpgArray)){
            $imagefile = imagecreatefromjpeg($image["tmp_name"]);
        }else{//Imagem é png
            $imagefile = imagecreatefrompng($image["tmp_name"]);
        }

        $imageNome = $selo->imageGenerateName();

        // Salva a imagem como JPEG no diretório especificado
        if (imagejpeg($imagefile, "../imagens/Selo/" . $imageNome .".jpeg", 100)) {
            // Define a imagem no usuário
            $selo->setImagemSelo($imageNome);
        } else {
            $message->setMessage("Erro!", "Erro ao salvar a imagem","error","back");
        }

}else{
    $message->setMessage("Erro!","Tipo de imagem inválido, insira um arquivo .PNG ou .JPG","error","back");
}
}else{
    echo ("erro");
}
    if ($seloDao->inserirSelo($selo)) {
        $message->setMessage("Selo adicionado", "O selo foi adicionado ao sistema", "success", "back");
    } else {
        $message->setMessage("Erro", "Houve um erro ao inserir o selo", "error", "back");
    }
}elseif ($tipo === "Atualizar") {
    $codigoSelo = filter_input(INPUT_POST, 'Codigo');
    $nome = filter_input(INPUT_POST, 'Nome');

    $selo = new Selo();
if (empty($nome)) {
    $message->setMessage("Erro!", "O nome do selo não pode ser vazio", "error", "back");
    exit();
} else {
    $selo->setCodigoSelo($codigoSelo);
    $selo->setNomeSelo($nome);
}

    $imagemAtualizada = false;

    if (isset($_FILES["Imagem"]) && !empty($_FILES["Imagem"]["tmp_name"])) {
        $image = $_FILES["Imagem"];
        $tiposImagem = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        if (in_array($image["type"], $tiposImagem)) {
            if (in_array($image["type"], $jpgArray)) {
                $imagefile = imagecreatefromjpeg($image["tmp_name"]);
            } else { // Imagem é png
                $imagefile = imagecreatefrompng($image["tmp_name"]);
            }

            if ($imagefile === false) {
                $message->setMessage("Erro!", "Erro ao criar a imagem a partir do arquivo enviado.", "error", "back");
                exit;
            }

            // Salva a imagem como JPEG no diretório especificado 
            $imageNome = pathinfo($image["name"], PATHINFO_FILENAME);
            if (imagejpeg($imagefile, "../imagens/Selo/" . $imageNome . ".jpeg", 100)) {
                $selo->setImagemSelo($imageNome);
                $imagemAtualizada = true;
            } else {
                $message->setMessage("Erro!", "Erro ao salvar a imagem do selo", "error", "back");
            }
        } else {
            $message->setMessage("Erro!", "Tipo de imagem inválido, insira um arquivo .PNG ou .JPG", "error", "back");
        }
    }

    if ($seloDao->atualizarSelo($selo)) {
        $message->setMessage("Sucesso", "O selo foi atualizado com sucesso.", "success", "back");
    } else {
        $message->setMessage("Erro", "Erro ao atualizar o selo.", "error", "back");
    }
}
else{
    $message->setMessage("Erro!", "Tipo não encontrado.", "error", "back");
}
?>