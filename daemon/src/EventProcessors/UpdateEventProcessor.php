<?php declare(strict_types=1);

namespace Daemon\EventProcessors;

use Daemon\InvalidateRule;
use MySQLReplication\Event\RowEvent\TableMap;

class UpdateEventProcessor extends BaseEventProcessor
{
    /**
     * @throws \Exception
     */
    public function process(): void
    {
        if (!$this->eventInfo['changedRows']
            || empty($this->eventInfo['values'])
            || empty($this->eventInfo['tableMap'])
            || !\count($this->eventInfo['values'])
        ) {
            return;
        }

        /** @var TableMap $tableMap */
        $tableMap = $this->eventInfo['tableMap'];
        /** @var InvalidateRule[] $rules */
        $rules = $this->getRulesFromDependencies($tableMap);

        foreach ($rules as $rule) {
            $warmer = $this->getWarmerBy($rule->getHashName());

            if ($rule->isSingleKey()) {
                $warmer->fetchAllEntities();
                $warmer->warm($warmer->getKey([]), $warmer->getEntities());
            } elseif ($rule->hasPrimaryKey()) {
                foreach ($this->eventInfo['values'] as $change) {
                    $pk = $rule->getPrimaryKey();
                    if (isset($change['before'][$pk], $change['after'][$pk])) {
                        $id = $change['after'][$pk];
                        $entity = $warmer->fetchById($id);
                        $warmer->warm($warmer->getKey($entity), $entity);

                        if ($change['before'][$pk] !== $id) {
                            $warmer->delete($warmer->getKey([$pk => $change['before'][$pk]]));
                        }
                    }
                }
            }
        }
    }
}