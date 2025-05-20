<?php
require_once "../vendor/autoload.php";

use Microblog\Enums\Destaque;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Noticia;
use Microblog\Services\CategoriaServico;
use Microblog\Services\NoticiaServico;

$mensagemErro = "";

$categoriaServico = new CategoriaServico();
$listaDeCategorias = $categoriaServico->listarTodos();

if (isset($_POST["inserir"])) {
    $titulo = Utils::sanitizar($_POST["titulo"]);
    $texto = Utils::sanitizar($_POST["texto"]);
    $resumo = Utils::sanitizar($_POST["resumo"]);
    $destaque = Utils::sanitizar($_POST["destaque"]);
    $categoria = Utils::sanitizar($_POST["categoria"], "inteiro");

    // Captura o arquivo de imagem
    $arquivoDeImagem = Utils::sanitizar($_FILES["imagem"], "arquivo");

    try {
        Validacoes::validarCategoria($categoria);
        Validacoes::validarTitulo($titulo);
        Validacoes::validarTexto($texto);
        Validacoes::validarResumo($resumo);
        Validacoes::validarDestaque($destaque);

        // Envia o arquivo de imagem para o servidor
        Utils::upload($arquivoDeImagem);

        // Captura o nome do arquivo
        $nomeDaImagem = $arquivoDeImagem["name"];
        $destaqueEnum = Destaque::from($destaque);

        $noticia = new Noticia($titulo, $texto, $resumo, $nomeDaImagem, $destaqueEnum, $idUsuario, $categoria);

        $noticiaServico = new NoticiaServico();
        $noticiaServico->inserir($noticia);

        header("location:noticias.php");
        exit;
    } catch (Throwable $e) {
        $mensagemErro = $e->getMessage();
        Utils::registrarLog($e);
    }
}


require_once "../includes/cabecalho-admin.php";
?>


<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">

        <h2 class="text-center">
            Inserir nova notícia
        </h2>

        <?php if (!empty($mensagemErro)) : ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= $mensagemErro ?>
            </div>
        <?php endif; ?>

        <!-- Para que o formulário aceite arquivos (upload), é necessário habilitar o atributo enctype -->
        <form class="mx-auto w-75" action="" method="post" id="form-inserir" name="form-inserir" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label" for="categoria">Categoria:</label>
                <select class="form-select" name="categoria" id="categoria">
                    <option value=""></option>

                    <?php foreach ($listaDeCategorias as $itemCategoria) { ?>
                        <option value="<?= $itemCategoria['id'] ?>">
                            <?= $itemCategoria['nome'] ?>
                        </option>
                    <?php } ?>

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label" for="titulo">Título:</label>
                <input class="form-control" type="text" id="titulo" name="titulo">
            </div>

            <div class="mb-3">
                <label class="form-label" for="texto">Texto:</label>
                <textarea class="form-control" name="texto" id="texto" cols="50" rows="6"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="resumo">Resumo (máximo de 300 caracteres):</label>
                <span id="maximo" class="badge bg-danger">0</span>
                <textarea class="form-control" name="resumo" id="resumo" cols="50" rows="2" maxlength="300"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="imagem" class="form-label">Selecione uma imagem:</label>
                <input class="form-control" type="file" id="imagem" name="imagem"
                    accept="image/png, image/jpeg, image/gif, image/svg+xml">
            </div>

            <div class="mb-3">
                <p>Deixar a notícia em destaque?
                    <input type="radio" class="btn-check" name="destaque" id="option1" checked autocomplete="off" value="nao">
                    <label class="btn btn-outline-danger" for="option1">Não</label>

                    <input type="radio" class="btn-check" name="destaque" id="option2" autocomplete="off" value="sim">
                    <label class="btn btn-outline-success" for="option2">Sim</label>
                </p>

            </div>

            <button class="btn btn-primary" id="inserir" name="inserir"><i class="bi bi-save"></i> Inserir</button>
        </form>

    </article>
</div>


<?php
require_once "../includes/rodape-admin.php";
?>