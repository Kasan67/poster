<?php
include_once('Db.php');

interface Mailer
{   
    private $adminEmail;
    public $subject;
    public function sendMail($mailto);
}

class FiveDayMail implements Mailer
{
    private $adminEmail ="";
    public $subject = "Your publication";
    public function sendMail($mailto)
    {
        $message = "Five days before disable the publication";
        
    }
}

class TwoDayMail implements Mailer
{
    private $adminEmail ="";
    public $subject = "Your publication";
    public function sendMail($mailto)
    {
        $message = "Two days before disable the publication";
        
    }
}

class OneDayMail implements Mailer
{
    private $adminEmail ="";
    public $subject = "Your publication";
    public function sendMail($mailto)
    {
        $message = "One day before disable the publication";
        
    }
}

class DisablePublicationMail implements Mailer
{
    private $adminEmail ="";
    public $subject = "Your publication";
    public function sendMail($mailto)
    {
        $message = "The publication disabled";
        
    }
}

class Sender
{
    private $mailer;
    function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    function execute()
    {
        $mails[] = $this->mailer->sendMail("Calc101");
        $mails[] = $this->mailer->sendMail("Stat2000");

        return $mails;
    }
}


switch($context){
    case 5: $context = new Context(new FiveDayMail());
        break;
    case 2: $context = new Context(new TwoDayMail());
        break;
    case 1: $context = new Context(new OneDayMail());
        break;
    case 0: $context = new Context(new DisablePublicationMail());
        break
    }
$context->execute();