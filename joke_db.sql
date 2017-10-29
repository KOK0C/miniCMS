-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 01 2017 г., 20:40
-- Версия сервера: 5.7.19
-- Версия PHP: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `joke_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Животные'),
(2, 'Блондинки'),
(3, 'Пешеходы'),
(4, 'Работники'),
(5, 'Черный юмор'),
(6, 'Пошлое'),
(7, 'Медицина'),
(8, 'Толстяки'),
(9, 'Пища'),
(10, 'Религия'),
(11, 'Дети'),
(12, 'Брак'),
(13, 'Мужчины и женщины'),
(15, 'Политика'),
(16, 'Пати'),
(17, 'Полиция&Армия'),
(18, 'Поп культура'),
(19, 'Учеба'),
(20, 'Спорт'),
(21, 'Технологии'),
(22, 'Путеществия'),
(23, 'Разное'),
(24, 'Семья'),
(25, 'Загадки'),
(26, 'Философия'),
(27, 'Юриспруденция'),
(28, 'Работа'),
(29, 'Деньги'),
(32, 'SW');

-- --------------------------------------------------------

--
-- Структура таблицы `jokes`
--

CREATE TABLE `jokes` (
  `joke_id` int(11) NOT NULL,
  `joke_text` text NOT NULL,
  `joke_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(11) NOT NULL,
  `is_edit` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jokes`
--

INSERT INTO `jokes` (`joke_id`, `joke_text`, `joke_date`, `author_id`, `is_edit`) VALUES
(1, 'У мужа и жены четыре сына. Трое старших - высокие с рыжими волосами и легкой кожей, в то время как младший сын низкий с черными волосами и темными глазами.\r\nОтец был на смертном одре, когда он повернулся к жене и сказал: «Дорогая, прежде чем я умру, будь честна со мной: мой младший сын мой ребенок?»\r\nЖена ответила: «Я клянусь всем что свято, что он твой сын».\r\nС этим муж скончался. Жена пробормотала: «Слава Богу, он не спрашивал о трех других».', '2017-09-23 12:58:14', 4, 0),
(2, 'Человек живет на высоком уровне на 15-м этаже. Каждое утро, по дороге на работу, он спускается на лифте до первого этажа. Но когда он приходит домой, он поднимается на лифте только на восьмой этаж и идет до конца. Единственное исключение - когда идет дождь. Почему?\r\nМужчина в маленького роста и не может дотянуться до кнопок. Когда идет дождь, у него есть зонтик, поэтому он может дотянуться до кнопки 15 с его помощью.', '2017-09-23 13:05:17', 4, 0),
(3, 'Когда вы умираете в возрасте 72-х лет, независимо от того, от чего вы умираете, это естественные причины. Даже если вас сбивает грузовик, это естественная причина. Потому что, если бы вы были моложе, вы бы ушели с дороги.', '2017-09-23 13:09:40', 2, 0),
(4, 'Билл Гейтс настолько богат, что нанял рак, чтобы убить Стива Джобса.', '2017-09-23 13:13:43', 4, 0),
(5, 'Его Святейшество Папа Римский разрешил одному известному журналисту взять у него интервью, но решил это делать не у себя в покоях, а прокатиться по Тибру в лодке. Журналист гребет и задает вопросы, а Папа на них отвечает. Тут порыв ветра содрал шляпу с головы журналиста и швырнул ее в воду. Папа встал, переступил через борт лодки, прошелся по воде до шляпы, поднял ее, вернулся назад в лодку и протянул ее обалдевшему журналисту. На следующий день все газеты пестрели заголовками: \r\n— Папа Римский не умеет плавать!', '2017-09-23 13:18:30', 1, 0),
(6, '- Я не понимаю: приходишь к бедняку - он приветлив и помогает, как может. Приходишь к богачу - он никого не видит. Неужели это только из-за денег? \r\n- Выгляни в окно. Что ты видишь? \r\n- Женщину с ребенком, повозку, едущую на базар... \r\n- Хорошо. А теперь посмотри в зеркало. Что ты там видишь? \r\n- Ну что я могу там видеть? Только себя самого. \r\n- Так вот: окно из стекла и зеркало из стекла. Стоит только добавить немного серебра, и уже видишь только себя.', '2017-09-23 13:20:08', 3, 0),
(7, 'Зал суда… Длиться процесс развода. Судья обращается к женщине : \r\n– Уважаемая, почему вы хотите развестись со своим мужем? \r\nЖенщина отвечает с возмущением: \r\n– Господин судья, он меня в постели не устраивает! \r\nИз зала слышаться женские возгласы: \r\n– Гляньте на неё, всех устраивает, а её не устраивает! \r\nСразу слышаться мужские голоса: \r\n– Да её вообще никто не устраивает!', '2017-09-23 13:25:43', 1, 0),
(8, 'В одной компьютерной фирме среднего пошиба была практика вкладывать в коробку с компьютером краткое описание его характеристик на простом языке — для продавцов (набрали чайников). И вот однажды врывается злобный клиент, с купленным намедни компьютером и требует немедленно вернуть деньги, демонстрируя бумажку, обнаруженную на дне коробки. Бумажка гласила: \"Материнка г%вно, видюха отстой. Впарить лoху\".', '2017-09-23 13:27:01', 2, 0),
(9, 'Гуляю по парку, впереди идет девушка с чихуахуа в розовом, собачка трясется вся. Хозяйка прячет ее в сумку. \"Фу, убогая псина, носят, как косметичку\", — думаю, гордо иду с ротвейлером на поводке. Подходим к ларьку за кофе, эта девушка перед нами. Утро, холодно, все молчат, ждут. И вдруг рык, вопль! Какая-то баба нечесаная отскакивает в сторону, а чихуа висит у нее на руке. Воровка полезла в сумку, не знала, что там собака. Проверили карманы — оказалось, успела вытащить мой телефон! А мой и ухом не повел', '2017-09-23 13:28:13', 4, 0),
(10, 'Одесский трамвай. Утро. Народ едет на работу. Недовольный дамский голос: \r\n- Ну шо за времена таки наступили? Настоящих мужчин вообще не осталось!! Это форменное безобразие – сами сидят, а женщины таки стоят...!! \r\n- Имею Вам заметить, шо настоящие женщины таки ещё спят – работают только лошади! А лошади спят стоя...', '2017-09-23 13:30:32', 2, 0),
(11, 'В Англии проводится заплыв по Темзе. \r\nПризнанный фаворит, большой любитель женщин, приплыл последним. \r\nВсе в недоумении. \r\nЖурналистка берет у него интервью: \r\n- Мистер Смит, но почему Вы пришли последним?! \r\nВедь Вы же лучший пловец Лондона! \r\n- Вы понимаете, сейчас тепло, на берегах Темзы загорают полураздетые женщины. \r\nМой организм реагирует на них независимо от меня. \r\nУвеличивается сопротивление воды. \r\nЖурналистка, смущаясь: \r\n-Но вы могли бы перевернуться и плыть на спине. \r\n- А мосты, мэм?', '2017-09-23 13:33:06', 1, 0),
(12, '— Че это у вашего Паши за привычка — дверь в кабинет к нам ногой открывать? \r\n— Он в ОМОНе служил, раньше вообще перед тем как зайти гранату закидывал. Еле отучили...', '2017-09-23 13:33:47', 2, 0),
(13, 'Мужчина, уберите свою собаку! А то по мне блохи скачут! \r\n- Тузик! Отойди! Не видишь, у девушки блохи!', '2017-09-29 13:17:39', 4, 1),
(14, '— Про помощи молотка вы убили десятки человек и ограбили сотни. Что вы можете сказать в свое оправдание? \r\n— Подсудимый, сядьте и перестаньте паясничать.', '2017-09-23 13:34:47', 3, 0),
(15, 'Рассказал знакомый гаишник. Утро часов 11.Последний пост ГАИ на трассе перед городом. Пустая дорога. Вдруг летит иномарка на бешеной скорости. Гаец ее тормозит.За рулем блондинистая дама.Он объясняет, что по этой трассе можно ехать только км. на 80 медленней, поэтому он вынужден выписать штраф. Дама в возмущении. Говорит, что в автошколе,которую она недавно окончила, их учили, что правильней двигаться со скоростью потока. Гаишник: \"Ну и где вы видите поток?\" Дама:\"Я ЕГО ДОГОНЯЮ\".Даже штрафовать не стал.', '2017-09-23 13:35:42', 3, 0),
(16, 'В одном морском университете работает преподаватель, который известен всему вузу тем, что никогда не ставит на экзамене двойки. Вот один студент поспорил с друзьями на ящик водки, что таки получит от него неуд. Вопрос на экзамене достался этому студенту донельзя простой: «Поведение на судне в экстремальной ситуации». Преподаватель, обращаясь к студенту: \r\n— Ну-с? \r\n— Не знаю! — отвечает студент. \r\n— Если, допустим, на судне возник пожар, что вы будете делать? \r\n— Ничего! \r\n— Правильно! Главное — не паниковать! Идите, четыре!', '2017-09-23 13:37:14', 4, 0),
(17, 'Идет Будда с учениками по дороге. Видит: яма, в ней вол, крестьянин пытается его вытащить, но сил не хватает. Он кивнул ученикам, они быстро помогли вытащить животное. Идут дальше. Снова яма, в ней вол, на краю сидит крестьянин и горько плачет. Будда прошел мимо и как бы не заметил. Ученики его спрашивают: \r\n— Учитель, почему Ты не захотел помочь этому крестьянину? \r\n— Помочь плакать?', '2017-09-23 13:38:29', 3, 0),
(18, 'Циля, где ты работаешь? \r\n— В аэропорту, туалеты мою. \r\n— Ну, шо это за работа, брось её! \r\n— Шо?! Вот так просто взять и уйти из авиации?!', '2017-09-23 13:39:37', 2, 0),
(19, 'Вчера, метро — время 8:40. Сел в вагон (точнее наверное меня туда внесло потоком) на станции Новокузнецкая. На следующей станции (Театральная) народ большим потоком выходит из вагона и такой же большой поток вливается обратно... Схватиться за поручень практически невозможно, поэтому народ держится кто за что может. Двери закрываются и тут одна девушка (Д) громко так, на весь вагон, обращается к молодому парню(П), стоящему рядом с ней: \r\nД: Мужчина! Не держите меня за грудь! \r\nП: У Вас ее нет!!! \r\nД: Хам!!! \r\nП: Фантазерка!!! \r\nНарод в вагоне просто лежал... Позитив с утра был обеспечен.', '2017-09-23 13:40:48', 4, 0),
(20, '— Девушка, а Вы животных любите? \r\n— Очень! \r\n— Возьмите меня к себе, я такая скотина...', '2017-09-23 13:41:30', 1, 0),
(21, 'Журналисты спрашивают у фермера:\r\n- Скажите, как у вас прошел год.\r\n- Не поверите, замечательно. Урожай зерна хороший - без хлеба не\r\nостанусь, картошка удалась - опять таки буду не голодный, а еще свинья\r\nопоросилась...\r\n- Вы не хотели бы поблагодарить за это президента?\r\n- Да с чего ж? Пахал сам, сеял сам, растил и собирал опять таки сам - в\r\nчем тут его заслуга.\r\n- Как так? (жестко) А вы подумайте!\r\n- А, ну ежли подумать, то насчет свиньи не отрицаю, тут всяко могло\r\nбыть...', '2017-09-23 18:58:49', 2, 0),
(22, 'Москва. Центр. Стоит мужик в пробке. Вдруг стук в окно. Опускает стекло и спрашивает, что надо.\r\n- Понимаете, террористы взяли премьер-министра Путина в заложники и требуют 10 миллионов долларов выкуп, иначе они обольют его бензином и подожгут. Мы решили пройти по машинам, кто сколько даст.\r\n- Ну... я - литров 5 могу дать...', '2017-09-29 12:27:46', 1, 1),
(23, 'Помню случай был у меня. Рано утром звонит чувак и говорит такой \"Але,а куда я попал?\" я отвечаю \"На 17 межгалактический совет джедаев\",он с таким потерянным голocом \"Бляяяяяяяя, извините, пожалуйста\" и добавляет \"Да прибудет с вами сила\"...и кладет трубку.', '2017-09-29 13:18:30', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `joke_category`
--

CREATE TABLE `joke_category` (
  `joke_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `joke_category`
