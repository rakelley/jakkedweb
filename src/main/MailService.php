<?php
/**
 * @package jakkedweb
 * @subpackage main
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */
namespace main;

use Psr\Log\LogLevel;

/**
 * Main implementation of IMail
 */
class MailService implements \rakelley\jhframe\interfaces\services\IMail
{
    use \rakelley\jhframe\traits\GetsServerProperty;

    /**
     * Store for admin email account
     * @var string
     */
    protected $accountAdmin;
    /**
     * Store for primary email account
     * @var string
     */
    protected $accountMain;
    /**
     * IIo service instance
     * @var object
     */
    protected $io;
    /**
     * ILogger instance
     * @var object
     */
    protected $logger;
    /**
     * UserAccount repo instance
     * @var object
     */
    protected $users;


    /**
     * @param \rakelley\jhframe\interfaces\services\IConfig $config
     * @param \rakelley\jhframe\interfaces\services\IIo     $io
     * @param \rakelley\jhframe\interfaces\services\ILogger $logger
     * @param \main\repositories\UserAccount                $users
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IConfig $config,
        \rakelley\jhframe\interfaces\services\IIo $io,
        \rakelley\jhframe\interfaces\services\ILogger $logger,
        \main\repositories\UserAccount $users
    ) {
        $this->io = $io;
        $this->logger = $logger;
        $this->users = $users;

        $this->accountAdmin = $config->Get('APP', 'email_admin');
        $this->accountMain = $config->Get('APP', 'email_main');
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\services\IMail::Send()
     */
    public function Send($recipient, $title, $body, $sender=self::ACCOUNT_MAIN,
                         $headers=null)
    {
        $recipient = (!is_int($recipient)) ? $recipient :
                     $this->getValueForConstant($recipient);
        $sender = (!is_int($sender)) ? $sender :
                  $this->getValueForConstant($sender);
        $headers = ($headers) ?: $this->getDefaultHeaders($sender);

        if (is_array($recipient)) {
            array_walk(
                $recipient,
                function($r) use ($title, $body, $headers) {
                    $this->io->toMail($r, $title, $body, $headers);
                }
            );
        } else {
            $this->io->toMail($recipient, $title, $body, $headers);
        }

        $this->logMail($recipient, $sender);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\services\IMail::getValueForConstant()
     */
    public function getValueForConstant($const)
    {
        switch ($const) {
            case self::ACCOUNT_MAIN:
                return $this->accountMain;
                break;
            case self::ACCOUNT_ADMIN:
                return $this->accountAdmin;
                break;
            case self::ALL_ADMIN_ACCOUNTS:
                return $this->users->getAdmins();
                break;
            default:
                throw new \DomainException('Undefined Constant Value Used');
                break;
        }
    }


    /**
     * Prepares default set of email headers
     *
     * @param  string $sender Email sender
     * @return string
     */
    protected function getDefaultHeaders($sender)
    {
        $main = $this->getValueForConstant(self::ACCOUNT_MAIN);
        $headers = 'From: ' . $main . "\r\n"
                 . 'Content-type: text/html; charset=utf-8' . "\r\n";

        if ($sender !== $main) {
            $headers .= 'Reply-To: ' . $sender . "\r\n";
        }

        return $headers;
    }


    /**
     * Internal method for logging that a mail was sent
     * 
     * @param  array|string $recipient Email recipient(s)
     * @param  string       $sender    Email sender
     * @return void
     */
    protected function logMail($recipient, $sender)
    {
        if (is_array($recipient)) {
            $recipient = implode(', ', $recipient);
        }
        $ip = $this->getServerProp('REMOTE_ADDR');

        $message = <<<TXT
Outgoing Mail Sent
    From: {$sender}
    To: {$recipient}
    Originating IP: {$ip}

TXT;

        $this->logger->Log(LogLevel::INFO, $message);
    }
}
