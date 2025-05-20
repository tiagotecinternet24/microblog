<?php 
require_once "../vendor/autoload.php";

use Microblog\Helpers\Utils;
use Microblog\Services\CategoriaServico;

$categoriaServico = new CategoriaServico();

$id = Utils::sanitizar($_GET['id'], 'inteiro') ?? null;
Utils::verificarId($id);


$dados = $categoriaServico->buscarPorId($id);
$categoriaServico->excluir($id);
header("location:categorias.php");
exit;
