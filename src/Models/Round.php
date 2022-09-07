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
    #[Column(name: 'round_id'), Id, GeneratedValue]
    public int $id;

    #[Column(name: 'round_name', unique: true)]
    public string $name;

    #[Column(name: 'round_url')]
    public string $url;

    #[Column(name: 'round_active')]
    public bool $active = false;

    #[Column(name: 'round_startdate')]
    public int $startDate = 0;
}
