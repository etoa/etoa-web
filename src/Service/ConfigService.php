<?php

namespace App\Service;

use App\Models\ConfigSetting;
use Doctrine\ORM\EntityManager;

final class ConfigService
{
    public function __construct(private EntityManager $em)
    {
    }

    public function get(string $name, ?string $defaultValue = null): ?string
    {
        $repo = $this->em->getRepository(ConfigSetting::class);
        $item = $repo->findOneBy(['name' => $name]);
        if ($item === null) {
            return $defaultValue;
        }

        return $item->getValue();
    }

    public function set(string $name, string $value): void
    {
        $repo = $this->em->getRepository(ConfigSetting::class);
        $item = $repo->findOneBy(['name' => $name]) ?? new ConfigSetting($name);
        $item->setValue($value);
        $this->em->persist($item);
        $this->em->flush();
    }
}
