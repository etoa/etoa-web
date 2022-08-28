<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'rounds')]
class Round
{
    #[Id, Column(type: 'integer', name: 'round_id'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', name: 'round_name', unique: true, nullable: false)]
    public string $name;

    #[Column(type: 'string', name: 'round_url', nullable: false)]
    public string $url;

    #[Column(type: 'boolean', name: 'round_active', nullable: false)]
    public bool $active = false;

    #[Column(type: 'integer', name: 'round_startdate', nullable: false)]
    public int $startDate = 0;

    public function getId(): int
    {
        return $this->id;
    }
}
