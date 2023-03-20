<div id="salesmanago-content">
<?php
use bhr\Frontend\Model\Helper;
$translations = function(){
    return "<script>
                window.salesmanago = {};
                window.salesmanago.translations = {
                    preparing:   '" . __( 'Preparing', 'salesmanago' ) . "',
                    in_progress: '" . __( 'In progress', 'salesmanago' ) . "',
                    done:        '" . __( 'Done', 'salesmanago' ) . "',
                    failed:      '" . __( 'Failed. Check console for details.', 'salesmanago' ) . "',
                    unknown:     '" . __( 'Unknown', 'salesmanago' ) . "',
                    starting:    '" . __( 'Starting', 'salesmanago' ) . "',
                    no_data:     '" . __( 'No data to export in the selected time range', 'salesmanago' ) . "',
                };
                </script>";
};
echo $translations();

if ( $this->AdminModel->getInstalledPluginByName( 'wc' ) ):?>
    <div class="sm-product-catalog-container">
        <?php
            $api_v3_key = $this->AdminModel->getConfiguration()->getApiV3Key();
            if ( empty( $api_v3_key ) ) :
        ?>
        <div class="sm-product-catalog-container">
            <div class="sm-product-catalog-text-container">
                <h2>
                    <?php _e( 'Real-time product synchronization', 'salesmanago' ); ?>
                </h2>
                <h2>
                    <?php _e( 'Get all the benefits of real-time Product Catalog synchronization with Product API', 'salesmanago' ); ?>
                </h2>
                <ol class="sm-product-catalog-info-list">
                    <li>
                        <?php _e( 'Easily set up product synchronization to instantly reflect all changes from WordPress in Recommendation Frames, Personal Shopping Inbox, and other modules', 'salesmanago' ); ?>
                    </li>
                    <li>
                        <?php _e( 'Log in to SALESmanago and go to Integration Center ➔ API ➔ API v3 tab', 'salesmanago' ); ?>
                    </li>
                    <li>
                        <?php _e( 'Create a new API key. Enter your name (e.g. WordPress), Webhook URL, and expiry time. You can copy the webhook URL from the Webhook URL field below.', 'salesmanago' ); ?>
                    </li>
                    <li>
                        <?php _e( 'Display the API key using an eye icon ', 'salesmanago' ); ?>
                        <svg role="img"
                             width="10px"
                             aria-hidden="true"
                             focusable="false"
                             data-prefix="fas"
                             data-icon="eye"
                             class="svg-inline--fa fa-eye fa-w-18"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 576 512">
                                <path fill="#676a6c" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path>
                        </svg>
                        <?php _e( ' and paste it below in the API v3 key field', 'salesmanago' ); ?>
                    </li>
                </ol>
                <div>
                    <label for="api-v3-webhook-url-input" class="sm-product-api-label">
                        <?php _e( 'Webhook URL', 'salesmanago' ); ?>
                    </label>
                </div>
                <div>
                    <div class="sm-product-catalog-input-container">
                        <input id="api-v3-webhook-url-input"
                               class="regular-text sm-product-catalog-input"
                               value="<?php
                               $api_v3_webhook_url = Helper::generate_api_v3_webhook_url();
                               echo $api_v3_webhook_url;
                               ?>"
                               readonly
                        />
                        <button class="sm-product-catalog-button" onclick=copyApiV3EndpointToClipBoard()
                        >
                            <?php _e( 'COPY', 'salesmanago' ); ?>
                        </button>
                    </div>
                    <p class="description sm-product-catalog-description">
                        <?php
                        _e( 'Webhook is a modern way to report any potential problems with data transfer back to Wordpress. Paste this URL when creating a new API v3 key.', 'salesmanago' );
                        if ( !Helper::checkEndpointForHTTPS( $api_v3_webhook_url ) ):
                        ?>
                            <br>
                            <span class="span-error">
                                <?php _e( 'Important: Your server must have SSL enabled to receive webhooks with error notices from SALESmanago', 'salesmanago' );?>
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
                <br>
                <div>
                    <label for="api-v3-key-input" class="sm-product-api-label">
                        <?php _e( 'API v3 key', 'salesmanago' );?>
                    </label>
                </div>
                <form action="" method="post" enctype="application/x-www-form-urlencoded">
                <div class="sm-product-catalog-input-container">
                    <input id="api-v3-key-input"
                           name="api-v3-key"
                           class="regular-text sm-product-catalog-input"
                           value="<?php echo $api_v3_key; ?>"
                           placeholder="<?php _e( 'Paste your API v3 key here', 'salesmanago' );?>"
                           required
                           onchange="salesmanagoValidateApiKey()"
                    />
                    <input id="sm-btn-submit-api-key" type="submit" class="sm-product-catalog-button" value="➔" required/>
                    <input type="hidden" name="name" value="SALESmanago">
                    <input type="hidden" name="action" value="addApiV3Key">
                </div>
                <p class="description sm-api-key-error-wrapper">
                    <span id="sm-api-key-error" class="span-error hidden"><?php _e( 'Invalid API Key. Make sure the key exists in the SALESmanago app', 'salesmanago' ) ?></span>
                </p>
                </form>
            </div>
            <div class="sm-product-catalog-image-container">
                <img height="300px" id="sm-product-catalog-flow" src="<?php echo( $this->AdminModel->getPluginUrl() . 'src/Admin/View/img/product_api.png' );?>" alt="Product catalog flow"/>
            </div>
        <?php else:
            $product_catalogs = json_decode( $this->AdminModel->getConfiguration()->getCatalogs() );
            $active_catalog = $this->AdminModel->getConfiguration()->getActiveCatalog();
            if ( empty ( $product_catalogs ) ):
                ?>
                <div>
                    <div class="sm-product-catalog-text-container">
                        <h2>
                            <?php _e( 'Start real-time product synchronization by creating a new Product Catalog', 'salesmanago' ); ?>
                        </h2>
                        <form action="" method="post" enctype="application/x-www-form-urlencoded">
                            <input type="submit" class="button-primary" value="<?php _e( '+ NEW PRODUCT CATALOG', 'salesmanago' ) ?>">
                            <input type="hidden" name="name" value="SALESmanago">
                            <input type="hidden" name="action" value="addProductCatalog">
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="sm-product-catalog-synchro-container">
                    <h1><?php _e( 'Real-time product synchronization', 'salesmanago' );?></h1>
                    <h2><?php _e( 'Product catalog setup', 'salesmanago' );?></h2>
                    <form action="" method="post">
                        <div class="sm-product-catalog-select-wrapper">
                            <select name="sm-product-catalog-select"
                                    id="sm-product-catalog-select"
                                    class="regular-text"
                                    onchange="salesmanagoShowModal()"
                            >
                                <option
                                    id="select-option-none"
                                    value=""
                                    <?php if ( empty( $active_catalog ) ) echo 'selected';?>>
                                    <?php _e( 'None (do not synchronize products in real-time)', 'salesmanago' ); ?>
                                    <!--Show disable active catalog modal INT-2394 -->
                                </option>
                                <?php
                                    foreach ( $product_catalogs as $catalog ) {
                                        $optionTag = '<option value="' . $catalog->catalogId . '" ';
                                        if ( $catalog->catalogId === $active_catalog )
                                        {
                                            $optionTag .= 'selected';
                                        }
                                        $optionTag .= '>';
                                        echo $optionTag . $catalog->name . '</option>';
                                    }
                                ?>
                            </select>
                            <input type="submit"
                                   class="button-primary"
                                   id="sm-btn-set-active-catalog"
                                   value="<?php _e( 'SAVE', 'salesmanago' ) ?>">
                            <input type="hidden" name="name" value="SALESmanago">
                            <input type="hidden" name="action" value="setActiveCatalog">
                        </div>
	                    <?php add_thickbox(); ?>
                        <a href="#TB_inline?&width=350&height=220&inlineId=sm-modal-warning-disconnect-catalog" class="thickbox" id="sm-anchor-open-warning-modal"></a>
                        <div class="sm-modal" id="sm-modal-warning-disconnect-catalog">
                            <div class="sm-center-content">
                                <h2><?php _e( 'Important', 'salesmanago' )?></h2>
                            </div>
                            <hr />
                            <p>
                                <?php _e('You are about to turn off the real-time product synchronization. As a result, your emails, Web Push notifications, and Recommendation Frames might not display accurate product data.', 'salesmanago')?>
                            </p>
                            <div class="sm-center-content">
                                <button
                                        class="button-primary"
                                        onclick="salesmanagoTurnOffCatalogSynchro()"
                                >
                                    <?php _e( 'TURN OFF', 'salesmanago' ) ?><span class="dashicons dashicons-dismiss" style="margin-top: 4px"></span>
                                </button>
                            </div>
                        </div>
                        <div class="sm-product-catalog-label-container">
                            <label for="sm-product-catalog-select">
                                <?php _e( 'Select Product Catalog for real-time synchronization', 'salesmanago' );?>
                            </label>
                        </div>
                    </form>
                    <div id="sm-product-export-container" class="sm-product-export-container">
                        <h3><?php _e( 'Export all products', 'salesmanago' );?></h3>
                        <hr />
                        <h3 class="sm-product-synchro-heading"><?php _e( 'Synchronize all products from WordPress now', 'salesmanago' );?></h3>
                        <p class="description">
		                    <?php _e( 'The export is divided into packages, each consisting of 100 products. This improves the export speed, without impacting the performance of your website.', 'salesmanago' ); ?>
                        </p>
                        <form onsubmit="return salesmanagoLaunchProductExport( event )" method="post" id="salesmanago-export-products">
                            <input
                                type="submit"
                                class="button-primary sm-btn-top-margin"
                                id="sm-btn-product-export"
                                <?php if ( empty( $active_catalog ) ) echo "disabled" ?>
                                value="<?php _e( 'START EXPORT', 'salesmanago' );?>"
                            >
                        </form>
                    </div>
                    <div id="sm-product-export-notice">
                        <div class="notice notice-info inline" id="sm-product-export-notice-type">
			                <?php _e( 'Export status', 'salesmanago' ); ?>: <span id="sm-product-export-status"><?php _e( 'Starting', 'salesmanago' ); ?></span><br>
                            <progress id="sm-product-export-progress" value="0" max="100"> 0% </progress>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        <?php endif;?>
    </div>
<?php else: ?>
    <div class="notice notice-info inline">
        <?php _e( 'In order to use Product Catalog, please install the WooCommerce plugin.', 'salesmanago' )?>
    </div>
<?php endif;?>
</div>
