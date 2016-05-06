-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 06 Mai 2016 à 17:46
-- Version du serveur :  5.7.12-0ubuntu1
-- Version de PHP :  7.0.6-1+donate.sury.org~xenial+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `my_mini_tweet`
--
CREATE DATABASE IF NOT EXISTS `my_mini_tweet` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `my_mini_tweet`;

-- --------------------------------------------------------

--
-- Structure de la table `tweets`
--

CREATE TABLE `tweets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `favorite` int(1) NOT NULL DEFAULT '0',
  `love` int(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `created_at` datetime NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tweets`
--

INSERT INTO `tweets` (`id`, `user_id`, `favorite`, `love`, `content`, `created_at`, `active`) VALUES
(1, 1, 1, 0, 'yo my first tweet\n', '2016-05-06 09:47:34', 1),
(2, 1, 1, 0, 'second tweet baby !!\nzdzd', '2016-05-06 09:48:02', 1),
(3, 1, 0, 1, 'third tweet !! changed !!!', '2016-05-06 09:48:46', 1),
(4, 1, 1, 0, 'and a fourth !! yo', '2016-05-06 09:48:58', 1),
(5, 1, 0, 0, 'yoyoyoyoyoyo', '2016-05-06 17:22:01', 0),
(6, 1, 0, 0, 'hey je rempli ce tweet', '2016-05-06 17:22:12', 1),
(7, 1, 0, 0, 'zddzdzdz', '2016-05-06 17:22:14', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `pass` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` text,
  `avatar` text,
  `created_at` datetime NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `lastname`, `firstname`, `login`, `pass`, `email`, `token`, `avatar`, `created_at`, `active`) VALUES
(1, 'Aydogmus', 'Ismail', 'isma91', '$2y$10$g/rKHiobJybzuvlK0bGndeLHf1xN/q6FtZMR1aLb8ofZ0p0QGdVgS', 'noatsuki@gmail.com', '518d3394c64bf8c2f1f0193370a311655a768bcb', 'ismail.png', '2016-05-03 12:49:09', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
