<?php

namespace SALESmanago\Controller;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Entity\Response;
use SALESmanago\Model\Report\ReportModel;
use SALESmanago\Services\UserAccountService;
use SALESmanago\Entity\User;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\Report\ReportService;

/**
 * Class LoginController
 */
class LoginController
{
    /**
     * @var ConfigurationInterface
     */
    protected $conf;

    /**
     * @var UserAccountService
     */
    protected $service;

    /**
     * LoginController constructor.
     * @param ConfigurationInterface $conf
     */
    public function __construct(ConfigurationInterface $conf)
    {
        Configuration::setInstance($conf);
        $this->conf = $conf;
        $this->service = new UserAccountService($this->conf);
    }

    /**
     * @param User $User
     * @throws Exception
     * @return Response
     */
    public function login(User $User) {
        $loginResponse = $this->service->login($User);

        try {
            ReportService::getInstance($this->conf)
                ->reportAction(ReportModel::ACT_LOGIN);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $loginResponse;
    }
}
