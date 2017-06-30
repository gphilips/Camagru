-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:8889
-- Généré le :  Ven 30 Juin 2017 à 15:08
-- Version du serveur :  5.6.35
-- Version de PHP :  7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `db_camagru`
--
CREATE DATABASE IF NOT EXISTS `db_camagru` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_camagru`;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirm_token` varchar(50) DEFAULT NULL,
  `confirm_at` datetime DEFAULT NULL,
  `reset_token` varchar(50) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `remember_token` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `confirm_token`, `confirm_at`, `reset_token`, `reset_at`, `remember_token`) VALUES
(22, 'gphilips', 'greg.philips08@gmail.com', '1480baeda2ae0cb462acf86488798d105a940859d0818c91ca73454ed89fd8ab64f02d1a4cb7480419f2349a5502d0ec15a029b22bb61608c4b7d0e83a86026a', NULL, '2017-06-29 02:54:48', NULL, NULL, 'b4d5a6265e155025976ba0e2020acd32a05f975f79187e8bbb');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;