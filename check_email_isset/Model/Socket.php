<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 2018-12-07
 * Time: 23:43
 */

/**
 * Class Socket
 * @package Model
 */
class Socket
{
    /**
     * MX server
     * @var array
     */
    private $mxRecords;

    /**
     * Email to
     * @var string
     */
    private $emailTo;

    /**
     * Email from
     * @var string
     */
    private $emailFrom;
    
    /**
     * Domain email
     * @var string
     */
    public $domain;
    
    /**
     * Server port
     * @var int
     */
    private $port = 25;

    /**
     * Socket resource
     * @var resource
     */
    private $socket;

    /**
     * Connection log
     * @var array
     */
    private $connectionLog;

    /**
     * Response string or false
     * @var bool|string
     */
    private $response;

    /**
     * Connect to socket
     *
     * @param string $emailTo
     * @param string $emailFrom
     * @return bool
     */
    public function connect($emailTo, $emailFrom = 'email@gmail.com'): bool
    {
        $this->emailTo = $emailTo;
        $this->emailFrom = $emailFrom;
        $this->getDomainByEmail();
        $this->getMxRecords();

        if (!count($this->mxRecords)) {
            return false; // no mx records..
        }

        return $this->checkEmailValid();
    }

    /**
     * Extract the domain.
     * @return void
     */
    private function getDomainByEmail(): void
    {
        $this->domain = explode('@', $this->emailTo)[1];
    }

    /**
     * Defining the domain's MX server
     * @return array
     */
    private function getMxRecords(): array
    {
        getmxrr($this->domain, $this->mxRecords);

        return $this->mxRecords;
    }

    /**
     * Check valid email
     * @return bool
     */
    private function checkEmailValid(): bool
    {
        $isValid = true; // assume the address is valid by default..
        $sResponseCode = null;

        foreach ($this->mxRecords as $mxRecord) {
            if ($isValid && $sResponseCode === null) {
                // open the connection..
                $this->socket = @fsockopen($mxRecord, $this->port, $errno, $errstr, 30);
                $_ = @fgets($this->socket);
                if (!$this->socket) {
                    $this->connectionLog['Connection'] = 'ERROR';
                    $this->connectionLog['ConnectionResponse'] = $errstr;
                    $isValid = false; // unable to connect..
                } else {
                    $this->connectionLog['Connection'] = 'SUCCESS';
                    $this->connectionLog['ConnectionResponse'] = $errstr;
                    $isValid = true; // so far so good..
                }

                if (!$isValid) {
                    return false;
                }

                $this->writeHELO();
                $this->writeMailFrom();
                $this->writeRCPT();

                // get the response code..
                $sResponseCode = substr($this->connectionLog['MailToResponse'], 0, 3);
                $baseResponseCode = $sResponseCode !== '' ? (int)$sResponseCode[0] : 0;

                $this->writeQuit();

                // get the quit code and response..
                $this->connectionLog['QuitResponse'] = $this->response;
                $this->connectionLog['QuitCode'] = substr($this->response, 0, 3);

                if ($baseResponseCode === 5) {
                    $isValid = false; // the address is not valid..
                }

                // close the connection..
                @fclose($this->socket);
            }
        }

        return $isValid;
    }

    /**
     * Say hello to the server
     * @return void
     */
    private function writeHELO(): void
    {
        fwrite($this->socket, "HELO {$this->domain}\r\n");
        $this->response = fgets($this->socket);
        $this->connectionLog['HELO'] = $this->response;
    }

    /**
     * Send the email from
     * @return void
     */
    private function writeMailFrom(): void
    {
        fwrite($this->socket, "MAIL FROM: <{$this->emailFrom}>\r\n");
        $this->response = fgets($this->socket);
        $this->connectionLog['MailFromResponse'] = $this->response;
    }

    /**
     * Send the email to
     * @return void
     */
    private function writeRCPT(): void
    {
        fwrite($this->socket, "RCPT TO: <{$this->emailTo}>\r\n");
        $this->response = fgets($this->socket);
        $this->connectionLog['MailToResponse'] = $this->response;
    }

    /**
     * Say goodbye
     * @return void
     */
    private function writeQuit(): void
    {
        fwrite($this->socket,"QUIT\r\n");
        $this->response = fgets($this->socket);
    }
}