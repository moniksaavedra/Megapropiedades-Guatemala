<?php

namespace SALESmanago\Exception;

class ApiV3Exception extends Exception
{
    const
        WARNING = 'warn',
        ERROR = 'err';

    /**
     * @var array
     */
    private $codes = [];

    /**
     * @var array
     */
    private $messages = [];

    /**
     * Sets error codes
     *
     * @param array $codes
     * @return $this
     */
    public function setCodes(array $codes)
    {
        $this->codes = $codes;
        return $this;
    }

    /**
     * Return error codes
     *
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }

    /**
     * Set messages
     *
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Return messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Combined error codes with messages [errorCode => errorMessage]
     *
     * @return array
     */
    public function getCombined()
    {
        $combined = [];
        foreach ($this->getCodes() as $index => $code) {
            $combined[$code] = $this->messages[$index];
        }
        return $combined;
    }

    /**
     * Based on statusCode return message which may be
     * translated to different language
     *
     * @param int $code
     * @param string $field
     * @param string $fieldType
     * @return string
     */
    public function getParsedMessage($code, $field = null, $fieldType = null)
    {
        switch($code) {
            case 10:
                $message = 'Authentication failed. Make sure your API Key is valid.';
                break;
            case 11:
                $message = 'The following field exceeds limit: $field$';
                break;
            case 12:
                $message = 'The following field was too long and was trimmed: $field$';
                break;
            case 13:
                $message = 'Missing required field. Required $field$';
                break;
            case 14:
                $message = 'The following field is of wrong type. $field$ must be of type $fieldType$';
                break;
            case 15:
                $message = 'A resource with the following field already exists in SALESmanago: $field$';
                break;
            case 16:
                $message = 'Trying to access resource with identifier not present in SALESmanago';
                break;
            case 17:
                $message = 'Trying to set a required value as null';
                break;
            case 18:
                $message = 'Value not matching a required structure (RegEx). For the field: $field$';
                break;
            case 19:
                $message = 'Trying to add a resource above available limit';
                break;
            default:
                $message = 'Success';
                break;
        }

        if ($field) {
            $message = str_replace('$field$', $field, $message);
        }

        if ($fieldType) {
            $message = str_replace('$fieldType$', $fieldType, $message);
        }

        return $message;
    }

    /**
     * @see https://docs.salesmanago.com/v3/#general-errors-and-warnings
     * @param string $type - self::WARNING || self::ERROR
     * @return array
     */
    public function getAllViewMessages($type = null)
    {
        $codes = $this->getCodes();
        $messages = $this->getMessages();

        $response = [];

        foreach ($codes as $key => $code) {
            if (($type === self::WARNING && $code !== 12)
                || ($type === self::ERROR && $code === 12)) {
                continue;
            }

            $field = null;
            $fieldType = null;
            if (10 < $code && $code < 19) {
                $field = explode('|', $messages[$key])[0];
                switch ($code) {
                    case 14:
                        $fieldWithType = $field;
                        $field = explode(':', $fieldWithType)[0];
                        $fieldType = explode(':', $fieldWithType)[1];
                        break;
                }
            }

            $response[$key] = $this->getParsedMessage($code, $field, $fieldType);
        }

        return $response;
    }
}
