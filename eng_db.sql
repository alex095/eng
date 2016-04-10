-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 10 2016 г., 23:46
-- Версия сервера: 5.5.47-0ubuntu0.14.04.1
-- Версия PHP: 5.6.20-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `eng_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Rest and leisure'),
(2, 'The human body'),
(3, 'Education');

-- --------------------------------------------------------

--
-- Структура таблицы `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `word_id` int(5) NOT NULL,
  `type_id` int(1) NOT NULL,
  `category_id` int(2) NOT NULL,
  `translation` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `translations`
--

INSERT INTO `translations` (`id`, `word_id`, `type_id`, `category_id`, `translation`) VALUES
(1, 1, 1, 1, 'дозвілля'),
(2, 2, 1, 1, 'курорт'),
(3, 3, 1, 1, 'пансіонат'),
(4, 4, 1, 1, 'похід'),
(5, 5, 1, 1, 'аптечка'),
(6, 6, 1, 2, 'потилиця'),
(7, 7, 1, 2, 'лоб'),
(8, 8, 1, 2, 'челюсть'),
(9, 9, 1, 3, 'виховання'),
(10, 10, 3, 3, 'вихований'),
(11, 11, 3, 3, 'грамотний'),
(22, 6, 2, 3, 'test-1'),
(23, 6, 7, 1, 'test-2'),
(24, 8, 3, 3, 'test-3');

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(1) NOT NULL,
  `type_name` varchar(15) NOT NULL,
  `type_translation` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `types`
--

INSERT INTO `types` (`id`, `type_name`, `type_translation`) VALUES
(1, 'Noun', 'Іменник'),
(2, 'Verb', 'Дієслово'),
(3, 'Adjective', 'Прикметник'),
(4, 'Adverb', 'Прислівник'),
(5, 'Pronoun', 'Займенник'),
(6, 'Preposition', 'Прийменник'),
(7, 'Conjunction', 'Сполучник'),
(8, 'Another', 'Інше');

-- --------------------------------------------------------

--
-- Структура таблицы `words_list`
--

CREATE TABLE IF NOT EXISTS `words_list` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `word` varchar(30) NOT NULL,
  `audio` varchar(35) NOT NULL,
  `transcription` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп данных таблицы `words_list`
--

INSERT INTO `words_list` (`id`, `word`, `audio`, `transcription`) VALUES
(1, 'leisure', 'leisure.mp3', '[ ˈleʒ.ər ]'),
(2, 'resort', 'resort.mp3', '[ rɪˈzɔːt ]'),
(3, 'boarding house', 'boarding_house.mp3', '[ ˈbɔː.dɪŋ ] [ haʊs ]'),
(4, 'walking tour', 'walking_tour.mp3', '[ ˈwɔːkɪŋ ] [ tʊə(r) ]'),
(5, 'first aid kit', 'first_aid_kit.mp3', '[ fɜːst ] [ eɪd ] [ kɪt ]'),
(6, 'back of the head', 'back_of_the_head.mp3', '[ bæk ] [ əv ] [ ðə ] [ hed ]'),
(7, 'forehead', 'forehead.mp3', '[ ˈfɒr.ɪd ]'),
(8, 'jaw', 'jaw.mp3', '[ dʒɔː ]'),
(9, 'upbringing', 'upbringing.mp3', '[ ˈʌpˌbrɪŋ.ɪŋ ]'),
(10, 'well-bred', 'well-bred.mp3', '[ wel ] [ bred ]'),
(11, 'literate', 'literate.mp3', '[ ˈlɪt.ər.ət ]');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
