<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'texts')]
class Text
{
    #[Id, Column(type: 'integer', name: 'text_id'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', name: 'text_keyword', unique: true, nullable: false)]
    public string $keyword;

    #[Column(type: 'string', name: 'text_name', nullable: false)]
    public string $name;

    #[Column(type: 'string', name: 'text_text', nullable: false)]
    public string $content;

    #[Column(type: 'integer', name: 'text_last_changes', nullable: false)]
    public int $lastChanges = 0;

    #[Column(type: 'integer', name: 'text_author_id', nullable: false)]
    public string $authorId;

    public function getId(): int
    {
        return $this->id;
    }
}
