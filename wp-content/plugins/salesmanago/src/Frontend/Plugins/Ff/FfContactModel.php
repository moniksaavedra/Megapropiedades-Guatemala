<?php

namespace bhr\Frontend\Plugins\Ff;

if(!defined('ABSPATH')) exit;

use bhr\Frontend\Model\AbstractContactModel;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Exception\Exception;

class FfContactModel extends AbstractContactModel
{
    private   $currentFormConfig;

    public function __construct($PlatformSettings)
    {
        //do not continue without settings
        if(empty($PlatformSettings) || empty($PlatformSettings->PluginFf)) {
            return false;
        }
        //create an Abstract Contact
        parent::__construct($PlatformSettings, $PlatformSettings->PluginFf);
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function setCurrentFormConfig($id)
    {
        //Get config (tags, owner) for submitted form
        $id = (int) $id;
        if(isset($this->PluginSettings->forms->$id)) {
            $this->currentFormConfig = $this->PluginSettings->forms->$id;
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getCurrentFormConfig()
    {
        return $this->currentFormConfig;
    }

    /**
     * @param $formData
     * @return Contact|null
     * @throws Exception
     */
    public function parseContact($formData)
    {
        try {
            //No email no fun
            if (!isset($formData['sm_email']) || empty($formData['sm_email'])) {
                return null;
            }

            /* Contact */
            /* Fluent Forms name field can be an array or string */
            $name = '';
            if(isset($formData['sm_name'])){
                $name = is_array($formData['sm_name'])
                    ? implode(' ', $formData['sm_name'])
                    : $formData['sm_name'];
            }

            $this->Contact
                ->setEmail($formData['sm_email'])
                ->setName($name)
                ->setPhone(isset($formData['sm_phone'])           ? $formData['sm_phone'] : '')
                ->setFax(isset($formData['sm_fax'])               ? $formData['sm_fax'] : '')
                ->setCompany(isset($formData['sm_company'])       ? $formData['sm_company'] : '');

            /* Address */
            if(isset($formData['sm_address']) && is_array($formData['sm_address'])) {
                /* If somebody uses Fluent Forms' built-in address field */
                $streetAddress = $this->implodeFields($formData['sm_address']['address_line_1'], $formData['sm_address']['address_line_2']);

                $this->Address
                    ->setCity(isset($formData['sm_address']['city']) ? $formData['sm_address']['city'] : '')
                    ->setCountry(isset($formData['sm_address']['country']) ? $formData['sm_address']['country'] : '')
                    ->setZipCode(isset($formData['sm_address']['zip']) ? $formData['sm_address']['zip'] : '')
                    ->setProvince(isset($formData['sm_address']['state']) ? $formData['sm_address']['state'] : '')
                    ->setStreetAddress($streetAddress);
            }
            else {
                /* If somebody creates their own address field */
                $streetAddress = $this->implodeFields($formData['sm_address1'], $formData['sm_address2']);

                $this->Address
                    ->setCity(isset($formData['sm_city']) ? $formData['sm_city'] : '')
                    ->setCountry(isset($formData['sm_country']) ? $formData['sm_country'] : '')
                    ->setZipCode(isset($formData['sm_postcode']) ? $formData['sm_postcode'] : '')
                    ->setProvince(isset($formData['sm_province']) ? $formData['sm_province'] : '')
                    ->setStreetAddress($streetAddress);
            }
            /* Options */
            $this->setLanguage();
            $this->Options
                ->setTags(
                    isset($this->currentFormConfig->tags)
                        ? $this->currentFormConfig->tags
                        : ''
                )->setRemoveTags(
                    isset($this->currentFormConfig->tagsToRemove)
                        ? $this->currentFormConfig->tagsToRemove
                        : ''
                );

            /* Global optin status (for both, email and mobile marketing) */
            $optIn = isset($formData['sm-optin'])
                && (is_array($formData['sm-optin']) && implode($formData['sm-optin']) != '')
                || (is_string($formData['sm-optin']) && boolval($formData['sm-optin']));

            /* Email marketing opt in status */
            $optInEmail = isset($formData['sm-optin-email'])
                && ((is_array($formData['sm-optin-email']) && implode($formData['sm-optin-email']) != '')
                || (is_string($formData['sm-optin-email']) && boolval($formData['sm-optin-email'])));

            /* Mobile marketing opt in status */
            $optInMobile = isset($formData['sm-optin-mobile'])
                && ((is_array($formData['sm-optin-mobile']) && implode($formData['sm-optin-mobile']) != '')
                || (is_string($formData['sm-optin-mobile']) && boolval($formData['sm-optin-mobile'])));


            if ($optIn || $optInEmail) {
                $this->Options->setIsSubscribesNewsletter(true);
                $this->Options->setIsSubscriptionStatusNoChange(false); //Set flag - opt-in status has changed
            }

            if ($optIn || $optInMobile) {
                $this->Options->setIsSubscribesMobile(true);
                $this->Options->setIsSubscriptionStatusNoChange(false); //Set flag - opt-in status has changed
            }

            /* Custom properties */
	        $propertiesMap = $this->getPropertiesMap($formData);
	        $propertiesMappingMode = !empty($this->PlatformSettings->PluginFF->propertiesMappingMode)
		        ? $this->PlatformSettings->PluginFF->propertiesMappingMode
		        : 'details';

	        $this->setPropertiesAsMappedType($propertiesMappingMode, $propertiesMap);

            return $this->Contact;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $formData
     * @return false|\stdClass
     */
    public function getCustomDoubleOptIn($formData)
    {
        /* per-form Double Opt-In templates */
        if (!empty($formData['sm_doi_template_id'])
            && !empty($formData['sm_doi_account_id'])
            && !empty($formData['sm_doi_subject'])) {
            $PlatformSettings        = new \stdClass();
            $DoubleOptIn             = new \stdClass();

            $DoubleOptIn->active     = true;
            $DoubleOptIn->templateId = $formData['sm_doi_template_id'];
            $DoubleOptIn->accountId  = $formData['sm_doi_account_id'];
            $DoubleOptIn->subject    = $formData['sm_doi_subject'];

            $PlatformSettings->DoubleOptIn = $DoubleOptIn;
            return $PlatformSettings;
        }
        return false;
    }


    /**
     * @param $formData
     * @return array
     */
    private function getPropertiesMap($formData)
    {
        $properties = array();
        if(isset($this->PluginSettings->properties)) {
            foreach ($this->PluginSettings->properties as $propertyName) {
                $customInput = isset($formData[$propertyName])
	                ? $formData[$propertyName]
	                : '';

                if ($propertyName != '' && $customInput != '') {
                    $properties[$propertyName] = $customInput;
                }
            }
        }
        return $properties;
    }

    /**
     * @param string $first
     * @param string $second
     * @return string
     */
    private function implodeFields($first = '', $second = '')
    {
        return trim($first . ' ' . $second);
    }
}
