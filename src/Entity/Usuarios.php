<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity
 */
class Usuarios
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_completo", type="string", length=255, nullable=false)
     */
    private $nombreCompleto;

    /**
     * @var float
     *
     * @ORM\Column(name="identificacion", type="float", precision=40, scale=0, nullable=false)
     */
    private $identificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="contrasena", type="string", length=255, nullable=false)
     */
    private $contrasena;

    /**
     * @var int|null
     *
     * @ORM\Column(name="activo", type="integer", nullable=true, options={"default"="1","comment"="1= Activo, 0=Inactivo"})
     */
    private $activo = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="auth", type="integer", nullable=true, options={"comment"="1=Autenticado, 0= No Autenticado"})
     */
    private $auth = '0';

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function getNombreCompleto(): ?string
    {
        return $this->nombreCompleto;
    }

    public function setNombreCompleto(string $nombreCompleto): self
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    public function getIdentificacion(): ?float
    {
        return $this->identificacion;
    }

    public function setIdentificacion(float $identificacion): self
    {
        $this->identificacion = $identificacion;

        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    public function getActivo(): ?int
    {
        return $this->activo;
    }

    public function setActivo(?int $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getAuth(): ?int
    {
        return $this->auth;
    }

    public function setAuth(?int $auth): self
    {
        $this->auth = $auth;

        return $this;
    }


}
