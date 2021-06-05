-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 31, 2021 at 10:01 AM
-- Server version: 10.5.9-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seawatch`
--
CREATE DATABASE IF NOT EXISTS `seawatch` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `seawatch`;

-- --------------------------------------------------------

--
-- Table structure for table `behaviour`
--

DROP TABLE IF EXISTS `behaviour`;
CREATE TABLE `behaviour` (
  `id` int(5) NOT NULL COMMENT 'the unique identifier for the behaiour',
  `behaviour` varchar(30) NOT NULL COMMENT 'the type of behaviour'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `behaviour`
--

INSERT INTO `behaviour` (`id`, `behaviour`) VALUES
(1, 'Surfacing'),
(2, 'Normal Swim'),
(3, 'Fast Swim'),
(4, 'blow'),
(5, 'Feeding'),
(6, 'Leap/Breach'),
(7, 'Tail Slap'),
(8, 'Bow-ride'),
(9, 'Rest/Milling'),
(10, 'Sexual'),
(11, 'Aggression');

-- --------------------------------------------------------

--
-- Table structure for table `confidence`
--

DROP TABLE IF EXISTS `confidence`;
CREATE TABLE `confidence` (
  `id` int(5) NOT NULL COMMENT 'the unique identifier for confidence',
  `name` varchar(60) NOT NULL COMMENT 'the confidence level for the sighting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `confidence`
--

INSERT INTO `confidence` (`id`, `name`) VALUES
(1, 'Definate'),
(2, 'Probable'),
(3, 'Possible');

-- --------------------------------------------------------

--
-- Table structure for table `environment`
--

DROP TABLE IF EXISTS `environment`;
CREATE TABLE `environment` (
  `id` bigint(20) NOT NULL COMMENT 'the unique identifier for the data',
  `seawatch` bigint(20) NOT NULL COMMENT 'the unique seawatch identifier',
  `start` time NOT NULL COMMENT 'the start time',
  `end` time NOT NULL COMMENT 'the end time',
  `sea_state` int(5) NOT NULL COMMENT 'the sea state id for the recording',
  `swell_height` int(5) NOT NULL COMMENT 'the swell height id for the recording',
  `wind_direction` int(5) NOT NULL COMMENT 'the wind direction id for the recording',
  `visibility` int(5) NOT NULL COMMENT 'the visibilty id for the recording',
  `additional_notes` mediumtext NOT NULL COMMENT 'any additional observations'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `seawatch`
--

DROP TABLE IF EXISTS `seawatch`;
CREATE TABLE `seawatch` (
  `id` bigint(20) NOT NULL COMMENT 'the unique identifier',
  `date` datetime NOT NULL COMMENT 'the date the seawatch was undertaken',
  `station` int(5) NOT NULL COMMENT 'the seawatch station id where the seawatch was undertaken',
  `volunteer` int(5) NOT NULL COMMENT 'The volunteer id who undertook the seawatch'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sea_state`
--

