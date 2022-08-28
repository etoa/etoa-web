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

    #[Column(type: 'string', name: 'config_name', unique: true, nullable: false)]
    private string $name;

    #[Column(type: 'string', name: 'config_value', nullable: true)]
    private string $value;

    public function __construct(string $name, ?string $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
