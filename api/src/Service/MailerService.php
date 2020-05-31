<?php


namespace App\Service;


class MailerService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $text;

    /**
     * MailerService constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $to
     */
    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function send()
    {
        $message = (new \Swift_Message())
            ->setFrom($_ENV['MAILER_FROM'])
            ->setSubject($this->subject)
            ->setTo($this->to)
            ->setBody(
                $this->text,
                'text/html'
            )
        ;

        if ($this->mailer->send($message)) return true;
        else return false;

    }
}