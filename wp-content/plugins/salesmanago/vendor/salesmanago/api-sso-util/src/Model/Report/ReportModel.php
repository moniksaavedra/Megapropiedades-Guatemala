<?php


namespace SALESmanago\Model\Report;

use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Exception\Exception;

class ReportModel
{
    const
        ACT_LOGOUT          = 'logout',
        ACT_LOGIN           = 'login',
        ACT_EXPORT          = 'export',
        ACT_EXPORT_EVENT    = 'export',
        ACT_EXPORT_CONTACTS = 'export',
        ACT_EXCEPTION       = 'exception',
        ACT_UPDATE          = 'update',
        ACT_DEACTIVATION    = 'deactivation',
        ACT_DELETE          = 'delete',
        ACT_UNKNOWN         = 'unknown',
        //use only for tests:
        ACT_TEST            = 'test';

    /**
     * @var string[] - mapping actual action to event type in SALESmanago
     */
    protected $actionToEventType = [
        //client actions:
        self::ACT_LOGOUT       => 'RETURN',
        self::ACT_LOGIN        => 'LOGIN',
        self::ACT_EXPORT       => 'TRANSACTION',
        //integration/plugin action:
        self::ACT_EXCEPTION    => 'CANCELLATION',
        self::ACT_UPDATE       => 'ACTIVATION',
        self::ACT_DEACTIVATION => 'CANCELLATION',
        self::ACT_DELETE       => 'APP_TYPE_RETENTION',
        self::ACT_UNKNOWN      => 'OTHER',
        self::ACT_TEST         => 'SURVEY'
    ];

    /**
     * @var ConfigurationInterface
     */
    private $conf;

    /**
     * ReportModel constructor.
     *
     * @param ConfigurationInterface $conf
     */
    public function __construct(ConfigurationInterface $conf)
    {
        $this->conf = $conf;
    }

    /**
     * @return Contact return new instance of contact
     */
    private function getContact()
    {
        return new Contact();
    }

    /**
     * @return Event return new instance of Event
     */
    private function getEvent()
    {
        return new Event();
    }

    /**
     * Generate client information for reporting service
     *
     * @param string $actionType - action tag
     * @return Contact
     */
    public function getClientAsContact($actionType = self::ACT_UNKNOWN)
    {
        $Contact = $this->getContact()
            ->setEmail($this->conf->getOwner())
            ->setName($this->conf->getPlatformDomain())
            ->setExternalId($this->conf->getClientId())
            ->setCompany($this->conf->getPlatformName());

        $Contact->setAddress(
            $Contact->getAddress()
                ->setStreetAddress($this->conf->getVersionOfIntegration())
                ->setCountry($this->conf->getPlatformLang())
        );

        $Contact->setOptions(
            $Contact->getOptions()
                ->setLang($this->conf->getPlatformLang())
                ->appendTags(
                    [
                        $this->conf->getPlatformName(),
                        'PLATFORM_VER_'.$this->conf->getPlatformVersion(),
                        'INTEGRATION_VER_'.$this->conf->getVersionOfIntegration(),
                        'PHP_' . $this->conf->getPhpVersion(),
                        $this->actionToEventType[$actionType]
                    ]
                )
                ->setIsSubscriptionStatusNoChange(false)
                ->setIsSubscribes(true)
        );

        return $Contact;
    }

    /**
     * Generate event information for reporting service
     *
     * @param string $actionType one of const
     * @param array $arr
     * @return Event
     * @throws Exception
     */
    public function getActionAsEvent($actionType = self::ACT_UNKNOWN, $arr = [])
    {
        $Event = $this->getEvent()
            ->setEmail($this->conf->getOwner())
            ->setDate(time())
            ->setProducts($this->conf->getPlatformVersion())
            ->setLocation($this->conf->getVersionOfIntegration())
            ->setDetail('PHP:' . $this->conf->getPhpVersion(), 1)
            ->setDetail('Date:' . time())
            ->setContactExtEventType($this->actionToEventType[$actionType]);

        if (!empty($arr)) {
            $Event->setDescription(json_encode($arr));
        }

        return $Event;
    }
}
