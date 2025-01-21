<?php
/**
 *
 */
class email_settings{
    private $SMTPAuth;                               // Enable SMTP authentication
    private $Host;                    // Set the SMTP server to send through
    private $Username;                 // SMTP username
    private $Password;                           // SMTP password
    private $SMTPSecure;                            // Enable TLS encryption, `ssl` also accepted
    private $Port;                                    // TCP port to connect to
    private $From;
    private $FromName;

    function __construct(){
        $this->SMTPAuth = true;                               // Enable SMTP authentication
        $this->Host='smtp.ionos.com';                    // Set the SMTP server to send through
        $this->Username = 'noreply@acepsales.app';                 // SMTP username
        $this->Password = 'fcXykkr%~lsYr-Hn';                           // SMTP password
        $this->SMTPSecure = 'tlc';                            // Enable TLS encryption, `ssl` also accepted
        $this->Port = 587;                                    // TCP port to connect to
        $this->From = 'noreply@acepsales.app';
        $this->FromName = 'NoReply Acepsales App';
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->Host;
    }

    /**
     * @param string $Host
     */
    public function setHost(string $Host): void
    {
        $this->Host = $Host;
    }

    /**
     * @return bool
     */
    public function isSMTPAuth(): bool
    {
        return $this->SMTPAuth;
    }

    /**
     * @param bool $SMTPAuth
     */
    public function setSMTPAuth(bool $SMTPAuth): void
    {
        $this->SMTPAuth = $SMTPAuth;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->Username;
    }

    /**
     * @param string $Username
     */
    public function setUsername(string $Username): void
    {
        $this->Username = $Username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->Password;
    }

    /**
     * @param string $Password
     */
    public function setPassword(string $Password): void
    {
        $this->Password = $Password;
    }

    /**
     * @return string
     */
    public function getSMTPSecure(): string
    {
        return $this->SMTPSecure;
    }

    /**
     * @param string $SMTPSecure
     */
    public function setSMTPSecure(string $SMTPSecure): void
    {
        $this->SMTPSecure = $SMTPSecure;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * @param int $Port
     */
    public function setPort(int $Port): void
    {
        $this->Port = $Port;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->From;
    }

    /**
     * @param string $From
     */
    public function setFrom(string $From): void
    {
        $this->From = $From;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->FromName;
    }

    /**
     * @param string $FromName
     */
    public function setFromName(string $FromName): void
    {
        $this->FromName = $FromName;
    }
}
?>