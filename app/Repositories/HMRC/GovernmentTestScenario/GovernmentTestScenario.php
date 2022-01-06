<?php

namespace App\Repositories\HMRC\GovernmentTestScenario;

use App\Repositories\HMRC\Helpers\VariableChecker;
use App\Repositories\HMRC\Exceptions\InvalidVariableValueException;
use ReflectionClass;

abstract class GovernmentTestScenario
{
    /**
     * @throws \ReflectionException
     *
     * @return array
     */
    public function getValidGovTestScenarios(): array
    {
        $oClass = new ReflectionClass(static::class);

        $constants = $oClass->getConstants();

        return array_values($constants);
    }

    /**
     * @param $govTestScenario
     *
     * @throws InvalidVariableValueException
     * @throws \ReflectionException
     */
    public function checkValid($govTestScenario)
    {
        VariableChecker::checkPossibleValue($govTestScenario, $this->getValidGovTestScenarios());
    }
}
