<?php

/**
 * Mail - Базовый объект письма
 */
class Mail 
{    
    private $_adminMail = "fromthis@gmail.com";
    private $_subject = "";
    private $_message = "";
    private $_mailTo = "";

    public function setSubject($subject) 
    {
        $this->_subject = $subject;
    }
    
    public function setMessage($message) 
    {
        $this->_message = $message;
    }
    
    public function setMailTo($mailTo) 
    {
        $this->_mailTo = $mailTo;
    }
    
    public function sendMail()
    {
        $result = array($this->_mailTo, $this->_subject, $this->_message, $this->_adminMail);
        return $result;
    }
}

/**
 * Builder - Абстрактный строитель
 */
abstract class BuilderMail 
{
    
    protected $_mail;

    public function getMail() 
    {
        return $this->_mail;
    }
    
    public function createNewMail() 
    {
        $this->_mail = new Mail ();
    }

    abstract public function buildSubject();
    abstract public function buildMessage();
    abstract public function buildMailTo($email);
    
}

/**
 * BuilderMailFiveDays - Конкретный строитель писем за 5 дней до деактивации
 */
class BuilderMailFiveDays extends BuilderMail 
{    
    public function buildSubject() 
    {
        $this->_mail->setSubject ( "Publication on site" );
    }
    
    public function buildMessage() 
    {
        $this->_mail->setMessage ( "Five days before disable the publication" );
    }
    
    public function buildMailTo($email) 
    {
        $this->_mail->setMailTo ($email);
    }
    
}

/**
 * BuilderMailTowDays - Конкретный строитель писем за 2 дня до деактивации
 */
class BuilderMailTowDays extends BuilderMail 
{
    public function buildSubject() 
    {
        $this->_mail->setSubject ( "Publication on site" );
    }
    
    public function buildMessage() 
    {
        $this->_mail->setMessage ( "Two days before disable the publication" );
    }
    
    public function buildMailTo($email) 
    {
        $this->_mail->setMailTo ($email);
    }
    
}

/**
 * BuilderMailOneDay - Конкретный строитель писем за один день до деактивации
 */
class BuilderMailOneDay extends BuilderMail 
{    
    public function buildSubject() 
    {
        $this->_mail->setSubject ( "Publication on site" );
    }
    
    public function buildMessage() 
    {
        $this->_mail->setMessage ( "One day before disable the publication" );
    }
    
    public function buildMailTo($email) 
    {
        $this->_mail->setMailTo ($email);
    }
    
}

/**
 * BuilderMailDisable - Конкретный строитель писем o деактивации
 */
class BuilderMailDisable extends BuilderMail 
{    
    public function buildSubject() 
    {
        $this->_mail->setSubject ( "Publication on site" );
    }
    
    public function buildMessage() 
    {
        $this->_mail->setMessage ( "The publication disabled" );
    }
    
    public function buildMailTo($email) 
    {
        $this->_mail->setMailTo ($email);
    }
    
}

/**
 * Director - Управляющий класс, запускающий строительство
 * MailBuilder - Реализация патерна Builder, показывающая делегирование процесса создания писем методу constructMail
 */
class MailBuilder 
{
    private $_builderMail;

    public function setBuilderMail(BuilderMail $mb)
    {
        $this->_builderMail = $mb;
    }

    public function getMail()
    {
        return $this->_builderMail->getMail();
    }
    
    public function constructMail($email) 
    {
        $this->_builderMail->createNewMail();
        $this->_builderMail->buildSubject();
        $this->_builderMail->buildMessage();
        $this->_builderMail->buildMailTo($email);
    }
}

// Инициализация управляющего
$mailBuilder = new MailBuilder();

// Инициализация доступных видов
$builderMailFiveDays  = new BuilderMailFiveDays();
$builderMailTowDays = new BuilderMailTowDays();
$builderMailOneDay = new BuilderMailOneDay();
$builderMailDisable = new BuilderMailDisable();

// Подготовка и отправка писем
$mailBuilder->setBuilderMail( $builderMailTowDays );
$mailBuilder->constructMail("mymail@gmail.com");
$mail = $mailBuilder->getMail();

var_dump($mail->sendMail());
