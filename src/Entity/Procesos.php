<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Procesos
 *
 * @ORM\Table(name="procesos", indexes={@ORM\Index(name="id_usuario", columns={"id_usuario"})})
 * @ORM\Entity
 */
class Procesos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_proceso", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProceso;

    /**
     * @var string
     *
     * @ORM\Column(name="nro_proceso", type="string", length=8, nullable=false)
     */
    private $nroProceso;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=false)
     */
    private $descripcion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $fechaCreacion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sede", type="string", length=255, nullable=true)
     */
    private $sede;

    /**
     * @var float|null
     *
     * @ORM\Column(name="presupuesto", type="float", precision=10, scale=0, nullable=true)
     */
    private $presupuesto;

    /**
     * @var \Usuarios
     *
     * @ORM\ManyToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id_usuario")
     * })
     */
    private $idUsuario;

    public function getIdProceso(): ?int
    {
        return $this->idProceso;
    }

    public function getNroProceso(): ?string
    {
        return $this->nroProceso;
    }

    public function setNroProceso(string $nroProceso): self
    {
        $this->nroProceso = $nroProceso;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(?\DateTimeInterface $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function getSede(): ?string
    {
        return $this->sede;
    }

    public function setSede(?string $sede): self
    {
        $this->sede = $sede;

        return $this;
    }

    public function getPresupuesto(): ?float
    {
        return $this->presupuesto;
    }

    public function setPresupuesto(?float $presupuesto): self
    {
        $this->presupuesto = $presupuesto;

        return $this;
    }

    public function getIdUsuario(): ?Usuarios
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(?Usuarios $idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }


}
