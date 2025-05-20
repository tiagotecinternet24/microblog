<?php
require_once "../vendor/autoload.php";

use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Categoria;
use Microblog\Services\CategoriaServico;


$categoriaServico = new CategoriaServico();

if (isset($_POST['inserir'])) {
	try {
		$nome = Utils::sanitizar($_POST["nome"]);
		Validacoes::validarNome($nome);
		$categoria = new Categoria($nome);
		$categoriaServico->inserir($categoria);
		header("location:categorias.php");
		exit;
	} catch (Throwable $e) {
		$mensagemErro = $e->getMessage();
	} catch (Throwable $e) {
		$mensagemErro = "Erro inesperado ao inserir categoria.";
		Utils::registrarLog($e);
	}
}

require_once "../includes/cabecalho-admin.php";
?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">

		<h2 class="text-center">
			Inserir nova categoria
		</h2>

		<?php if (!empty($mensagemErro)) : ?>
			<div class="alert alert-danger text-center" role="alert">
				<?= $mensagemErro ?>
			</div>
		<?php endif; ?>

		<form class="mx-auto w-75" action="" method="post" id="form-inserir" name="form-inserir">

			<div class="mb-3">
				<label class="form-label" for="nome">Nome:</label>
				<input class="form-control" type="text" id="nome" name="nome">
			</div>

			<button class="btn btn-primary" id="inserir" name="inserir"><i class="bi bi-save"></i> Inserir</button>
		</form>

	</article>
</div>


<?php
require_once "../includes/rodape-admin.php";
?>