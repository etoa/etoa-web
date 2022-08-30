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
    #[Id, Column(type: 'integer', name: 'config_id'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    public function __construct(
        #[Column(type: 'string', name: 'config_name', unique: true, nullable: false)] private string $name,
        #[Column(type: 'string', name: 'config_value', nullable: true)] public ?string $value = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
