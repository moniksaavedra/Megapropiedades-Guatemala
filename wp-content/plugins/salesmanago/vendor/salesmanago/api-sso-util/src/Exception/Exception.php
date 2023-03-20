<?php

namespace SALESmanago\Exception;

use SALESmanago\Entity\Configuration;
use SALESmanago\Factories\ReportFactory;
use SALESmanago\Services\Report\ReportService;
use SALESmanago\Services\RequestService;

class Exception extends \Exception
{
    protected $code;
    protected $defaultEnglishMessage;
    const
        EXCEPTION_HEADER_NAME = "SALESmanago: \n",
        TRACE = "Trace: \n",
        MESSAGE = 'Message: ',
        FILE = 'File: ',
        LINE = 'Line: ';

    /**
     * @return string - massage for logs files
     */
    public function getLogMessage()
    {
        $message = self::EXCEPTION_HEADER_NAME;
        $message.= self::MESSAGE;
        $message.= $this->getMessage() . PHP_EOL;
        $message.= self::FILE . $this->getFile() . PHP_EOL;
        $message.= self::LINE . $this->getLine() . PHP_EOL;
        $message.= self::TRACE;
        $message.= $this->getTraceAsString() . PHP_EOL;

        $this->reportException($message);

        return $message;
    }

    /**
     * @return string - massage for view popups, tooltips, etc.
     */
    public function getViewMessage()
    {
        $message = $this->getMessage() . ': ';
        $message.= $this->getFile() . ': ';
        $message.= $this->getLine() . PHP_EOL;

        $this->reportException($message);

        return $message;
    }

    /**
     * @param int $code
     * @return Exception
     */
    public function setCode($code = 0)
    {
        if(is_numeric($code)) {
            $this->code = intval($code);
        } else {
            $this->code = 0;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultEnglishMessage()
    {
        return $this->defaultEnglishMessage;
    }

    /**
     * @param mixed $defaultEnglishMessage
     */
    public function setDefaultEnglishMessage($defaultEnglishMessage)
    {
        $this->defaultEnglishMessage = $defaultEnglishMessage;
        return $this;
    }

    /**
     * @param string $message
     * @return void
     */
    protected function reportException($message)
    {
        try {
            $ReportService = ReportService::getInstance();

            if($ReportService != null){
                $ReportService->reportException($message);
            }
        } catch (\Exception $e) {
            //do nothing
        }
    }
}
