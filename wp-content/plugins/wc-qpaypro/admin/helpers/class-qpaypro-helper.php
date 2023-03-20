<?php

namespace qpaypro_woocommerce\Admin\Helpers;

use qpaypro_woocommerce\Admin\Util;

class QPayPro_Helper
{

    function __construct() {

    	$this->API_URL = '';
    	$this->Public_Key = '';
    	$this->API_Key = '';
    	$this->API_Secret = '';
    	$this->Merchant_ID = '';
        $this->frm_data = '';
        $this->frm_entry_id = '';
        $this->request_data = '';
    }

    public function set_frm_data($values, $entry_id) {
        $this->frm_data = $values;
        $this->frm_entry_id = $entry_id;
    }


    public function send_http_request($data, $method = 'POST') {

        if(defined('dl_qpp_staging') && true === dl_qpp_staging) {
            echo 'Data sent: <pre>' . print_r($data, true) . '</pre><br>';
        }

        $result = wp_remote_post( $this->API_URL, array( 
              'method'    => 'POST', 
              'body'      => json_encode( $data ), 
              'timeout'   => 90, 
              'sslverify' => true, 
              'headers' => array( 'Content-Type' => 'application/json' ) 
            ) ); 

        if ( is_wp_error( $result ) ) {
             $error_message = $result->get_error_message();
              echo "Something went wrong: $error_message";
        }

        $response_body = wp_remote_retrieve_body($result);

        if(defined('dl_qpp_staging') && true === dl_qpp_staging) {
            echo "<br>Response: <pre>" . print_r($response_body, true) . "</pre><br>";
        }

        return json_decode($response_body, true);

    }


    public function set_request_data($data) {
    	$this->request_data = $data;
    }

    public function set_api_url($url) {
    	$this->API_URL = $url;
    }

    public function make_payment() {
        $response_body = $this->send_http_request($this->request_data);
        $Logger = new Util\Logger();
        $payment_result = false;
        $notification = "";
        $notification_client = "";
        if (is_array($response_body)) {
            // 100 o 200 means the transaction was a success
            if ( array_key_exists("responseCode", $response_body) && ($response_body['responseCode'] == '100' || $response_body['responseCode'] == '00') ) {
                // Payment successful
                $payment_result = true;
                $notification_client = __("Successful payment. ", 'wp-qpaypro-woocommerce');
                $notification = __("Successful payment.", 'wp-qpaypro-woocommerce');
                $Logger->log("successful payment: " . print_r($response_body, true) );                          
            } else {
                //transiction fail
                $notification_client = __("Payment failed. ", 'wp-qpaypro-woocommerce');
                $notification = __("Payment failed. ", 'wp-qpaypro-woocommerce');
                if (array_key_exists("title",$response_body)) {
                    $notification .= "Desición: " . strval($response_body['title']);
                }
                if (array_key_exists("responseText",$response_body)) {
                    $notification .= "Descripción: " . strval(print_r($response_body['responseText'], true));
                    $notification_client .= strval(print_r($response_body['responseText'], true));
                }
                if (array_key_exists("responseCode",$response_body)) {
                    $notification .= 'Código de error: ' . strval($response_body['responseCode']);
                }
                $Logger->log("Payment failed: " . print_r($response_body, true) );
            }
        }
        else {
            $payment_result = false;
            $notification_client = __("The connection with the payment gateway service could not be completed. Please try again later.", 'wp-qpaypro-woocommerce');
            $notification = "No response was obtained from the qPayPro service";
        }

        $payment = array(
            'result' => $payment_result,
            "message" => $notification_client 
            );

        return $payment;

    }                                                              
                                              

}


