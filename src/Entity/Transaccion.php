<?php

namespace App\Entity;

use App\Repository\TransaccionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransaccionRepository::class)
 */
class Transaccion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
    * Billetera que realizo la tansaccion.
    *
    * @var \AppBundle\Entity\Billetera
    *
    * @ORM\ManyToOne(targetEntity="App\Entity\Billetera")
    * @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="billetera_id", referencedColumnName="id", onDelete="CASCADE")
    * })
    */
    private $idBilletera;


    /**
     * @ORM\Column(type="float")
     */
    private $valor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdBilletera(): ?int
    {
        return $this->idBilletera;
    }

    public function setIBilletera(int $idBilletera): self
    {
        $this->idBilletera = $idBilletera;

        return $this;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): self
    {
        $this->valor = $valor;

        return $this;
    }
}
