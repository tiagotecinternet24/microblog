<?php
require_once "vendor/autoload.php";

use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;


$noticiaServico = new NoticiaServico();

Utils::verificarId($_GET["id"] ?? null);

$dados = $noticiaServico->listarDetalhes($_GET["id"]); 

require_once "includes/cabecalho.php";
?>


<div class="row my-1 mx-md-n1">

    <article class="col-12">
        <h2> <?=$dados['titulo']?> </h2>
        <p class="font-weight-light">
            <time><?=Utils::formataData($dados['data'])?></time> - 
            <span><?=$dados['autor']?></span>
        </p>
        <img src="images/<?=$dados['imagem']?>" alt="" class="float-start pe-2 img-fluid">
        <p class="ajusta-texto"><?=$dados['texto']?></p>
    </article>
    

</div>        
                  

<?php 
require_once "includes/todas.php";
require_once "includes/rodape.php";
?>

