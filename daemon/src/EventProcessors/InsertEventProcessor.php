<?php declare(strict_types=1);

namespace Daemon\EventProcessors;

class InsertEventProcessor extends BaseEventProcessor
{
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

        #var_export($this->eventInfo);
        #var_export($rules);

        foreach ($rules as $rule) {
            $warmer = $this->getWarmerBy($rule->getHashName());

            if ($rule->isSingleKey()) {
                $warmer->fetchAllEntities();
                #$warmer->warm($warmer->getKey([]), $warmer->getEntities());
                foreach ($warmer->getEntities() as $entity)
                    $warmer->warm($warmer->getKey($entity), $entity);
            } elseif ($rule->hasPrimaryKey()) {
                foreach ($this->eventInfo['values'] as $change) {
                    #echo "CHANGE";
                    #var_export($change);
                    $pk = $rule->getPrimaryKey();
                    $id = $change[$pk];
                    #echo "ID is " . $id;
                    $entity = $warmer->fetchById($id);
                    #var_export($entity);
                    if (!empty($entity))
                        $warmer->warm($warmer->getKey($entity), $entity);
                }
            }
        }
    }
}
