 	Table user
 	----------------------------------------------------------------------------------
 	1 	id_user     int(11)     AUTO_INCREMENT    User_ID
	2 	login 	    varchar(64)                   логин
	3 	passw 	    varchar(64)   MD5             пароль
	4 	email 	    varchar(128)                  email
	5 	access_time datetime                      дата регистрации на сайте
	6 	booble_index	int(11)                     для сортировки (пока не используется)
	7 	ip 	        varchar(15)                   IP адрес (пока не используется)
	8 	status 	    smallint(6) 			            Статус (1-4) Deprecated
	9 	isblocked 	smallint(6)                   0/1  1-Заблокирован
	10 	cid         varchar(16)                   хэш ключ
	11 	cname 	    varchar(16)                   имя в соцсетях Deprecated
	12  utype       varchar(6)                    тип пользователя (промоутер, работодатель, РА, ...)


Table user_attr
 	----------------------------------------------------------------------------------
  1   id            int(11)     AUTO_INCREMENT
  2   id_user       int(11)                     связь многие к одному с табл. user
  3   van           varchar(6)                  ключ  (цвет волос, рост, телефон, ...)
  4   val           varchar(255)                описание

  -- В эти 2 таблицы умещаются все типы пользователей со всеми аттрибутами/анкетами,
  -- больше нет отдельных таблиц и полей для РА, промоутеров и работодателей