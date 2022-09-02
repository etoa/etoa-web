<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'redirects')]
class Redirect
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    public string $source;

    #[Column(type: 'string', nullable: false)]
    public string $target;

    #[Column(type: 'boolean', nullable: false)]
    public bool $active = true;

    public function getId(): int
    {
        return $this->id;
    }
}
