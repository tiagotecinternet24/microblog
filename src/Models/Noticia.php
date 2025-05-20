<?php

namespace Microblog\Models;

use Microblog\Enums\Destaque;

final class Noticia
{
    private ?int $id;
    private ?string $data;
    private string $titulo;
    private string $texto;
    private string $resumo;
    private string $imagem;
    private Destaque $destaque;
    private int $usuarioId;
    private int $categoriaId;

    public function __construct(
        string $titulo,
        string $texto,
        string $resumo,
        string $imagem,
        Destaque $destaque,
        int $usuarioId,
        int $categoriaId,
        ?int $id = null,
        ?string $data = null
    ) {
        $this->setTitulo($titulo);
        $this->setTexto($texto);
        $this->setResumo($resumo);
        $this->setImagem($imagem);
        $this->setDestaque($destaque);
        $this->setUsuarioId($usuarioId);
        $this->setCategoriaId($categoriaId);
        $this->setId($id);
        $this->setData($data);
    }


    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setData(?string $data): void
    {

        $this->data = $data;
    }
    public function getData(): ?string
    {
        return $this->data;
    }
    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }
    public function getTitulo(): string
    {
        return $this->titulo;
    }
    public function setTexto(string $texto): void
    {
        $this->texto = $texto;
    }
    public function getTexto(): string
    {
        return $this->texto;
    }
    public function setResumo(string $resumo): void
    {
        $this->resumo = $resumo;
    }
    public function getResumo(): string
    {
        return $this->resumo;
    }
    public function setImagem(string $imagem): void
    {
        $this->imagem = $imagem;
    }
    public function getImagem(): string
    {
        return $this->imagem;
    }
    public function setDestaque(Destaque $destaque): void
    {
        $this->destaque = $destaque;
    }
    public function getDestaque(): Destaque
    {
        return $this->destaque;
    }
    public function setUsuarioId(int $usuarioId): void
    {
        $this->usuarioId = $usuarioId;
    }
    public function getUsuarioId(): int
    {
        return $this->usuarioId;
    }
    public function setCategoriaId(int $categoriaId): void
    {
        $this->categoriaId = $categoriaId;
    }
    public function getCategoriaId(): int
    {
        return $this->categoriaId;
    }
}
