<?php


namespace SALESmanago\Services\Report;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\Report\ReportModel;
use SALESmanago\Entity\Reporting\Platform;
use SALESmanago\Services\ContactAndEventTransferService;

/**
 * Class ReportService
 * Please instantiating this as soon as get proper ReportConfigurationInterface configuration
 *
 * @package SALESmanago\Services\Report
 */
class ReportService
{
    /**
     * @var ConfigurationInterface
     */
    private $conf;

    /**
     * @var ReportModel
     */
    private $reportModel;

    /**
     * @var ContactAndEventTransferService
     */
    private $transferService;

    /**
     * @var array of instances
     */
    private static $instances = [];

    /**
     * @var string
     */
    private $customerEndpoint;

    /**
     * ReportService constructor.
     *
     * @param ConfigurationInterface $conf
     */
    final private function __construct(ConfigurationInterface $conf)
    {
        $this->conf = $conf;
        $this->customerEndpoint = $this->conf->getEndpoint();
        $this->reportModel = new ReportModel($this->conf);
    }

    protected function __clone() {}

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * @param ConfigurationInterface|null $conf
     * @return mixed|static
     * @throws \Exception
     */
    public static function getInstance($conf = null)
    {
        $cls = static::class;

        if (!isset(self::$instances[$cls])) {

            if ($conf === null) {
                return null;
            }

            self::$instances[$cls] = new static($conf);
        }

        return self::$instances[$cls];
    }

    /**
     * @param $actType - one of ReportModel const ACT_...
     * @param array $additionalInformation
     *
     * @return bool
     */
    public function reportAction($actType, $additionalInformation = [])
    {
        if (!$this->conf->getActiveReporting()) {
            return false;
        }

        try {
            $this->conf->setEndpoint('https://survey.salesmanago.com/2.0');

            $this->transferService = new ContactAndEventTransferService($this->conf);
            $this->transferService->transferBoth(
                $this->reportModel->getClientAsContact($actType),
                $this->reportModel->getActionAsEvent($actType, $additionalInformation)
            );
            $this->conf->setEndpoint($this->customerEndpoint);
            return true;
        } catch (Exception $e) {
            $this->conf->setEndpoint($this->customerEndpoint);
            return false;
        }
    }

    /**
     * Report exceptions from SALESmanago\Exception\Exception
     *
     * @param $exceptionViewMessage
     * @return false
     */
    public function reportException($exceptionViewMessage)
    {
        return $this->reportAction(ReportModel::ACT_EXCEPTION, [$exceptionViewMessage]);
    }
}
