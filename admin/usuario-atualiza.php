<?php
require_once "../vendor/autoload.php";

use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Usuario;
use Microblog\Services\UsuarioServico;


$mensagemErro = "";
$usuarioServico = new UsuarioServico();

$id = Utils::sanitizar($_GET["id"], 'inteiro');
$dados = $usuarioServico->buscarPorId($id);

if(empty($dados)){
    Utils::alertaErro("Usuário não encontrado.");
    header("location:noticias.php");
    exit;
}

if (isset($_POST["atualizar"])) {
	try {
		$nome = Utils::sanitizar($_POST["nome"]);
		Validacoes::validarNome($nome);

		$email = Utils::sanitizar($_POST["email"], 'email');
		Validacoes::validarEmail($email);

		$senhaBruta = $_POST["senha"];

		// Se a senha não foi preenchida, mantém a senha atual. 
		// Se a senha foi preenchida, verifica se a senha é a mesma e a mantem. Senão, codifica a nova.
		$senha = empty($senhaBruta) ? $dados["senha"] : Utils::verificarSenha($senhaBruta, $dados["senha"]);

		$tipoStr = $_POST["tipo"];
		Validacoes::validarTipo($tipoStr);
		$tipo = TipoUsuario::from($tipoStr);

		$usuario = new Usuario($nome, $email, $senha, $tipo, $id);
		$usuarioServico->atualizar($usuario);
		header("location:usuarios.php");
		exit;
	} catch (Throwable $e) {
		$mensagemErro = $e->getMessage();
	} catch (Throwable $e) {
		// Captura erros inesperados 
		$mensagemErro = "Erro inesperado ao atualizar usuário.";
		Utils::registrarLog($e);
	}
}

require_once "../includes/cabecalho-admin.php";
?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">

		<h2 class="text-center">
			Atualizar dados do usuário
		</h2>

		<?php if (!empty($mensagemErro)) : ?>
			<div class="alert alert-danger text-center" role="alert">
				<?= $mensagemErro ?>
			</div>
		<?php endif; ?>

		<form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar">
			<input type="hidden" name="id" value="<?= $dados["id"] ?>">

			<div class="mb-3">
				<label class="form-label" for="nome">Nome:</label>
				<input class="form-control" type="text" id="nome" name="nome"  value="<?= $dados['nome'] ?>">
			</div>

			<div class="mb-3">
				<label class="form-label" for="email">E-mail:</label>
				<input class="form-control" type="email" id="email" name="email"  value="<?= $dados['email'] ?>">
			</div>

			<div class="mb-3">
				<label class="form-label" for="senha">Senha:</label>
				<input class="form-control" type="password" id="senha" name="senha" placeholder="Preencha apenas se for alterar">
			</div>

			<div class="mb-3">
				<label class="form-label" for="tipo">Tipo:</label>
				<select class="form-select" name="tipo" id="tipo" >
					<option value=""></option>

					<option <?php if ($dados['tipo'] === 'editor') echo " selected "; ?>
						value="editor">Editor</option>

					<option <?php if ($dados['tipo'] === 'admin') echo " selected "; ?>
						value="admin">Administrador</option>

				</select>
			</div>

			<button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
		</form>

	</article>
</div>


<?php
require_once "../includes/rodape-admin.php";
?>