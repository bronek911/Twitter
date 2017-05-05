-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 05 Maj 2017, 14:15
-- Wersja serwera: 10.1.21-MariaDB
-- Wersja PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `twitter`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE `comments` (
  `id_comment` int(11) NOT NULL,
  `id_tweet` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `commentText` varchar(160) COLLATE utf8_polish_ci NOT NULL,
  `dateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`id_comment`, `id_tweet`, `id_user`, `commentText`, `dateTime`) VALUES
(24, 10, 15, 'sasasassas2', '2017-04-23 16:57:49'),
(26, 1, 15, 'Nice!', '2017-04-23 17:06:36'),
(27, 23, 16, 'abc', '2017-04-23 17:10:26'),
(28, 10, 15, 'abc', '2017-04-23 20:17:06'),
(29, 23, 15, 'Działa!', '2017-05-05 13:54:07');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `conversation`
--

CREATE TABLE `conversation` (
  `id_conversation` int(11) NOT NULL,
  `id_sender` int(11) NOT NULL,
  `id_receiver` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `conversation`
--

INSERT INTO `conversation` (`id_conversation`, `id_sender`, `id_receiver`) VALUES
(1, 1, 15),
(3, 15, 16);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `messages`
--

CREATE TABLE `messages` (
  `id_message` int(11) NOT NULL,
  `id_conversation` int(11) NOT NULL,
  `id_sender` int(11) NOT NULL,
  `message` varchar(1500) COLLATE utf8_polish_ci NOT NULL,
  `dateTime` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `messages`
--

INSERT INTO `messages` (`id_message`, `id_conversation`, `id_sender`, `message`, `dateTime`, `status`) VALUES
(7, 3, 16, 'dsfgs', '2017-05-05 12:55:42', 0),
(8, 3, 16, 'asdfa', '2017-05-05 12:55:52', 0),
(9, 3, 16, 'bronus2', '2017-05-05 12:56:29', 0),
(10, 3, 15, 'zxcazxc', '2017-05-05 13:00:24', 0),
(11, 3, 15, 'sasdc', '2017-05-05 13:05:11', 0),
(12, 1, 15, 'asdcas', '2017-05-05 13:05:22', 0),
(13, 3, 15, 'reh', '2017-05-05 13:59:12', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tweets`
--

CREATE TABLE `tweets` (
  `id_tweet` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tweet` varchar(160) COLLATE utf8_polish_ci NOT NULL,
  `dateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `tweets`
--

INSERT INTO `tweets` (`id_tweet`, `id_user`, `tweet`, `dateTime`) VALUES
(1, 1, 'Tweet 1', '2017-04-03 04:12:24'),
(10, 15, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras non ornare ligula, at volutpat felis. Phasellus blandit laoreet orci eu elementum. Aliquam semper', '2017-04-23 11:37:52'),
(23, 16, 'abc', '2017-04-23 17:10:21');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `hash_pass` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `img_src` varchar(128) COLLATE utf8_polish_ci NOT NULL DEFAULT 'img\\users\\default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `hash_pass`, `img_src`) VALUES
(1, 'bronek9-11@tlen.pl', 'bronek', '$2y$10$hEr8RpyV.XQ/uLT9g3rdLuPXd.OkR9yQszKWK5zk5CUA0KzJ5Jmge', 'img\\users\\default.png'),
(15, 'bronus911@gmail.com', 'bronus', '$2y$11$63X4LCHORqmdIdm0EBGLIOGNC3BX.4Bj1U211lf9QwsCWBK2Zn/66', 'img\\users\\bronek.jpg'),
(16, 'abc@abc.pl', 'bronus2', '$2y$11$mcMJsYW8rFwkgH7fXddHzOTt3QeRynTufohH1u7wNY6BEYHIxr7o6', 'img\\users\\default.png');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `comment_tweet` (`id_tweet`),
  ADD KEY `comment_user` (`id_user`);

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id_conversation`),
  ADD KEY `id_sender` (`id_sender`),
  ADD KEY `id_receiver` (`id_receiver`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `id_conversation` (`id_conversation`),
  ADD KEY `message_sender` (`id_sender`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id_tweet`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT dla tabeli `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id_conversation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT dla tabeli `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT dla tabeli `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id_tweet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_tweet` FOREIGN KEY (`id_tweet`) REFERENCES `tweets` (`id_tweet`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `id_receiver` FOREIGN KEY (`id_receiver`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_sender` FOREIGN KEY (`id_sender`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `id_conversation` FOREIGN KEY (`id_conversation`) REFERENCES `conversation` (`id_conversation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_sender` FOREIGN KEY (`id_sender`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `tweets`
--
ALTER TABLE `tweets`
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
