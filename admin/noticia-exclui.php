<?php
require_once "../vendor/autoload.php";

use Microblog\Auth\ControleDeAcesso;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

ControleDeAcesso::exigirLogin();

$idNoticia = Utils::sanitizar($_GET["id"], "inteiro");
Utils::verificarId($idNoticia);

// Configurar após programar Controle de Acesso
$idUsuario = $_SESSION['id'];
$tipoUsuario = TipoUsuario::from($_SESSION['tipo']);

$noticiaServico = new NoticiaServico();
$noticiaServico->excluir($idNoticia, $tipoUsuario, $idUsuario);

header("location:noticias.php");
exit;