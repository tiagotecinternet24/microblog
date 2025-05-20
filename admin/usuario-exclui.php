<?php
require_once "../vendor/autoload.php";

use Microblog\Helpers\Utils;
use Microblog\Services\UsuarioServico;

$usuarioServico = new UsuarioServico();

$id = Utils::sanitizar($_GET['id'], 'inteiro');
Utils::verificarId($id);

$usuarioServico->excluir($id);

header("location:usuarios.php");
exit;