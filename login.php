<?php

use Microblog\Auth\ControleDeAcesso;
use Microblog\Helpers\Utils;
use Microblog\Helpers\Validacoes;
use Microblog\Models\Usuario;
use Microblog\Services\UsuarioServico;

require_once "vendor/autoload.php";


/* Mensagens relacionadas ao processo de login/logout */
if( isset($_GET["campos_obrigatorios"]) ){
	$feedback = "Preencha e-mail e senha!";
} elseif( isset($_GET['dados_incorretos']) ){
	$feedback = "Algo de errado não está certo!";
} elseif( isset($_GET['logout']) ){
	$feedback = "Você saiu do sistema!";
} elseif( isset($_GET['acesso_proibido']) ){
	$feedback = "Você deve logar primeiro";
}

if (isset($_POST['entrar'])) {

    $email = Utils::sanitizar($_POST['email'], 'email');
    $senha = $_POST['senha']; // não precisa sanitizar, pois será codificada/verificada

    // Verificando campos obrigatórios
    if (empty($email) || empty($senha)) {
        header("Location: login.php?campos_obrigatorios");
        exit;
    }

    /* Processo de busca do usuário pelo e-mail e login na área administrativa */
    try {
        // Buscar o usuário através do e-mail informado
        $usuarioServico = new UsuarioServico();
        $usuario = $usuarioServico->buscarPorEmail($email);
        
        // Se não existir usuário com o e-mail informado,
        // mantem na página login e apresenta mensagem
        if(!$usuario){
            header("location:login.php?dados_incorretos");
            exit;
        }

        // Se o usuário foi encontrado, verifica a senha digitada
        if($usuario && password_verify($senha, $usuario['senha'])){
            // Estando tudo ok (usuario e senha), passamos 
            // para o método login os dados da pessoa que está logando
            ControleDeAcesso::login($usuario['id'], $usuario['nome'], $usuario['tipo']);
            header("location:admin/index.php");
            exit;
        } else {
            // Caso contrário (senha errada), mantenha a pessoa em login
            header("location:login.php?dados_incorretos");
            exit;
        }

    } catch (Throwable $erro) {
        Utils::registrarLog($erro);
        header("location:login.php?erro");
        exit;
    }
    
    
}
require_once "includes/cabecalho.php";
?>

<div class="row">
    <div class="bg-white rounded shadow col-12 my-1 py-4">
        <h2 class="text-center fw-light">Acesso à área administrativa</h2>

        <form action="" method="post" id="form-login" name="form-login" class="mx-auto w-50" autocomplete="off">
			<?php if( isset($feedback) ): ?>
                <p class="my-2 alert alert-warning text-center">
                    <?= $feedback ?>
                </p>
            <?php endif; ?>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input autofocus class="form-control" type="email" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input class="form-control" type="password" id="senha" name="senha">
            </div>

            <button class="btn btn-primary btn-lg" name="entrar" type="submit">Entrar</button>
        </form>
    </div>
</div>

<?php 
require_once "includes/todas.php";
require_once "includes/rodape.php";
?>
