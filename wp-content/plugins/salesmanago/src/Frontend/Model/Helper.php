<?php

namespace bhr\Frontend\Model;

if(!defined('ABSPATH')) exit;

use WPCF7_Submission;

class Helper
{
    use \bhr\Includes\Helper;

    const PREVENT_DOI_MAILS_TIME = 30;

    /**
     * @param $string
     * @return string
     */
    private static function atbash($string)
    {
        $atbash = Array(
            "a" => "Z", "g" => "T", "m" => "N", "s" => "H",
            "b" => "Y", "h" => "S", "n" => "M", "t" => "G",
            "c" => "X", "i" => "R", "o" => "L", "u" => "F",
            "d" => "W", "j" => "Q", "p" => "K", "v" => "E",
            "e" => "V", "k" => "P", "q" => "J", "w" => "D",
            "f" => "U", "l" => "O", "r" => "I", "x" => "C",
            "y" => "B", "z" => "A",
            "A" => "z", "G" => "t", "M" => "n", "S" => "h",
            "B" => "y", "H" => "s", "N" => "m", "T" => "g",
            "C" => "x", "I" => "r", "O" => "l", "U" => "f",
            "D" => "w", "J" => "q", "P" => "k", "V" => "e",
            "E" => "v", "K" => "p", "Q" => "j", "W" => "d",
            "F" => "u", "L" => "o", "R" => "i", "X" => "c",
            "Y" => "b", "Z" => "a",
        );

        return strtr($string, $atbash);
    }

    /**
     * @param $message
     * @param bool $compressed
     * @return string
     */
    public static function encrypt($message, $compressed = true)
    {
        if ($compressed) {
            return self::atbash(strtr(base64_encode(str_rot13(json_encode($message))), '+/=', '._-'));
        }
        return self::atbash(strtr(base64_encode(json_encode($message)), '+/=', '._-'));
    }

    /**
     * @param $message
     * @param bool $compressed
     * @return mixed|null
     */
    public static function decrypt($message, $compressed = true)
    {
        if ($compressed) {
            return json_decode(str_rot13(base64_decode(strtr(self::atbash($message), '._-', '+/='))), true);
        }
        return json_decode(base64_decode(strtr(self::atbash($message), '._-', '+/=')), true);
    }

	/**
	 * @return string
	 */
	public static function currentFilter()
	{
		if (function_exists('current_filter')) {
			return current_filter();
		}
		return '';
	}

	/**
	 * @param $namespace
	 * @param $route
	 * @param $args
	 */
	public static function registerRestRoute($namespace, $route, $args)
	{
		if (function_exists('register_rest_route')) {
			register_rest_route($namespace, $route, $args);
		}
	}

	/**
	 * @retrun void
	 */
	public static function redirectToCart()
	{
		if (function_exists('wp_redirect') && function_exists('wc_get_cart_url')) {
			wp_redirect(wc_get_cart_url());
		}
	}

	/**
	 * @return int
	 */
	public static function getCurrentUserId()
	{
		return get_current_user_id();
	}

	/**
	 * @param $formValues
	 * @param $id
	 *
	 * @return mixed|string|null
	 */
	public static function getGfFieldValue($formValues, $id)
	{
		if (function_exists('rgar')) {
			return rgar($formValues, $id);
		}
		return null;
	}

	/**
	 * @return string|null
	 */
	public static function getCurrentAction()
	{
		if (function_exists('current_action')) {
			return current_action();
		}
		return null;
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return int|mixed|void
	 */
	public static function setFilter($name, $value)
	{
		if (function_exists('apply_filters')) {
			return apply_filters($name, $value);
		}
		return 0;
	}

	/**
	 * @param $id
	 */
	public static function setCurrentUser($id)
	{
		if (function_exists('wp_set_current_user')) {
			wp_set_current_user($id);
		}
	}

	/**
	 * @param ...$args
	 *
	 * @return string
	 */
	public static function getQueryArgs(...$args)
	{
		if (function_exists('add_query_arg')) {
			return add_query_arg(...$args);
		}
		return  '';
	}

	/**
	 * @param $param
	 *
	 * @return string|void
	 */
	public static function getHomeUrl($param)
	{
		if (function_exists('home_url')) {
			return home_url($param);
		}
		return '';
	}

	/**
	 * @return WPCF7_Submission|null
	 */
	public static function getCf7SubmissionInstance()
	{
		if (class_exists('WPCF7_Submission')) {
			return WPCF7_Submission::get_instance();
		}
		return null;
	}

	/**
	 * @param $hook_name
	 * @param ...$arg
	 */
	public static function doAction($hook_name, ...$arg )
	{
		do_action($hook_name, ...$arg );
	}

    /**
     * @return bool
     */
    public static function preventMultipleDoubleOptInMails()
    {
        session_start();
        if (
            isset( $_SESSION['preventMultipleDoiMails'] )
            && $_SESSION['preventMultipleDoiMails'] + self::PREVENT_DOI_MAILS_TIME > time()
        ) {
            return false;
        } else {
            $_SESSION['preventMultipleDoiMails'] = time();
            return true;
        }
    }
}
