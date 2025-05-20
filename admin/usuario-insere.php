<?php
require_once "../vendor/autoload.php";

use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Usuario;
use Microblog\Services\UsuarioServico;


$mensagemErro = "";
$usuarioServico = new UsuarioServico();

if (isset($_POST['inserir'])) {
	try {
		$nome = Utils::sanitizar($_POST["nome"]);
		Validacoes::validarNome($nome);

		$email = Utils::sanitizar($_POST["email"], 'email');
		Validacoes::validarEmail($email);

		$senhaBruta = $_POST["senha"];
		Validacoes::validarSenha($senhaBruta);
		$senha = Utils::codificarSenha($senhaBruta);

		$tipoStr = $_POST["tipo"];
		Validacoes::validarTipo($tipoStr);
		$tipo = TipoUsuario::from($tipoStr);

		$usuario = new Usuario($nome, $email, $senha, $tipo);
		$usuarioServico->inserir($usuario);

		header("location:usuarios.php");
		exit;
	} catch (Throwable $e) {
		$mensagemErro = $e->getMessage();
	} catch (Throwable $e) {
		// Captura erros inesperados 
		$mensagemErro = "Erro inesperado ao inserir usuário.";
		Utils::registrarLog($e);
	}
}

require_once "../includes/cabecalho-admin.php";
?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">

		<h2 class="text-center">
			Inserir novo usuário
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

			<div class="mb-3">
				<label class="form-label" for="email">E-mail:</label>
				<input class="form-control" type="email" id="email" name="email">
			</div>

			<div class="mb-3">
				<label class="form-label" for="senha">Senha:</label>
				<input class="form-control" type="password" id="senha" name="senha">
			</div>

			<div class="mb-3">
				<label class="form-label" for="tipo">Tipo (editor é o padrão):</label>
				<select class="form-select" name="tipo" id="tipo">
					<option value=""></option>
					<option value="editor" selected>Editor</option>
					<option value="admin">Administrador</option>
				</select>
			</div>

			<button class="btn btn-primary" id="inserir" name="inserir"><i class="bi bi-save"></i> Inserir</button>
		</form>

	</article>
</div>


<?php
require_once "../includes/rodape-admin.php";
?>