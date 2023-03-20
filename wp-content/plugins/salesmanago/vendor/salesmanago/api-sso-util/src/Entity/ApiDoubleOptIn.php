<?php


namespace SALESmanago\Entity;


use SALESmanago\Entity\AbstractEntity;

class ApiDoubleOptIn extends AbstractEntity
{
    const
        U_API_D_OPT_IN        = 'useApiDoubleOptIn',
        D_OPT_IN_TEMPLATE_ID  = 'apiDoubleOptInEmailTemplateId',
        D_OPT_IN_EMAIL_ACC_ID = 'apiDoubleOptInEmailAccountId',
        D_OPT_IN_EMAIL_SUBJ   = 'apiDoubleOptInEmailSubject';

    private $enabled    = false;
    private $templateId = null;
    private $accountId  = null;
    private $subject    = null;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->setDataWithSetters($data);
        }
    }

    public function set($data)
    {
        $this->setDataWithSetters($data);
        return $this;
    }

    public function setEnabled($param)
    {
        $this->enabled = $param;
        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setTemplateId($param)
    {
        $this->templateId = $param;
        return $this;
    }

    public function getTemplateId()
    {
        return $this->templateId;
    }

    public function setAccountId($param)
    {
        $this->accountId = $param;
        return $this;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param $param
     * @return $this;
     */
    public function setSubject($param)
    {
        $this->subject = $param;
        return $this;
    }

    public function getSubject(){
        return $this->subject;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'enabled'    => $this->getEnabled(),
            'templateId' => $this->getTemplateId(),
            'accountId'  => $this->getAccountId(),
            'subject'    => $this->getSubject()
        );
    }
}