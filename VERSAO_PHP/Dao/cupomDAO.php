<?php
require_once("../models/cupom.php");
require_once("../models/message.php");

class cupomDao {   
    private $conexao;
    private $url;
    private $message;


    public function __construct(PDO $conexao, $url){
        $this->conexao = $conexao;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildCupom($data){

        $cupom = new Cupom();
        $cupom->setCodigoCupom($data["Codigo_Cupom"]);
        $cupom->setNomeCupom($data["Nome_Cupom"]);
        $cupom->setStatusCupom($data["Status_Cupom"]);
        $cupom->setPorcentagemCupom($data["Porcentagem_Cupom"]);

        return $cupom;
    }

    public function getCupomPorNome($nome_cupom){
        $stmt = $this->conexao->prepare("SELECT * FROM cupom WHERE Nome_Cupom = :nome_cupom");
        $stmt->bindParam(":nome_cupom", $nome_cupom);
        $stmt->execute();

        if($stmt->rowCount() > 0){ //Ve se o número de linhas retornada é maior que zero, basicamente vendo se retornou algo do banco
            $data = $stmt->fetch(PDO::FETCH_ASSOC); // Usa fetch para obter apenas o primeiro resultado
            $cupom = $this->buildCupom($data); // Constrói o objeto Cupom
            return $cupom; // Retorna o objeto Cupom
        }else{
            return false;
        }
}

public function getCupomPorCodigo($codigo_cupom){
    $stmt = $this->conexao->prepare("SELECT * FROM cupom WHERE Codigo_Cupom = :codigo_cupom");
    $stmt->bindParam(":codigo_cupom",$codigo_cupom_cupom);
    $stmt->execute();

    if($stmt->rowCount() > 0){ //Ve se o número de linhas retornada é maior que zero, basicamente vendo se retornou algo do banco
        $data = $stmt->fetch();
        $cupom = $this->buildCupom($data);
    
        return $cupom;
    }else{
        return false;
    }
}

}
?>