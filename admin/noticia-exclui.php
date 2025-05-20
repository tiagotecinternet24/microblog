<?php
require_once "../vendor/autoload.php";

use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

$idNoticia = Utils::sanitizar($_GET["id"], "inteiro");
Utils::verificarId($idNoticia);

// Configurar após programar Controle de Acesso
$idUsuario = 1;
$tipoUsuario = TipoUsuario::from('admin');


$noticiaServico = new NoticiaServico();
$noticiaServico->excluir($idNoticia, $tipoUsuario, $idUsuario);

header("location:noticias.php");
exit;