<?php
require_once "../vendor/autoload.php";

use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Categoria;
use Microblog\Services\CategoriaServico;

$categoriaServico = new CategoriaServico();

$id = Utils::sanitizar($_GET['id'], 'inteiro') ?? null;
Utils::verificarId($id);


$dados = $categoriaServico->buscarPorId($id);
if(empty($dados)){
    Utils::alertaErro("Categoria não encontrada.");
    header("location:noticias.php");
    exit;
}

if (isset($_POST['atualizar'])) {
	try {
		$nome = Utils::sanitizar($_POST["nome"]);
		Validacoes::validarNome($nome);
		$categoria = new Categoria($nome, $id);
		$categoriaServico->atualizar($categoria);
		header("location:categorias.php");
		exit;
	} catch (Throwable $e) {
		$mensagemErro = $e->getMessage();
	} catch (Throwable $e) {
		$mensagemErro = "Erro inesperado ao atualizar categoria.";
		Utils::registrarLog($e);
	}
}

require_once "../includes/cabecalho-admin.php";
?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">

		<h2 class="text-center">
			Atualizar dados da categoria
		</h2>

		<?php if (!empty($mensagemErro)) : ?>
			<div class="alert alert-danger text-center" role="alert">
				<?= $mensagemErro ?>
			</div>
		<?php endif; ?>

		<form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar">

			<input value="<?= $dados['id'] ?>" class="form-control" type="hidden" id="id" name="id">

			<div class="mb-3">
				<label class="form-label" for="nome">Nome:</label>
				<input value="<?= $dados['nome'] ?>" class="form-control" type="text" id="nome" name="nome">
			</div>

			<button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
		</form>

	</article>
</div>


<?php
require_once "../includes/rodape-admin.php";
?>