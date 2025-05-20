<?php

namespace Microblog\Database;

use Microblog\Helpers\Utils;
use Exception;
use PDO;
use Throwable;


abstract class ConexaoBD
{

    private static PDO $conexao;
    private static string $servidor = "localhost";
    private static string $usuario = "root";
    private static string $senha = "";
    private static string $banco = "microblog";

    public static function getConexao(): PDO
    {
        if (!isset(self::$conexao)) {
            try {
                self::$conexao = new PDO(
                    "mysql:host=" . self::$servidor . ";dbname=" . self::$banco . ";charset=utf8",
                    self::$usuario,
                    self::$senha
                );

                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Throwable $e) {
                Utils::registrarLog($e);
                throw new Exception("Erro ao conectar ao banco de dados. Contate o suporte.");
            }
        }

        return self::$conexao;
    }
}