DROP TABLE IF EXISTS `sea_state`;
CREATE TABLE `sea_state` (
  `id` int(5) NOT NULL COMMENT 'The unique identifier for the sea state',
  `state` int(2) NOT NULL COMMENT 'the sea state',
  `description` varchar(60) NOT NULL COMMENT 'a brief description for the sea state'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sea_state`
--

INSERT INTO `sea_state` (`id`, `state`, `description`) VALUES
(1, 0, 'mirror calm'),
(2, 1, 'slight ripples, no foam crests'),
(3, 2, 'small wavelets, glassy crests, but no whitecaps'),
(4, 3, 'large wavelets, crests begin to break, few whitecaps'),
(5, 4, 'longer waves,  many whitecaps'),
(6, 5, 'moderate waves of longer form, some spray'),
(7, 6, 'large waves, whitecaps everywhere, frequent spray'),
(8, 7, 'sea heaps up, white foam blows in streaks'),
(9, 8, 'long, high waves  edges breaking, foam blows in streaks'),
(10, 9, 'high waves, sea begins to roll, dense foam streaks');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` bigint(20) NOT NULL COMMENT 'the unique identifier for the session',
  `volunteer` int(5) NOT NULL COMMENT 'the volunteer id the session is for',
  `access_token` varchar(100) NOT NULL COMMENT 'The access token supplied to the voluteer',
  `access_expiry` datetime NOT NULL COMMENT 'the date and time the access token expires',
  `refresh_token` varchar(100) NOT NULL COMMENT 'the token used to re-supply an expired access token',
  `refresh_expiry` datetime NOT NULL COMMENT 'the refresh token exoiry date and time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sighting`
--

DROP TABLE IF EXISTS `sighting`;
CREATE TABLE `sighting` (
  `id` bigint(20) NOT NULL COMMENT 'the unique identifier for the sighting',
  `seawatch` bigint(20) NOT NULL COMMENT 'the seawatch id for the sighting',
  `first_seen` time NOT NULL COMMENT 'the time the sighting was first seen',
  `last_seen` time NOT NULL COMMENT 'the time the sighting was last seen',
  `species` int(5) NOT NULL COMMENT 'the id of the species seen',
  `confidence` int(5) NOT NULL COMMENT 'the confidence id for the sighting',
  `group_size` int(5) NOT NULL COMMENT 'the number of cetaceans recorded',
  `calves` int(5) NOT NULL COMMENT 'the number of calves in the sighting',
  `juveniles` int(5) NOT NULL COMMENT 'the number of juveniles in the sighting',
  `bearing` int(5) NOT NULL COMMENT 'the direction id for the direction the cetaceans were travelling',
  `distance` int(5) NOT NULL COMMENT 'the distance in km from the station',
  `behaviour` int(5) NOT NULL COMMENT 'the behaviour id for  how the sightings group were behaving',
  `associated_birds` mediumtext NOT NULL COMMENT 'any associated birds travelling with the group'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `species`
--

DROP TABLE IF EXISTS `species`;
CREATE TABLE `species` (
  `id` int(5) NOT NULL COMMENT 'the unique identifier for the species',
  `species` varchar(60) NOT NULL COMMENT 'the name of the species'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `species`
--

INSERT INTO `species` (`id`, `species`) VALUES
(1, 'Harbour Porpoise'),
(2, 'Common Dolphin'),
(3, 'Bottlenose Dolphin'),
(4, 'White Beaked Dolphin'),
(5, 'Long Finned Pilot Wahle'),
(6, 'Killer Whale or Orca'),
(7, 'Minke Whale'),
(8, 'Fin Whale'),
(9, 'Humpback Whale'),
(10, 'Sperm Whale'),
(11, 'Risso\'s Dolphin'),
(12, 'Cuvier\'s Beaked Whale'),
(13, 'Sei Whale'),
(14, 'Northern Bottlenose Dolphin'),
(15, 'Atlantic White-sided Dolphin'),
(16, 'Sowerby\'s Beaked Whale');

-- --------------------------------------------------------

--
-- Table structure for table `station`
--

DROP TABLE IF EXISTS `station`;
CREATE TABLE `station` (
  `id` int(5) NOT NULL COMMENT 'the unique identifier for the station',
  `name` varchar(60) NOT NULL COMMENT 'the name of the seawatch station',
  `latitude` float(9,7) NOT NULL COMMENT 'the latitude for the position of the seawatch station',
  `longitude` float(9,7) NOT NULL COMMENT 'the longitude for the position of the seawatch station'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `station`
--

INSERT INTO `station` (`id`, `name`, `latitude`, `longitude`) VALUES
(3, 'Aldborough', 53.8369484, -0.0915600),
(4, 'Bay Ness', 54.4413872, -0.5225000),
(5, 'Bempton Cliffs', 54.1515999, -0.1749900),
(6, 'Bridlington south beach', 54.0743713, -0.2011900),
(7, 'Cayton Bay South', 54.2416992, -0.3393200),
(8, 'Cloughton Newlands', 54.3500595, -0.4361700),
(9, 'Cloughton Wyke', 54.3400002, -0.4318125),
(10, 'Danes Dyke', 54.1045685, -0.1414800),
(11, 'Filey Brigg', 54.2175102, -0.2698400),
(12, 'Filey North', 54.2191887, -0.2768700),
(13, 'Flamborough Head', 54.1164017, -0.0773100),
(14, 'Flamborough Head North Landing', 54.1307182, -0.1031500),
(15, 'Flamborough South Landing', 54.1044579, -0.1184060),
(16, 'Hornsea North', 53.9151993, -0.1611000),
(17, 'Hornsea South', 53.9064484, -0.1570500),
(18, 'Humber Bridge', 53.7150497, -0.4505900),
(19, 'Huntcliff', 54.5861511, -0.9499800),
(20, 'Kettleness', 54.5294304, -0.7176600),
(21, 'Long Nab', 54.3313217, -0.4184800),
(22, 'Mappleton', 53.8760796, -0.1338000),
(23, 'Marine Drive, Scarborough', 54.2876015, -0.3845600),
(24, 'Old Nab', 54.5564194, -0.7758800),
(25, 'Ravenscar Radar Station', 54.3936386, -0.4736900),
(26, 'Scarborough North Bay', 54.3501015, -0.4087400),
(27, 'Spurn Warren', 53.6125717, -0.1446490),
(28, 'The Deep', 53.7386017, -0.3296400),
(29, 'Whitby Abbey', 54.4899101, -0.6053700),
(30, 'Whitby Hgh Light', 54.4781799, -0.5684900);

-- --------------------------------------------------------

--
-- Table structure for table `swell_height`
--

DROP TABLE IF EXISTS `swell_height`;
CREATE TABLE `swell_height` (
  `id` int(5) NOT NULL COMMENT 'the unique identifier for the swell height',
  `type` varchar(15) NOT NULL COMMENT 'the description of the swell height',
  `height` varchar(8) NOT NULL COMMENT 'the swell height'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `swell_height`
--

INSERT INTO `swell_height` (`id`, `type`, `height`) VALUES
(1, 'Light', '<1m'),
(2, 'Moderate', '1-2m'),
(3, 'Heavy', '>2m');

-- --------------------------------------------------------

--
-- Table structure for table `visibility`
--

DROP TABLE IF EXISTS `visibility`;
CREATE TABLE `visibility` (
  `id` int(5) NOT NULL COMMENT 'the unique identifier for visibility',
  `distance` varchar(8) NOT NULL COMMENT 'the visability distance'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visibility`
--

INSERT INTO `visibility` (`id`, `distance`) VALUES
(1, '< 1km'),
(2, '1-5km'),
(3, '6-10km'),
(4, '>10km');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

DROP TABLE IF EXISTS `volunteer`;
CREATE TABLE `volunteer` (
  `id` int(5) NOT NULL COMMENT 'the unique id for each volunteer',
  `first_name` varchar(40) NOT NULL COMMENT 'the volunteers first name',
  `last_name` varchar(40) NOT NULL COMMENT 'the volunteers last name',
  `email` varchar(70) NOT NULL COMMENT 'Volunteers email address',
  `phone` varchar(16) DEFAULT NULL COMMENT 'the phone number',
  `password` varchar(255) NOT NULL COMMENT 'the volunteer password'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wind_direction`
--

DROP TABLE IF EXISTS `wind_direction`;
CREATE TABLE `wind_direction` (
  `id` int(5) NOT NULL COMMENT 'The unique reference to the direction',
  `direction` varchar(50) NOT NULL COMMENT 'the direction',
  `abbreviation` varchar(3) NOT NULL COMMENT 'the direction abbreviation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wind_direction`
--

INSERT INTO `wind_direction` (`id`, `direction`, `abbreviation`) VALUES
(1, 'North', 'N'),
(2, 'North East', 'NE'),
(3, 'East', 'E'),
(4, 'South East', 'SE'),
(5, 'South', 'S'),
(6, 'South West', 'SW'),
(7, 'West', 'W'),
(8, 'North West', 'NW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `behaviour`
--
ALTER TABLE `behaviour`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `confidence`
--
ALTER TABLE `confidence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `environment`
--
ALTER TABLE `environment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seawatch`
--
ALTER TABLE `seawatch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sea_state`
--
ALTER TABLE `sea_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sighting`
--
ALTER TABLE `sighting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `species`
--
ALTER TABLE `species`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `swell_height`
--
ALTER TABLE `swell_height`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visibility`
--
ALTER TABLE `visibility`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wind_direction`
--
ALTER TABLE `wind_direction`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `behaviour`
--
ALTER TABLE `behaviour`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for the behaiour', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `confidence`
--
ALTER TABLE `confidence`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for confidence', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `environment`
--
ALTER TABLE `environment`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for the data';

--
-- AUTO_INCREMENT for table `seawatch`
--
ALTER TABLE `seawatch`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier';

--
-- AUTO_INCREMENT for table `sea_state`
--
ALTER TABLE `sea_state`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'The unique identifier for the sea state', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for the session';

--
-- AUTO_INCREMENT for table `sighting`
--
ALTER TABLE `sighting`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for the sighting';

--
-- AUTO_INCREMENT for table `species`
--
ALTER TABLE `species`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for the species', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `station`
--
ALTER TABLE `station`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for the station', AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `swell_height`
--
ALTER TABLE `swell_height`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for the swell height', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `visibility`
--
ALTER TABLE `visibility`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'the unique identifier for visibility', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `volunteer`
--
ALTER TABLE `volunteer`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'the unique id for each volunteer';

--
-- AUTO_INCREMENT for table `wind_direction`
--
ALTER TABLE `wind_direction`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'The unique reference to the direction', AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
