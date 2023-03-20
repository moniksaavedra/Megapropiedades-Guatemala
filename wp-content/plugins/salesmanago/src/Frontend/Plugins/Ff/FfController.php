<?php

namespace bhr\Frontend\Plugins\Ff;

if(!defined('ABSPATH')) exit;

use bhr\Frontend\Plugins\Ff\FfContactModel as ContactModel;
use bhr\Frontend\Controller\TransferController;
use SALESmanago\Exception\Exception;
use bhr\Frontend\Model\Helper;


class FfController
{
    private $TransferController;
    private $ContactModel;

    public function __construct($PlatformSettings, TransferController $TransferController)
    {
        $this->TransferController = $TransferController;
        if(!$this->ContactModel = new ContactModel($PlatformSettings)) {
            return false;
        }
        return $this;
    }

    /**
     * @param $formData
     * @param $entryId
     * @return bool
     */
    public function execute($formData, $entryId)
    {
        try {
            //Get config for submitted form (tags, owner)
            if (!$this->ContactModel->setCurrentFormConfig($entryId['form_id'])) {
                return false;
            }

            //Set Contact Owner
            if (!empty($this->ContactModel->getCurrentFormConfig())) {
                $this->TransferController->setOwner($this->ContactModel->getCurrentFormConfig()->owner);
            }

            //Populate new Contact Model with fields from submitted data
            if (!$this->ContactModel->parseContact($formData)) {
                return false;
            }

            //Optional: Set Double Opt-in defined per-form
            if ($AdditionalPlatformSettings = $this->ContactModel->getCustomDoubleOptIn($formData)) {
                $this->TransferController->setAdditionalConfigurationFields($AdditionalPlatformSettings);
            }

	        Helper::doAction('salesmanago_ff_contact', array('Contact' => $this->ContactModel->get()));

	        //Transfer Contact with global controller
            return $this->TransferController->transferContact($this->ContactModel->get());
        } catch (Exception $e) {
            error_log(print_r($e->getLogMessage(), true));
            return false;
        } catch (\Exception $e) {
            error_log(print_r($e->getMessage(), true));
            return false;
        }
    }
}
