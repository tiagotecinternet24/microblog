<?php

namespace Microblog\Helpers;

use InvalidArgumentException;
use Microblog\Enums\Destaque;
use Microblog\Enums\TipoUsuario;

final class Validacoes
{
    private function __construct() {}

    public static function validarNome(string $nome): void
    {
        if (empty($nome)) {
            throw new InvalidArgumentException("Nome não pode ser vazio.");
        }
    }

    public static function validarEmail(string $email): void
    {
        if (empty($email)) {
            throw new InvalidArgumentException("Email não pode ser vazio.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email inválido.");
        }
    }

    public static function validarSenha(string $senha): void
    {
        if (empty($senha)) {
            throw new InvalidArgumentException("Senha não pode ser vazia.");
        }
    }

    public static function validarTipo(string $tipo): void
    {
        if (empty($tipo)) {
            throw new InvalidArgumentException("Selecione um tipo de usuário.");
        }

        if (!TipoUsuario::tryFrom($tipo)) {
            throw new InvalidArgumentException("Tipo de usuário inválido.");
        }
    }




    public static function validarTitulo(string $titulo): void
    {
        if (empty($titulo)) {
            throw new InvalidArgumentException("Título não pode ser vazio.");
        }
    }

    public static function validarTexto(string $texto): void
    {
        if (empty($texto)) {
            throw new InvalidArgumentException("Texto não pode ser vazio.");
        }
    }

    public static function validarResumo(string $resumo): void
    {
        if (empty($resumo)) {
            throw new InvalidArgumentException("Resumo não pode ser vazio.");
        }

        if (mb_strlen($resumo) > 300) {
            throw new InvalidArgumentException("Resumo deve ter no máximo 300 caracteres.");
        }
    }

    public static function validarDestaque(string $valor): void
    {
        if (empty($valor)) {
            throw new InvalidArgumentException("Selecione uma opção de destaque.");
        }

        if (!Destaque::tryFrom($valor)) {
            throw new InvalidArgumentException("Valor de destaque inválido.");
        }
    }

    public static function validarCategoria(int $categoriaId): void
    {
        if (empty($categoriaId) || $categoriaId <= 0) {
            throw new InvalidArgumentException("Selecione uma categoria válida.");
        }
    }
}
