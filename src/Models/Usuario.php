<?php

namespace Microblog\Models;

use Microblog\Enums\TipoUsuario;

final class Usuario
{
    private ?int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private TipoUsuario $tipo;

    public function __construct(
        string $nome,
        string $email,
        string $senha,
        TipoUsuario $tipo = TipoUsuario::EDITOR,
        ?int $id = null
    ) {
        $this->setNome($nome);
        $this->setEmail($email);
        $this->setSenha($senha);
        $this->setTipoUsuario($tipo);
        $this->setId($id);
    }


    public function getTipoUsuario(): TipoUsuario
    {
        return $this->tipo;
    }

    private function setTipoUsuario(TipoUsuario $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    private function setId(?int $id):void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    private function setNome(string $nome): void
    {
        $this->nome = $nome;
    }


    public function getEmail(): string
    {
        return $this->email;
    }

    private function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function getSenha(): string
    {
        return $this->senha;
    }

    private function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }
}
