# poster

ТЗ от компании ІSky выполнено при помощи паттернов Singleton, Factory и Builder

Отправка email уведомлений о завершении срока публикации объявлений.

Есть таблица объявлений:
CREATE TABLE IF NOT EXISTS `items` (
  id int(11) unsigned NOT NULL auto_increment, -- ID объявления
  user_id int(11) unsigned NOT NULL default 0, -- ID пользователя
  status tinyint(1) unsigned NOT NULL default 1, -- статус объявления
  title varchar(150) NOT NULL default '', -- заголовок объявления
  link text, -- ссылка на страницу просмотра объявления
  descr text, -- описание объявления
  publicated_to timestamp NOT NULL default '0000-00-00 00:00:00', -- срок окончания публикации объявления
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


И таблица пользователей:
CREATE TABLE IF NOT EXISTS `users` (
  id int(11) unsigned NOT NULL auto_increment, -- ID пользователя
  email text, -- Email пользователя
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


Цикл публикации объявления:
Пользователь заходит на сайт объявлений и размещает свое объявление на сайте.
После размещения оно публикуется на период в 30 дней, по истечению срока снимается с публикации.
1) Неопубликовано: status = 1, publicated_to = не указана
2) Опубликовано: status = 2, publicated_to = дата завершения срока публикации
3) Снято с публикации: status = 3, publicated_to = дата снятия с публикации


- Выполняется рассылка email-уведомления о скором завершении срока публикации объявления на email-адрес пользователя (владельца объявления).
- Уведомление должно отправляться за 1,2,5 дней до завершения срока публикации.


=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
