<?php
include_once "index.php";
include_once "Db.php";
include_once "Builder.php";

$db = Db::getInstance(); 
$link = $db->getConnect();
$result = $link->query("SELECT * FROM items WHERE status = 2"); 
$items = mysqli_fetch_all($result, MYSQLI_ASSOC); 
$mail5;
$emails2 = [];
$emails1 = [];
$emailsDisabled = [];

// Инициализация управляющего
$mailBuilder = new MailBuilder();

// Инициализация Фабрики строителей
$concretBuilder = new Factory();
$builder = $concretBuilder->factoryMethod(5);

// Подготовка и отправка писем
$mailBuilder->setBuilderMail( $builder );
$mailBuilder->constructMail("email@email");
$mail = $mailBuilder->getMail();

//обрабатываем все данные таблицы с постами
foreach($items as $text){ 
    //сравниваем текущее время с сроком окончания публикаций
    $dateEnd = strtotime($text['publicated_to']);
    $dateNow = time();
    $interval = $dateEnd - $dateNow;
    $diff = floor($interval/(60*60*24));
    switch($diff){
        case 5: $email = getEmail($text['user_id']);
            //setMailBuilder($email, 5);

            break;
        case 2: $email = getEmail($text['user_id']);
            //setMailBuilder($email, 2);
            break;
        case 1: $email = getEmail($text['user_id']);
            //setMailBuilder($email, 1);
            break;
        case 0: $email = getEmail($text['user_id']);
            deactivatePost($text['id']);
            //setMailBuilder($email, 0);
            break;
    } 
    
}

////функция получает email юзера и определяет его в нужный список для рассылки
function getEmail($UserId)
{
    global $link;
    $queryUser = $link->query("SELECT email FROM users WHERE id = $UserId");
    $items = mysqli_fetch_all($queryUser, MYSQLI_ASSOC);
    $email = $items[0]['email'];
    return $email;
}

// Удаление поста который был размещен более месяца назад
function deactivatePost($postId)
{
    global $link;
    //изменяем статус в базе
    $link->query("UPDATE items SET status = 3 WHERE id=$postId");
}

//function setMailBuilder($email, $days)
//{
//    $mailBuilder = new MailBuilder();
//    // Инициализация Фабрики строителей
//    $concretBuilder = new Factory();
//    $builder = $concretBuilder->factoryMethod($days);
//
//    // Подготовка и отправка писем
//    $mailBuilder->setBuilderMail( $builder );
//    $mailBuilder->constructMail($email);
//    return $mailBuilder;
//}

var_dump($mail->sendMail());