<?php

namespace App\Repository;

use App\Models\ConfigSetting;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class ConfigSettingRepository
{
    private EntityRepository $repo;

    public function __construct(private readonly EntityManager $em)
    {
        $this->repo = $em->getRepository(ConfigSetting::class);
    }

    public function get(string $name, string $defaultValue = null, bool $useCache = true): ?string
    {
        static $cache = [];
        if ($useCache && isset($cache[$name])) {
            return $cache[$name];
        }

        /** @var ?ConfigSetting $item */
        $item = $this->repo->findOneBy(['name' => $name]);
        if (null === $item) {
            return $defaultValue;
        }
        $cache[$name] = $item->value;

        return $item->value;
    }

    public function getInt(string $name, int $defaultValue = 0): int
    {
        $value = $this->get($name);

        return null !== $value ? intval($value) : $defaultValue;
    }

    public function set(string $name, ?string $value): void
    {
        /** @var ?ConfigSetting $item */
        $item = $this->repo->findOneBy(['name' => $name]) ?? new ConfigSetting($name);
        $item->value = null !== $value ? trim($value) : null;
        $this->em->persist($item);
        $this->em->flush();
    }

    public function setInt(string $name, int $value): void
    {
        $this->set($name, (string) $value);
    }
}
