<?php

namespace Microblog\Services;

use Microblog\Database\ConexaoBD;
use Microblog\Helpers\Utils;
use Microblog\Models\Usuario;

use PDO, Exception, Throwable;

final class UsuarioServico
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }


    public function listarTodos(): array
    {
        $sql = "SELECT * FROM usuarios ORDER BY nome";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar usuários.");
        }
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar usuário por ID.");
        }
    }

    public function inserir(Usuario $usuario): void
    {
        $sql = "INSERT INTO usuarios(nome, email, senha, tipo)
                VALUES(:nome, :email, :senha, :tipo)";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":nome", $usuario->getNome(), PDO::PARAM_STR);
            $consulta->bindValue(":email", $usuario->getEmail(), PDO::PARAM_STR);
            $consulta->bindValue(":senha", $usuario->getSenha(), PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $usuario->getTipoUsuario()->name, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir usuário.");
        }
    }

    public function atualizar(Usuario $usuario): void
    {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email,
        senha = :senha, tipo = :tipo WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":nome", $usuario->getNome(), PDO::PARAM_STR);
            $consulta->bindValue(":email", $usuario->getEmail(), PDO::PARAM_STR);
            $consulta->bindValue(":senha", $usuario->getSenha(), PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $usuario->getTipoUsuario()->name, PDO::PARAM_STR);
            $consulta->bindValue(":id", $usuario->getId(), PDO::PARAM_INT);
            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao atualizar usuário.");
        }
    }

    public function excluir(int $id): void
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao excluir usuário.");
        }
    }

    /* Método para buscar no banco um usuário através do e-mail */
    public function buscarPorEmail(string $email): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":email", $email, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar usuário por e-mail.");
        }
    }


}
