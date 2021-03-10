<?php

namespace App\Entity;

use App\Repository\BilleteraRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BilleteraRepository::class)
 */
class Billetera
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * Cliente al que pertenece.
     *
     * @var \AppBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Cliente_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $cliente;

     /**
     * @ORM\Column(type="float")
     */
    private $balance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dinamicToken;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function setCliente(\App\Entity\Cliente $cliente = null): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getDinamicToken(): ?int
    {
        return $this->dinamicToken;
    }

    public function setDinamicToken(?int $dinamicToken): self
    {
        $this->dinamicToken = $dinamicToken;

        return $this;
    }






}
