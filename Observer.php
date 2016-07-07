<?php
interface Observer
{
    function notify($obj);
}

class Deadline
{
    static private $instance = NULL;
    private $clients = array();
    private $deadline_date;

    private function __construct()
    {}
    
    private function __clone()
    {}

    static public function getInstance()
    {
        if(self::$instance == NULL)
        {
            self::$instance = new Deadline();
        }
        return self::$instance;
    }

    public function getDeadline()
    {
        return $this->deadline_date;
    }

    public function setDeadlineMail($deadline)
    {
        $this->deadline_date = $deadline;
        $this->notifyObservers();
    }

    public function registerObserver(Observer $obj)
    {
        $this->clients[] = $obj;
    }

    function notifyObservers()
    {
        foreach($this->clients as $obj)
        {
            $obj->notify($this);
        }
    }
}

class MailItem implements Observer
{

    public function __construct()
    {
        Deadline::getInstance()->registerObserver($this);
    }

    public function notify($obj)
    {
        if($obj instanceof Deadline)
        {
            echo "Hello";
        }
    }
}

$mail1 = new MailItem();
$mail2 = new MailItem();

Deadline::getInstance()->setDeadlineMail("vasya");