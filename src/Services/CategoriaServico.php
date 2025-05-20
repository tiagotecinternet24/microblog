<?php

namespace Microblog\Services;

use Microblog\Database\ConexaoBD;
use Microblog\Helpers\Utils;

use Exception, PDO, Throwable;
use Microblog\Models\Categoria;

final class CategoriaServico
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }

    /* Métodos para rotinas de CRUD no Banco */

    public function listarTodos(): array
    {
        $sql = "SELECT * FROM categorias ORDER BY nome";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar categorias: ");
        }
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM categorias WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar categoria por ID: ");
        }
    }

    public function inserir(Categoria $categoria): void
    {
        $sql = "INSERT INTO categorias(nome) VALUES(:nome)";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":nome", $categoria->getNome(), PDO::PARAM_STR);
            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir categoria: ");
        }
    }

    public function atualizar(Categoria $categoria): void
    {
        $sql = "UPDATE categorias SET nome = :nome WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":nome", $categoria->getNome(), PDO::PARAM_STR);
            $consulta->bindValue(":id", $categoria->getId(), PDO::PARAM_INT);
            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao atualizar categoria: ");
        }
    }

    public function excluir(int $id): void
    {
        $sql = "DELETE FROM categorias WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao excluir categoria: ");
        }
    }
}
