<?php
require_once "../vendor/autoload.php";

use Microblog\Auth\ControleDeAcesso;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Usuario;
use Microblog\Services\UsuarioServico;

// Necessário chamar o exigirLogin para que esta página tenha acesso
// aos dados da sessão em andamento e do usuário logado.
ControleDeAcesso::exigirLogin();

$usuarioServico = new UsuarioServico();

// Buscando os dados do usuário logado na SESSÃO a partir do id dele
$dados = $usuarioServico->buscarPorId( $_SESSION['id'] ); 

if (isset($_POST["atualizar"])) {
	try {
		$nome = Utils::sanitizar($_POST["nome"]);
		Validacoes::validarNome($nome);

		$email = Utils::sanitizar($_POST["email"], 'email');
		Validacoes::validarEmail($email);

		$senhaBruta = $_POST["senha"];

		/* Avaliando a senha
		Se o campo de senha estiver vazio, então vamos manter a mesma
		senha já existente no banco. Caso contrário, vamos repassar a senha
		digitada para a verificação. */
		$senha = empty($senhaBruta) ? $dados["senha"] : Utils::verificarSenha($senhaBruta, $dados["senha"]);

		// O tipo de usuário não pode ser alterado pelo próprio usuário
		$tipo = TipoUsuario::from($dados["tipo"]);

		// O ID do usuário é obtido da sessão (configurar após programar Controle de Acesso)
		$id = $_SESSION['id'];

		$usuario = new Usuario($nome, $email, $senha, $tipo, $id);
		$usuarioServico->atualizar($usuario);

		// Atualizando a variável de sessão com o novo nome
		$_SESSION['nome'] = $nome;

		header("location:index.php?perfil_atualizado");
		exit;
	} catch (Throwable $e) {
		$mensagemErro = $e->getMessage();
	} catch (Throwable $e) {
		$mensagemErro = "Erro inesperado ao atualizar perfil.";
		Utils::registrarLog($e);
	}
}

require_once "../includes/cabecalho-admin.php";
?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">

		<h2 class="text-center">
			Atualizar meus dados
		</h2>

		<?php if (!empty($mensagemErro)) : ?>
			<div class="alert alert-danger text-center" role="alert">
				<?= $mensagemErro ?>
			</div>
		<?php endif; ?>

		<form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar">
			<input type="hidden" name="id" value="<?= $dados["id"] ?? '' ?>">

			<div class="mb-3">
				<label class="form-label" for="nome">Nome:</label>
				<input value="<?= $dados["nome"] ?? '' ?>" class="form-control" type="text" id="nome" name="nome">
			</div>

			<div class="mb-3">
				<label class="form-label" for="email">E-mail:</label>
				<input value="<?= $dados["email"] ?? '' ?>" class="form-control" type="email" id="email" name="email">
			</div>

			<div class="mb-3">
				<label class="form-label" for="senha">Senha:</label>
				<input class="form-control" type="password" id="senha" name="senha" placeholder="Preencha apenas se for alterar">
			</div>

			<button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
		</form>

	</article>
</div>


<?php
require_once "../includes/rodape-admin.php";
?>