<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'config')]
class ConfigSetting
{
    #[Column(name: 'config_id'), Id, GeneratedValue]
    public int $id;

    public function __construct(
        #[Column(name: 'config_name', unique: true)] private readonly string $name,
        #[Column(name: 'config_value', nullable: true)] public ?string $value = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
