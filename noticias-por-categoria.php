<?php
require_once "vendor/autoload.php";

use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

Utils::verificarId($_GET["id"] ?? null);

$noticiaServico = new NoticiaServico();
$dados = $noticiaServico->listarPorCategoria($_GET["id"]);

require_once "includes/cabecalho.php";
?>


<div class="row my-1 mx-md-n1">

    <article class="col-12">
        <?php if( count($dados) > 0 ){ ?>
        <h2 class="text-center">
            Notícias sobre <span class="badge bg-primary">
                <?=$dados[0]["categoria"]?>
            </span> 
        </h2>
        <?php } else { ?>
            <h2 class="alert alert-warning text-center">
                Não há notícias desta categoria</h2>
        <?php } ?>
        
        <div class="row my-1">
            <div class="col-12 px-md-1">
                <div class="list-group">
                <?php foreach($dados as $itemNoticia) { ?>
                    <a href="noticia.php?id=<?=$itemNoticia['id']?>" class="list-group-item list-group-item-action">
                        <h3 class="fs-6"><?=$itemNoticia['titulo']?></h3>
                        <p><time><?=Utils::formataData($itemNoticia['data'])?></time> 
                        - <?=$itemNoticia['autor']?></p>
                        <p><?=$itemNoticia['resumo']?></p>
                    </a>
                <?php } ?>
                </div>
            </div>
        </div>


    </article>
    

</div>        
        

<?php 
require_once "includes/todas.php";
require_once "includes/rodape.php";
?>

