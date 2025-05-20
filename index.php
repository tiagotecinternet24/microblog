<?php
require_once "vendor/autoload.php";
use Microblog\Services\NoticiaServico;

$noticiaServico = new NoticiaServico();
$destaques = $noticiaServico->listarDestaques();

require_once "includes/cabecalho.php";
?>

<div class="row my-1 mx-md-n1">

        <!-- INÍCIO Card -->
    <?php foreach( $destaques as $destaque ){ ?>
		<div class="col-md-6 my-1 px-md-1">
            <article class="card shadow-sm h-100">
                <a href="noticia.php?id=<?=$destaque["id"]?>" class="card-link">
                    <img src="images/<?=$destaque["imagem"]?>" class="card-img-top" alt="">
                    <div class="card-body">
                        <h3 class="fs-4 card-title"><?=$destaque["titulo"]?></h3>
                        <p class="card-text"><?=$destaque["resumo"]?></p>
                    </div>
                </a>
            </article>
		</div>
    <?php } ?>
		<!-- FIM Card -->

</div>        
        
            
<?php 
require_once "includes/todas.php";
require_once "includes/rodape.php";
?>

