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
    #[Column(name: 'text_id'), Id, GeneratedValue]
    public int $id;

    #[Column(name: 'text_keyword', unique: true)]
    public string $keyword;

    #[Column(name: 'text_name')]
    public string $name;

    #[Column(name: 'text_text')]
    public string $content;

    #[Column(name: 'text_last_changes')]
    public int $lastChanges = 0;

    #[Column(name: 'text_author_id')]
    public string $authorId;
}
