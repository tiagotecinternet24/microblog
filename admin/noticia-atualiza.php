<?php
require_once "../vendor/autoload.php";

use Microblog\Enums\Destaque;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Noticia;
use Microblog\Services\CategoriaServico;
use Microblog\Services\NoticiaServico;

$idNoticia = Utils::sanitizar($_GET["id"], "inteiro");
Utils::verificarId($idNoticia);

// Configurar após programar Controle de Acesso
$idUsuario = 1;
$tipoUsuario = TipoUsuario::from('admin ou editor');


$mensagemErro = "";

$categoriaServico = new CategoriaServico();
$listaDeCategorias = $categoriaServico->listarTodos();

$noticiaServico = new NoticiaServico();
$dados = $noticiaServico->buscarPorId($idNoticia, $tipoUsuario, $idUsuario);


if (empty($dados)) {
    Utils::alertaErro("Notícia não encontrada.");
    header("location:noticias.php");
    exit;
}


if (isset($_POST["atualizar"])) {
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

        $destaqueEnum = Destaque::from($destaque);

        // Se enviou nova imagem, faz upload e substitui
        if ($arquivoDeImagem !== null) {
            Utils::upload($arquivoDeImagem);
            $nomeDaImagem = $arquivoDeImagem["name"]; // captura o nome do arquivo
        } else {
            $nomeDaImagem = $dados['imagem']; // Mantém a imagem antiga
        }

        $noticia = new Noticia($titulo, $texto, $resumo, $nomeDaImagem, $destaqueEnum, $idUsuario, $categoria, $idNoticia);

        $noticiaServico = new NoticiaServico();
        $noticiaServico->atualizar($noticia, $tipoUsuario);

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
            Atualizar dados da notícia
        </h2>

        <?php if (!empty($mensagemErro)) : ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= $mensagemErro ?>
            </div>
        <?php endif; ?>

        <form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label" for="categoria">Categoria:</label>
                <select class="form-select" name="categoria" id="categoria">
                    <option value=""></option>

                    <?php foreach ($listaDeCategorias as $itemCategoria) { ?>
                        <option
                            <?php if ($dados["categoria_id"] === $itemCategoria["id"]) echo " selected " ?>
                            value="<?= $itemCategoria['id'] ?>">
                            <?= $itemCategoria['nome'] ?>
                        </option>
                    <?php } ?>

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label" for="titulo">Título:</label>
                <input value="<?= $dados['titulo'] ?>" class="form-control" type="text" id="titulo" name="titulo">
            </div>

            <div class="mb-3">
                <label class="form-label" for="texto">Texto:</label>
                <textarea class="form-control" name="texto" id="texto" cols="50" rows="6"><?= $dados['texto'] ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="resumo">Resumo (máximo de 300 caracteres):</label>
                <span id="maximo" class="badge bg-danger">0</span>
                <textarea class="form-control" name="resumo" id="resumo" cols="50" rows="2" maxlength="300"><?= $dados['resumo'] ?></textarea>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-5">
                        <label for="imagem-existente" class="form-label">Imagem atual:</label>
                        <!-- campo somente leitura, meramente informativo -->
                        <input value="<?= $dados['imagem'] ?>" class="form-control" type="text" id="imagem-existente" name="imagem-existente" readonly disabled>
                    </div>
                    <div class="col-7 text-end">
                        <img src="../images/<?= $dados['imagem'] ?>" alt="Imagem da notícia" class="img-fluid rounded">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="imagem" class="form-label">Caso queira mudar, selecione outra imagem:</label>
                <input class="form-control" type="file" id="imagem" name="imagem" accept="image/png, image/jpeg, image/gif, image/svg+xml">
            </div>

            <div class="mb-3">
                <p>Deixar a notícia em destaque?
                    <!-- aparentemente, o id precisa ser optionX -->
                    <input
                        <?php if ($dados["destaque"] === "nao") echo " checked " ?>
                        type="radio" class="btn-check" name="destaque" id="option1" autocomplete="off" checked value="nao">
                    <label class="btn btn-outline-danger" for="option1">Não</label>

                    <input
                        <?php if ($dados["destaque"] === "sim") echo " checked " ?>
                        type="radio" class="btn-check" name="destaque" id="option2" autocomplete="off" value="sim">
                    <label class="btn btn-outline-success" for="option2">Sim</label>
                </p>
            </div>

            <button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
        </form>

    </article>
</div>


<?php
require_once "../includes/rodape-admin.php";
?>