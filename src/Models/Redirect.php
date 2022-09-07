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
    #[Column, Id, GeneratedValue]
    public int $id;

    #[Column(unique: true)]
    public string $source;

    #[Column(nullable: false)]
    public string $target;

    #[Column(nullable: false)]
    public bool $active = true;
}
