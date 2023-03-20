<?php

namespace bhr\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use bhr\Admin\Controller\AdminActionController;
use bhr\Admin\Controller\ProductCatalogController;
use bhr\Admin\Controller\SettingsController as SettingsController;
use bhr\Admin\Entity\MessageEntity;
use bhr\Admin\Model\AdminModel;
use bhr\Admin\Model\Helper;
use bhr\Admin\Model\ProductCatalogModel;
use bhr\Includes\Helper as IncludesHelper;
use bhr\Includes\GlobalConstant;
use SALESmanago\Exception\Exception;

class Admin {

	protected $SettingsController;
	protected $AdminModel;
    protected $AdminActionController;
	protected $ProductCatalogController;

	public function __construct() {
		$isSalesmanagoView = ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], 'salesmanago' ) !== false );

		try {
			Helper::loadPluginTextDomain( 'salesmanago', false, 'salesmanago/languages' );
			Helper::addAction( 'admin_enqueue_scripts', array( $this, 'registerAssets' ) );
		} catch ( \Exception $e ) {
			MessageEntity::getInstance()->addException( new Exception( $e->getMessage(), 603 ) );
		}

		try {
			$this->AdminModel         = new AdminModel();
			$this->SettingsController = new SettingsController( $this->AdminModel );
			$ProductCatalogModel = new ProductCatalogModel( $this->AdminModel );
			$this->ProductCatalogController = new ProductCatalogController( $ProductCatalogModel );
		} catch ( \Exception $e ) {
			MessageEntity::getInstance()->addException( new Exception( $e->getMessage(), 600 ) );
		}

		$this->AdminModel->getConfigurationFromDb();
		$this->AdminModel->getPlatformSettingsFromDb();

        $this->AdminActionController = new AdminActionController(
            $this->AdminModel->getConfiguration(),
            $this->AdminModel->getPlatformSettings()
        );

        if ( $isSalesmanagoView ) {
			try {
				$this->SettingsController->route();
			} catch ( \Exception $e ) {
				MessageEntity::getInstance()->addException( new Exception( $e->getMessage(), 604 ) );
			}
		}
		try {
			$this->SettingsController->setUserLogged();
			if ( $this->AdminModel->getUserLogged() && $isSalesmanagoView ) {
				$this->SettingsController->checkPluginVersion();
			}
			$this->SettingsController->setAvailableTabs();
		} catch ( \Exception $e ) {
			MessageEntity::getInstance()->addException( new Exception( $e->getMessage(), 500 ) );
		}
		Helper::addAction( "activated_plugin", array( $this, "loadSMPluginLast" ), 1 );
		Helper::addAction( "deactivated_plugin", array( $this, "loadSMPluginLast" ), 1 );
		Helper::addAction( 'admin_menu', array( $this, 'registerAdminDashboardPage' ) );
		Helper::addAction( 'wp_ajax_salesmanago_refresh_owners', array( $this, 'refreshOwner' ) );
		Helper::addAction( 'wp_ajax_salesmanago_generate_swjs', array( $this, 'generateSwJs' ) );
		if ( $this->AdminModel->getPlatformSettings()->getPluginWc()->isActive() ) {
			Helper::addAction( 'woocommerce_order_status_cancelled', array( $this, 'wcEventStatusChanged' ), 10, 1 );
			Helper::addAction( 'woocommerce_order_status_refunded', array( $this, 'wcEventStatusChanged' ), 10, 1 );
			Helper::addAction( 'woocommerce_order_status_processing', array( $this, 'wcEventStatusChanged' ), 10, 1 );
            Helper::addAction( 'woocommerce_new_product', array( $this, 'wcProduct' ), 10, 2 );
            Helper::addAction( 'woocommerce_update_product', array( $this, 'wcProduct' ), 10, 2 );
		}
        Helper::addAction( 'profile_update', array( $this, 'userUpdate'), 10, 2);
        Helper::addAction( 'user_register', array( $this, 'userCreate'), 10, 2);
	}

	/**
	 * Make sure that salesmanago plugin is loaded last so that woocommerce functions can be used
	 *
	 * @return void
	 */
	public function loadSMPluginLast()
	{
        IncludesHelper::loadSMPluginLast();
	}

	/**
	 *
	 */
	public function registerAdminDashboardPage()
    {
		try {
            Helper::grantAccessToSalesmanagoPlugin('administrator');
			$this->SettingsController->registerMenuPages();
		} catch ( \Exception $e ) {
			MessageEntity::getInstance()->addException( new Exception( $e->getMessage(), 601 ) );
		}
	}

	/**
	 *
	 */
	public function registerAssets()
    {
		try {
			Helper::wpEnqueueStyle( 'salesmanago', plugin_dir_url( __FILE__ ) . 'View/css/salesmanago-admin.css', array(), SM_VERSION, 'all' );
			Helper::wpEnqueueScript( 'salesmanago', plugin_dir_url( __FILE__ ) . 'View/js/salesmanago-admin.js', array(), SM_VERSION, true );
		} catch ( \Exception $e ) {
			MessageEntity::getInstance()->addException( new Exception( $e->getMessage(), 603 ) );
		}
	}

	/**
	 * @return void
	 */
	public function refreshOwner()
    {
		echo $this->AdminModel->buildOptions( $this->SettingsController->refreshOwnerList() );
		wp_die();
	}

	/**
	 * @return void
	 */
	public function generateSwJs() {
		echo $this->AdminModel->generateSwJs();
		wp_die();
	}

    /**
     * @param $data
     *
     * @return void
     */
    public function wcEventStatusChanged( $data )
    {
		$this->AdminActionController->orderStatusChanged( $data );
	}

	/**
	 * Handle Wc Product hooks
	 * @param $data
	 * @param $wc_product
	 *
	 * @return void
	 */
	public function wcProduct( $data, $wc_product )
    {
	    $this->ProductCatalogController->upsertProduct( $wc_product );
    }

    /**
     * @param $userId
     * @param $oldData
     *
     * @return void
     */
    public function userUpdate($userId, $oldData)
    {
        if (
            ! in_array( GlobalConstant::WP_USR_ROLE_SUBSCRIBER, $oldData->roles )
            && ! in_array( GlobalConstant::WP_USR_ROLE_CUSTOMER, $oldData->roles )
        ) {
            return;
        }
        $this->AdminActionController->updateUser( $userId, $oldData );
    }

    /**
     * @param int $userId
     * @param array $userData
     *
     * @return void
     */
    public function userCreate($userId, $userData = null)
    {
        if (
            GlobalConstant::WP_USR_ROLE_SUBSCRIBER !== $userData['role']
            && GlobalConstant::WP_USR_ROLE_CUSTOMER !== $userData['role']
        ) {
            return;
        }
        $this->AdminActionController->updateUser( $userId, $userData );
    }
}
