<?php

namespace bhr\Admin\Entity\Plugins;

if(!defined('ABSPATH')) exit;

use bhr\Admin\Entity\AbstractEntity;

class DoubleOptIn extends AbstractEntity
{
    protected $active     = false;
    protected $templateId = '';
    protected $accountId  = '';
    protected $subject    = '';

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active = false)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * @param string $templateId
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param string $accountId
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setDoubleOptIn($doubleOptIn)
    {
        if(is_array($doubleOptIn)) {
            $this->active = isset($doubleOptIn['active']) ? boolval($doubleOptIn['active']) : false;
            $this->templateId = isset($doubleOptIn['template-id']) ? $doubleOptIn['template-id'] : '';
            $this->accountId = isset($doubleOptIn['account-id']) ? $doubleOptIn['account-id'] : '';
            $this->subject = isset($doubleOptIn['subject']) ? $doubleOptIn['subject'] : '';
        } else {
            $this->active = isset($doubleOptIn->active) ? boolval($doubleOptIn->active) : false;
            $this->templateId = isset($doubleOptIn->templateId) ? $doubleOptIn->templateId : '';
            $this->accountId = isset($doubleOptIn->accountId) ? $doubleOptIn->accountId : '';
            $this->subject = isset($doubleOptIn->subject) ? $doubleOptIn->subject : '';
        }
        return $this;
    }

    public function setLegacyDoubleOptIn($doubleOptIn = null)
    {
        if(!empty($doubleOptIn)) {
            $this->active = isset($doubleOptIn['double']) ? boolval($doubleOptIn['double']) : false;
            $this->templateId = isset($doubleOptIn['template']) ? $doubleOptIn['template'] : '';
            $this->accountId = isset($doubleOptIn['email']) ? $doubleOptIn['email'] : '';
            $this->subject = isset($doubleOptIn['topic']) ? $doubleOptIn['topic'] : '';
        }
        return $this;
    }
}