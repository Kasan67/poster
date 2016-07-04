<?php
include_once "index.php";

//обьявляем три массива, для списка рассылки 5, 2 и 1 дня
$emails5 = [];
$emails2 = [];
$emails1 = [];
// обьявляем переменные в гломальном скоупе
$day = "";
$message = "";
$subject = "iSky ";
$adminEmail = "spamer@sobaka.ua";

//функция получает email юзера и определяет его в нужный список для рассылки
function getEmail($UserId, $days=null)
{
    global $link, $emails5, $emails2, $emails1;
    $queryUser = $link->query("SELECT email FROM users WHERE id = $UserId");
    $items = mysqli_fetch_all($queryUser, MYSQLI_ASSOC);
    
    switch($days){
        case 5: $emails5[] = $items[0]['email'];
            return $emails5;
            break;
        case 2: $emails2[] = $items[0]['email'];
            return $emails2;
            break;
        case 1: $emails1[] = $items[0]['email'];
            return $emails1;
            break;
        default: $email = $items[0]['email'];
            return $email;
    }
    
}

// Отправляем сообщения на созданные списки
function sendEmail($emails, $subject, $message, $item)
{
    global $adminEmail;
    $email = implode(', ',$emails);
    $message .= $item['title']." ".$item['descr']." Данный пост будет удален. ";
    echo "To email: ".$email." были oтправлены письма ".$subject;
    echo "С текстом: ".$message;
    //mail($emails, $subject, $message, "From:$adminEmail");
}

// Удаление поста который был размещен более месяца назад
function deactivatePost($item)
{
    global $link;
    $postId = $item['id'];
    $userId = $item['user_id'];
    
    //изменяем статус в базе
    $deactivate = $link->query("UPDATE items SET status = 3 WHERE id=$postId");
    $email = getEmail($userId);
    $message = $item['title']." ".$item['descr']." Данный пост был удален. ";
    
    //отправляем уведомление об удалении
    $send = sendEmail($email, "Удаление поста", $message, $item);
}


//обрабатываем все данные таблицы с постами
foreach($items as $text){ 
    
    //сравниваем текущее время с сроком окончания публикаций
    $dateEnd = strtotime($text['publicated_to']);
    $dateNow = time();
    $interval = $dateEnd - $dateNow;
    $diff = floor($interval/(60*60*24));
    
    //создаем тексты сообщений согласно оставшимся дням публикации
    switch($diff){
        case 5: $day = $diff;
                $userToMail = $text['user_id'];
                $emails5 = getEmail($userToMail, $day);
                $message = "Ваша публикация будет деактивирована через : ".$day." дней. ";
                $length = count($emails5); //проверяем чтобы длинна массива для отправки была не более 100
                if($length=100){            //при достижении максимальной длинны массива инициализируется принудительная отправка  писем и очистка массива. 
                    sendEmail($emails5, $subject, $message, $text);
                    $emails5 = [];
                }
            break;
        case 2: $day = $diff;
                $userToMail = $text['user_id'];
                $emails2 = getEmail($userToMail, $day);
                $message = "Ваша публикация будет деактивирована через : ".$day." дня. ";
                $length = count($emails2);
                if($length=100){
                    sendEmail($emails2, $subject, $message, $text);
                    $emails2 = [];
                }
            break;
        case 1: $day = $diff;
                $userToMail = $text['user_id'];
                $emails1 = getEmail($userToMail, $day);
                $message = "Ваша публикация будет деактивирована через : ".$day." день. ";
                $length = count($emails1);    
                if($length=100){
                    sendEmail($emails1, $subject, $message, $text);
                    $emails1 = [];
                }
            break;
        case 0: deactivatePost($text);
            break;
    }
}
