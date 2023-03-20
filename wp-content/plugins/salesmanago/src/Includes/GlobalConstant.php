<?php

namespace bhr\Includes;

class GlobalConstant
{
    const
        NICK_N = 'nickname',
        F_NAME = 'first_name',
        L_NAME = 'last_name',
        DESC   = 'description',
        LOCALE = 'locale',

        LOCATION_PREFIX = 'sm_',

        B_EMAIL     = 'billing_email',
        B_F_NAME    = 'billing_first_name',
        B_L_NAME    = 'billing_last_name',
        B_CITY      = 'billing_city',
        B_COMPANY   = 'billing_company',
        B_PHONE     = 'billing_phone',
        B_POSTCODE  = 'billing_postcode',
        B_ADDRESS_1 = 'billing_address_1',
        B_ADDRESS_2 = 'billing_address_2',
        B_COUNTRY   = 'billing_country',

        P_NO_ACC_EMAIL     = '_billing_email',
        P_NO_ACC_COMPANY   = '_billing_company',
        P_NO_ACC_F_NAME    = '_billing_first_name',
        P_NO_ACC_L_NAME    = '_billing_last_name',
        P_NO_ACC_PHONE     = '_billing_phone',
        P_NO_ACC_ADDRESS_1 = '_billing_address_1',
        P_NO_ACC_ADDRESS_2 = '_billing_address_2',
        P_NO_ACC_POSTCODE  = '_billing_postcode',
        P_NO_ACC_CITY      = '_billing_city',
        P_NO_ACC_COUNTRY   = '_billing_country',

        SINGLE_VALUE       = true,
        ID                 = 'id',
        LOGIN              = 'login',

        WP_USR_ROLE_CUSTOMER   = 'customer',
        WP_USR_ROLE_SUBSCRIBER = 'subscriber',

		API_V3_CALLBACK_URL = '/wp-json/salesmanago/v1/callbackApiV3';
}