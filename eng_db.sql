-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Час створення: Бер 02 2016 р., 19:07
-- Версія сервера: 5.5.45
-- Версія PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База даних: `eng_db`
--

-- --------------------------------------------------------

--
-- Структура таблиці `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(2) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп даних таблиці `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Rest and leisure'),
(2, 'The human body'),
(3, 'Education');

-- --------------------------------------------------------

--
-- Структура таблиці `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `word_id` int(5) NOT NULL,
  `category_id` int(2) NOT NULL,
  PRIMARY KEY (`word_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `category`
--

INSERT INTO `category` (`word_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 2),
(7, 2),
(8, 2),
(9, 3),
(10, 3),
(11, 3);

-- --------------------------------------------------------

--
-- Структура таблиці `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `word_id` int(5) NOT NULL,
  `type_id` int(1) NOT NULL,
  `translation` varchar(30) NOT NULL,
  PRIMARY KEY (`word_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `translations`
--

INSERT INTO `translations` (`word_id`, `type_id`, `translation`) VALUES
(1, 1, 'дозвілля'),
(2, 1, 'курорт'),
(3, 1, 'пансіонат'),
(4, 1, 'похід'),
(5, 1, 'аптечка'),
(6, 1, 'потилиця'),
(7, 1, 'лоб'),
(8, 1, 'челюсть'),
(9, 1, 'виховання'),
(10, 3, 'вихований'),
(11, 3, 'грамотний');

-- --------------------------------------------------------

--
-- Структура таблиці `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `type_id` int(1) NOT NULL,
  `type_name` varchar(15) NOT NULL,
  `type_translation` varchar(15) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `types`
--

INSERT INTO `types` (`type_id`, `type_name`, `type_translation`) VALUES
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
-- Структура таблиці `words_list`
--

CREATE TABLE IF NOT EXISTS `words_list` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `word` varchar(30) NOT NULL,
  `audio` varchar(35) NOT NULL,
  `transcription` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп даних таблиці `words_list`
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