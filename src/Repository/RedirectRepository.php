<?php

namespace App\Repository;

use App\Models\Redirect;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class RedirectRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManager $em)
    {
        $this->repo = $em->getRepository(Redirect::class);
    }

    /**
     * @return Redirect[]
     */
    public function all(): array
    {
        return $this->repo->findBy([]);
    }

    /**
     * @return Redirect[]
     */
    public function active(): array
    {
        return $this->repo->findBy(['active' => true]);
    }

    public function get(int $id): ?Redirect
    {
        return $this->repo->find($id);
    }

    public function create(string $source, string $target, bool $active = true): Redirect
    {
        $redirect = new Redirect();
        $redirect->source = $source;
        $redirect->target = $target;
        $redirect->active = $active;
        $this->em->persist($redirect);
        $this->em->flush();

        return $redirect;
    }

    public function update(int $id, string $target, bool $active = true): Redirect
    {
        /** @var ?Redirect $redirect */
        $redirect = $this->em->getReference(Redirect::class, $id);
        $redirect->target = $target;
        $redirect->active = $active;
        $this->em->persist($redirect);
        $this->em->flush();

        return $redirect;
    }

    public function delete(int ...$ids): void
    {
        foreach ($ids as $id) {
            $redirect = $this->em->getReference(Redirect::class, $id);
            $this->em->remove($redirect);
        }
        $this->em->flush();
    }
}
