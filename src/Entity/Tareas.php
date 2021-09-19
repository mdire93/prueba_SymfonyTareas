<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TareasRepository")
 */
class Tareas
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $id_usuario;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $fecha_creacion;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $id_estado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getIdUsuario(): ?string
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?string $id_usuario): self
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    public function getFechaCreacion(): ?string
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(?string $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getIdEstado(): ?string
    {
        return $this->id_estado;
    }

    public function setIdEstado(?string $id_estado): self
    {
        $this->id_estado = $id_estado;

        return $this;
    }
}
