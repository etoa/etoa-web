<?php

namespace App\Service;

use App\Models\ConfigSetting;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class ConfigService
{
    private EntityRepository $repo;

    public function __construct(private EntityManager $em)
    {
        $this->repo = $em->getRepository(ConfigSetting::class);
    }

    public function get(string $name, ?string $defaultValue = null): ?string
    {
        /** @var ?ConfigSetting $item */
        $item = $this->repo->findOneBy(['name' => $name]);
        if ($item === null) {
            return $defaultValue;
        }

        return $item->value;
    }

    public function set(string $name, string $value): void
    {
        /** @var ?ConfigSetting $item */
        $item =  $this->repo->findOneBy(['name' => $name]) ?? new ConfigSetting($name);
        $item->value = $value;
        $this->em->persist($item);
        $this->em->flush();
    }
}
