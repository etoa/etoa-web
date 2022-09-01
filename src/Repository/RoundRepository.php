<?php

namespace App\Repository;

use App\Models\Round;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class RoundRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManager $em)
    {
        $this->repo = $em->getRepository(Round::class);
    }

    /**
     * @return Round[]
     */
    public function all(): array
    {
        return $this->repo->findBy([], orderBy: [
            'active' => 'desc',
            'name' => 'asc',
        ]);
    }

    /**
     * @return Round[]
     */
    public function active(): array
    {
        return $this->repo->findBy(['active' => true], orderBy: [
            'name' => 'asc',
        ]);
    }

    public function get(int $id): ?Round
    {
        return $this->repo->find($id);
    }

    public function create(string $name, string $url, bool $active = false, int $startDate = 0): Round
    {
        $round = new Round();
        $round->name = $name;
        $round->url = $url;
        $round->active = $active;
        $round->startDate = $startDate;
        $this->em->persist($round);
        $this->em->flush();

        return $round;
    }

    public function update(int $id, string $name, string $url, bool $active, int $startDate): Round
    {
        /** @var ?Round $round */
        $round = $this->em->getReference(Round::class, $id);
        $round->name = $name;
        $round->url = $url;
        $round->active = $active;
        $round->startDate = $startDate;
        $this->em->persist($round);
        $this->em->flush();

        return $round;
    }

    public function delete(int ...$ids): void
    {
        foreach ($ids as $id) {
            $round = $this->em->getReference(Round::class, $id);
            $this->em->remove($round);
        }
        $this->em->flush();
    }

    public function createPageUrl(Round $round, string $page): string
    {
        return $round->url . '/show.php?index=' . $page;
    }
}
