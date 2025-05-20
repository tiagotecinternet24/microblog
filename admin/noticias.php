<?php
require_once "../vendor/autoload.php";

use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Services\NoticiaServico;

// Configurar após programar Controle de Acesso
$tipoUsuario = TipoUsuario::from('admin'); 
$idUsuario = 1;

$noticiaServico = new NoticiaServico();
$listaDeNoticias = $noticiaServico->listarTodos($tipoUsuario, $idUsuario);

require_once "../includes/cabecalho-admin.php";
?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">

		<h2 class="text-center">
			Notícias
			<span class="badge bg-dark">
				<?= count($listaDeNoticias) ?>
			</span>
		</h2>

		<p class="text-center mt-5">
			<a class="btn btn-primary" href="noticia-insere.php">
				<i class="bi bi-plus-circle"></i>
				Inserir nova notícia</a>
		</p>

		<div class="table-responsive">

			<table class="table table-hover align-middle">
				<thead class="table-light">
					<tr>
						<th>Título</th>
						<th>Data</th>
						<th>Autor</th>
						<th class="text-center">Destaque</th>
						<th class="text-center" colspan="2">Operações</th>
					</tr>
				</thead>

				<tbody>

					<?php foreach ($listaDeNoticias as $itemNoticia) { ?>
						<tr>
							<td> <?= $itemNoticia['titulo'] ?> </td>
							<td> <?= Utils::formataData($itemNoticia['data']) ?> </td>
							<td> <?= $itemNoticia['autor'] ?> </td>
							<td class="text-center"><?= $itemNoticia['destaque'] ?></td>

							<td class="text-center">
								<a class="btn btn-warning btn-sm"
									href="noticia-atualiza.php?id=<?= $itemNoticia['id'] ?>">
									<i class="bi bi-pencil"></i> Atualizar
								</a>
							</td>
							<td>
								<a class="btn btn-danger excluir btn-sm"
									href="noticia-exclui.php?id=<?= $itemNoticia['id'] ?>">
									<i class="bi bi-trash"></i> Excluir
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

	</article>
</div>


<?php
require_once "../includes/rodape-admin.php";
?>