--

INSERT INTO `joke_category` (`joke_id`, `category_id`) VALUES
(1, 5),
(1, 24),
(2, 5),
(2, 25),
(3, 5),
(4, 5),
(5, 4),
(5, 5),
(5, 10),
(6, 26),
(7, 12),
(7, 13),
(7, 27),
(8, 21),
(8, 23),
(8, 28),
(9, 1),
(10, 13),
(10, 23),
(11, 6),
(11, 13),
(12, 17),
(13, 1),
(13, 13),
(14, 27),
(15, 2),
(15, 17),
(16, 19),
(17, 26),
(18, 4),
(18, 28),
(19, 13),
(20, 13),
(21, 15),
(22, 5),
(22, 15),
(22, 29),
(23, 23),
(23, 32);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `hash_password` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `date_registration` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_type` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `user_email`, `hash_password`, `name`, `surname`, `date_registration`, `user_type`) VALUES
(1, 'radchenko1995@gmail.com', '$2y$10$P9vaIzvUvt3wxZpZepBwkOQNl2.aJnpQZJCGAICW8rSnyQNge.4Vy', 'Игорь', 'Радченко', '2017-09-25 19:30:29', 2),
(2, 'The_victory_will_be_for_me@galaxy.com', '$2y$10$xAvgYNjP5zjfxfTysSiJKuvQB6dENbdfdsE9ac0QD9tdscC7JOw1K', 'Dart', 'Vader', '2017-09-25 20:12:48', 1),
(3, 'pabloboss@gmail.com', '$2y$10$DZ4FjrnLgkvxLZdH4aelWOBYKLxMsB334ZGdHkMRF3FVV1t15Lsz.', 'Пабло', 'Эскобар', '2017-09-25 20:24:38', 1),
(4, 'griffin@mail.ru', '$2y$10$Kjlk.sA8ymwERN3vE0R9UOBvsoWcJGfqej1a/zwf0Y2Ek0JxTehZK', 'Питер', 'Гриффин', '2017-09-26 16:49:00', 1),
(5, 'prikol@example.com', '$2y$10$X4ByBhISpDhqZL6E8cNuwec0W9xu5/OJKcOZ.7EA1anz0oObdoCfC', 'Джон', 'Смит', '2017-09-30 12:39:38', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users_type`
--

CREATE TABLE `users_type` (
  `user_type_id` int(11) NOT NULL,
  `user_type_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_type`
--

INSERT INTO `users_type` (`user_type_id`, `user_type_name`) VALUES
(1, 'user'),
(2, 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `jokes`
--
ALTER TABLE `jokes`
  ADD PRIMARY KEY (`joke_id`);

--
-- Индексы таблицы `joke_category`
--
ALTER TABLE `joke_category`
  ADD PRIMARY KEY (`joke_id`,`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `users_type`
--
ALTER TABLE `users_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `jokes`
--
ALTER TABLE `jokes`
  MODIFY `joke_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `users_type`
--
ALTER TABLE `users_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;