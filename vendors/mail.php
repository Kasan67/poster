<?php
include_once "index.php";
include_once "Db.php";
include_once "Builder.php";

$db = Db::getInstance(); 
$link = $db->getConnect();
$result = $link->query("SELECT * FROM items WHERE status = 2"); 
$items = mysqli_fetch_all($result, MYSQLI_ASSOC); 

$mail5 = '';
$mail2 = '';
$mail1 = '';
$mail0 = '';

//обрабатываем все данные таблицы с постами
foreach($items as $text){ 
    //сравниваем текущее время с сроком окончания публикаций
    $dateEnd = strtotime($text['publicated_to']);
    $dateNow = time();
    $interval = $dateEnd - $dateNow;
    $diff = floor($interval/(60*60*24));
    switch($diff){
        case 5: $email = getEmail($text['user_id']);
            $mail5 = setMailBuilder($email, 5, $mail5);
            //пример для проверки письма которое будет отправлено
            //var_dump($mail5->sendMail()); 
            break;
        case 2: $email = getEmail($text['user_id']);
            $mail2 = setMailBuilder($email, 2, $mail2);
            break;
        case 1: $email = getEmail($text['user_id']);
            $mail1 = setMailBuilder($email, 1, $mail1);
            break;
        case 0: $email = getEmail($text['user_id']);
            deactivatePost($text['id']);
            $mail0 = setMailBuilder($email, 0, $mail0);
            break;
    } 
    
}

//функция получает email юзера
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

function setMailBuilder($email, $days, $obj)
{
    if(!is_object($obj)){
        $obj = new MailBuilder();
        // Инициализация Фабрики строителей
        $concretBuilder = new Factory();
        $builder = $concretBuilder->factoryMethod($days);
        // Подготовка писем
        $obj->setBuilderMail($builder);
        $obj->constructMail($email);
        return $obj;
    }else{
        $obj->constructMail($email);
        return $obj;                              
    }
}
