<?php

namespace Microblog\Services;

use Microblog\Database\ConexaoBD;
use Microblog\Enums\Destaque;
use Microblog\Enums\TipoUsuario;
use Microblog\Models\Noticia;
use Microblog\Helpers\Utils;

use PDO, Exception;
use Throwable;

class NoticiaServico
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }

    public function listarTodos(TipoUsuario $tipoUsuario, int $usuarioId): array
    {

        if ($tipoUsuario === TipoUsuario::ADMIN) {
            $sql = "SELECT noticias.id, noticias.titulo, 
                    noticias.data, usuarios.nome AS autor, noticias.destaque
                    FROM noticias INNER JOIN usuarios
                    ON noticias.usuario_id = usuarios.id
                    ORDER BY data DESC";
        } else {
            $sql = "SELECT id, titulo, data, destaque
                    FROM noticias WHERE usuario_id = :usuario_id
                    ORDER BY data DESC";
        }
        
        // die($sql);

        try {
            $consulta = $this->conexao->prepare($sql);

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId, PDO::PARAM_INT);
            }

            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar notícias.");
        }
    }

    public function buscarPorId(int $id, TipoUsuario $tipoUsuario, int $usuarioId): ?array
    {
        if ($tipoUsuario === TipoUsuario::ADMIN) {
            $sql = "SELECT * FROM noticias WHERE id = :id";
        } else {
            $sql = "SELECT * FROM noticias WHERE usuario_id = :usuario_id AND id = :id
                    ORDER BY data DESC";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId, PDO::PARAM_INT);
            }

            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar notícia por ID.");
        }
    }

    public function inserir(Noticia $noticia): void
    {
        $sql = "INSERT INTO noticias(
            titulo, texto, resumo,
            imagem, destaque, 
            usuario_id, categoria_id
        ) VALUES(
            :titulo, :texto, :resumo,
            :imagem, :destaque, 
            :usuario_id, :categoria_id
        )";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo", $noticia->getTitulo(), PDO::PARAM_STR);
            $consulta->bindValue(":texto", $noticia->getTexto(), PDO::PARAM_STR);
            $consulta->bindValue(":resumo", $noticia->getResumo(), PDO::PARAM_STR);
            $consulta->bindValue(":imagem", $noticia->getImagem(), PDO::PARAM_STR);
            $consulta->bindValue(":destaque", $noticia->getDestaque()->value, PDO::PARAM_STR);
            $consulta->bindValue(":usuario_id", $noticia->getUsuarioId(), PDO::PARAM_INT);
            $consulta->bindValue(":categoria_id", $noticia->getCategoriaId(), PDO::PARAM_INT);
            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir notícia.");
        }
    }

    public function atualizar(Noticia $noticia, TipoUsuario $tipoUsuario): void
    {
        // SQL varia dependendo do tipo de usuário
        if ($tipoUsuario === TipoUsuario::ADMIN) {
            $sql = "UPDATE noticias SET
                    titulo = :titulo,
                    texto = :texto,
                    resumo = :resumo,
                    imagem = :imagem,
                    categoria_id = :categoria_id,
                    destaque = :destaque
                WHERE id = :id";
        } else {
            $sql = "UPDATE noticias SET
                    titulo = :titulo,
                    texto = :texto,
                    resumo = :resumo,
                    imagem = :imagem,
                    categoria_id = :categoria_id,
                    destaque = :destaque
                WHERE id = :id AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo", $noticia->getTitulo(), PDO::PARAM_STR);
            $consulta->bindValue(":texto", $noticia->getTexto(), PDO::PARAM_STR);
            $consulta->bindValue(":resumo", $noticia->getResumo(), PDO::PARAM_STR);
            $consulta->bindValue(":imagem", $noticia->getImagem(), PDO::PARAM_STR);
            $consulta->bindValue(":destaque", $noticia->getDestaque()->name, PDO::PARAM_STR);
            $consulta->bindValue(":categoria_id", $noticia->getCategoriaId(), PDO::PARAM_INT);
            $consulta->bindValue(":id", $noticia->getId(), PDO::PARAM_INT);

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $noticia->getUsuarioId(), PDO::PARAM_INT);
            }

            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao atualizar notícia.");
        }
    }

    public function excluir(int $id, TipoUsuario $tipoUsuario, int $usuarioId): void
    {
        // SQL varia dependendo do tipo de usuário
        if ($tipoUsuario === TipoUsuario::ADMIN) {
            $sql = "DELETE FROM noticias WHERE id = :id";
        } else {
            $sql = "DELETE FROM noticias WHERE id = :id AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId, PDO::PARAM_INT);
            }

            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao excluir notícia.");
        }
    }


    public function listarDestaques(): array
    {
        $sql = "SELECT id, titulo, resumo, imagem FROM noticias
            WHERE destaque = :destaque ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":destaque", Destaque::SIM->name, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $erro) {
            Utils::registrarLog($erro);
            throw new Exception("Erro ao carregar destaques.");
        }
    }

    public function listarParaPublico(): array
    {
        $sql = "SELECT id, data, titulo, resumo FROM noticias ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $erro) {
            Utils::registrarLog($erro);
            throw new Exception("Erro ao carregar notícias.");
        }
    }

    public function listarDetalhes(int $id): array
    {
        $sql = "SELECT noticias.id, noticias.titulo, noticias.data, 
                    usuarios.nome AS autor, noticias.texto, noticias.imagem
                FROM noticias INNER JOIN usuarios
                ON noticias.usuario_id = usuarios.id
                WHERE noticias.id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $erro) {
            Utils::registrarLog($erro);
            throw new Exception("Erro ao abrir a notícia.");
        }
    }

    public function listarPorCategoria(int $categoriaId): array {
        $sql = "SELECT 
                    noticias.id, 
                    noticias.titulo, 
                    noticias.data, 
                    noticias.resumo, 
                    usuarios.nome AS autor, 
                    categorias.nome AS categoria
                
                FROM noticias 
                    INNER JOIN usuarios ON noticias.usuario_id = usuarios.id
                    INNER JOIN categorias ON noticias.categoria_id = categorias.id
                
                WHERE noticias.categoria_id = :categoria_id";
    
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(':categoria_id', $categoriaId, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $erro) {
            Utils::registrarLog($erro);
            throw new Exception("Erro ao listar notícias por categoria.");
        }
    }
    
    public function buscar(string $termo): array {
        $sql = "SELECT id, titulo, data, resumo 
                FROM noticias
                WHERE titulo LIKE :termo
                   OR resumo LIKE :termo 
                   OR texto LIKE :termo
                ORDER BY data DESC";
    
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $erro) {
            Utils::registrarLog($erro);
            throw new Exception("Erro ao buscar notícias.");
        }
    }

   
    
}
