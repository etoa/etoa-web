<?php

namespace App\Repository;

use App\Models\Text;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class TextRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManager $em)
    {
        $this->repo = $em->getRepository(Text::class);
    }

    /**
     * @return Text[]
     */
    public function all(): array
    {
        return $this->repo->findBy([], orderBy: [
            'name' => 'asc',
        ]);
    }

    public function get(int $id): ?Text
    {
        return $this->repo->find($id);
    }

    public function findByKeyword(string $keyword): ?Text
    {
        return $this->repo->findOneBy(['keyword' => $keyword]);
    }

    public function create(string $keyword, string $content): Text
    {
        $text = new Text();
        $text->keyword = $keyword;
        $text->name = ucfirst($keyword);
        $text->content = $content;
        $text->lastChanges = time();
        $this->em->persist($text);
        $this->em->flush();

        return $text;
    }

    public function update(int $id, string $content): void
    {
        /** @var ?Text $text */
        $text = $this->em->getReference(Text::class, $id);
        $text->content = $content;
        $text->lastChanges = time();
        $this->em->persist($text);
        $this->em->flush();
    }

    public function delete(int ...$ids): void
    {
        foreach ($ids as $id) {
            $text = $this->em->getReference(Text::class, $id);
            $this->em->remove($text);
        }
        $this->em->flush();
    }

    public function getContent(string $keyword): ?string
    {
        $text = $this->findByKeyword($keyword);
        if (null !== $text) {
            if ('' != $text->content) {
                return $text->content;
            }
        }

        $templates = require APP_DIR . '/config/texts.php';

        return isset($templates[$keyword]) ? $templates[$keyword]->default : null;
    }
}
