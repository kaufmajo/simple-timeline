-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql24j14.db.hostpoint.internal
-- Erstellungszeit: 23. Okt 2023 um 08:32
-- Server-Version: 10.6.15-MariaDB-log
-- PHP-Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `tajo_timeline`
--
CREATE DATABASE IF NOT EXISTS `tajo_timeline` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tajo_timeline`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tajo1_datum`
--

CREATE TABLE `tajo1_datum` (
  `datum_id` int(11) NOT NULL,
  `datum_datum` date NOT NULL,
  `datum_tag` int(11) NOT NULL,
  `datum_monat` int(11) NOT NULL,
  `datum_jahr` int(11) NOT NULL,
  `datum_woche` int(11) DEFAULT NULL,
  `datum_wochentag` int(11) DEFAULT NULL,
  `datum_wochentag_lang_de` varchar(10) DEFAULT NULL,
  `datum_wochentag_lang_en` varchar(10) DEFAULT NULL,
  `datum_monat_lang_de` varchar(10) DEFAULT NULL,
  `datum_monat_lang_en` varchar(10) DEFAULT NULL,
  `datum_datum_1_de` varchar(10) DEFAULT NULL,
  `datum_datum_2_de` varchar(10) DEFAULT NULL,
  `datum_datum_3_de` varchar(10) DEFAULT NULL,
  `datum_datum_4_de` varchar(10) DEFAULT NULL,
  `datum_datum_5_de` varchar(10) DEFAULT NULL,
  `datum_datum_6_de` varchar(10) DEFAULT NULL,
  `datum_datum_7_de` varchar(10) DEFAULT NULL,
  `datum_datum_8_de` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tajo1_history`
--

CREATE TABLE `tajo1_history` (
  `history_id` int(11) NOT NULL,
  `history_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `history_ip` varchar(45) DEFAULT NULL,
  `history_url` varchar(10000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tajo1_lnk_datum_termin`
--

CREATE TABLE `tajo1_lnk_datum_termin` (
  `lnk_id` int(11) NOT NULL,
  `datum_id` int(11) NOT NULL,
  `termin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tajo1_media`
--

CREATE TABLE `tajo1_media` (
  `media_id` int(11) NOT NULL,
  `media_parent_id` int(11) DEFAULT NULL,
  `media_groesse` int(11) NOT NULL DEFAULT 0,
  `media_mimetype` varchar(255) DEFAULT NULL,
  `media_name` varchar(255) NOT NULL,
  `media_anzeige` varchar(255) DEFAULT NULL,
  `media_von` date DEFAULT NULL,
  `media_bis` date DEFAULT NULL,
  `media_tag` varchar(100) DEFAULT NULL,
  `media_hash` varchar(100) NOT NULL,
  `media_privat` tinyint(1) NOT NULL DEFAULT 0,
  `media_box` tinyint(1) NOT NULL DEFAULT 0,
  `media_erstellt_am` timestamp NOT NULL DEFAULT current_timestamp(),
  `media_aktualisiert_am` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tajo1_termin`
--

CREATE TABLE `tajo1_termin` (
  `termin_id` int(11) NOT NULL,
  `termin_ansicht` varchar(50) NOT NULL DEFAULT '',
  `termin_status` varchar(50) NOT NULL,
  `termin_datum_start` date NOT NULL,
  `termin_datum_ende` date NOT NULL,
  `termin_zeit_start` time NOT NULL,
  `termin_zeit_ende` time NOT NULL,
  `termin_zeit_ganztags` int(1) NOT NULL DEFAULT 1,
  `termin_betreff` varchar(1024) NOT NULL,
  `termin_text` varchar(1024) NOT NULL,
  `termin_kategorie` varchar(255) NOT NULL,
  `termin_mitvon` varchar(255) NOT NULL,
  `termin_image` varchar(255) DEFAULT NULL,
  `termin_link` varchar(255) DEFAULT NULL,
  `termin_label` varchar(255) DEFAULT NULL,
  `termin_link_titel` varchar(255) DEFAULT NULL,
  `termin_erstellt_am` timestamp NOT NULL DEFAULT current_timestamp(),
  `termin_aktualisiert_am` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `termin_aktualisiert_am_trigger` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `termin_ist_geloescht` int(1) NOT NULL DEFAULT 0,
  `termin_ist_konfliktrelevant` int(1) NOT NULL DEFAULT 1,
  `termin_zeige_konflikt` int(1) NOT NULL DEFAULT 1,
  `termin_zeige_einmalig` int(1) NOT NULL DEFAULT 0,
  `termin_zeige_tagezuvor` int(11) DEFAULT NULL,
  `termin_aktiviere_drucken` int(1) NOT NULL DEFAULT 1,
  `termin_sortierung` int(11) GENERATED ALWAYS AS (case when `termin_status` = '0_normal' then 110 when `termin_status` = '1_bild' then 100 when `termin_status` = '3_gestrichen' then 120 when `termin_status` = '5_warnung' then 130 when `termin_status` = '9_mitteilung' then 90 else 200 end) STORED,
  `termin_notiz` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tajo1_terminHistory`
--

CREATE TABLE `tajo1_terminHistory` (
  `terminHistory_id` int(11) NOT NULL,
  `terminHistory_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `trigger_table` varchar(255) NOT NULL,
  `trigger_action` varchar(255) NOT NULL,
  `termin_id` int(11) DEFAULT NULL,
  `termin_status` varchar(50) DEFAULT NULL,
  `termin_datum_start` date DEFAULT NULL,
  `termin_datum_ende` date DEFAULT NULL,
  `termin_zeit_start` time DEFAULT NULL,
  `termin_zeit_ende` time DEFAULT NULL,
  `termin_zeit_ganztags` int(1) DEFAULT NULL,
  `termin_betreff` varchar(1024) DEFAULT NULL,
  `termin_text` varchar(1024) DEFAULT NULL,
  `termin_kategorie` varchar(255) DEFAULT NULL,
  `termin_mitvon` varchar(255) DEFAULT NULL,
  `termin_link` varchar(255) DEFAULT NULL,
  `termin_label` varchar(255) DEFAULT NULL,
  `termin_link_titel` varchar(255) DEFAULT NULL,
  `termin_erstellt_am` timestamp NULL DEFAULT NULL,
  `termin_aktualisiert_am` timestamp NULL DEFAULT NULL,
  `termin_aktualisiert_am_trigger` timestamp NULL DEFAULT NULL,
  `termin_ist_geloescht` int(1) DEFAULT NULL,
  `termin_ist_konfliktrelevant` int(1) DEFAULT NULL,
  `termin_zeige_konflikt` int(1) DEFAULT NULL,
  `termin_zeige_einmalig` int(1) DEFAULT NULL,
  `termin_zeige_tagezuvor` int(11) DEFAULT NULL,
  `termin_aktiviere_drucken` int(1) DEFAULT NULL,
  `termin_ansicht` varchar(50) DEFAULT NULL,
  `termin_notiz` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tajo1_user`
--

CREATE TABLE `tajo1_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tajo1_datum`
--
ALTER TABLE `tajo1_datum`
  ADD PRIMARY KEY (`datum_id`),
  ADD UNIQUE KEY `UNIQUE_datum_a_idx` (`datum_tag`,`datum_monat`,`datum_jahr`),
  ADD UNIQUE KEY `UNIQUE_datum_b_idx` (`datum_datum`);

--
-- Indizes für die Tabelle `tajo1_history`
--
ALTER TABLE `tajo1_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indizes für die Tabelle `tajo1_lnk_datum_termin`
--
ALTER TABLE `tajo1_lnk_datum_termin`
  ADD PRIMARY KEY (`lnk_id`),
  ADD KEY `FK_lnk_datum_termin_b` (`termin_id`),
  ADD KEY `FK_lnk_datum_termin_a` (`datum_id`);

--
-- Indizes für die Tabelle `tajo1_media`
--
ALTER TABLE `tajo1_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `FK_media_a` (`media_parent_id`);

--
-- Indizes für die Tabelle `tajo1_termin`
--
ALTER TABLE `tajo1_termin`
  ADD PRIMARY KEY (`termin_id`),
  ADD KEY `INDEX_termin_e_idx` (`termin_datum_start`),
  ADD KEY `INDEX_termin_f_idx` (`termin_datum_ende`),
  ADD KEY `INDEX_termin_g_idx` (`termin_zeit_start`),
  ADD KEY `INDEX_termin_h_idx` (`termin_zeit_ende`);
ALTER TABLE `tajo1_termin` ADD FULLTEXT KEY `FULLTEXT_termin_a_idx` (`termin_betreff`,`termin_kategorie`,`termin_mitvon`);

--
-- Indizes für die Tabelle `tajo1_terminHistory`
--
ALTER TABLE `tajo1_terminHistory`
  ADD PRIMARY KEY (`terminHistory_id`);

--
-- Indizes für die Tabelle `tajo1_user`
--
ALTER TABLE `tajo1_user`
  ADD PRIMARY KEY (`user_id`) USING BTREE,
  ADD UNIQUE KEY `user_name` (`user_name`) USING BTREE,
  ADD UNIQUE KEY `user_email` (`user_email`) USING BTREE;

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tajo1_datum`
--
ALTER TABLE `tajo1_datum`
  MODIFY `datum_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tajo1_history`
--
ALTER TABLE `tajo1_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tajo1_lnk_datum_termin`
--
ALTER TABLE `tajo1_lnk_datum_termin`
  MODIFY `lnk_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tajo1_media`
--
ALTER TABLE `tajo1_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tajo1_termin`
--
ALTER TABLE `tajo1_termin`
  MODIFY `termin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tajo1_terminHistory`
--
ALTER TABLE `tajo1_terminHistory`
  MODIFY `terminHistory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tajo1_user`
--
ALTER TABLE `tajo1_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tajo1_lnk_datum_termin`
--
ALTER TABLE `tajo1_lnk_datum_termin`
  ADD CONSTRAINT `FK_lnk_datum_termin_a` FOREIGN KEY (`datum_id`) REFERENCES `tajo1_datum` (`datum_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_lnk_datum_termin_b` FOREIGN KEY (`termin_id`) REFERENCES `tajo1_termin` (`termin_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `tajo1_media`
--
ALTER TABLE `tajo1_media`
  ADD CONSTRAINT `FK_media_a` FOREIGN KEY (`media_parent_id`) REFERENCES `tajo1_media` (`media_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
