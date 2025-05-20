<?php
require_once "../vendor/autoload.php";

use Microblog\Auth\ControleDeAcesso;
use Microblog\Helpers\Utils;
use Microblog\Services\UsuarioServico;

ControleDeAcesso::exigirLogin();
ControleDeAcesso::exigirAdmin();

$usuarioServico = new UsuarioServico();

$id = Utils::sanitizar($_GET['id'], 'inteiro');
Utils::verificarId($id);

$usuarioServico->excluir($id);

header("location:usuarios.php");
exit;