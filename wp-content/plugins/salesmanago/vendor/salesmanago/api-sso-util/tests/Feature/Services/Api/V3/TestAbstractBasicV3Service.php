<?php

namespace Tests\Feature\Services\Api\V3;

use SALESmanago\Entity\Api\V3\ConfigurationEntity;
use Tests\Feature\TestCaseUnit;

class TestAbstractBasicV3Service extends TestCaseUnit
{
    //create Configuration entity for api v3
    protected function createConfigurationEntity()
    {
        ConfigurationEntity::getInstance()
            ->setApiV3Endpoint(getenv('ApiV3Endpoint'))
            ->setApiV3Key(getenv('ApiV3Key'));
    }
}