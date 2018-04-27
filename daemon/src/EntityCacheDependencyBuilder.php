<?php declare(strict_types=1);

namespace Daemon;

class EntityCacheDependencyBuilder
{
    /**
     * @return array
     * @throws \Exception
     */
    public function build()
    {
        $data = $this->getCacheEntityDependencies();
        return $this->buildDependencies($data);
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getCacheEntityDependencies(): array
    {
        $json = file_get_contents(__DIR__ . '/../dependencies.json');
        $data = json_decode($json, true);
        if (empty($data) || !\count($data)) {
            throw new \Exception('Failed to load entity cache dependencies');
        }

        return $data;
    }

    private function buildDependencies(array $data): array
    {
        $result = [];

        // построим зависимости в виде table -> [settings, tag]
        foreach ($data as $item) {
            $hashName = $item['hash_name'];
            $isSingleKey = $item['single_key'];
            foreach ($item['dependencies'] as $dependency) {
                $primaryKey = $dependency['primary_key'] ?? null;
                $dbTableName = $dependency['table_name'];

                list($database, $table) = explode('.', $dbTableName);

                if (!isset($result[$database])) {
                    $result[$database] = [];
                }
                if (!isset($result[$database][$table])) {
                    $result[$database][$table] = [];
                }

                $result[$database][$table][] = new InvalidateRule($hashName, $isSingleKey, $primaryKey);
            }
        }

        return $result;
    }
}