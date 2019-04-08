# File name: C:\2004NSAA.sql
# Creation date: 11/16/2004
# Created by Access to MySQL 3.3 
# --------------------------------------------------
# More conversion tools at http://www.convert-in.com

SET FOREIGN_KEY_CHECKS = 0;

#
# Table structure for table 'Agegroup'
#

DROP TABLE IF EXISTS `Agegroup`;
CREATE TABLE `Agegroup` (
  `Low_age` SMALLINT,
  `High_age` SMALLINT
);

#
# Dumping data for table 'Agegroup'
#

LOCK TABLES `Agegroup` WRITE;
UNLOCK TABLES;

#
# Table structure for table 'AltScoring'
#

DROP TABLE IF EXISTS `AltScoring`;
CREATE TABLE `AltScoring` (
  `score_divno` SMALLINT,
  `score_sex` VARCHAR(1),
  `score_place` SMALLINT,
  `ind_score` FLOAT,
  `rel_score` FLOAT,
  INDEX `altscore` (`score_sex`, `score_place`)
);

#
# Dumping data for table 'AltScoring'
#

LOCK TABLES `AltScoring` WRITE;
INSERT INTO `AltScoring` VALUES(0, 'F', 1, 2.000000e+001, 4.000000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 2, 1.700000e+001, 3.400000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 3, 1.600000e+001, 3.200000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 4, 1.500000e+001, 3.000000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 5, 1.400000e+001, 2.800000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 6, 1.300000e+001, 2.600000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 7, 1.200000e+001, 2.400000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 8, 1.100000e+001, 2.200000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 9, 9.000000e+000, 1.800000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 10, 7.000000e+000, 1.400000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 11, 6.000000e+000, 1.200000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 12, 5.000000e+000, 1.000000e+001);
INSERT INTO `AltScoring` VALUES(0, 'F', 13, 4.000000e+000, 8.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 14, 3.000000e+000, 6.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 15, 2.000000e+000, 4.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 16, 1.000000e+000, 2.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 17, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 18, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 19, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 20, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 21, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 22, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 23, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 24, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 25, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 26, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 27, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 28, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 29, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 30, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 31, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 32, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 33, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 34, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 35, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 36, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 37, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 38, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 39, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'F', 40, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 1, 2.000000e+001, 4.000000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 2, 1.700000e+001, 3.400000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 3, 1.600000e+001, 3.200000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 4, 1.500000e+001, 3.000000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 5, 1.400000e+001, 2.800000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 6, 1.300000e+001, 2.600000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 7, 1.200000e+001, 2.400000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 8, 1.100000e+001, 2.200000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 9, 9.000000e+000, 1.800000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 10, 7.000000e+000, 1.400000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 11, 6.000000e+000, 1.200000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 12, 5.000000e+000, 1.000000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 13, 4.000000e+000, 8.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 14, 3.000000e+000, 6.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 15, 2.000000e+000, 4.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 16, 1.000000e+000, 2.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 17, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 18, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 19, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 20, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 21, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 22, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 23, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 24, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 25, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 26, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 27, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 28, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 29, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 30, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 31, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 32, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 33, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 34, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 35, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 36, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 37, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 38, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 39, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 40, 0.000000e+000, 0.000000e+000);
UNLOCK TABLES;

#
# Table structure for table 'Athlete'
#

DROP TABLE IF EXISTS `Athlete`;
CREATE TABLE `Athlete` (
  `Ath_no` INT NOT NULL AUTO_INCREMENT,
  `Last_name` VARCHAR(20),
  `First_name` VARCHAR(20),
  `Initial` VARCHAR(1),
  `Ath_Sex` VARCHAR(1),
  `Birth_date` CHAR(19),
  `Team_no` INT,
  `Schl_yr` VARCHAR(2),
  `Ath_age` SMALLINT,
  `Reg_no` VARCHAR(14),
  `Ath_stat` VARCHAR(1),
  `Div_no` INT,
  `Comp_no` INT,
  `Pref_name` VARCHAR(20),
  `Home_addr1` VARCHAR(30),
  `Home_addr2` VARCHAR(30),
  `Home_city` VARCHAR(30),
  `Home_prov` VARCHAR(30),
  `Home_state` VARCHAR(2),
  `Home_zip` VARCHAR(10),
  `Home_cntry` VARCHAR(3),
  `Home_daytele` VARCHAR(20),
  `Home_evetele` VARCHAR(20),
  `Home_faxtele` VARCHAR(20),
  `Citizen_of` VARCHAR(3),
  `Picture_bmp` VARCHAR(30),
  `home_statenew` VARCHAR(3),
  `second_club` VARCHAR(16),
  `home_email` VARCHAR(36),
  PRIMARY KEY (`Ath_no`),
  INDEX `athteam` (`Team_no`),
  INDEX `idnum` (`Reg_no`),
  INDEX `lastname` (`Last_name`)
);

#
# Dumping data for table 'Athlete'
#

LOCK TABLES `Athlete` WRITE;
INSERT INTO `Athlete` VALUES(2386, 'Boeding             ', 'Angela              ', ' ', 'F', NULL, 124, 'SR', 0, '              ', ' ', NULL, 39, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2387, 'Boeding             ', 'Anna                ', ' ', 'F', NULL, 124, 'FR', 0, '              ', ' ', NULL, 40, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2388, 'Delancey            ', 'Angela              ', ' ', 'F', NULL, 124, 'SR', 0, '              ', ' ', NULL, 41, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2389, 'Hardwick            ', 'Brent               ', ' ', 'M', NULL, 124, 'SR', 0, '              ', ' ', NULL, 42, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2390, 'Reed                ', 'Travis              ', ' ', 'M', NULL, 124, 'JR', 0, '              ', ' ', NULL, 43, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2391, 'Tate                ', 'Zachary             ', ' ', 'M', NULL, 124, 'SO', 0, '              ', ' ', NULL, 44, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2392, 'Hoeft               ', 'Jessica             ', ' ', 'F', NULL, 124, 'SR', 0, '              ', ' ', NULL, 45, 'Jessie              ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2393, 'Jackson             ', 'Stacia              ', ' ', 'F', NULL, 124, 'SR', 0, '              ', ' ', NULL, 46, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2394, 'Johnson             ', 'Rebecca             ', ' ', 'F', NULL, 124, 'JR', 0, '              ', ' ', NULL, 47, 'Becky               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2395, 'Kluth               ', 'Emilie              ', ' ', 'F', NULL, 124, 'SR', 0, '              ', ' ', NULL, 48, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2396, 'Morton              ', 'Joseph              ', ' ', 'M', NULL, 124, 'SR', 0, '              ', ' ', NULL, 49, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2397, 'Sharman             ', 'Jared               ', ' ', 'M', NULL, 124, 'FR', 0, '              ', ' ', NULL, 50, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2398, 'Bartels             ', 'Richard             ', ' ', 'M', NULL, 125, 'SR', 0, '              ', ' ', NULL, 51, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2399, 'Brunkow             ', 'Kendra              ', ' ', 'F', NULL, 125, 'SO', 0, '              ', ' ', NULL, 52, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2400, 'Busboom             ', 'Gerrad              ', ' ', 'M', NULL, 125, 'SR', 0, '              ', ' ', NULL, 53, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2401, 'DeBarros            ', 'IGOR                ', ' ', 'M', NULL, 125, 'JR', 0, '              ', ' ', NULL, 54, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2402, 'Gay                 ', 'Devin               ', ' ', 'M', NULL, 125, 'SO', 0, '              ', ' ', NULL, 55, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2403, 'Karel               ', 'Lisa                ', ' ', 'F', NULL, 125, 'JR', 0, '              ', ' ', NULL, 56, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2404, 'Kubicek             ', 'Michael             ', ' ', 'M', NULL, 125, 'SO', 0, '              ', ' ', NULL, 57, 'Mike                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2405, 'Laflin              ', 'Andrew              ', ' ', 'M', NULL, 125, 'SO', 0, '              ', ' ', NULL, 58, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2406, 'Laflin              ', 'Meagan              ', ' ', 'F', NULL, 125, 'SO', 0, '              ', ' ', NULL, 59, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2407, 'Lintz               ', 'Amanda              ', ' ', 'F', NULL, 125, 'SR', 0, '              ', ' ', NULL, 60, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2408, 'MCCOWN              ', 'ADDIE               ', ' ', 'F', NULL, 125, 'FR', 0, '              ', ' ', NULL, 61, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2409, 'McCown              ', 'Danielle            ', ' ', 'F', NULL, 125, 'SR', 0, '              ', ' ', NULL, 62, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2410, 'Mullins             ', 'Sarah               ', ' ', 'F', NULL, 125, 'SR', 0, '              ', ' ', NULL, 63, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2411, 'Nelson              ', 'Heather             ', ' ', 'F', NULL, 125, 'FR', 0, '              ', ' ', NULL, 64, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2412, 'Penner              ', 'Elizabeth           ', ' ', 'F', NULL, 125, 'SR', 0, '              ', ' ', NULL, 65, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2413, 'Schrock             ', 'Jessalyn            ', ' ', 'F', NULL, 125, 'JR', 0, '              ', ' ', NULL, 66, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2414, 'Siebe               ', 'Sarah               ', ' ', 'F', NULL, 125, 'SR', 0, '              ', ' ', NULL, 67, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2415, 'Summers             ', 'Gregory             ', ' ', 'M', NULL, 125, 'SO', 0, '              ', ' ', NULL, 68, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2416, 'Tomes               ', 'Alex                ', ' ', 'M', NULL, 125, 'SO', 0, '              ', ' ', NULL, 69, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2417, 'Wright              ', 'Wesley              ', ' ', 'M', NULL, 125, 'SR', 0, '              ', ' ', NULL, 70, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2418, 'Bond                ', 'Kyle                ', ' ', 'M', NULL, 125, 'JR', 0, '              ', ' ', NULL, 71, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2419, 'Engelman            ', 'Megan               ', ' ', 'F', NULL, 125, 'JR', 0, '              ', ' ', NULL, 72, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2420, 'Billings            ', 'Tara                ', ' ', 'F', NULL, 126, 'JR', 0, '              ', ' ', NULL, 73, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2421, 'Copeland            ', 'Brandon             ', ' ', 'M', NULL, 126, 'JR', 0, '              ', ' ', NULL, 74, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2422, 'Coultas             ', 'Grant               ', ' ', 'M', NULL, 126, 'FR', 0, '              ', ' ', NULL, 75, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2423, 'Goble               ', 'Michael             ', ' ', 'M', NULL, 126, 'SO', 0, '              ', ' ', NULL, 76, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2424, 'Gomez               ', 'Danita              ', ' ', 'F', NULL, 126, 'SR', 0, '              ', ' ', NULL, 77, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2425, 'Grange              ', 'Margaret            ', ' ', 'F', NULL, 126, 'SO', 0, '              ', ' ', NULL, 78, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2426, 'Hopkins             ', 'George              ', ' ', 'M', NULL, 126, 'SO', 0, '              ', ' ', NULL, 79, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2427, 'Hughes              ', 'Rebekah             ', ' ', 'F', NULL, 126, 'SR', 0, '              ', ' ', NULL, 80, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2428, 'Loffler             ', 'Julia               ', ' ', 'F', NULL, 126, 'SR', 0, '              ', ' ', NULL, 81, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2429, 'Lucia               ', 'Michael             ', ' ', 'M', NULL, 126, 'SR', 0, '              ', ' ', NULL, 82, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2430, 'Schlange            ', 'Steven              ', ' ', 'M', NULL, 126, 'SR', 0, '              ', ' ', NULL, 83, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2431, 'Stanback            ', 'Victoria            ', ' ', 'F', NULL, 126, 'JR', 0, '              ', ' ', NULL, 84, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2432, 'Tesar               ', 'Cole                ', ' ', 'M', NULL, 126, 'SO', 0, '              ', ' ', NULL, 85, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2433, 'Wolz                ', 'Tracy               ', ' ', 'F', NULL, 126, 'SR', 0, '              ', ' ', NULL, 86, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2434, 'Wolz                ', 'Travis              ', ' ', 'M', NULL, 126, 'SR', 0, '              ', ' ', NULL, 87, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2435, 'Copeland            ', 'Amanda              ', ' ', 'F', NULL, 126, 'SR', 0, '              ', ' ', NULL, 88, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2436, 'Myers               ', 'Riley               ', ' ', 'M', NULL, 127, 'SO', 0, '              ', ' ', NULL, 89, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2437, 'Fitzgerald          ', 'Tracy               ', ' ', 'F', NULL, 127, 'SR', 0, '              ', ' ', NULL, 90, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2438, 'Amundson            ', 'Dean                ', 'P', 'M', '1987-05-29', 127, 'JR', 16, '052987DEAPAMUN', ' ', NULL, 91, 'Dean                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2439, 'Anderson            ', 'Marcus              ', 'D', 'M', '1987-07-24', 127, 'SO', 16, '072487MARDANDE', ' ', NULL, 92, 'Marc                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2440, 'Birch               ', 'Tasha               ', 'M', 'F', '1988-03-26', 127, 'SO', 15, '032688TASMBIRC', ' ', NULL, 93, 'Tasha               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2441, 'Centeno             ', 'Maria               ', 'V', 'F', '1987-03-30', 127, 'JR', 16, '033087MARVCENT', ' ', NULL, 94, 'Maria               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2442, 'Hoffman             ', 'Allison             ', 'M', 'F', '1987-01-06', 127, 'JR', 17, '010687ALLMHOFF', ' ', NULL, 95, 'Allison             ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2443, 'McGovern            ', 'Anne                ', 'E', 'F', '1985-08-15', 127, 'SR', 18, '081585ANNEMC  ', ' ', NULL, 96, 'Anne                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2444, 'Mundt               ', 'Devon               ', 'L', 'M', '1987-01-08', 127, 'JR', 17, '010887DEVLMUND', ' ', NULL, 97, 'Devon               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2445, 'Osborn              ', 'Nicholas            ', 'S', 'M', '1986-10-04', 127, 'JR', 17, '100486NICSOSBO', ' ', NULL, 98, 'Nick                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2446, 'Dinwiddie           ', 'Mercy               ', 'L', 'F', '1987-03-05', 127, 'JR', 16, '030587MERLDINW', ' ', NULL, 99, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2447, 'Koziol              ', 'Vincent             ', 'K', 'M', '1988-06-25', 127, 'SO', 15, '062588VINKKOZI', ' ', NULL, 100, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2448, 'Eastman             ', 'Zachary             ', ' ', 'M', NULL, 127, 'SR', 0, '              ', ' ', NULL, 101, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2449, 'Adams               ', 'Brittany            ', ' ', 'F', NULL, 128, 'FR', 0, '              ', ' ', NULL, 102, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2450, 'Dack                ', 'Brandon             ', ' ', 'M', NULL, 128, 'SO', 0, '              ', ' ', NULL, 103, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2451, 'Erickson            ', 'James               ', ' ', 'M', NULL, 128, 'SR', 0, '              ', ' ', NULL, 104, 'Jamie               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2452, 'Fast                ', 'Maggie              ', ' ', 'F', NULL, 128, 'SO', 0, '              ', ' ', NULL, 105, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2453, 'Helzer              ', 'Ashley              ', ' ', 'F', NULL, 128, 'FR', 0, '              ', ' ', NULL, 106, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2454, 'Howard              ', 'Austin              ', ' ', 'M', NULL, 128, 'SR', 0, '              ', ' ', NULL, 107, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2455, 'McDermott           ', 'Nicole              ', ' ', 'F', NULL, 128, 'SR', 0, '              ', ' ', NULL, 108, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2456, 'Nicholson           ', 'Kate                ', ' ', 'F', NULL, 128, 'SO', 0, '              ', ' ', NULL, 109, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2457, 'Odell               ', 'Josh                ', ' ', 'M', NULL, 128, 'SR', 0, '              ', ' ', NULL, 110, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2458, 'Plein               ', 'Jocelyn             ', ' ', 'F', NULL, 128, 'FR', 0, '              ', ' ', NULL, 111, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2459, 'Taylor              ', 'Kari                ', ' ', 'F', NULL, 128, 'FR', 0, '              ', ' ', NULL, 112, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2460, 'Welch               ', 'John                ', ' ', 'M', NULL, 128, 'SO', 0, '              ', ' ', NULL, 113, 'Jack                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2461, 'Porter              ', 'Mackenzie           ', ' ', 'F', NULL, 128, 'JR', 0, '              ', ' ', NULL, 114, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2462, 'Skutnik             ', 'Benjamin            ', ' ', 'M', NULL, 128, 'SR', 0, '              ', ' ', NULL, 115, 'Ben                 ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2463, 'Zoucha              ', 'Peter               ', ' ', 'M', NULL, 128, 'JR', 0, '              ', ' ', NULL, 116, 'Pete                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2464, 'O\'Connell           ', 'Kara                ', ' ', 'F', NULL, 129, 'FR', 0, '              ', ' ', NULL, 117, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2465, 'Steinke             ', 'Derk                ', ' ', 'M', NULL, 129, 'SR', 0, '              ', ' ', NULL, 118, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2466, 'Widman              ', 'Austin              ', ' ', 'M', NULL, 129, 'JR', 0, '              ', ' ', NULL, 119, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2467, 'Wilnes              ', 'Kasey               ', ' ', 'F', NULL, 129, 'SO', 0, '              ', ' ', NULL, 120, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2468, 'Anderson            ', 'Annalise            ', ' ', 'F', NULL, 129, 'FR', 0, '              ', ' ', NULL, 121, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2469, 'Beckman             ', 'Adam                ', ' ', 'M', NULL, 129, 'JR', 0, '              ', ' ', NULL, 122, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2470, 'Bicak               ', 'Libby               ', ' ', 'F', NULL, 129, 'JR', 0, '              ', ' ', NULL, 123, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2471, 'BRAGG               ', 'JARED               ', ' ', 'M', NULL, 129, 'SR', 0, '              ', ' ', NULL, 124, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2472, 'Holmes              ', 'Sam                 ', ' ', 'M', NULL, 129, 'SO', 0, '              ', ' ', NULL, 125, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2473, 'Kennedy             ', 'Meghann             ', ' ', 'F', NULL, 129, 'FR', 0, '              ', ' ', NULL, 126, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2474, 'Kirkland            ', 'Jennifer            ', ' ', 'F', NULL, 129, 'JR', 0, '              ', ' ', NULL, 127, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2475, 'Lind                ', 'David               ', ' ', 'M', NULL, 129, 'SR', 0, '              ', ' ', NULL, 128, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2476, 'O\'Connell           ', 'Kylie               ', ' ', 'F', NULL, 129, 'SR', 0, '              ', ' ', NULL, 129, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2477, 'Roberts             ', 'Rhitney             ', ' ', 'M', NULL, 129, 'SR', 0, '              ', ' ', NULL, 130, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2478, 'Wegner              ', 'Mackenzie           ', ' ', 'F', NULL, 129, 'SR', 0, '              ', ' ', NULL, 131, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2479, 'Wilson              ', 'Charles             ', ' ', 'M', NULL, 129, 'SR', 0, '              ', ' ', NULL, 132, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2480, 'Castro              ', 'Jason               ', ' ', 'M', NULL, 130, 'JR', 0, '              ', ' ', NULL, 133, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2481, 'Coleman             ', 'Misha               ', ' ', 'F', NULL, 130, 'SR', 0, '              ', ' ', NULL, 134, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2482, 'Costello            ', 'Sarah               ', ' ', 'F', NULL, 130, 'JR', 0, '              ', ' ', NULL, 135, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2483, 'Henry               ', 'Ashley              ', ' ', 'F', NULL, 130, 'SR', 0, '              ', ' ', NULL, 136, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2484, 'Herrick             ', 'Katherine           ', ' ', 'F', NULL, 130, 'SO', 0, '              ', ' ', NULL, 137, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2485, 'Jones               ', 'Lucas               ', ' ', 'M', NULL, 130, 'SR', 0, '              ', ' ', NULL, 138, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2486, 'Krenk               ', 'Renee               ', ' ', 'F', NULL, 130, 'JR', 0, '              ', ' ', NULL, 139, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2487, 'Lenz                ', 'Jeremy              ', ' ', 'M', NULL, 130, 'FR', 0, '              ', ' ', NULL, 140, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2488, 'Lenz                ', 'Marisha             ', ' ', 'F', NULL, 130, 'SR', 0, '              ', ' ', NULL, 141, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2489, 'Meyer               ', 'Stephanie           ', ' ', 'F', NULL, 130, 'SO', 0, '              ', ' ', NULL, 142, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2490, 'Mikkelson           ', 'Raymond             ', ' ', 'M', NULL, 130, 'JR', 0, '              ', ' ', NULL, 143, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2491, 'Parr                ', 'Morgan              ', ' ', 'F', NULL, 130, 'SR', 0, '              ', ' ', NULL, 144, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2492, 'Price               ', 'Gillian             ', ' ', 'F', NULL, 130, 'SR', 0, '              ', ' ', NULL, 145, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2493, 'Rosenau             ', 'Timothy             ', ' ', 'M', NULL, 130, 'JR', 0, '              ', ' ', NULL, 146, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2494, 'STALDER             ', 'BEN                 ', ' ', 'M', NULL, 130, 'SR', 0, '              ', ' ', NULL, 147, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2495, 'Staley              ', 'Samantha            ', ' ', 'F', NULL, 130, 'SR', 0, '              ', ' ', NULL, 148, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2496, 'Svoboda             ', 'Nicholaus           ', ' ', 'M', NULL, 130, 'SR', 0, '              ', ' ', NULL, 149, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2497, 'Terry               ', 'Maggie              ', ' ', 'F', NULL, 130, 'SO', 0, '              ', ' ', NULL, 150, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2498, 'Yashirin            ', 'Lev                 ', ' ', 'M', NULL, 130, 'FR', 0, '              ', ' ', NULL, 151, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2499, 'Crane               ', 'Michael             ', ' ', 'M', NULL, 130, 'SR', 0, '              ', ' ', NULL, 152, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2500, 'Sapp                ', 'Seth                ', ' ', 'M', NULL, 130, 'JR', 0, '              ', ' ', NULL, 153, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2501, 'Aguirre             ', 'Sarah               ', ' ', 'F', NULL, 131, 'JR', 0, '              ', ' ', NULL, 154, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2502, 'Culver              ', 'Kimbra              ', ' ', 'F', NULL, 131, 'SR', 0, '              ', ' ', NULL, 155, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2503, 'Fleming             ', 'Leigh               ', ' ', 'F', NULL, 131, 'JR', 0, '              ', ' ', NULL, 156, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2504, 'Higgins             ', 'Brita               ', ' ', 'F', NULL, 131, 'FR', 0, '              ', ' ', NULL, 157, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2505, 'Pedersen            ', 'Kristin             ', ' ', 'F', NULL, 131, 'JR', 0, '              ', ' ', NULL, 158, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2506, 'Pella               ', 'Jaime               ', ' ', 'F', NULL, 131, 'FR', 0, '              ', ' ', NULL, 159, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2507, 'Svoboda             ', 'Morgan              ', ' ', 'F', NULL, 131, 'SO', 0, '              ', ' ', NULL, 160, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2508, 'Wissink             ', 'Erienne             ', ' ', 'F', NULL, 131, 'JR', 0, '              ', ' ', NULL, 161, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2509, 'Bachman             ', 'Kyle                ', ' ', 'M', NULL, 131, 'SO', 0, '              ', ' ', NULL, 162, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2510, 'Fraley              ', 'Vince               ', ' ', 'M', NULL, 131, 'SR', 0, '              ', ' ', NULL, 163, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2511, 'Hatfeild            ', 'Steven              ', ' ', 'M', NULL, 131, 'SO', 0, '              ', ' ', NULL, 164, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2512, 'Morris              ', 'John                ', ' ', 'M', NULL, 131, 'SO', 0, '              ', ' ', NULL, 165, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2513, 'Pedersen            ', 'Matt                ', ' ', 'M', NULL, 131, 'FR', 0, '              ', ' ', NULL, 166, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2514, 'Pella               ', 'Corey               ', ' ', 'M', NULL, 131, 'JR', 0, '              ', ' ', NULL, 167, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2515, 'Traudt              ', 'Tim                 ', ' ', 'M', NULL, 131, 'FR', 0, '              ', ' ', NULL, 168, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2516, 'Lynch               ', 'Jena                ', ' ', 'F', NULL, 131, 'JR', 0, '              ', ' ', NULL, 169, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2517, 'Marshall            ', 'Jennifer            ', ' ', 'F', NULL, 131, 'SO', 0, '              ', ' ', NULL, 170, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2518, 'Smith               ', 'Anna                ', ' ', 'F', NULL, 131, 'FR', 0, '              ', ' ', NULL, 171, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2519, 'Rice                ', 'Trevor              ', ' ', 'M', NULL, 131, 'JR', 0, '              ', ' ', NULL, 172, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2520, 'Sutton              ', 'Reid                ', ' ', 'M', NULL, 131, 'JR', 0, '              ', ' ', NULL, 173, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2521, 'Homewood            ', 'Darryl              ', ' ', 'M', NULL, 132, 'SO', 0, '              ', ' ', NULL, 174, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2522, 'Katt                ', 'Daniel              ', ' ', 'M', NULL, 132, 'FR', 0, '              ', ' ', NULL, 175, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2523, 'Massa               ', 'Nicholas            ', ' ', 'M', NULL, 132, 'FR', 0, '              ', ' ', NULL, 176, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2524, 'Ratliff             ', 'Justin              ', ' ', 'M', NULL, 132, 'SO', 0, '              ', ' ', NULL, 177, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2525, 'Rubeiz              ', 'Christian           ', ' ', 'M', NULL, 132, 'FR', 0, '              ', ' ', NULL, 178, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2526, 'Terry               ', 'Bejamin             ', ' ', 'M', NULL, 132, 'FR', 0, '              ', ' ', NULL, 179, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2527, 'Wolfe               ', 'Cody                ', ' ', 'M', NULL, 132, 'SO', 0, '              ', ' ', NULL, 180, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2528, 'HALL                ', 'JOHN                ', ' ', 'M', NULL, 132, 'SO', 0, '              ', ' ', NULL, 181, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2529, 'Plummer             ', 'Ian                 ', ' ', 'M', NULL, 132, 'SO', 0, '              ', ' ', NULL, 182, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2530, 'Blumer              ', 'Bess                ', ' ', 'F', NULL, 133, 'JR', 0, '              ', ' ', NULL, 183, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2531, 'Ebers               ', 'Jessica             ', ' ', 'F', NULL, 133, 'JR', 0, '              ', ' ', NULL, 184, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2532, 'Greenwald           ', 'Kate                ', ' ', 'F', NULL, 133, 'SR', 0, '              ', ' ', NULL, 185, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2533, 'Babcock             ', 'Rob                 ', ' ', 'M', NULL, 133, 'FR', 0, '              ', ' ', NULL, 186, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2534, 'BARTLE              ', 'ALI                 ', ' ', 'F', NULL, 133, 'JR', 11, '              ', ' ', NULL, 187, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2535, 'BLUM                ', 'MATT                ', ' ', 'M', NULL, 133, 'SR', 12, '              ', ' ', NULL, 188, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2536, 'Bonin               ', 'Haley               ', ' ', 'F', NULL, 133, 'SR', 0, '              ', ' ', NULL, 189, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2537, 'Case-Ruchala        ', 'Celeste             ', ' ', 'F', NULL, 133, 'FR', 0, '              ', ' ', NULL, 190, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2538, 'Dorland             ', 'Jared               ', ' ', 'M', NULL, 133, 'SR', 0, '              ', ' ', NULL, 191, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2539, 'Durand              ', 'Ashley              ', ' ', 'F', NULL, 133, 'JR', 0, '              ', ' ', NULL, 192, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2540, 'Ernst               ', 'Carly               ', ' ', 'F', NULL, 133, 'JR', 0, '              ', ' ', NULL, 193, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2541, 'Hartmann            ', 'Nicholas            ', ' ', 'M', NULL, 133, 'JR', 0, '              ', ' ', NULL, 194, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2542, 'Hohensee            ', 'Nicholas            ', ' ', 'M', NULL, 133, 'SR', 0, '              ', ' ', NULL, 195, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2543, 'Hutchinson          ', 'Max                 ', ' ', 'M', NULL, 133, 'FR', 0, '              ', ' ', NULL, 196, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2544, 'Lesage              ', 'Michael             ', ' ', 'M', NULL, 133, 'SR', 0, '              ', ' ', NULL, 197, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2545, 'Masek               ', 'Kyle                ', ' ', 'M', NULL, 133, 'SR', 0, '              ', ' ', NULL, 198, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2546, 'Ohs                 ', 'Matthew             ', ' ', 'M', NULL, 133, 'FR', 0, '              ', ' ', NULL, 199, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2547, 'Pappas              ', 'Nikki               ', ' ', 'F', NULL, 133, 'FR', 0, '              ', ' ', NULL, 200, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2548, 'Torske              ', 'Kayla               ', ' ', 'F', NULL, 133, 'FR', 0, '              ', ' ', NULL, 201, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2549, 'West                ', 'Nathaniel           ', ' ', 'M', NULL, 133, 'SO', 0, '              ', ' ', NULL, 202, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2550, 'Couillard           ', 'Kylie               ', ' ', 'F', NULL, 134, 'FR', 0, '              ', ' ', NULL, 203, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2551, 'Hill                ', 'Anna                ', ' ', 'F', NULL, 134, 'JR', 0, '              ', ' ', NULL, 204, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2552, 'Janssen             ', 'Rachel              ', ' ', 'F', NULL, 134, 'FR', 0, '              ', ' ', NULL, 205, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2553, 'Latta               ', 'Anna                ', ' ', 'F', NULL, 134, 'SR', 0, '              ', ' ', NULL, 206, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2554, 'Lausten             ', 'Jacob               ', ' ', 'M', NULL, 134, 'JR', 0, '              ', ' ', NULL, 207, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2555, 'McGowan             ', 'Claire              ', ' ', 'F', NULL, 134, 'JR', 0, '              ', ' ', NULL, 208, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2556, 'Ryan                ', 'Meghan              ', ' ', 'F', NULL, 134, 'SO', 0, '              ', ' ', NULL, 209, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2557, 'Spanel              ', 'Jessica             ', ' ', 'F', NULL, 134, 'SR', 0, '              ', ' ', NULL, 210, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2558, 'Stange              ', 'Jordan              ', ' ', 'M', NULL, 134, 'JR', 0, '              ', ' ', NULL, 211, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2559, 'Taylor              ', 'Lance               ', ' ', 'M', NULL, 134, 'JR', 0, '              ', ' ', NULL, 212, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2560, 'Troxel              ', 'Kellie              ', ' ', 'F', NULL, 134, 'SO', 0, '              ', ' ', NULL, 213, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2561, 'Troxel              ', 'Tyler               ', ' ', 'M', NULL, 134, 'FR', 0, '              ', ' ', NULL, 214, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2562, 'Walter              ', 'Chelsea             ', ' ', 'F', NULL, 134, 'SO', 0, '              ', ' ', NULL, 215, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2563, 'Anderson            ', 'Ryan                ', ' ', 'M', NULL, 134, 'SR', 0, '              ', ' ', NULL, 216, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2564, 'Christiansen        ', 'Kelsey              ', ' ', 'F', NULL, 134, 'SO', 0, '              ', ' ', NULL, 217, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2565, 'Eckery              ', 'Dane                ', ' ', 'M', NULL, 134, 'SR', 0, '              ', ' ', NULL, 218, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2566, 'Jones               ', 'Kari                ', ' ', 'F', NULL, 134, 'SO', 0, '              ', ' ', NULL, 219, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2567, 'MILLS               ', 'TJ                  ', ' ', 'M', NULL, 134, 'SO', 10, '              ', ' ', NULL, 220, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2568, 'Moore               ', 'Nicholas            ', ' ', 'M', NULL, 134, 'JR', 0, '              ', ' ', NULL, 221, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2569, 'Murphy              ', 'Aaron               ', ' ', 'M', NULL, 134, 'FR', 0, '              ', ' ', NULL, 222, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2570, 'Tyler', 'Benjamin', ' ', 'M', NULL, 134, 'SR', 0, '              ', ' ', NULL, 223, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2571, 'Hall                ', 'Jessica             ', ' ', 'F', NULL, 135, 'JR', 0, '              ', ' ', NULL, 224, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2572, 'ANDERSON            ', 'BETH                ', ' ', 'F', NULL, 136, 'SO', 10, '              ', ' ', NULL, 225, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2573, 'BAILIS              ', 'JESSIE              ', ' ', 'F', NULL, 136, 'SR', 12, '              ', ' ', NULL, 226, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2574, 'Byman               ', 'Bradley             ', ' ', 'M', NULL, 136, 'JR', 0, '              ', ' ', NULL, 227, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2575, 'Clark               ', 'Justin              ', ' ', 'M', NULL, 136, 'SO', 0, '              ', ' ', NULL, 228, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2576, 'Ensor               ', 'Kevin               ', ' ', 'M', NULL, 136, 'SO', 0, '              ', ' ', NULL, 229, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2577, 'Gilmer              ', 'Wesley              ', ' ', 'M', NULL, 136, 'SR', 0, '              ', ' ', NULL, 230, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2578, 'Hallberg            ', 'Berit               ', ' ', 'F', NULL, 136, 'SR', 0, '              ', ' ', NULL, 231, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2579, 'Hines               ', 'Ryan                ', ' ', 'M', NULL, 136, 'SR', 0, '              ', ' ', NULL, 232, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2580, 'Holthaus            ', 'Ashley              ', ' ', 'F', NULL, 136, 'JR', 0, '              ', ' ', NULL, 233, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2581, 'Kline               ', 'Lindsey             ', ' ', 'F', NULL, 136, 'JR', 0, '              ', ' ', NULL, 234, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2582, 'Marty               ', 'Elizabeth           ', ' ', 'F', NULL, 136, 'SO', 0, '              ', ' ', NULL, 235, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2583, 'Nichols             ', 'Eric                ', ' ', 'M', NULL, 136, 'JR', 0, '              ', ' ', NULL, 236, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2584, 'Paulsen             ', 'Brooke              ', ' ', 'F', NULL, 136, 'SR', 0, '              ', ' ', NULL, 237, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2585, 'Redding             ', 'Taylor              ', ' ', 'F', NULL, 136, 'SO', 0, '              ', ' ', NULL, 238, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2586, 'Sakaris             ', 'Angela              ', ' ', 'F', NULL, 136, 'SR', 0, '              ', ' ', NULL, 239, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2587, 'Samson              ', 'Benjamin            ', ' ', 'M', NULL, 136, 'JR', 0, '              ', ' ', NULL, 240, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2588, 'Schreiber           ', 'Spencer             ', ' ', 'M', NULL, 136, 'SO', 0, '              ', ' ', NULL, 241, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2589, 'Shaffer             ', 'Ryan                ', ' ', 'M', NULL, 136, 'SR', 0, '              ', ' ', NULL, 242, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2590, 'Shaffer             ', 'Scott               ', ' ', 'M', NULL, 136, 'SO', 0, '              ', ' ', NULL, 243, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2591, 'Shald               ', 'Katie               ', ' ', 'F', NULL, 136, 'SR', 0, '              ', ' ', NULL, 244, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2592, 'Vetter              ', 'Danielle            ', ' ', 'F', NULL, 136, 'FR', 0, '              ', ' ', NULL, 245, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2593, 'Wood                ', 'Allison             ', ' ', 'F', NULL, 136, 'FR', 0, '              ', ' ', NULL, 246, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2594, 'Bailis              ', 'Laura               ', ' ', 'F', NULL, 136, 'SO', 0, '              ', ' ', NULL, 247, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2595, 'Ellison             ', 'Carey               ', ' ', 'F', NULL, 136, 'SR', 0, '              ', ' ', NULL, 248, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2596, 'Freyer              ', 'Callie              ', ' ', 'F', NULL, 136, 'JR', 0, '              ', ' ', NULL, 249, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2597, 'Gidley              ', 'Sarah               ', ' ', 'F', NULL, 136, 'FR', 0, '              ', ' ', NULL, 250, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2598, 'OHRT', 'Kalen', ' ', 'F', NULL, 136, 'SO', 0, '              ', ' ', NULL, 251, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2599, 'Shkolnik            ', 'Laura               ', ' ', 'F', NULL, 136, 'SR', 0, '              ', ' ', NULL, 252, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2600, 'Stephenson          ', 'Christina           ', ' ', 'F', NULL, 136, 'SO', 0, '              ', ' ', NULL, 253, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2601, 'Tomek               ', 'Chelsea             ', ' ', 'F', NULL, 136, 'FR', 0, '              ', ' ', NULL, 254, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2602, 'Tully               ', 'Jamie               ', ' ', 'F', NULL, 136, 'JR', 0, '              ', ' ', NULL, 255, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2603, 'CURTIS', 'ASHLEY', ' ', 'F', NULL, 137, 'JR', 0, '              ', ' ', NULL, 256, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2604, 'DEGEORGE', 'BREANNA', ' ', 'F', NULL, 137, 'SO', 0, '              ', ' ', NULL, 257, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2605, 'DIXSON', 'SARAH', ' ', 'F', NULL, 137, 'SO', 0, '              ', ' ', NULL, 258, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2606, 'HOLMES', 'KELLEY', ' ', 'F', NULL, 137, 'FR', 0, '              ', ' ', NULL, 259, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2607, 'JONES', 'CHRISTOPHER', ' ', 'M', NULL, 137, 'SR', 0, '              ', ' ', NULL, 260, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2608, 'KUBOVY', 'CAROLINE', ' ', 'F', NULL, 137, 'SR', 0, '              ', ' ', NULL, 261, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2609, 'LEZANIC', 'COURTNEY', ' ', 'F', NULL, 137, 'SO', 0, '              ', ' ', NULL, 262, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2610, 'MEIER', 'MAGGIE', ' ', 'F', NULL, 137, 'JR', 0, '              ', ' ', NULL, 263, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2611, 'OVIATT', 'ASHLEY', ' ', 'F', NULL, 137, 'SR', 0, '              ', ' ', NULL, 264, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2612, 'PFLUG', 'WILLIAM', ' ', 'M', NULL, 137, 'JR', 0, '              ', ' ', NULL, 265, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2613, 'SCHROEDER', 'KATHARINE', ' ', 'F', NULL, 137, 'SR', 0, '              ', ' ', NULL, 266, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2614, 'WILSON', 'JERICK', ' ', 'M', NULL, 137, 'SR', 0, '              ', ' ', NULL, 267, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2615, 'PETERSEN', 'JACOB', ' ', 'M', NULL, 137, 'JR', 0, '              ', ' ', NULL, 268, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2616, 'EPPERSON', 'MICHAEL', ' ', 'M', NULL, 137, 'JR', 0, '              ', ' ', NULL, 269, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2617, 'BAKER', 'NICHOLAS', ' ', 'M', NULL, 137, 'SO', 0, '              ', ' ', NULL, 270, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2618, 'DINNING', 'RYAN', ' ', 'M', NULL, 137, 'SO', 0, '              ', ' ', NULL, 271, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2619, 'GMEINER', 'KRISTY', ' ', 'F', NULL, 137, 'SR', 0, '              ', ' ', NULL, 272, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2620, 'HAWLEY', 'ZACHARY', ' ', 'M', NULL, 137, 'SR', 0, '              ', ' ', NULL, 273, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2621, 'QUANDT', 'TARA', ' ', 'F', NULL, 137, 'FR', 0, '              ', ' ', NULL, 274, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2622, 'STOTHERT', 'ELIZABETH', ' ', 'F', NULL, 137, 'SR', 0, '              ', ' ', NULL, 275, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2623, 'WAY', 'ALLISON', ' ', 'F', NULL, 137, 'SO', 0, '              ', ' ', NULL, 276, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2624, 'Bergner             ', 'Norman              ', ' ', 'M', NULL, 138, 'SR', 0, '              ', ' ', NULL, 277, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2625, 'BOLIN               ', 'DANIELLE            ', ' ', 'F', NULL, 138, 'SO', 10, '              ', ' ', NULL, 278, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2626, 'Dinslage            ', 'Tyler               ', ' ', 'M', NULL, 138, 'SO', 0, '              ', ' ', NULL, 279, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2627, 'WALTERS             ', 'MOLLY               ', ' ', 'F', NULL, 138, 'SO', 10, '              ', ' ', NULL, 280, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2628, 'Zechmann            ', 'Lance               ', ' ', 'M', NULL, 138, 'SR', 0, '              ', ' ', NULL, 281, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2629, 'Cleveland           ', 'Cody                ', ' ', 'M', NULL, 138, 'SR', 0, '              ', ' ', NULL, 282, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2630, 'Hatfield            ', 'Jenna               ', ' ', 'F', NULL, 138, 'JR', 0, '              ', ' ', NULL, 283, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2631, 'HOESING             ', 'BECCA               ', ' ', 'F', NULL, 138, 'SO', 0, '              ', ' ', NULL, 284, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2632, 'Jones               ', 'Angela              ', ' ', 'F', NULL, 138, 'SR', 0, '              ', ' ', NULL, 285, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2633, 'Kieff               ', 'Natalie             ', ' ', 'F', NULL, 138, 'SR', 0, '              ', ' ', NULL, 286, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2634, 'Powell              ', 'Tanner              ', ' ', 'M', NULL, 138, 'JR', 0, '              ', ' ', NULL, 287, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2635, 'PRUSS               ', 'AJ                  ', 'J', 'M', NULL, 138, 'SO', 0, '              ', ' ', NULL, 288, 'A.J.                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2636, 'Schlake             ', 'Lesley              ', ' ', 'F', NULL, 138, 'SO', 0, '              ', ' ', NULL, 289, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2637, 'Hennessy            ', 'Chris               ', ' ', 'M', NULL, 139, 'SR', 0, '              ', ' ', NULL, 290, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2638, 'Mertens             ', 'Ashley              ', ' ', 'F', NULL, 139, 'JR', 0, '              ', ' ', NULL, 291, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2639, 'Ravenscroft         ', 'Laura               ', ' ', 'F', NULL, 139, 'SR', 0, '              ', ' ', NULL, 292, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2640, 'Smith               ', 'Megan               ', ' ', 'F', NULL, 139, 'SR', 0, '              ', ' ', NULL, 293, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2641, 'Wright              ', 'Kaitlyn             ', ' ', 'F', NULL, 139, 'SO', 0, '              ', ' ', NULL, 294, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2642, 'Beggs               ', 'Jake                ', ' ', 'M', NULL, 139, 'JR', 0, '              ', ' ', NULL, 295, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2643, 'Jacobson            ', 'Josh                ', ' ', 'M', NULL, 139, 'FR', 0, '              ', ' ', NULL, 296, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2644, 'Janak               ', 'Molly               ', ' ', 'F', NULL, 139, 'SO', 0, '              ', ' ', NULL, 297, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2645, 'McNare              ', 'Casey               ', ' ', 'M', NULL, 139, 'JR', 0, '              ', ' ', NULL, 298, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2646, 'SHANAHAN            ', 'SEAN                ', ' ', 'M', NULL, 139, 'SO', 0, '              ', ' ', NULL, 299, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2647, 'Blum                ', 'Jenna               ', ' ', 'F', NULL, 140, 'FR', 0, '              ', ' ', NULL, 300, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2648, 'McCann              ', 'Lane                ', ' ', 'F', NULL, 140, 'FR', 0, '              ', ' ', NULL, 301, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2649, 'Sebastian           ', 'Kayla               ', ' ', 'F', NULL, 140, 'FR', 0, '              ', ' ', NULL, 302, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2650, 'York                ', 'Brittany            ', ' ', 'F', NULL, 140, 'SR', 0, '              ', ' ', NULL, 303, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2651, 'Gatzemeyer          ', 'Nikoal              ', ' ', 'F', NULL, 140, 'SR', 0, '              ', ' ', NULL, 304, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2652, 'Klug                ', 'Aaron               ', ' ', 'M', NULL, 140, 'SO', 0, '              ', ' ', NULL, 305, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2653, 'Arciniegas          ', 'Santiago            ', ' ', 'M', NULL, 141, 'SO', 0, '              ', ' ', NULL, 306, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2654, 'Chapman             ', 'Leah                ', ' ', 'F', NULL, 141, 'FR', 0, '              ', ' ', NULL, 307, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2655, 'Harris              ', 'Ricky               ', ' ', 'M', NULL, 141, 'JR', 0, '              ', ' ', NULL, 308, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2656, 'Locke               ', 'Abby                ', ' ', 'F', NULL, 141, 'SO', 0, '              ', ' ', NULL, 309, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2657, 'Locke               ', 'Jeanette            ', ' ', 'F', NULL, 141, 'SR', 0, '              ', ' ', NULL, 310, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2658, 'MARTENS             ', 'JOSH                ', ' ', 'M', NULL, 141, 'FR', 0, '              ', ' ', NULL, 311, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2659, 'MCCANN              ', 'ALEX                ', ' ', 'F', NULL, 141, 'FR', 0, '              ', ' ', NULL, 312, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2660, 'Prost               ', 'Tim                 ', ' ', 'M', NULL, 141, 'SO', 0, '              ', ' ', NULL, 313, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2661, 'Stahlecker          ', 'Jill                ', ' ', 'F', NULL, 141, 'FR', 0, '              ', ' ', NULL, 314, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2662, 'Barnett', 'Bryan', ' ', 'M', NULL, 141, 'FR', 0, '              ', ' ', NULL, 315, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2663, 'BAUMAN              ', 'QUINN               ', ' ', 'F', NULL, 141, 'JR', 11, '              ', ' ', NULL, 316, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2664, 'Benson              ', 'Curtis              ', ' ', 'M', NULL, 141, 'JR', 0, '              ', ' ', NULL, 317, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2665, 'Biga                ', 'Brian               ', ' ', 'M', NULL, 141, 'SR', 0, '              ', ' ', NULL, 318, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2666, 'Bloomquist          ', 'Lindsay             ', ' ', 'F', NULL, 141, 'SR', 0, '              ', ' ', NULL, 319, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2667, 'CHRISTIANSEN        ', 'BECKY               ', ' ', 'F', NULL, 141, 'SR', 0, '              ', ' ', NULL, 320, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2668, 'Christiansen        ', 'BJ                  ', ' ', 'M', NULL, 141, 'SO', 0, '              ', ' ', NULL, 321, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2669, 'Connick             ', 'Jessica             ', ' ', 'F', NULL, 141, 'SR', 0, '              ', ' ', NULL, 322, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2670, 'Conway              ', 'Leigh               ', ' ', 'F', NULL, 141, 'SR', 0, '              ', ' ', NULL, 323, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2671, 'Daigle              ', 'Adrienne            ', ' ', 'F', NULL, 141, 'SO', 0, '              ', ' ', NULL, 324, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2672, 'Friis               ', 'Scott               ', ' ', 'M', NULL, 141, 'JR', 0, '              ', ' ', NULL, 325, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2673, 'Hafner              ', 'Krysten             ', ' ', 'F', NULL, 141, 'SR', 0, '              ', ' ', NULL, 326, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2674, 'Hansen              ', 'Rachel              ', ' ', 'F', NULL, 141, 'SR', 0, '              ', ' ', NULL, 327, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2675, 'John                ', 'Mitch               ', ' ', 'M', NULL, 141, 'JR', 0, '              ', ' ', NULL, 328, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2676, 'Miller              ', 'Max                 ', ' ', 'M', NULL, 141, 'FR', 0, '              ', ' ', NULL, 329, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2677, 'Naughton            ', 'Andrea              ', ' ', 'F', NULL, 141, 'SO', 0, '              ', ' ', NULL, 330, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2678, 'NAUGHTON            ', 'CHRIS               ', ' ', 'M', NULL, 141, 'JR', 0, '              ', ' ', NULL, 331, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2679, 'Nelson              ', 'Carolyn             ', ' ', 'F', NULL, 141, 'FR', 0, '              ', ' ', NULL, 332, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2680, 'NUESSENDORFER       ', 'ASHLEY              ', ' ', 'F', NULL, 141, 'SO', 0, '              ', ' ', NULL, 333, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2681, 'Patton              ', 'Erik                ', ' ', 'M', NULL, 141, 'SR', 0, '              ', ' ', NULL, 334, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2682, 'Patton              ', 'Michael             ', ' ', 'M', NULL, 141, 'SO', 0, '              ', ' ', NULL, 335, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2683, 'Stahlecker          ', 'Chad                ', ' ', 'M', NULL, 141, 'JR', 0, '              ', ' ', NULL, 336, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2684, 'Stark               ', 'Paige               ', ' ', 'F', NULL, 141, 'JR', 0, '              ', ' ', NULL, 337, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2685, 'Stick               ', 'Ashley              ', ' ', 'F', NULL, 141, 'JR', 0, '              ', ' ', NULL, 338, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2686, 'Vacek               ', 'Tommy               ', ' ', 'M', NULL, 141, 'SR', 0, '              ', ' ', NULL, 339, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2687, 'Vail                ', 'Stephanie           ', ' ', 'F', NULL, 141, 'SO', 0, '              ', ' ', NULL, 340, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2688, 'Watson              ', 'Allex               ', ' ', 'F', NULL, 141, 'FR', 0, '              ', ' ', NULL, 341, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2689, 'Williams            ', 'Jessica             ', ' ', 'F', NULL, 141, 'SO', 0, '              ', ' ', NULL, 342, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2690, 'Anderson            ', 'Lindsey             ', ' ', 'F', NULL, 142, 'SO', 0, '              ', ' ', NULL, 343, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2691, 'Bottger             ', 'Erin                ', ' ', 'F', NULL, 142, 'FR', 0, '              ', ' ', NULL, 344, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2692, 'Danielson           ', 'Ian                 ', ' ', 'M', NULL, 142, 'SO', 0, '              ', ' ', NULL, 345, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2693, 'Farhat              ', 'Abraham             ', ' ', 'M', NULL, 142, 'SR', 0, '              ', ' ', NULL, 346, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2694, 'Gumbiner            ', 'Ally                ', ' ', 'F', NULL, 142, 'SR', 0, '              ', ' ', NULL, 347, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2695, 'Hartnett            ', 'Kaitlynn            ', ' ', 'F', NULL, 142, 'SR', 0, '              ', ' ', NULL, 348, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2696, 'Berzins             ', 'Elisa               ', ' ', 'F', NULL, 142, 'JR', 0, '              ', ' ', NULL, 349, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2697, 'DELASHMUTT          ', 'DREW                ', ' ', 'M', NULL, 142, 'JR', 9, '              ', ' ', NULL, 350, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2698, 'Goding              ', 'Nicholas            ', ' ', 'M', NULL, 142, 'SR', 0, '              ', ' ', NULL, 351, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2699, 'Hallgren            ', 'Sara                ', ' ', 'F', NULL, 142, 'FR', 0, '              ', ' ', NULL, 352, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2700, 'Kellon              ', 'Alisha              ', ' ', 'F', NULL, 142, 'SR', 0, '              ', ' ', NULL, 353, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2701, 'KIELION             ', 'KRIS                ', ' ', 'M', NULL, 142, 'JR', 0, '              ', ' ', NULL, 354, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2702, 'McCall              ', 'Sean                ', ' ', 'M', NULL, 142, 'FR', 0, '              ', ' ', NULL, 355, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2703, 'McClure             ', 'Brittany            ', ' ', 'F', NULL, 142, 'JR', 0, '              ', ' ', NULL, 356, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2704, 'Norvell             ', 'Kim                 ', ' ', 'F', NULL, 142, 'JR', 0, '              ', ' ', NULL, 357, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2705, 'Denton              ', 'Charles             ', ' ', 'M', NULL, 143, 'SO', 0, '              ', ' ', NULL, 358, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2706, 'Justice             ', 'James               ', ' ', 'M', NULL, 143, 'FR', 0, '              ', ' ', NULL, 359, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2707, 'BLOOMINGDALE        ', 'RICK                ', ' ', 'M', NULL, 143, 'JR', 0, '              ', ' ', NULL, 360, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2708, 'Bowen               ', 'Rob                 ', ' ', 'M', NULL, 143, 'JR', 0, '              ', ' ', NULL, 361, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2709, 'Enenbach            ', 'Jake                ', ' ', 'M', NULL, 143, 'JR', 0, '              ', ' ', NULL, 362, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2710, 'FISCHER             ', 'SAM                 ', ' ', 'M', NULL, 143, 'SR', 0, '              ', ' ', NULL, 363, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2711, 'FLEISSNER           ', 'JIM                 ', ' ', 'M', NULL, 143, 'SR', 0, '              ', ' ', NULL, 364, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2712, 'Fry                 ', 'Tommy               ', ' ', 'M', NULL, 143, 'SO', 0, '              ', ' ', NULL, 365, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2713, 'Gehringer           ', 'Kyle                ', ' ', 'M', NULL, 143, 'SR', 0, '              ', ' ', NULL, 366, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2714, 'GOEDE               ', 'MIKE                ', ' ', 'M', NULL, 143, 'SR', 0, '              ', ' ', NULL, 367, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2715, 'Lacy                ', 'Jordan              ', ' ', 'M', NULL, 143, 'SR', 0, '              ', ' ', NULL, 368, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2716, 'MacDonald           ', 'LARKIN              ', ' ', 'M', NULL, 143, 'SO', 0, '              ', ' ', NULL, 369, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2717, 'Murphy              ', 'Jesse               ', ' ', 'M', NULL, 143, 'FR', 0, '              ', ' ', NULL, 370, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2718, 'Murphy              ', 'Spencer             ', ' ', 'M', NULL, 143, 'FR', 0, '              ', ' ', NULL, 371, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2719, 'Niemann             ', 'Andrew              ', ' ', 'M', NULL, 143, 'SO', 0, '              ', ' ', NULL, 372, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2720, 'Riesberg            ', 'Josh                ', ' ', 'M', NULL, 143, 'SO', 0, '              ', ' ', NULL, 373, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2721, 'SLOVEK              ', 'QUIN                ', ' ', 'M', NULL, 143, 'SO', 0, '              ', ' ', NULL, 374, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2722, 'TROIA               ', 'ANDREW              ', ' ', 'M', NULL, 143, 'SR', 0, '              ', ' ', NULL, 375, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2723, 'Wedlock             ', 'Matt                ', ' ', 'M', NULL, 143, 'SO', 0, '              ', ' ', NULL, 376, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2724, 'Burnett             ', 'Mary                ', ' ', 'F', NULL, 144, 'JR', 0, '              ', ' ', NULL, 377, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2725, 'Schropp             ', 'Margaret            ', ' ', 'F', NULL, 144, 'SO', 0, '              ', ' ', NULL, 378, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2726, 'Baltzell            ', 'Liz                 ', ' ', 'F', NULL, 144, 'SR', 0, '              ', ' ', NULL, 379, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2727, 'Griffin             ', 'Bethanie            ', ' ', 'F', NULL, 144, 'FR', 0, '              ', ' ', NULL, 380, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2728, 'Hasebrook           ', 'Abigail             ', ' ', 'F', NULL, 144, 'SR', 0, '              ', ' ', NULL, 381, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2729, 'Jarrett             ', 'Megan               ', ' ', 'F', NULL, 144, 'SO', 0, '              ', ' ', NULL, 382, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2730, 'Kemp                ', 'Laura               ', ' ', 'F', NULL, 144, 'FR', 0, '              ', ' ', NULL, 383, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2731, 'Reynolds            ', 'Kate                ', ' ', 'F', NULL, 144, 'JR', 0, '              ', ' ', NULL, 384, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2732, 'SCHLATER            ', 'EMILY               ', ' ', 'F', NULL, 144, '  ', 0, '              ', ' ', NULL, 385, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2733, 'Stryker             ', 'Ellen               ', ' ', 'F', NULL, 145, 'JR', 0, '              ', ' ', NULL, 386, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2734, 'Wells               ', 'Caroline            ', ' ', 'F', NULL, 145, 'JR', 0, '              ', ' ', NULL, 387, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2735, 'Benak               ', 'Amanda              ', ' ', 'F', NULL, 145, 'SO', 0, '              ', ' ', NULL, 388, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2736, 'Criss               ', 'Colleen             ', ' ', 'F', NULL, 145, 'SO', 0, '              ', ' ', NULL, 389, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2737, 'Criss               ', 'Karen               ', ' ', 'F', NULL, 145, 'SO', 0, '              ', ' ', NULL, 390, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2738, 'Golwitzer           ', 'Laura               ', ' ', 'F', NULL, 145, 'JR', 0, '              ', ' ', NULL, 391, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2739, 'Hagar               ', 'Jordan              ', ' ', 'F', NULL, 145, 'FR', 0, '              ', ' ', NULL, 392, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2740, 'Healy               ', 'Elizabeth           ', ' ', 'F', NULL, 145, 'SO', 0, '              ', ' ', NULL, 393, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2741, 'Hoesing             ', 'Ashley              ', ' ', 'F', NULL, 145, 'SO', 0, '              ', ' ', NULL, 394, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2742, 'Holtmeyer           ', 'Erin                ', ' ', 'F', NULL, 145, 'JR', 0, '              ', ' ', NULL, 395, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2743, 'Hopkins             ', 'Caitlin             ', ' ', 'F', NULL, 145, 'FR', 0, '              ', ' ', NULL, 396, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2744, 'Houston             ', 'Louree              ', ' ', 'F', NULL, 145, 'SR', 0, '              ', ' ', NULL, 397, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2745, 'Koch                ', 'Deanna              ', ' ', 'F', NULL, 145, 'FR', 0, '              ', ' ', NULL, 398, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2746, 'KOCSIS              ', 'JENN                ', ' ', 'F', NULL, 145, 'SO', 0, '              ', ' ', NULL, 399, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2747, 'LEWIS               ', 'SAM                 ', ' ', 'F', NULL, 145, 'JR', 0, '              ', ' ', NULL, 400, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2748, 'Mulligan            ', 'Maureen             ', ' ', 'F', NULL, 145, 'FR', 0, '              ', ' ', NULL, 401, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2749, 'Murphy              ', 'Carrie              ', ' ', 'F', NULL, 145, 'JR', 0, '              ', ' ', NULL, 402, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2750, 'Prince              ', 'Sara                ', ' ', 'F', NULL, 145, 'JR', 0, '              ', ' ', NULL, 403, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2751, 'Rezac               ', 'Maggie              ', ' ', 'F', NULL, 145, 'SR', 0, '              ', ' ', NULL, 404, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2752, 'ROACH               ', 'BETH                ', ' ', 'F', NULL, 145, 'SR', 0, '              ', ' ', NULL, 405, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2753, 'Smolczyk            ', 'Jill                ', ' ', 'F', NULL, 145, 'SR', 0, '              ', ' ', NULL, 406, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2754, 'SMOLINSKI           ', 'JACKIE              ', ' ', 'F', NULL, 145, 'FR', 0, '              ', ' ', NULL, 407, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2755, 'Steinauer           ', 'Madeline            ', ' ', 'F', NULL, 145, 'FR', 0, '              ', ' ', NULL, 408, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2756, 'Varnormer           ', 'Claire              ', ' ', 'F', NULL, 145, 'FR', 0, '              ', ' ', NULL, 409, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2757, 'Cassel              ', 'Stephanie           ', ' ', 'F', NULL, 146, 'SR', 0, '              ', ' ', NULL, 410, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2758, 'Garder              ', 'Jason               ', ' ', 'M', NULL, 146, 'FR', 0, '              ', ' ', NULL, 411, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2759, 'Gartin              ', 'Alexander           ', ' ', 'M', NULL, 146, 'FR', 0, '              ', ' ', NULL, 412, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2760, 'Koslosky            ', 'Matthew             ', ' ', 'M', NULL, 146, 'SO', 0, '              ', ' ', NULL, 413, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2761, 'Mattern             ', 'Carley              ', ' ', 'F', NULL, 146, 'SO', 0, '              ', ' ', NULL, 414, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2762, 'Musick              ', 'Jacqueline          ', ' ', 'F', NULL, 146, 'FR', 0, '              ', ' ', NULL, 415, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2763, 'Powers              ', 'Trevor              ', ' ', 'M', NULL, 146, 'SO', 0, '              ', ' ', NULL, 416, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2764, 'Price               ', 'Drew                ', ' ', 'M', NULL, 146, 'JR', 0, '              ', ' ', NULL, 417, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2765, 'Schuermann          ', 'Nicole              ', ' ', 'F', NULL, 146, 'JR', 0, '              ', ' ', NULL, 418, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2766, 'Strain              ', 'Daniel              ', ' ', 'M', NULL, 146, 'SR', 0, '              ', ' ', NULL, 419, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2767, 'Bowery              ', 'Elliott             ', ' ', 'M', NULL, 146, 'SR', 0, '              ', ' ', NULL, 420, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2768, 'Harris              ', 'Kellie              ', ' ', 'F', NULL, 146, 'JR', 0, '              ', ' ', NULL, 421, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2769, 'Johnson             ', 'Kathleen            ', ' ', 'F', NULL, 146, 'SO', 0, '              ', ' ', NULL, 422, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2770, 'Losee               ', 'Adrienne            ', ' ', 'F', NULL, 146, 'SR', 0, '              ', ' ', NULL, 423, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2771, 'Thomas              ', 'Geoffrey            ', ' ', 'M', NULL, 146, 'SR', 0, '              ', ' ', NULL, 424, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2772, 'Werth', 'Tracy', ' ', 'F', NULL, 147, 'SR', 17, '              ', ' ', NULL, 425, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2773, 'Wirthlin', 'Brook', ' ', 'F', NULL, 147, 'JR', 15, '              ', ' ', NULL, 426, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2774, 'Boldra', 'Kyle', ' ', 'M', NULL, 147, 'SO', 15, '              ', ' ', NULL, 427, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2775, 'Pinkelman', 'Kevin', ' ', 'M', NULL, 147, 'JR', 0, '              ', ' ', NULL, 428, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2776, 'Bumpus', 'Michael', ' ', 'M', NULL, 147, 'FR', 0, '              ', ' ', NULL, 429, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2777, 'Bowley', 'Lindsay', ' ', 'F', NULL, 147, 'SO', 0, '              ', ' ', NULL, 430, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2778, 'Tompsett', 'Stephanie', ' ', 'F', NULL, 147, 'JR', 0, '              ', ' ', NULL, 431, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2779, 'Dugger', 'Michelle', ' ', 'F', NULL, 147, 'SO', 0, '              ', ' ', NULL, 432, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2780, 'Christopherson', 'Suzanne', ' ', 'F', NULL, 147, 'SO', 0, '              ', ' ', NULL, 433, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2781, 'Proplesch', 'Kari', ' ', 'F', NULL, 147, 'FR', 0, '              ', ' ', NULL, 434, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2782, 'Bross', 'Andrew', ' ', 'M', NULL, 147, 'SR', 17, '              ', ' ', NULL, 435, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2783, 'Nowak', 'Chris', ' ', 'M', NULL, 147, 'SR', 17, '              ', ' ', NULL, 436, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2784, 'Cutshall', 'Jason', ' ', 'M', NULL, 147, 'SR', 0, '              ', ' ', NULL, 437, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2785, 'Clarke', 'Brett', ' ', 'M', NULL, 147, 'JR', 0, '              ', ' ', NULL, 438, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2786, 'Bachmann            ', 'Angela              ', ' ', 'F', NULL, 149, 'JR', 0, '              ', ' ', NULL, 439, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2787, 'Landolt             ', 'Caitlin             ', ' ', 'F', NULL, 149, 'JR', 0, '              ', ' ', NULL, 440, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2788, 'Prenosil            ', 'Sarah               ', ' ', 'F', NULL, 149, 'SR', 0, '              ', ' ', NULL, 441, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2789, 'Silberstein         ', 'Juliet              ', ' ', 'F', NULL, 149, 'SO', 0, '              ', ' ', NULL, 442, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2790, 'Kremen              ', 'Heather             ', ' ', 'F', NULL, 149, 'FR', 0, '              ', ' ', NULL, 443, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2791, 'Mausbach            ', 'Nathan              ', ' ', 'M', NULL, 149, 'SR', 0, '              ', ' ', NULL, 444, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2792, 'Nairn               ', 'Carolyn             ', ' ', 'F', NULL, 149, 'SR', 0, '              ', ' ', NULL, 445, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2793, 'Welniak             ', 'Sara                ', ' ', 'F', NULL, 150, 'JR', 0, '              ', ' ', NULL, 446, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2794, 'Dornan              ', 'Kevin               ', ' ', 'M', NULL, 151, 'JR', 0, '              ', ' ', NULL, 447, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2795, 'Gard                ', 'Shayna              ', ' ', 'F', NULL, 151, 'JR', 0, '              ', ' ', NULL, 448, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2796, 'Goolsby             ', 'Karen               ', ' ', 'F', NULL, 151, 'SR', 0, '              ', ' ', NULL, 449, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2797, 'Moy                 ', 'Arthur              ', ' ', 'M', NULL, 151, 'JR', 0, '              ', ' ', NULL, 450, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2798, 'Nielsen             ', 'Tyler               ', ' ', 'M', NULL, 151, 'SO', 0, '              ', ' ', NULL, 451, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2799, 'Pascale             ', 'Shay                ', ' ', 'F', NULL, 151, 'SR', 0, '              ', ' ', NULL, 452, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2800, 'Patton              ', 'Steven              ', ' ', 'M', NULL, 151, 'SR', 0, '              ', ' ', NULL, 453, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2801, 'Reynolds            ', 'Megan               ', ' ', 'F', NULL, 151, 'SO', 0, '              ', ' ', NULL, 454, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2802, 'Rich                ', 'Molly               ', ' ', 'F', NULL, 151, 'JR', 0, '              ', ' ', NULL, 455, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2803, 'Samson              ', 'Adam                ', ' ', 'M', NULL, 151, 'SO', 0, '              ', ' ', NULL, 456, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2804, 'Thiessen            ', 'Tara                ', ' ', 'F', NULL, 151, 'SO', 0, '              ', ' ', NULL, 457, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2805, 'Waxse               ', 'Bennett             ', ' ', 'M', NULL, 151, 'SR', 0, '              ', ' ', NULL, 458, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2806, 'Woracek             ', 'Lindsay             ', ' ', 'F', NULL, 151, 'SR', 0, '              ', ' ', NULL, 459, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2807, 'Young               ', 'Braden              ', ' ', 'F', NULL, 151, 'JR', 0, '              ', ' ', NULL, 460, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2808, 'Young               ', 'Clark               ', ' ', 'M', NULL, 151, 'JR', 0, '              ', ' ', NULL, 461, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2809, 'Zimmerman           ', 'Amber               ', ' ', 'F', NULL, 151, 'SR', 0, '              ', ' ', NULL, 462, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2810, 'CAP                 ', 'ALLISON             ', ' ', 'F', NULL, 151, 'JR', 0, '              ', ' ', NULL, 463, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2811, 'ECKERMAN            ', 'GREGORY             ', ' ', 'M', NULL, 151, 'SR', 0, '              ', ' ', NULL, 464, 'GREG                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2812, 'FARNHAM             ', 'MATTHEW             ', ' ', 'M', NULL, 151, 'FR', 0, '              ', ' ', NULL, 465, 'MATT                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2813, 'HANSEN              ', 'PETER               ', ' ', 'M', NULL, 151, 'JR', 0, '              ', ' ', NULL, 466, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2814, 'HENRY               ', 'JENSEN              ', ' ', 'F', NULL, 151, 'FR', 0, '              ', ' ', NULL, 467, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2815, 'LAUVER              ', 'SPENSER             ', ' ', 'M', NULL, 151, 'FR', 0, '              ', ' ', NULL, 468, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2816, 'MCATEE              ', 'JONATHAN            ', ' ', 'M', NULL, 151, 'SR', 0, '              ', ' ', NULL, 469, 'Jon                 ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2817, 'MCMILLAN            ', 'ELIZABETH           ', ' ', 'F', NULL, 151, 'SO', 0, '              ', ' ', NULL, 470, 'Beth                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2818, 'MERTZ               ', 'ERIN                ', ' ', 'F', NULL, 151, 'FR', 0, '              ', ' ', NULL, 471, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2819, 'MESSEL              ', 'MATTHEW             ', ' ', 'M', NULL, 151, 'JR', 0, '              ', ' ', NULL, 472, 'Matt                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2820, 'MOORE               ', 'ROBERT              ', ' ', 'M', NULL, 151, 'SR', 0, '              ', ' ', NULL, 473, 'Rob                 ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2821, 'PEARSON             ', 'JULIA               ', ' ', 'F', NULL, 151, 'FR', 0, '              ', ' ', NULL, 474, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2822, 'QUEVEDO             ', 'CATALINA            ', ' ', 'F', NULL, 151, 'SO', 0, '              ', ' ', NULL, 475, 'Cat                 ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2823, 'RAFAEL              ', 'VICTORIA            ', ' ', 'F', NULL, 151, 'SO', 0, '              ', ' ', NULL, 476, 'Tori                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2824, 'ROSALES YEPEZ       ', 'JOSE                ', ' ', 'M', NULL, 151, 'JR', 0, '              ', ' ', NULL, 477, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2825, 'SAMLAND             ', 'ZACHARY             ', ' ', 'M', NULL, 151, 'JR', 0, '              ', ' ', NULL, 478, 'Zac                 ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2826, 'SISO                ', 'MAURO               ', ' ', 'M', NULL, 151, 'FR', 0, '              ', ' ', NULL, 479, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2827, 'STRUBLE             ', 'DAVID               ', ' ', 'M', NULL, 151, 'SO', 0, '              ', ' ', NULL, 480, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2828, 'WOODS               ', 'TREVOR              ', ' ', 'M', NULL, 151, 'FR', 0, '              ', ' ', NULL, 481, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2829, 'ZICAFOOSE           ', 'KALE                ', ' ', 'F', NULL, 151, 'FR', 0, '              ', ' ', NULL, 482, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2830, 'BAUMERT             ', 'CHRISTOPHER         ', ' ', 'M', '1989-02-04', 152, 'FR', 15, '              ', ' ', NULL, 483, 'Chris               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2831, 'STAHLNECKER         ', 'ANDY                ', ' ', 'M', '1989-01-23', 152, 'FR', 15, '              ', ' ', NULL, 484, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2832, 'BUELT               ', 'ELIZA               ', ' ', 'F', '1988-06-10', 152, 'SO', 15, '              ', ' ', NULL, 485, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2833, 'MILETTA             ', 'LINDSEY             ', ' ', 'F', '1986-08-01', 152, 'SR', 17, '              ', ' ', NULL, 486, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2834, 'OLSON               ', 'DREW                ', ' ', 'M', '1986-05-11', 152, 'SR', 17, '              ', ' ', NULL, 487, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2835, 'STEIN               ', 'ASHLEY              ', ' ', 'F', '1988-08-10', 152, 'SO', 15, '              ', ' ', NULL, 488, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2836, 'WILSON              ', 'HEATHER             ', ' ', 'F', '1986-02-09', 152, 'SR', 18, '              ', ' ', NULL, 489, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2837, 'BARTON              ', 'WILLIAM             ', ' ', 'M', '1986-02-25', 152, 'SR', 18, '              ', ' ', NULL, 490, 'Kyle                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2838, 'LITTLE              ', 'ANDREW              ', ' ', 'M', '1986-08-30', 152, 'JR', 17, '              ', ' ', NULL, 491, 'Drew                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2839, 'SATTERFIELD         ', 'SHAUN               ', ' ', 'M', '1985-11-27', 152, 'SR', 18, '              ', ' ', NULL, 492, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2840, 'KETCHAM             ', 'LAUREN              ', ' ', 'F', '1988-11-23', 152, 'FR', 15, '              ', ' ', NULL, 493, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2841, 'BUELT               ', 'ABRAHAM             ', ' ', 'M', '1985-11-16', 152, 'SR', 18, '              ', ' ', NULL, 494, 'Abe                 ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2842, 'DUIMSTRA            ', 'CHELSEA             ', ' ', 'F', '1986-08-13', 152, 'SR', 17, '              ', ' ', NULL, 495, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2843, 'GRASHORN            ', 'KYLIE               ', ' ', 'F', '1987-11-27', 152, 'SO', 16, '              ', ' ', NULL, 496, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2844, 'GREGORY             ', 'THOMAS              ', ' ', 'M', '1988-07-24', 152, 'SO', 15, '              ', ' ', NULL, 497, 'Tommy               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2845, 'SCHEUBER            ', 'THERESA             ', ' ', 'F', '1987-12-17', 152, 'SO', 16, '              ', ' ', NULL, 498, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2846, 'BERTOLINO           ', 'LINDSAY             ', ' ', 'F', '1989-02-24', 152, 'FR', 15, '              ', ' ', NULL, 499, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2847, 'PARKS               ', 'KATHERINE           ', ' ', 'F', '1986-05-27', 152, 'JR', 17, '              ', ' ', NULL, 500, 'Katie               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2848, 'SCHEUBER            ', 'TIMOTHY             ', ' ', 'M', '1986-06-24', 152, 'SR', 17, '              ', ' ', NULL, 501, 'Tim                 ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2849, 'SENGER III          ', 'JOHN                ', ' ', 'M', '1986-01-28', 152, 'SR', 18, '              ', ' ', NULL, 502, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2850, 'BENNETT', 'KATE', ' ', 'F', '1986-06-19', 153, 'SR', 17, '              ', ' ', NULL, 503, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2851, 'KUMRU', 'SUZAN', ' ', 'F', '1986-03-26', 153, 'SR', 17, '              ', ' ', NULL, 504, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2852, 'PEARSON', 'ANDREW', ' ', 'M', '1986-09-16', 153, 'SR', 17, '              ', ' ', NULL, 505, 'A J', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2853, 'SMITH', 'MARK', ' ', 'M', '1985-12-11', 153, 'SR', 18, '              ', ' ', NULL, 506, 'Mark', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2854, 'SORENSEN', 'SCOTT', ' ', 'M', '1986-08-27', 153, 'SR', 17, '              ', ' ', NULL, 507, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2855, 'ECABERT', 'JACLYN', ' ', 'F', '1986-08-26', 153, 'SR', 17, '              ', ' ', NULL, 508, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2856, 'JACK', 'MIKE', ' ', 'M', '1987-03-19', 153, 'JR', 16, '              ', ' ', NULL, 509, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2857, 'DWORNICKI', 'EMILY', ' ', 'F', '1987-10-05', 153, 'JR', 16, '              ', ' ', NULL, 510, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2858, 'BARROW', 'KAYLA', ' ', 'F', '1987-08-29', 153, 'JR', 16, '              ', ' ', NULL, 511, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2859, 'JORGENSON', 'MATTHEW', ' ', 'M', '1987-02-11', 153, 'JR', 17, '              ', ' ', NULL, 512, 'MATT', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2860, 'PETERSEN', 'DAMIEN', ' ', 'M', '1986-09-07', 153, 'JR', 17, '              ', ' ', NULL, 513, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2861, 'SHANAHAN', 'KYLE', ' ', 'M', '1987-01-11', 153, 'JR', 17, '              ', ' ', NULL, 514, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2862, 'PATRICK', 'MAKAYLA', ' ', 'F', '1987-09-21', 153, 'JR', 16, '              ', ' ', NULL, 515, 'KAYLA', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2863, 'HAMERSKY', 'KARLEY', ' ', 'F', '1985-11-14', 153, 'SR', 18, '              ', ' ', NULL, 516, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2864, 'BENAK', 'ANDREW', ' ', 'M', '1986-11-25', 153, 'JR', 17, '              ', ' ', NULL, 517, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2865, 'BERTINO', 'NINA', ' ', 'F', '1988-01-14', 153, 'SO', 16, '              ', ' ', NULL, 518, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2866, 'KIRKLAND', 'PATRICK', ' ', 'M', '1987-09-29', 153, 'SO', 16, '              ', ' ', NULL, 519, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2867, 'STROYEK', 'BRYAN', ' ', 'M', '1986-09-09', 153, 'SR', 17, '              ', ' ', NULL, 520, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2868, 'DEAN', 'SAMANTHA', ' ', 'F', '1989-05-10', 153, 'FR', 14, '              ', ' ', NULL, 521, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2869, 'PETERSEN', 'CHELSEA', ' ', 'F', '1989-04-16', 153, 'FR', 14, '              ', ' ', NULL, 522, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2870, 'PRZIODA', 'FELIX', ' ', 'M', '1986-11-11', 153, 'SR', 17, '              ', ' ', NULL, 523, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2871, 'SIMON', 'JULIA', ' ', 'F', '1989-04-05', 153, 'FR', 14, '              ', ' ', NULL, 524, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2872, 'Kraus', 'Jacob', ' ', 'M', '1989-04-11', 153, 'FR', 14, '              ', ' ', NULL, 525, 'Jake', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2873, 'Dwornicki', 'Anna', ' ', 'F', '1989-04-03', 153, 'FR', 14, '              ', ' ', NULL, 526, 'Anna', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2874, 'Dittbenner          ', 'Jenny               ', ' ', 'F', NULL, 154, 'SO', 0, '              ', ' ', NULL, 527, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2875, 'Self                ', 'Russell             ', ' ', 'M', NULL, 154, 'FR', 0, '              ', ' ', NULL, 528, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2876, 'Blaylock            ', 'Erin                ', ' ', 'F', NULL, 154, 'SR', 0, '              ', ' ', NULL, 529, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2877, 'Blaylock            ', 'Nathan              ', ' ', 'M', NULL, 154, 'FR', 0, '              ', ' ', NULL, 530, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2878, 'Butcher             ', 'Marie               ', ' ', 'F', NULL, 154, 'SR', 0, '              ', ' ', NULL, 531, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2879, 'Chain               ', 'Zack                ', ' ', 'M', NULL, 154, 'SO', 0, '              ', ' ', NULL, 532, 'Zack                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2880, 'Foreman             ', 'Victoria            ', ' ', 'F', NULL, 154, 'SO', 0, '              ', ' ', NULL, 533, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2881, 'Harvey              ', 'Sarah               ', ' ', 'F', NULL, 154, 'FR', 0, '              ', ' ', NULL, 534, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2882, 'Jackson             ', 'Grant               ', ' ', 'M', NULL, 154, 'JR', 0, '              ', ' ', NULL, 535, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2883, 'Knapper             ', 'Anna                ', ' ', 'F', NULL, 154, 'SR', 0, '              ', ' ', NULL, 536, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2884, 'Lemons              ', 'Jordan              ', ' ', 'M', NULL, 154, 'FR', 0, '              ', ' ', NULL, 537, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2885, 'Looney              ', 'Michael             ', ' ', 'M', NULL, 154, 'JR', 0, '              ', ' ', NULL, 538, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2886, 'Patterson           ', 'Miles               ', ' ', 'M', NULL, 154, 'JR', 0, '              ', ' ', NULL, 539, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2887, 'Post                ', 'Jordan              ', ' ', 'M', NULL, 154, 'SO', 0, '              ', ' ', NULL, 540, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2888, 'Reinhardt           ', 'Becca               ', ' ', 'F', NULL, 154, 'SR', 0, '              ', ' ', NULL, 541, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2889, 'Sindt               ', 'Janel               ', ' ', 'F', NULL, 154, 'JR', 0, '              ', ' ', NULL, 542, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2890, 'Skiles              ', 'Maggie              ', ' ', 'F', NULL, 154, 'SO', 0, '              ', ' ', NULL, 543, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2891, 'Skiles              ', 'Rachael             ', ' ', 'F', NULL, 154, 'SR', 0, '              ', ' ', NULL, 544, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2892, 'TAYLOR              ', 'JOEY                ', ' ', 'M', NULL, 154, 'SO', 0, '              ', ' ', NULL, 545, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2893, 'Trout               ', 'Russell             ', ' ', 'M', NULL, 154, 'SR', 0, '              ', ' ', NULL, 546, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2894, 'Winter              ', 'Melissa             ', ' ', 'F', NULL, 154, 'JR', 0, '              ', ' ', NULL, 547, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2895, 'Buehler             ', 'Elizabeth           ', ' ', 'F', NULL, 155, 'JR', 0, '              ', ' ', NULL, 548, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2896, 'Kennedy             ', 'Tiffany             ', ' ', 'F', NULL, 155, 'SO', 0, '              ', ' ', NULL, 549, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2897, 'Pfister             ', 'Erin                ', ' ', 'F', NULL, 155, 'SR', 0, '              ', ' ', NULL, 550, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2898, 'Yacevich            ', 'Heather             ', ' ', 'F', NULL, 155, 'JR', 0, '              ', ' ', NULL, 551, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2899, 'Clausen             ', 'Stephanie           ', ' ', 'F', NULL, 155, 'SR', 0, '              ', ' ', NULL, 552, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2900, 'Koch                ', 'Ian                 ', ' ', 'M', NULL, 156, 'JR', 0, '              ', ' ', NULL, 553, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2901, 'Krasne              ', 'Seth                ', ' ', 'M', NULL, 156, 'FR', 0, '              ', ' ', NULL, 554, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2902, 'Macklin             ', 'Scott               ', ' ', 'M', NULL, 156, 'FR', 0, '              ', ' ', NULL, 555, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2903, 'Maschmeier          ', 'Mackenzie           ', ' ', 'M', NULL, 156, 'JR', 0, '              ', ' ', NULL, 556, 'Mack                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2904, 'Rozanek             ', 'Logan               ', ' ', 'M', NULL, 156, 'SO', 0, '              ', ' ', NULL, 557, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2905, 'Vancil              ', 'Brandon             ', ' ', 'M', NULL, 156, 'FR', 0, '              ', ' ', NULL, 558, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2906, 'Yosten              ', 'Nathan              ', ' ', 'M', NULL, 156, 'SO', 0, '              ', ' ', NULL, 559, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2907, 'Ahl                 ', 'Candice             ', ' ', 'F', NULL, 156, 'SR', 0, '              ', ' ', NULL, 560, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2908, 'Gaeth               ', 'Troy                ', ' ', 'M', NULL, 156, 'JR', 0, '              ', ' ', NULL, 561, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2909, 'Groh                ', 'Andrew              ', ' ', 'M', NULL, 156, 'JR', 0, '              ', ' ', NULL, 562, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2910, 'Lofgren             ', 'Jonathan            ', ' ', 'M', NULL, 156, 'SR', 0, '              ', ' ', NULL, 563, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2911, 'Schultz             ', 'Ashley              ', ' ', 'F', NULL, 156, 'JR', 0, '              ', ' ', NULL, 564, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2912, 'Ashby               ', 'Andrew              ', ' ', 'M', NULL, 157, 'SO', 0, '              ', ' ', NULL, 565, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2913, 'Bach                ', 'Maggie              ', ' ', 'F', NULL, 157, 'SO', 0, '              ', ' ', NULL, 566, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2914, 'Bennett             ', 'Josh                ', ' ', 'M', NULL, 157, 'FR', 0, '              ', ' ', NULL, 567, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2915, 'Bunde               ', 'Ali                 ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 568, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2916, 'Butler', 'Beth', ' ', 'F', NULL, 157, 'SO', 0, '              ', ' ', NULL, 569, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', '   ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2917, 'Cederdahl           ', 'Metta               ', ' ', 'F', NULL, 157, 'SR', 0, '              ', ' ', NULL, 570, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2918, 'Choi                ', 'Frankie             ', ' ', 'M', NULL, 157, 'FR', 0, '              ', ' ', NULL, 571, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2919, 'Connelly            ', 'Emily               ', ' ', 'F', NULL, 157, 'SO', 0, '              ', ' ', NULL, 572, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2920, 'Davis               ', 'Tori                ', ' ', 'F', NULL, 157, 'SR', 0, '              ', ' ', NULL, 573, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2921, 'Elsener             ', 'Pat                 ', ' ', 'M', NULL, 157, 'SR', 0, '              ', ' ', NULL, 574, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2922, 'Eppert              ', 'Taylor              ', ' ', 'M', NULL, 157, 'SO', 0, '              ', ' ', NULL, 575, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2923, 'Fleming             ', 'Josh                ', ' ', 'M', NULL, 157, 'SR', 0, '              ', ' ', NULL, 576, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2924, 'Frohner             ', 'Melissa             ', ' ', 'F', NULL, 157, 'SR', 0, '              ', ' ', NULL, 577, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2925, 'Genrich             ', 'Nick                ', ' ', 'M', NULL, 157, 'FR', 0, '              ', ' ', NULL, 578, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2926, 'Genrich             ', 'Stephanie           ', ' ', 'F', NULL, 157, 'SO', 0, '              ', ' ', NULL, 579, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2927, 'Gharzai             ', 'Laila               ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 580, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2928, 'Grubbe              ', 'Bryant              ', ' ', 'M', NULL, 157, 'SO', 0, '              ', ' ', NULL, 581, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2929, 'Hubach              ', 'Anna                ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 582, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2930, 'Hubbell             ', 'Katie               ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 583, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2931, 'Kohl                ', 'Kayla               ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 584, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2932, 'Korab               ', 'Emily               ', ' ', 'F', NULL, 157, 'SR', 0, '              ', ' ', NULL, 585, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2933, 'Lee                 ', 'Candace             ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 586, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2934, 'Masters             ', 'Christa             ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 587, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2935, 'Million             ', 'Kelsey              ', ' ', 'F', NULL, 157, 'SO', 0, '              ', ' ', NULL, 588, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2936, 'Mota                ', 'Lauren              ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 589, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2937, 'Moyer               ', 'Annie               ', ' ', 'F', NULL, 157, 'SO', 0, '              ', ' ', NULL, 590, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2938, 'Mueller             ', 'Rodney              ', ' ', 'M', NULL, 157, 'FR', 0, '              ', ' ', NULL, 591, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2939, 'Mueller             ', 'Russell             ', ' ', 'M', NULL, 157, 'JR', 0, '              ', ' ', NULL, 592, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2940, 'Murman              ', 'Tim                 ', ' ', 'M', NULL, 157, 'SR', 0, '              ', ' ', NULL, 593, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2941, 'Olson               ', 'Mike                ', ' ', 'M', NULL, 157, 'FR', 0, '              ', ' ', NULL, 594, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2942, 'Ottemann            ', 'Brendan             ', ' ', 'M', NULL, 157, 'SO', 0, '              ', ' ', NULL, 595, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2943, 'Ottemann            ', 'Corbin              ', ' ', 'M', NULL, 157, 'FR', 0, '              ', ' ', NULL, 596, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2944, 'Ottemann            ', 'Heather             ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 597, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2945, 'Petersen            ', 'Jennifer            ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 598, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2946, 'Petersen            ', 'Steph               ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 599, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2947, 'Phillips            ', 'Jessie              ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 600, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2948, 'Poppert             ', 'Katie               ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 601, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2949, 'Potter              ', 'Kylie               ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 602, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2950, 'Reichenbach         ', 'Lauren              ', ' ', 'F', NULL, 157, 'SR', 0, '              ', ' ', NULL, 603, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2951, 'Roling              ', 'Birgit              ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 604, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2952, 'Rye                 ', 'Anne Marie          ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 605, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2953, 'Sabin               ', 'Carli               ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 606, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2954, 'Samuelson           ', 'Britt               ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 607, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2955, 'Schoettger          ', 'Dani                ', ' ', 'F', NULL, 157, 'SO', 0, '              ', ' ', NULL, 608, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2956, 'Schorr              ', 'Allison             ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 609, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2957, 'Schultze            ', 'Conor               ', ' ', 'M', NULL, 157, 'SR', 0, '              ', ' ', NULL, 610, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2958, 'Sneed               ', 'Avery               ', ' ', 'F', NULL, 157, 'SO', 0, '              ', ' ', NULL, 611, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2959, 'Southworth          ', 'Sally               ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 612, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2960, 'Stavas              ', 'Andy                ', ' ', 'M', NULL, 157, 'SO', 0, '              ', ' ', NULL, 613, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2961, 'Stroud              ', 'Jenna               ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 614, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2962, 'Swift               ', 'Rory                ', ' ', 'M', NULL, 157, 'SR', 0, '              ', ' ', NULL, 615, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2963, 'Tetrault            ', 'Katie               ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 616, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2964, 'Thompson            ', 'Lauren              ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 617, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2965, 'Unzicker            ', 'Sydney              ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 618, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2966, 'Voigt               ', 'Peter               ', ' ', 'M', NULL, 157, 'FR', 0, '              ', ' ', NULL, 619, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2967, 'Wagner              ', 'Larissa             ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 620, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2968, 'Werner              ', 'Carolyn             ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 621, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2969, 'Wertz               ', 'Julie               ', ' ', 'F', NULL, 157, 'FR', 0, '              ', ' ', NULL, 622, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2970, 'Whittier            ', 'Dain                ', ' ', 'M', NULL, 157, 'SO', 0, '              ', ' ', NULL, 623, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2971, 'Wolford             ', 'Amie                ', ' ', 'F', NULL, 157, 'JR', 0, '              ', ' ', NULL, 624, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2972, 'Cunningham          ', 'Shawn               ', ' ', 'M', NULL, 158, 'FR', 0, '              ', ' ', NULL, 625, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2973, 'Doey                ', 'Loren               ', ' ', 'F', NULL, 158, 'SO', 0, '              ', ' ', NULL, 626, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2974, 'Ferrara             ', 'Matthew             ', ' ', 'M', NULL, 158, 'JR', 0, '              ', ' ', NULL, 627, 'Matt                ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2975, 'Fischer             ', 'John                ', ' ', 'M', NULL, 158, 'SR', 0, '              ', ' ', NULL, 628, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2976, 'Grubbs              ', 'Nathan              ', ' ', 'M', NULL, 158, 'SR', 0, '              ', ' ', NULL, 629, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2977, 'Hardin              ', 'Kayla               ', ' ', 'F', NULL, 158, 'JR', 0, '              ', ' ', NULL, 630, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2978, 'Haro                ', 'Adam                ', ' ', 'M', NULL, 158, 'SR', 0, '              ', ' ', NULL, 631, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2979, 'Hokkanen            ', 'Sarah               ', ' ', 'F', NULL, 158, 'JR', 0, '              ', ' ', NULL, 632, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2980, 'McCormick           ', 'Joshua              ', ' ', 'M', NULL, 158, 'SO', 0, '              ', ' ', NULL, 633, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2981, 'McMasters           ', 'Kaitlin             ', ' ', 'F', NULL, 158, 'JR', 0, '              ', ' ', NULL, 634, 'Katie               ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2982, 'Smith               ', 'Erika               ', ' ', 'F', NULL, 158, 'SO', 0, '              ', ' ', NULL, 635, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2983, 'Stanek              ', 'Anton               ', ' ', 'M', NULL, 158, 'SR', 0, '              ', ' ', NULL, 636, 'AJ                  ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2984, 'Williams            ', 'Joseph              ', ' ', 'M', NULL, 158, 'JR', 0, '              ', ' ', NULL, 637, 'Joe                 ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2985, 'Born                ', 'Elisa               ', ' ', 'F', NULL, 158, 'SO', 0, '              ', ' ', NULL, 638, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2986, 'Gabriel             ', 'Luke                ', ' ', 'M', NULL, 158, 'JR', 0, '              ', ' ', NULL, 639, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2987, 'Mehaffy             ', 'Stacy               ', ' ', 'F', NULL, 158, 'SO', 0, '              ', ' ', NULL, 640, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2988, 'Rodgers             ', 'Laura               ', ' ', 'F', NULL, 158, 'JR', 0, '              ', ' ', NULL, 641, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2989, 'Tufts               ', 'Cameron             ', ' ', 'M', NULL, 158, 'FR', 0, '              ', ' ', NULL, 642, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2990, 'Tufts               ', 'Winfield            ', ' ', 'M', NULL, 158, 'JR', 0, '              ', ' ', NULL, 643, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2991, 'Wiles               ', 'Tracy               ', ' ', 'F', NULL, 158, 'SO', 0, '              ', ' ', NULL, 644, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2992, 'Bottum', 'Justin', ' ', 'M', NULL, 147, 'SR', 0, ' ', ' ', NULL, 645, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2993, 'Rasnick', 'Laura', ' ', 'F', NULL, 147, 'JR', 0, ' ', ' ', NULL, 646, ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ', ' ', ' ', ' ', "", ' ', ' ', ' ');
INSERT INTO `Athlete` VALUES(2994, 'Arntz               ', 'Kaitlin             ', ' ', 'F', NULL, 159, 'SO', 0, '              ', ' ', NULL, 647, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2995, 'BRAY                ', 'C.J.                ', ' ', 'F', NULL, 159, 'JR', 11, '              ', ' ', NULL, 648, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2996, 'Carnaby             ', 'Coleen              ', ' ', 'F', NULL, 159, 'SR', 0, '              ', ' ', NULL, 649, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2997, 'Horn                ', 'Kevin               ', ' ', 'M', NULL, 159, 'JR', 0, '              ', ' ', NULL, 650, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2998, 'Hunt                ', 'Andrew              ', ' ', 'M', NULL, 159, 'SR', 0, '              ', ' ', NULL, 651, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(2999, 'Kaeter              ', 'Maxwell             ', ' ', 'M', NULL, 159, 'JR', 0, '              ', ' ', NULL, 652, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3000, 'Kaeter              ', 'Sophie              ', ' ', 'F', NULL, 159, 'FR', 0, '              ', ' ', NULL, 653, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3001, 'Klaiber             ', 'Dawn                ', ' ', 'F', NULL, 159, 'SO', 0, '              ', ' ', NULL, 654, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3002, 'Lenzen              ', 'Nathan              ', ' ', 'M', NULL, 159, 'SR', 0, '              ', ' ', NULL, 655, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3003, 'Masterson           ', 'Brett               ', ' ', 'M', NULL, 159, 'SO', 0, '              ', ' ', NULL, 656, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3004, 'McClay              ', 'Sean                ', ' ', 'M', NULL, 159, 'FR', 0, '              ', ' ', NULL, 657, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3005, 'Modlin              ', 'Kyle                ', ' ', 'M', NULL, 159, 'SR', 0, '              ', ' ', NULL, 658, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3006, 'Murphy              ', 'Meghan              ', ' ', 'F', NULL, 159, 'JR', 0, '              ', ' ', NULL, 659, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3007, 'Nuzzolillo          ', 'Erica               ', ' ', 'F', NULL, 159, 'SR', 0, '              ', ' ', NULL, 660, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3008, 'Pengilly            ', 'Teresa              ', ' ', 'F', NULL, 159, 'SO', 0, '              ', ' ', NULL, 661, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3009, 'PETROSIK            ', 'CHARLIE             ', ' ', 'M', NULL, 159, 'SR', 0, '              ', ' ', NULL, 662, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3010, 'Pufall              ', 'Emily               ', ' ', 'F', NULL, 159, 'SR', 0, '              ', ' ', NULL, 663, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3011, 'Raffety             ', 'Nathan              ', ' ', 'M', NULL, 159, 'SO', 0, '              ', ' ', NULL, 664, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3012, 'Rogers              ', 'Whitney             ', ' ', 'F', NULL, 159, 'SR', 0, '              ', ' ', NULL, 665, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3013, 'Sampson             ', 'Alex                ', ' ', 'M', NULL, 159, 'JR', 0, '              ', ' ', NULL, 666, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3014, 'Sheppard            ', 'Alex                ', ' ', 'M', NULL, 159, 'FR', 0, '              ', ' ', NULL, 667, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3015, 'Sirotkin            ', 'Emily               ', ' ', 'F', NULL, 159, 'FR', 0, '              ', ' ', NULL, 668, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3016, 'Stauffer            ', 'Seth                ', ' ', 'M', NULL, 159, 'FR', 0, '              ', ' ', NULL, 669, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3017, 'Wright              ', 'Eric                ', ' ', 'M', NULL, 159, 'JR', 0, '              ', ' ', NULL, 670, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
INSERT INTO `Athlete` VALUES(3018, 'Zubrod              ', 'Brendan             ', ' ', 'M', NULL, 159, 'JR', 0, '              ', ' ', NULL, 671, ' ', "", "", "", "", "", "", "", "", "", "", '   ', "", "", "", "");
UNLOCK TABLES;
ALTER TABLE `Athlete` CHANGE `Birth_date` `Birth_date` DATE;

#
# Table structure for table 'Divisions'
#

DROP TABLE IF EXISTS `Divisions`;
CREATE TABLE `Divisions` (
  `Div_no` INT,
  `Div_abbr` VARCHAR(2),
  `Div_name` VARCHAR(20)
);

#
# Dumping data for table 'Divisions'
#

LOCK TABLES `Divisions` WRITE;
INSERT INTO `Divisions` VALUES(1, 'V', 'Varsity');
INSERT INTO `Divisions` VALUES(2, 'JV', 'Junior Varsity');
INSERT INTO `Divisions` VALUES(3, ' ', ' ');
INSERT INTO `Divisions` VALUES(4, ' ', ' ');
INSERT INTO `Divisions` VALUES(5, ' ', ' ');
INSERT INTO `Divisions` VALUES(6, ' ', ' ');
INSERT INTO `Divisions` VALUES(7, ' ', ' ');
INSERT INTO `Divisions` VALUES(8, ' ', ' ');
INSERT INTO `Divisions` VALUES(9, ' ', ' ');
INSERT INTO `Divisions` VALUES(10, ' ', ' ');
UNLOCK TABLES;

#
# Table structure for table 'Dualteams'
#

DROP TABLE IF EXISTS `Dualteams`;
CREATE TABLE `Dualteams` (
  `team_gender` VARCHAR(1),
  `ateam_no` INT,
  `bteam_no` INT
);

#
# Dumping data for table 'Dualteams'
#

LOCK TABLES `Dualteams` WRITE;
UNLOCK TABLES;

#
# Table structure for table 'Entry'
#

DROP TABLE IF EXISTS `Entry`;
CREATE TABLE `Entry` (
  `Event_ptr` INT,
  `Ath_no` INT,
  `ActSeed_course` VARCHAR(1),
  `ActualSeed_time` FLOAT,
  `ConvSeed_course` VARCHAR(1),
  `ConvSeed_time` FLOAT,
  `Scr_stat` BIT,
  `Spec_stat` VARCHAR(1),
  `Dec_stat` VARCHAR(1),
  `Alt_stat` BIT,
  `Bonus_event` BIT,
  `Div_no` INT,
  `Ev_score` FLOAT,
  `dq_type` VARCHAR(1),
  `Pre_heat` SMALLINT,
  `Pre_lane` SMALLINT,
  `Pre_stat` VARCHAR(1),
  `Pre_Time` FLOAT,
  `Pre_course` VARCHAR(1),
  `Pre_heatplace` SMALLINT,
  `Pre_place` SMALLINT,
  `Pre_jdplace` SMALLINT,
  `Pre_exh` VARCHAR(1),
  `Pre_points` SMALLINT,
  `Pre_back1` FLOAT,
  `Pre_back2` FLOAT,
  `Pre_back3` FLOAT,
  `Fin_heat` SMALLINT,
  `Fin_lane` SMALLINT,
  `Fin_group` SMALLINT,
  `Fin_stat` VARCHAR(1),
  `Fin_Time` FLOAT,
  `Fin_course` VARCHAR(1),
  `Fin_heatplace` SMALLINT,
  `Fin_place` SMALLINT,
  `Fin_jdplace` SMALLINT,
  `Fin_ptsplace` SMALLINT,
  `Fin_exh` VARCHAR(1),
  `Fin_points` SMALLINT,
  `Fin_back1` FLOAT,
  `Fin_back2` FLOAT,
  `Fin_back3` FLOAT,
  `Sem_heat` SMALLINT,
  `Sem_lane` SMALLINT,
  `Sem_stat` VARCHAR(1),
  `Sem_Time` FLOAT,
  `Sem_course` VARCHAR(1),
  `Sem_heatplace` SMALLINT,
  `Sem_place` SMALLINT,
  `Sem_jdplace` SMALLINT,
  `Sem_exh` VARCHAR(1),
  `Sem_points` SMALLINT,
  `Sem_back1` FLOAT,
  `Sem_back2` FLOAT,
  `Sem_back3` FLOAT,
  `Swimoff_heat` SMALLINT,
  `Swimoff_lane` SMALLINT,
  `Swimoff_stat` VARCHAR(1),
  `Swimoff_Time` FLOAT,
  `Swimoff_course` VARCHAR(1),
  `Swimoff_heatplace` SMALLINT,
  `Swimoff_place` SMALLINT,
  `Swimoff_jdplace` SMALLINT,
  `Swimoff_points` SMALLINT,
  `Swimoff_back1` FLOAT,
  `Swimoff_back2` FLOAT,
  `Swimoff_back3` FLOAT,
  `JDEv_score` FLOAT,
  `fin_jdheatplace` SMALLINT,
  `Seed_place` SMALLINT,
  `fin_heatltr` VARCHAR(1),
  INDEX `entathno` (`Ath_no`),
  INDEX `entevtptr` (`Event_ptr`)
);

#
# Dumping data for table 'Entry'
#

LOCK TABLES `Entry` WRITE;
INSERT INTO `Entry` VALUES(3, 2392, 'Y', 1.330900e+002, 'Y', 1.330900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2393, 'Y', 3.518500e+002, 'Y', 3.518500e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2394, 'Y', 6.568000e+001, 'Y', 6.568000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2394, 'Y', 5.793000e+001, 'Y', 5.793000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2395, 'Y', 3.589500e+002, 'Y', 3.589500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2396, 'Y', 3.254000e+002, 'Y', 3.254000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2397, 'Y', 2.385000e+001, 'Y', 2.385000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2397, 'Y', 5.247000e+001, 'Y', 5.247000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2398, 'Y', 2.362000e+001, 'Y', 2.362000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2398, 'Y', 7.051000e+001, 'Y', 7.051000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2399, 'Y', 2.551000e+001, 'Y', 2.551000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2399, 'Y', 5.518000e+001, 'Y', 5.518000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2402, 'Y', 3.089000e+002, 'Y', 3.089000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2403, 'Y', 1.424800e+002, 'Y', 1.424800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2403, 'Y', 3.450400e+002, 'Y', 3.450400e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2404, 'Y', 1.321100e+002, 'Y', 1.321100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2404, 'Y', 5.828000e+001, 'Y', 5.828000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2405, 'Y', 3.254400e+002, 'Y', 3.254400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2405, 'Y', 7.121000e+001, 'Y', 7.121000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2406, 'Y', 1.236100e+002, 'Y', 1.236100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2406, 'Y', 3.323800e+002, 'Y', 3.323800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2410, 'Y', 1.536400e+002, 'Y', 1.536400e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2410, 'Y', 6.198000e+001, 'Y', 6.198000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2413, 'Y', 1.330000e+002, 'Y', 1.330000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2413, 'Y', 3.564000e+002, 'Y', 3.564000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2414, 'Y', 2.755000e+001, 'Y', 2.755000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2414, 'Y', 6.865000e+001, 'Y', 6.865000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2415, 'Y', 6.516000e+001, 'Y', 6.516000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2416, 'Y', 1.335200e+002, 'Y', 1.335200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2416, 'Y', 6.629000e+001, 'Y', 6.629000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2418, 'Y', 3.198500e+002, 'Y', 3.198500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2419, 'Y', 3.152500e+002, 'Y', 3.152500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2435, 'Y', 7.475000e+001, 'Y', 7.475000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2438, 'Y', 2.488000e+001, 'Y', 2.488000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2439, 'Y', 2.495000e+001, 'Y', 2.495000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2440, 'Y', 8.020000e+001, 'Y', 8.020000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2441, 'Y', 2.696000e+001, 'Y', 2.696000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2441, 'Y', 6.088000e+001, 'Y', 6.088000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2442, 'Y', 6.998000e+001, 'Y', 6.998000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2443, 'Y', 7.036000e+001, 'Y', 7.036000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2444, 'Y', 6.075000e+001, 'Y', 6.075000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2444, 'Y', 6.034000e+001, 'Y', 6.034000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2445, 'Y', 1.181700e+002, 'Y', 1.181700e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2445, 'Y', 3.297600e+002, 'Y', 3.297600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2446, 'Y', 1.466800e+002, 'Y', 1.466800e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2446, 'Y', 6.684000e+001, 'Y', 6.684000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2447, 'Y', 1.272000e+002, 'Y', 1.272000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2447, 'Y', 6.468000e+001, 'Y', 6.468000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2448, 'Y', 4.132500e+002, 'Y', 4.132500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2461, 'Y', 3.703000e+002, 'Y', 3.703000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2462, 'Y', 2.291000e+001, 'Y', 2.291000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2462, 'Y', 5.102000e+001, 'Y', 5.102000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2463, 'Y', 1.180400e+002, 'Y', 1.180400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2463, 'Y', 3.308700e+002, 'Y', 3.308700e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2468, 'Y', 1.395500e+002, 'Y', 1.395500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2468, 'Y', 6.275000e+001, 'Y', 6.275000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2469, 'Y', 1.139600e+002, 'Y', 1.139600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2469, 'Y', 5.336000e+001, 'Y', 5.336000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2470, 'Y', 6.540000e+001, 'Y', 6.540000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2471, 'Y', 6.117000e+001, 'Y', 6.117000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2472, 'Y', 1.281600e+002, 'Y', 1.281600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2472, 'Y', 3.098000e+002, 'Y', 3.098000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2473, 'Y', 7.184000e+001, 'Y', 7.184000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2474, 'Y', 3.037000e+002, 'Y', 3.037000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2475, 'Y', 1.109300e+002, 'Y', 1.109300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2475, 'Y', 5.018000e+001, 'Y', 5.018000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2476, 'Y', 5.705000e+001, 'Y', 5.705000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2477, 'Y', 3.425500e+002, 'Y', 3.425500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2478, 'Y', 3.390000e+002, 'Y', 3.390000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2479, 'Y', 2.253000e+001, 'Y', 2.253000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2479, 'Y', 6.626000e+001, 'Y', 6.626000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2480, 'Y', 2.469000e+001, 'Y', 2.469000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2480, 'Y', 6.176000e+001, 'Y', 6.176000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2482, 'Y', 7.997000e+001, 'Y', 7.997000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2483, 'Y', 1.350300e+002, 'Y', 1.350300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2483, 'Y', 6.823000e+001, 'Y', 6.823000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2485, 'Y', 2.397000e+001, 'Y', 2.397000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2485, 'Y', 6.031000e+001, 'Y', 6.031000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2486, 'Y', 2.693000e+001, 'Y', 2.693000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2486, 'Y', 5.930000e+001, 'Y', 5.930000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2487, 'Y', 6.081000e+001, 'Y', 6.081000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2488, 'Y', 2.579000e+001, 'Y', 2.579000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2488, 'Y', 5.596000e+001, 'Y', 5.596000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2491, 'Y', 3.649900e+002, 'Y', 3.649900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2493, 'Y', 2.322000e+001, 'Y', 2.322000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2493, 'Y', 5.229000e+001, 'Y', 5.229000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2495, 'Y', 1.410500e+002, 'Y', 1.410500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2495, 'Y', 6.315000e+001, 'Y', 6.315000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2496, 'Y', 2.418000e+001, 'Y', 2.418000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2496, 'Y', 5.355000e+001, 'Y', 5.355000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2497, 'Y', 1.328400e+002, 'Y', 1.328400e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2497, 'Y', 6.290000e+001, 'Y', 6.290000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2499, 'Y', 3.729500e+002, 'Y', 3.729500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2500, 'Y', 3.534000e+002, 'Y', 3.534000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2516, 'Y', 2.785000e+001, 'Y', 2.785000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2517, 'Y', 0.000000e+000, 'Y', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2518, 'Y', 1.358500e+002, 'Y', 1.358500e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2518, 'Y', 6.139000e+001, 'Y', 6.139000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2519, 'Y', 0.000000e+000, 'Y', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2520, 'Y', 3.295300e+002, 'Y', 3.295300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2520, 'Y', 6.076000e+001, 'Y', 6.076000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2528, 'Y', 2.997000e+002, 'Y', 2.997000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2529, 'Y', 2.487000e+001, 'Y', 2.487000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2533, 'Y', 3.507500e+002, 'Y', 3.507500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2534, 'Y', 1.251200e+002, 'Y', 1.251200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2534, 'Y', 3.333700e+002, 'Y', 3.333700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2535, 'Y', 1.317700e+002, 'Y', 1.317700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2535, 'Y', 6.226000e+001, 'Y', 6.226000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2536, 'Y', 2.509000e+001, 'Y', 2.509000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2537, 'Y', 6.439000e+001, 'Y', 6.439000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2538, 'Y', 2.369000e+001, 'Y', 2.369000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2538, 'Y', 5.148000e+001, 'Y', 5.148000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2539, 'Y', 1.316000e+002, 'Y', 1.316000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2539, 'Y', 6.846000e+001, 'Y', 6.846000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2540, 'Y', 1.325000e+002, 'Y', 1.325000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2540, 'Y', 3.502300e+002, 'Y', 3.502300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2541, 'Y', 2.317000e+001, 'Y', 2.317000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2542, 'Y', 3.523000e+002, 'Y', 3.523000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2543, 'Y', 6.330000e+001, 'Y', 6.330000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2544, 'Y', 6.675000e+001, 'Y', 6.675000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2545, 'Y', 5.997000e+001, 'Y', 5.997000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2545, 'Y', 6.112000e+001, 'Y', 6.112000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2546, 'Y', 1.152000e+002, 'Y', 1.152000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2546, 'Y', 3.108100e+002, 'Y', 3.108100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2547, 'Y', 7.271000e+001, 'Y', 7.271000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2548, 'Y', 4.074500e+002, 'Y', 4.074500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2549, 'Y', 1.194200e+002, 'Y', 1.194200e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2563, 'Y', 5.813000e+001, 'Y', 5.813000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2563, 'Y', 6.038000e+001, 'Y', 6.038000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2564, 'Y', 2.701000e+001, 'Y', 2.701000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2564, 'Y', 7.927000e+001, 'Y', 7.927000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2565, 'Y', 6.074000e+001, 'Y', 6.074000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2565, 'Y', 3.349700e+002, 'Y', 3.349700e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2566, 'Y', 3.658600e+002, 'Y', 3.658600e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2566, 'Y', 6.799000e+001, 'Y', 6.799000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2567, 'Y', 2.449000e+001, 'Y', 2.449000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2567, 'Y', 5.479000e+001, 'Y', 5.479000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2568, 'Y', 3.321500e+002, 'Y', 3.321500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2569, 'Y', 7.119000e+001, 'Y', 7.119000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2570, 'Y', 2.456000e+001, 'Y', 2.456000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2570, 'Y', 6.855000e+001, 'Y', 6.855000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2571, 'Y', 3.559500e+002, 'Y', 3.559500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2572, 'Y', 2.678000e+001, 'Y', 2.678000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2572, 'Y', 5.851000e+001, 'Y', 5.851000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2573, 'Y', 1.367000e+002, 'Y', 1.367000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2573, 'Y', 6.370000e+001, 'Y', 6.370000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2574, 'Y', 5.957000e+001, 'Y', 5.957000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2574, 'Y', 6.942000e+001, 'Y', 6.942000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2575, 'Y', 2.395000e+001, 'Y', 2.395000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2576, 'Y', 5.750000e+001, 'Y', 5.750000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2576, 'Y', 6.014000e+001, 'Y', 6.014000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2577, 'Y', 5.908000e+001, 'Y', 5.908000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2578, 'Y', 5.889000e+001, 'Y', 5.889000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2579, 'Y', 1.101700e+002, 'Y', 1.101700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2579, 'Y', 4.992000e+001, 'Y', 4.992000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2580, 'Y', 2.609000e+001, 'Y', 2.609000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2580, 'Y', 5.738000e+001, 'Y', 5.738000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2581, 'Y', 1.157000e+002, 'Y', 1.157000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2581, 'Y', 3.110900e+002, 'Y', 3.110900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2582, 'Y', 3.419500e+002, 'Y', 3.419500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2583, 'Y', 2.423000e+001, 'Y', 2.423000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2583, 'Y', 5.303000e+001, 'Y', 5.303000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2584, 'Y', 6.205000e+001, 'Y', 6.205000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2584, 'Y', 6.125000e+001, 'Y', 6.125000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2585, 'Y', 2.615000e+001, 'Y', 2.615000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2585, 'Y', 7.283000e+001, 'Y', 7.283000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2586, 'Y', 1.249900e+002, 'Y', 1.249900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2586, 'Y', 3.324400e+002, 'Y', 3.324400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2587, 'Y', 6.586000e+001, 'Y', 6.586000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2588, 'Y', 1.121900e+002, 'Y', 1.121900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2588, 'Y', 3.065900e+002, 'Y', 3.065900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2589, 'Y', 2.337000e+001, 'Y', 2.337000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2589, 'Y', 5.082000e+001, 'Y', 5.082000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2590, 'Y', 1.342400e+002, 'Y', 1.342400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2590, 'Y', 5.225000e+001, 'Y', 5.225000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2591, 'Y', 5.464000e+001, 'Y', 5.464000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2591, 'Y', 5.946000e+001, 'Y', 5.946000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2592, 'Y', 6.692000e+001, 'Y', 6.692000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2593, 'Y', 2.585000e+001, 'Y', 2.585000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2593, 'Y', 6.328000e+001, 'Y', 6.328000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2594, 'Y', 7.298000e+001, 'Y', 7.298000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2595, 'Y', 3.337500e+002, 'Y', 3.337500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2596, 'Y', 3.456000e+002, 'Y', 3.456000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2597, 'Y', 1.301700e+002, 'Y', 1.301700e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2597, 'Y', 6.558000e+001, 'Y', 6.558000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2598, 'Y', 1.443300e+002, 'Y', 1.443300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2598, 'Y', 3.363500e+002, 'Y', 3.363500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2599, 'Y', 3.013000e+002, 'Y', 3.013000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2600, 'Y', 1.408100e+002, 'Y', 1.408100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2600, 'Y', 6.188000e+001, 'Y', 6.188000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2601, 'Y', 1.226300e+002, 'Y', 1.226300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2601, 'Y', 6.413000e+001, 'Y', 6.413000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2602, 'Y', 1.468300e+002, 'Y', 1.468300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2602, 'Y', 7.275000e+001, 'Y', 7.275000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2618, 'Y', 4.186500e+002, 'Y', 4.186500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2619, 'Y', 2.489000e+001, 'Y', 2.489000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2619, 'Y', 5.520000e+001, 'Y', 5.520000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2620, 'Y', 3.967500e+002, 'Y', 3.967500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2621, 'Y', 1.290500e+002, 'Y', 1.290500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2621, 'Y', 5.922000e+001, 'Y', 5.922000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2622, 'Y', 1.315900e+002, 'Y', 1.315900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2623, 'Y', 1.194700e+002, 'Y', 1.194700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2623, 'Y', 5.843000e+001, 'Y', 5.843000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2629, 'Y', 1.227700e+002, 'Y', 1.227700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2629, 'Y', 6.239000e+001, 'Y', 6.239000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2630, 'Y', 7.433000e+001, 'Y', 7.433000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2631, 'Y', 1.485900e+002, 'Y', 1.485900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2631, 'Y', 3.411200e+002, 'Y', 3.411200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2632, 'Y', 2.522000e+001, 'Y', 2.522000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2632, 'Y', 5.555000e+001, 'Y', 5.555000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2633, 'Y', 1.349800e+002, 'Y', 1.349800e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2634, 'Y', 1.324700e+002, 'Y', 1.324700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2634, 'Y', 5.952000e+001, 'Y', 5.952000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2635, 'Y', 1.177300e+002, 'Y', 1.177300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2635, 'Y', 3.075700e+002, 'Y', 3.075700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2636, 'Y', 6.493000e+001, 'Y', 6.493000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2636, 'Y', 6.740000e+001, 'Y', 6.740000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2642, 'Y', 3.550500e+002, 'Y', 3.550500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2643, 'Y', 1.161200e+002, 'Y', 1.161200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2643, 'Y', 5.682000e+001, 'Y', 5.682000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2644, 'Y', 1.439000e+002, 'Y', 1.439000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2644, 'Y', 6.535000e+001, 'Y', 6.535000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2645, 'Y', 5.661000e+001, 'Y', 5.661000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2645, 'Y', 3.053100e+002, 'Y', 3.053100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2646, 'Y', 6.919000e+001, 'Y', 6.919000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2651, 'Y', 2.690000e+001, 'Y', 2.690000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2651, 'Y', 6.169000e+001, 'Y', 6.169000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2652, 'Y', 2.468000e+001, 'Y', 2.468000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2652, 'Y', 5.534000e+001, 'Y', 5.534000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2662, 'Y', 2.398000e+001, 'Y', 2.398000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2662, 'Y', 5.377000e+001, 'Y', 5.377000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2663, 'Y', 2.671000e+001, 'Y', 2.671000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2663, 'Y', 6.573000e+001, 'Y', 6.573000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2664, 'Y', 1.220100e+002, 'Y', 1.220100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2664, 'Y', 6.339000e+001, 'Y', 6.339000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2665, 'Y', 4.443500e+002, 'Y', 4.443500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2666, 'Y', 6.629000e+001, 'Y', 6.629000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2667, 'Y', 1.271900e+002, 'Y', 1.271900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2667, 'Y', 3.422900e+002, 'Y', 3.422900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2668, 'Y', 3.015300e+002, 'Y', 3.015300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2668, 'Y', 5.873000e+001, 'Y', 5.873000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2669, 'Y', 3.998000e+002, 'Y', 3.998000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2670, 'Y', 3.272300e+002, 'Y', 3.272300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2671, 'Y', 1.440000e+002, 'Y', 1.440000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2671, 'Y', 3.441700e+002, 'Y', 3.441700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2672, 'Y', 2.459000e+001, 'Y', 2.459000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2672, 'Y', 6.084000e+001, 'Y', 6.084000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2673, 'Y', 2.598000e+001, 'Y', 2.598000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2673, 'Y', 5.785000e+001, 'Y', 5.785000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2674, 'Y', 3.786800e+002, 'Y', 3.786800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2675, 'Y', 1.160800e+002, 'Y', 1.160800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2675, 'Y', 3.156800e+002, 'Y', 3.156800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2676, 'Y', 6.329000e+001, 'Y', 6.329000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2677, 'Y', 6.415000e+001, 'Y', 6.415000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2677, 'Y', 6.425000e+001, 'Y', 6.425000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2678, 'Y', 5.863000e+001, 'Y', 5.863000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2678, 'Y', 6.744000e+001, 'Y', 6.744000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2679, 'Y', 1.256600e+002, 'Y', 1.256600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2679, 'Y', 3.402800e+002, 'Y', 3.402800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2680, 'Y', 3.522900e+002, 'Y', 3.522900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2681, 'Y', 2.295000e+001, 'Y', 2.295000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2681, 'Y', 5.071000e+001, 'Y', 5.071000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2682, 'Y', 6.177000e+001, 'Y', 6.177000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2682, 'Y', 5.908000e+001, 'Y', 5.908000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2683, 'Y', 5.412000e+001, 'Y', 5.412000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2683, 'Y', 6.682000e+001, 'Y', 6.682000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2684, 'Y', 4.031500e+002, 'Y', 4.031500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2685, 'Y', 1.435000e+002, 'Y', 1.435000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2685, 'Y', 7.401000e+001, 'Y', 7.401000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2686, 'Y', 2.258000e+001, 'Y', 2.258000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2686, 'Y', 4.971000e+001, 'Y', 4.971000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2687, 'Y', 2.707000e+001, 'Y', 2.707000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2687, 'Y', 5.758000e+001, 'Y', 5.758000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2688, 'Y', 1.405700e+002, 'Y', 1.405700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2688, 'Y', 6.299000e+001, 'Y', 6.299000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2689, 'Y', 2.708000e+001, 'Y', 2.708000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2696, 'Y', 3.043500e+002, 'Y', 3.043500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2697, 'Y', 4.143500e+002, 'Y', 4.143500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2698, 'Y', 6.164000e+001, 'Y', 6.164000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2699, 'Y', 3.660000e+002, 'Y', 3.660000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2700, 'Y', 4.151000e+002, 'Y', 4.151000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2701, 'Y', 6.400000e+001, 'Y', 6.400000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2702, 'Y', 2.351000e+001, 'Y', 2.351000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2702, 'Y', 6.065000e+001, 'Y', 6.065000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2703, 'Y', 6.571000e+001, 'Y', 6.571000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2703, 'Y', 7.144000e+001, 'Y', 7.144000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2704, 'Y', 1.239000e+002, 'Y', 1.239000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2704, 'Y', 3.292100e+002, 'Y', 3.292100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2707, 'Y', 6.049000e+001, 'Y', 6.049000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2708, 'Y', 3.306200e+002, 'Y', 3.306200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2708, 'Y', 6.021000e+001, 'Y', 6.021000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2709, 'Y', 2.296000e+001, 'Y', 2.296000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2709, 'Y', 6.723000e+001, 'Y', 6.723000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2710, 'Y', 2.389000e+001, 'Y', 2.389000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2710, 'Y', 5.351000e+001, 'Y', 5.351000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2711, 'Y', 5.419000e+001, 'Y', 5.419000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2711, 'Y', 1.252900e+002, 'Y', 1.252900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2712, 'Y', 6.987000e+001, 'Y', 6.987000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2713, 'Y', 1.113900e+002, 'Y', 1.113900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2713, 'Y', 5.505000e+001, 'Y', 5.505000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2714, 'Y', 2.285000e+001, 'Y', 2.285000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2714, 'Y', 5.135000e+001, 'Y', 5.135000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2715, 'Y', 2.189000e+001, 'Y', 2.189000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2715, 'Y', 4.862000e+001, 'Y', 4.862000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2716, 'Y', 1.199600e+002, 'Y', 1.199600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2716, 'Y', 5.391000e+001, 'Y', 5.391000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2717, 'Y', 6.054000e+001, 'Y', 6.054000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2717, 'Y', 6.585000e+001, 'Y', 6.585000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2718, 'Y', 6.126000e+001, 'Y', 6.126000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2719, 'Y', 5.420000e+001, 'Y', 5.420000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2720, 'Y', 1.134000e+002, 'Y', 1.134000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2720, 'Y', 2.995400e+002, 'Y', 2.995400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2721, 'Y', 3.228200e+002, 'Y', 3.228200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2722, 'Y', 1.302500e+002, 'Y', 1.302500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2722, 'Y', 5.600000e+001, 'Y', 5.600000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2723, 'Y', 6.654000e+001, 'Y', 6.654000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2726, 'Y', 6.511000e+001, 'Y', 6.511000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2727, 'Y', 6.659000e+001, 'Y', 6.659000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2728, 'Y', 2.647000e+001, 'Y', 2.647000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2728, 'Y', 5.913000e+001, 'Y', 5.913000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2729, 'Y', 2.717000e+001, 'Y', 2.717000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2729, 'Y', 7.034000e+001, 'Y', 7.034000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2730, 'Y', 1.414300e+002, 'Y', 1.414300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2730, 'Y', 7.159000e+001, 'Y', 7.159000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2731, 'Y', 2.781000e+001, 'Y', 2.781000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2732, 'Y', 3.813500e+002, 'Y', 3.813500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2735, 'Y', 2.647000e+001, 'Y', 2.647000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2735, 'Y', 5.848000e+001, 'Y', 5.848000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2736, 'Y', 1.286600e+002, 'Y', 1.286600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2736, 'Y', 6.689000e+001, 'Y', 6.689000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2737, 'Y', 1.243400e+002, 'Y', 1.243400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2737, 'Y', 5.727000e+001, 'Y', 5.727000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2738, 'Y', 3.312900e+002, 'Y', 3.312900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2739, 'Y', 6.220000e+001, 'Y', 6.220000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2739, 'Y', 3.284700e+002, 'Y', 3.284700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2740, 'Y', 6.328000e+001, 'Y', 6.328000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2741, 'Y', 3.457900e+002, 'Y', 3.457900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2742, 'Y', 2.367000e+001, 'Y', 2.367000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2742, 'Y', 5.181000e+001, 'Y', 5.181000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2743, 'Y', 1.224700e+002, 'Y', 1.224700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2743, 'Y', 5.520000e+001, 'Y', 5.520000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2744, 'Y', 2.673000e+001, 'Y', 2.673000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2744, 'Y', 6.606000e+001, 'Y', 6.606000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2745, 'Y', 7.668000e+001, 'Y', 7.668000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2746, 'Y', 1.121700e+002, 'Y', 1.121700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2746, 'Y', 2.967000e+002, 'Y', 2.967000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2747, 'Y', 6.829000e+001, 'Y', 6.829000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2748, 'Y', 1.422600e+002, 'Y', 1.422600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2748, 'Y', 6.397000e+001, 'Y', 6.397000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2749, 'Y', 1.239900e+002, 'Y', 1.239900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2749, 'Y', 6.985000e+001, 'Y', 6.985000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2750, 'Y', 2.771000e+001, 'Y', 2.771000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2751, 'Y', 4.225000e+002, 'Y', 4.225000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2752, 'Y', 1.142600e+002, 'Y', 1.142600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2752, 'Y', 5.273000e+001, 'Y', 5.273000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2753, 'Y', 3.919000e+002, 'Y', 3.919000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2754, 'Y', 1.419200e+002, 'Y', 1.419200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2754, 'Y', 6.076000e+001, 'Y', 6.076000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2755, 'Y', 3.320000e+002, 'Y', 3.320000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2756, 'Y', 3.338500e+002, 'Y', 3.338500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2767, 'Y', 3.069500e+002, 'Y', 3.069500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2768, 'Y', 3.478900e+002, 'Y', 3.478900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2768, 'Y', 1.290300e+002, 'Y', 1.290300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2769, 'Y', 1.425400e+002, 'Y', 1.425400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2769, 'Y', 6.585000e+001, 'Y', 6.585000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2770, 'Y', 1.403100e+002, 'Y', 1.403100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2770, 'Y', 6.193000e+001, 'Y', 6.193000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2771, 'Y', 5.873000e+001, 'Y', 5.873000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2771, 'Y', 6.042000e+001, 'Y', 6.042000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2782, 'Y', 5.430000e+001, 'Y', 5.430000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2782, 'Y', 5.776000e+001, 'Y', 5.776000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2783, 'Y', 2.441000e+001, 'Y', 2.441000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2783, 'Y', 5.480000e+001, 'Y', 5.480000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2784, 'Y', 2.337000e+001, 'Y', 2.337000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2784, 'Y', 5.164000e+001, 'Y', 5.164000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2785, 'Y', 1.381400e+002, 'Y', 1.381400e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2785, 'Y', 6.158000e+001, 'Y', 6.158000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2790, 'Y', 3.484500e+002, 'Y', 3.484500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2791, 'Y', 2.314000e+001, 'Y', 2.314000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2791, 'Y', 5.180000e+001, 'Y', 5.180000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2792, 'Y', 1.181300e+002, 'Y', 1.181300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2792, 'Y', 3.200100e+002, 'Y', 3.200100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2793, 'Y', 2.794000e+001, 'Y', 2.794000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2794, 'Y', 6.511000e+001, 'Y', 6.511000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2795, 'Y', 1.284400e+002, 'Y', 1.284400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2795, 'Y', 3.376500e+002, 'Y', 3.376500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2796, 'Y', 1.422500e+002, 'Y', 1.422500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2796, 'Y', 7.119000e+001, 'Y', 7.119000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2797, 'Y', 2.242000e+001, 'Y', 2.242000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2797, 'Y', 4.974000e+001, 'Y', 4.974000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2798, 'Y', 1.197500e+002, 'Y', 1.197500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2798, 'Y', 5.403000e+001, 'Y', 5.403000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2799, 'Y', 3.761000e+002, 'Y', 3.761000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2800, 'Y', 3.111700e+002, 'Y', 3.111700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2801, 'Y', 1.537100e+002, 'Y', 1.537100e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2801, 'Y', 7.394000e+001, 'Y', 7.394000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2802, 'Y', 2.695000e+001, 'Y', 2.695000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2802, 'Y', 6.582000e+001, 'Y', 6.582000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2803, 'Y', 5.732000e+001, 'Y', 5.732000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2804, 'Y', 2.653000e+001, 'Y', 2.653000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2804, 'Y', 5.995000e+001, 'Y', 5.995000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2805, 'Y', 1.124500e+002, 'Y', 1.124500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2805, 'Y', 3.084600e+002, 'Y', 3.084600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2806, 'Y', 1.254800e+002, 'Y', 1.254800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2806, 'Y', 6.438000e+001, 'Y', 6.438000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2807, 'Y', 3.263000e+002, 'Y', 3.263000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2808, 'Y', 1.326100e+002, 'Y', 1.326100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2808, 'Y', 6.014000e+001, 'Y', 6.014000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2809, 'Y', 6.528000e+001, 'Y', 6.528000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2810, 'Y', 2.618000e+001, 'Y', 2.618000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2810, 'Y', 5.659000e+001, 'Y', 5.659000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2811, 'Y', 6.658000e+001, 'Y', 6.658000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2812, 'Y', 6.419000e+001, 'Y', 6.419000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2813, 'Y', 1.313000e+002, 'Y', 1.313000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2813, 'Y', 6.201000e+001, 'Y', 6.201000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2814, 'Y', 1.193600e+002, 'Y', 1.193600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2814, 'Y', 3.172700e+002, 'Y', 3.172700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2815, 'Y', 1.284000e+002, 'Y', 1.284000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2815, 'Y', 2.944100e+002, 'Y', 2.944100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2816, 'Y', 2.207000e+001, 'Y', 2.207000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2816, 'Y', 4.894000e+001, 'Y', 4.894000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2817, 'Y', 3.011000e+002, 'Y', 3.011000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2818, 'Y', 3.600500e+002, 'Y', 3.600500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2819, 'Y', 2.413000e+001, 'Y', 2.413000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2819, 'Y', 5.400000e+001, 'Y', 5.400000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2820, 'Y', 1.113500e+002, 'Y', 1.113500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2820, 'Y', 5.508000e+001, 'Y', 5.508000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2821, 'Y', 1.393600e+002, 'Y', 1.393600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2821, 'Y', 3.291400e+002, 'Y', 3.291400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2822, 'Y', 1.299200e+002, 'Y', 1.299200e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2822, 'Y', 3.496500e+002, 'Y', 3.496500e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2823, 'Y', 2.588000e+001, 'Y', 2.588000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2823, 'Y', 6.486000e+001, 'Y', 6.486000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2824, 'Y', 1.102800e+002, 'Y', 1.102800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2824, 'Y', 2.935700e+002, 'Y', 2.935700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2825, 'Y', 1.034700e+002, 'Y', 1.034700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2825, 'Y', 5.273000e+001, 'Y', 5.273000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2826, 'Y', 5.599000e+001, 'Y', 5.599000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2826, 'Y', 5.347000e+001, 'Y', 5.347000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2827, 'Y', 2.501000e+001, 'Y', 2.501000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2827, 'Y', 6.458000e+001, 'Y', 6.458000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2828, 'Y', 5.982000e+001, 'Y', 5.982000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2829, 'Y', 1.416900e+002, 'Y', 1.416900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2829, 'Y', 6.532000e+001, 'Y', 6.532000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2832, 'Y', 2.620000e+001, 'Y', 2.620000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2832, 'Y', 6.742000e+001, 'Y', 6.742000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2833, 'Y', 5.809000e+001, 'Y', 5.809000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2834, 'Y', 2.295000e+001, 'Y', 2.295000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2834, 'Y', 5.358000e+001, 'Y', 5.358000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2835, 'Y', 1.459200e+002, 'Y', 1.459200e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2835, 'Y', 3.383500e+002, 'Y', 3.383500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2836, 'Y', 2.698000e+001, 'Y', 2.698000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2836, 'Y', 7.124000e+001, 'Y', 7.124000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2837, 'Y', 3.374500e+002, 'Y', 3.374500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2838, 'Y', 5.031000e+001, 'Y', 5.031000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2838, 'Y', 5.643000e+001, 'Y', 5.643000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2839, 'Y', 3.222000e+002, 'Y', 3.222000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2840, 'Y', 1.243400e+002, 'Y', 1.243400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2840, 'Y', 3.331300e+002, 'Y', 3.331300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2841, 'Y', 1.150000e+002, 'Y', 1.150000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2841, 'Y', 3.186100e+002, 'Y', 3.186100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2842, 'Y', 5.916000e+001, 'Y', 5.916000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2842, 'Y', 6.166000e+001, 'Y', 6.166000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2843, 'Y', 1.485600e+002, 'Y', 1.485600e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2843, 'Y', 6.833000e+001, 'Y', 6.833000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2844, 'Y', 1.079400e+002, 'Y', 1.079400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2844, 'Y', 3.014100e+002, 'Y', 3.014100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2845, 'Y', 1.421900e+002, 'Y', 1.421900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2845, 'Y', 7.409000e+001, 'Y', 7.409000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2846, 'Y', 7.392000e+001, 'Y', 7.392000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2847, 'Y', 7.028000e+001, 'Y', 7.028000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2848, 'Y', 2.153000e+001, 'Y', 2.153000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2848, 'Y', 6.165000e+001, 'Y', 6.165000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2849, 'Y', 1.272100e+002, 'Y', 1.272100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2849, 'Y', 5.841000e+001, 'Y', 5.841000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2850, 'Y', 2.672000e+001, 'Y', 2.672000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2850, 'Y', 7.682000e+001, 'Y', 7.682000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2851, 'Y', 6.998000e+001, 'Y', 6.998000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2852, 'Y', 1.139100e+002, 'Y', 1.139100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2852, 'Y', 3.152000e+002, 'Y', 3.152000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2853, 'Y', 4.458500e+002, 'Y', 4.458500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2854, 'Y', 2.502000e+001, 'Y', 2.502000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2855, 'Y', 2.828000e+001, 'Y', 2.828000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2856, 'Y', 3.101900e+002, 'Y', 3.101900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2856, 'Y', 6.046000e+001, 'Y', 6.046000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2857, 'Y', 2.585000e+001, 'Y', 2.585000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2857, 'Y', 5.772000e+001, 'Y', 5.772000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2858, 'Y', 3.598000e+002, 'Y', 3.598000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2859, 'Y', 1.203800e+002, 'Y', 1.203800e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2859, 'Y', 3.332000e+002, 'Y', 3.332000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2860, 'Y', 5.875000e+001, 'Y', 5.875000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2860, 'Y', 7.118000e+001, 'Y', 7.118000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2861, 'Y', 5.514000e+001, 'Y', 5.514000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2862, 'Y', 8.081000e+001, 'Y', 8.081000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2863, 'Y', 6.084000e+001, 'Y', 6.084000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2863, 'Y', 3.296300e+002, 'Y', 3.296300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2864, 'Y', 3.730600e+002, 'Y', 3.730600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2865, 'Y', 2.643000e+001, 'Y', 2.643000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2865, 'Y', 3.627300e+002, 'Y', 3.627300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2866, 'Y', 2.221000e+001, 'Y', 2.221000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2866, 'Y', 6.193000e+001, 'Y', 6.193000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2867, 'Y', 2.309000e+001, 'Y', 2.309000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2868, 'Y', 6.890000e+001, 'Y', 6.890000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2868, 'Y', 3.703000e+002, 'Y', 3.703000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2869, 'Y', 5.934000e+001, 'Y', 5.934000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2869, 'Y', 7.242000e+001, 'Y', 7.242000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2870, 'Y', 1.201300e+002, 'Y', 1.201300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2870, 'Y', 5.376000e+001, 'Y', 5.376000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2871, 'Y', 3.649700e+002, 'Y', 3.649700e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2872, 'Y', 3.312000e+002, 'Y', 3.312000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2873, 'Y', 6.963000e+001, 'Y', 6.963000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2876, 'Y', 2.605000e+001, 'Y', 2.605000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2876, 'Y', 7.596000e+001, 'Y', 7.596000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2877, 'Y', 1.160200e+002, 'Y', 1.160200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2877, 'Y', 5.312000e+001, 'Y', 5.312000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2878, 'Y', 5.929000e+001, 'Y', 5.929000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2878, 'Y', 2.694000e+001, 'Y', 2.694000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2879, 'Y', 1.060300e+002, 'Y', 1.060300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2879, 'Y', 2.883400e+002, 'Y', 2.883400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2880, 'Y', 1.229700e+002, 'Y', 1.229700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2880, 'Y', 3.260700e+002, 'Y', 3.260700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2881, 'Y', 2.795000e+001, 'Y', 2.795000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2882, 'Y', 7.071000e+001, 'Y', 7.071000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2883, 'Y', 7.914000e+001, 'Y', 7.914000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2884, 'Y', 3.311500e+002, 'Y', 3.311500e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2884, 'Y', 7.163000e+001, 'Y', 7.163000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2885, 'Y', 2.359000e+001, 'Y', 2.359000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2885, 'Y', 5.304000e+001, 'Y', 5.304000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2886, 'Y', 2.332000e+001, 'Y', 2.332000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2886, 'Y', 5.093000e+001, 'Y', 5.093000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2887, 'Y', 6.084000e+001, 'Y', 6.084000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2888, 'Y', 3.199500e+002, 'Y', 3.199500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2889, 'Y', 6.111000e+001, 'Y', 6.111000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2889, 'Y', 6.969000e+001, 'Y', 6.969000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2890, 'Y', 1.189800e+002, 'Y', 1.189800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2890, 'Y', 3.177500e+002, 'Y', 3.177500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2891, 'Y', 1.259100e+002, 'Y', 1.259100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2891, 'Y', 6.647000e+001, 'Y', 6.647000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2892, 'Y', 2.480000e+001, 'Y', 2.480000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2892, 'Y', 5.541000e+001, 'Y', 5.541000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2893, 'Y', 1.269100e+002, 'Y', 1.269100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2893, 'Y', 5.916000e+001, 'Y', 5.916000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2894, 'Y', 1.419400e+002, 'Y', 1.419400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2894, 'Y', 6.611000e+001, 'Y', 6.611000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2899, 'Y', 2.597000e+001, 'Y', 2.597000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2899, 'Y', 5.752000e+001, 'Y', 5.752000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2907, 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 2908, 'Y', 2.447000e+001, 'Y', 2.447000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2908, 'Y', 5.418000e+001, 'Y', 5.418000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2909, 'Y', 5.544000e+001, 'Y', 5.544000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2909, 'Y', 6.406000e+001, 'Y', 6.406000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2910, 'Y', 6.997000e+001, 'Y', 6.997000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2911, 'Y', 2.671000e+001, 'Y', 2.671000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2911, 'Y', 6.009000e+001, 'Y', 6.009000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2912, 'Y', 7.213000e+001, 'Y', 7.213000e+001, -1, ' ', "", 0, 0, NULL, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, NULL, "");
INSERT INTO `Entry` VALUES(11, 2913, 'Y', 5.916000e+001, 'Y', 5.916000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2913, 'Y', 1.364600e+002, 'Y', 1.364600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2914, 'Y', 6.530000e+001, 'Y', 6.530000e+001, -1, ' ', "", 0, 0, NULL, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, NULL, "");
INSERT INTO `Entry` VALUES(7, 2916, 'Y', 2.734000e+001, 'Y', 2.734000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2916, 'Y', 6.864000e+001, 'Y', 6.864000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2919, 'Y', 2.635000e+001, 'Y', 2.635000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2919, 'Y', 6.241000e+001, 'Y', 6.241000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2920, 'Y', 3.693700e+002, 'Y', 3.693700e+002, -1, ' ', "", 0, 0, NULL, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, NULL, "");
INSERT INTO `Entry` VALUES(8, 2921, 'Y', 2.323000e+001, 'Y', 2.323000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2921, 'Y', 5.617000e+001, 'Y', 5.617000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2922, 'Y', 1.398100e+002, 'Y', 1.398100e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2922, 'Y', 6.293000e+001, 'Y', 6.293000e+001, -1, ' ', "", 0, 0, NULL, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, NULL, "");
INSERT INTO `Entry` VALUES(6, 2923, 'Y', 1.261100e+002, 'Y', 1.261100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2923, 'Y', 6.322000e+001, 'Y', 6.322000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2924, 'Y', 1.345100e+002, 'Y', 1.345100e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2924, 'Y', 2.790000e+001, 'Y', 2.790000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2925, 'Y', 1.358200e+002, 'Y', 1.358200e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2925, 'Y', 6.680000e+001, 'Y', 6.680000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2930, 'Y', 5.813000e+001, 'Y', 5.813000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2930, 'Y', 7.448000e+001, 'Y', 7.448000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2933, 'Y', 3.013500e+002, 'Y', 3.013500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2937, 'Y', 3.317000e+002, 'Y', 3.317000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2939, 'Y', 1.099900e+002, 'Y', 1.099900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2939, 'Y', 4.988000e+001, 'Y', 4.988000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2940, 'Y', 1.128100e+002, 'Y', 1.128100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2940, 'Y', 5.068000e+001, 'Y', 5.068000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2947, 'Y', 6.650000e+001, 'Y', 6.650000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2947, 'Y', 3.584300e+002, 'Y', 3.584300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2948, 'Y', 2.728000e+002, 'Y', 2.728000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2949, 'Y', 7.880000e+001, 'Y', 7.880000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2950, 'Y', 1.382600e+002, 'Y', 1.382600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 4, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2950, 'Y', 6.343000e+001, 'Y', 6.343000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(9, 2952, 'Y', 3.379500e+002, 'Y', 3.379500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2954, 'Y', 5.599000e+001, 'Y', 5.599000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2957, 'Y', 1.163300e+002, 'Y', 1.163300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2957, 'Y', 6.022000e+001, 'Y', 6.022000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2958, 'Y', 1.287100e+002, 'Y', 1.287100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2958, 'Y', 3.453800e+002, 'Y', 3.453800e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2959, 'Y', 8.030000e+001, 'Y', 8.030000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2961, 'Y', 5.531000e+001, 'Y', 5.531000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2961, 'Y', 2.551000e+001, 'Y', 2.551000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2962, 'Y', 1.266500e+002, 'Y', 1.266500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2962, 'Y', 5.745000e+001, 'Y', 5.745000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2965, 'Y', 1.481700e+002, 'Y', 1.481700e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2965, 'Y', 6.507000e+001, 'Y', 6.507000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2968, 'Y', 1.403500e+002, 'Y', 1.403500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2968, 'Y', 6.498000e+001, 'Y', 6.498000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 2971, 'Y', 1.320000e+002, 'Y', 1.320000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2971, 'Y', 6.017000e+001, 'Y', 6.017000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2985, 'Y', 1.421500e+002, 'Y', 1.421500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2985, 'Y', 6.537000e+001, 'Y', 6.537000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 2986, 'Y', 3.506500e+002, 'Y', 3.506500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2987, 'Y', 6.529000e+001, 'Y', 6.529000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2987, 'Y', 7.475000e+001, 'Y', 7.475000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2988, 'Y', 6.963000e+001, 'Y', 6.963000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 2988, 'Y', 3.540600e+002, 'Y', 3.540600e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2989, 'Y', 1.295400e+002, 'Y', 1.295400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 2989, 'Y', 6.755000e+001, 'Y', 6.755000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 2990, 'Y', 5.066000e+001, 'Y', 5.066000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 2990, 'Y', 5.975000e+001, 'Y', 5.975000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2991, 'Y', 2.665000e+001, 'Y', 2.665000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 2991, 'Y', 5.868000e+001, 'Y', 5.868000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2994, 'Y', 1.310500e+002, 'Y', 1.310500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 2994, 'Y', 5.968000e+001, 'Y', 5.968000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 2995, 'Y', 2.513000e+001, 'Y', 2.513000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 2995, 'Y', 6.165000e+001, 'Y', 6.165000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 2996, 'Y', 1.392700e+002, 'Y', 1.392700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 2996, 'Y', 7.186000e+001, 'Y', 7.186000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2997, 'Y', 1.265700e+002, 'Y', 1.265700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2997, 'Y', 5.614000e+001, 'Y', 5.614000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 2998, 'Y', 1.153200e+002, 'Y', 1.153200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 2998, 'Y', 3.154400e+002, 'Y', 3.154400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(6, 2999, 'Y', 1.300800e+002, 'Y', 1.300800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(12, 2999, 'Y', 5.500000e+001, 'Y', 5.500000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 3000, 'Y', 7.545000e+001, 'Y', 7.545000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(5, 3001, 'Y', 1.488900e+002, 'Y', 1.488900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(11, 3001, 'Y', 6.606000e+001, 'Y', 6.606000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 3002, 'Y', 2.381000e+001, 'Y', 2.381000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 3002, 'Y', 5.167000e+001, 'Y', 5.167000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 3003, 'Y', 2.457000e+001, 'Y', 2.457000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 3004, 'Y', 1.211000e+002, 'Y', 1.211000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 3005, 'Y', 1.214300e+002, 'Y', 1.214300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 3005, 'Y', 3.329600e+002, 'Y', 3.329600e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 3006, 'Y', 1.293200e+002, 'Y', 1.293200e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 3006, 'Y', 3.378800e+002, 'Y', 3.378800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 3007, 'Y', 5.979000e+001, 'Y', 5.979000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(21, 3007, 'Y', 7.547000e+001, 'Y', 7.547000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 3008, 'Y', 3.629900e+002, 'Y', 3.629900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(10, 3009, 'Y', 3.833500e+002, 'Y', 3.833500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 3010, 'Y', 2.401000e+001, 'Y', 2.401000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(13, 3010, 'Y', 5.266000e+001, 'Y', 5.266000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 3011, 'Y', 2.328000e+001, 'Y', 2.328000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 3011, 'Y', 5.778000e+001, 'Y', 5.778000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(7, 3012, 'Y', 2.622000e+001, 'Y', 2.622000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(19, 3012, 'Y', 6.491000e+001, 'Y', 6.491000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(8, 3013, 'Y', 2.455000e+001, 'Y', 2.455000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 3014, 'Y', 7.153000e+001, 'Y', 7.153000e+001, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(3, 3015, 'Y', 1.302600e+002, 'Y', 1.302600e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(15, 3015, 'Y', 3.517200e+002, 'Y', 3.517200e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(22, 3016, 'Y', 6.892000e+001, 'Y', 6.892000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(16, 3017, 'Y', 3.036700e+002, 'Y', 3.036700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(20, 3017, 'Y', 5.431000e+001, 'Y', 5.431000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(4, 3018, 'Y', 1.092300e+002, 'Y', 1.092300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
INSERT INTO `Entry` VALUES(14, 3018, 'Y', 4.996000e+001, 'Y', 4.996000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 0, 0, "");
UNLOCK TABLES;

#
# Table structure for table 'Event'
#

DROP TABLE IF EXISTS `Event`;
CREATE TABLE `Event` (
  `Event_no` SMALLINT,
  `Event_ltr` VARCHAR(1),
  `Event_ptr` INT NOT NULL AUTO_INCREMENT,
  `Ind_rel` VARCHAR(1),
  `Event_sex` VARCHAR(1),
  `Event_gender` VARCHAR(1),
  `Event_dist` FLOAT,
  `Event_stroke` VARCHAR(1),
  `Low_age` SMALLINT,
  `High_Age` SMALLINT,
  `Multi_age` BIT,
  `Event_stat` VARCHAR(1),
  `Event_rounds` SMALLINT,
  `Num_prelanes` SMALLINT,
  `Num_finlanes` SMALLINT,
  `Heats_infinal` VARCHAR(1),
  `Heats_insemi` SMALLINT,
  `Std_lanes` VARCHAR(1),
  `Auto_seed` BIT,
  `Twoperlane_req` BIT,
  `Preheat_order` VARCHAR(1),
  `Finheat_order` VARCHAR(1),
  `Score_event` BIT,
  `Div_no` SMALLINT,
  `Relay_size` SMALLINT,
  `Comm_1` VARCHAR(36),
  `Comm_2` VARCHAR(36),
  `Comm_3` VARCHAR(36),
  `Comm_4` VARCHAR(36),
  `Entry_fee` DECIMAL(19,4),
  `Is_locked` BIT,
  `Locked_by` VARCHAR(20),
  `Event_Type` VARCHAR(1),
  `Locked_list` VARCHAR(12),
  `Event_note` VARCHAR(20),
  `Suppress_stroke` BIT,
  `Custom_ABCFinal` BIT,
  `Num_dives` SMALLINT,
  PRIMARY KEY (`Event_ptr`)
);

#
# Dumping data for table 'Event'
#

LOCK TABLES `Event` WRITE;
INSERT INTO `Event` VALUES(1, ' ', 1, 'R', 'G', 'F', 2.000000e+002, 'E', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(2, ' ', 2, 'R', 'B', 'M', 2.000000e+002, 'E', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(3, ' ', 3, 'I', 'G', 'F', 2.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(4, ' ', 4, 'I', 'B', 'M', 2.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(5, ' ', 5, 'I', 'G', 'F', 2.000000e+002, 'E', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(6, ' ', 6, 'I', 'B', 'M', 2.000000e+002, 'E', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(7, ' ', 7, 'I', 'G', 'F', 5.000000e+001, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(8, ' ', 8, 'I', 'B', 'M', 5.000000e+001, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(9, ' ', 9, 'I', 'G', 'F', 0.000000e+000, 'F', 0, 109, 0, '1', 1, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", ' ', 0, 0, 11);
INSERT INTO `Event` VALUES(10, ' ', 10, 'I', 'B', 'M', 0.000000e+000, 'F', 0, 109, 0, '1', 1, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", ' ', 0, 0, 11);
INSERT INTO `Event` VALUES(11, ' ', 11, 'I', 'G', 'F', 1.000000e+002, 'D', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(12, ' ', 12, 'I', 'B', 'M', 1.000000e+002, 'D', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(13, ' ', 13, 'I', 'G', 'F', 1.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(14, ' ', 14, 'I', 'B', 'M', 1.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(15, ' ', 15, 'I', 'G', 'F', 5.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(16, ' ', 16, 'I', 'B', 'M', 5.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(17, ' ', 17, 'R', 'G', 'F', 2.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(18, ' ', 18, 'R', 'B', 'M', 2.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(19, ' ', 19, 'I', 'G', 'F', 1.000000e+002, 'B', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(20, ' ', 20, 'I', 'B', 'M', 1.000000e+002, 'B', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(21, ' ', 21, 'I', 'G', 'F', 1.000000e+002, 'C', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(22, ' ', 22, 'I', 'B', 'M', 1.000000e+002, 'C', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(23, ' ', 23, 'R', 'G', 'F', 4.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
INSERT INTO `Event` VALUES(24, ' ', 24, 'R', 'B', 'M', 4.000000e+002, 'A', 0, 109, 0, '1', 2, 8, 6, 'C', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', "", "", 0, 0, NULL);
UNLOCK TABLES;

#
# Table structure for table 'Meet'
#

DROP TABLE IF EXISTS `Meet`;
CREATE TABLE `Meet` (
  `Meet_name1` VARCHAR(45),
  `Meet_header1` VARCHAR(45),
  `Meet_header2` VARCHAR(45),
  `Meet_location` VARCHAR(45),
  `Meet_start` CHAR(19),
  `Meet_end` CHAR(19),
  `Meet_idformat` SMALLINT,
  `Meet_class` SMALLINT,
  `Meet_meettype` SMALLINT,
  `Meet_course` SMALLINT,
  `Enter_ages` BIT,
  `Enter_birthdate` BIT,
  `Calc_date` CHAR(19),
  `Enter_schoolyr` BIT,
  `A_Relaysonly` BIT,
  `Use_hometown` BIT,
  `Show_countrycode` BIT,
  `Scores_afterevt` BIT,
  `Lastname_first` BIT,
  `Punct_names` BIT,
  `Punct_teams` BIT,
  `win_mm` BIT,
  `meet_numlanes` SMALLINT,
  `prelimheats_circle` SMALLINT,
  `timedfinal_circleseed` BIT,
  `foreign_infinal` BIT,
  `exh_infinal` BIT,
  `nonconform_last` BIT,
  `course_order` VARCHAR(255),
  `seed_exhlast` BIT,
  `dual_evenodd` BIT,
  `strict_evenodd` BIT,
  `team_evenlanes` INT,
  `team_oddlanes` INT,
  `masters_bytimeonly` BIT,
  `masters_agegrpsskip` SMALLINT,
  `timer_port` SMALLINT,
  `scbd_port` SMALLINT,
  `timer_vendor` VARCHAR(4),
  `scbd_vendor` VARCHAR(4),
  `show_initial` BIT,
  `ucase_names` BIT,
  `ucase_teams` BIT,
  `open_senior_none` VARCHAR(1),
  `entryqual_faster` BIT,
  `indentryfee_surcharge` DECIMAL(19,4),
  `anyone_onrelay` BIT,
  `language_choice` VARCHAR(20),
  `military_time` BIT,
  `check_times` BIT,
  `enterkey_astab` BIT,
  `double_endedsplits` BIT,
  `use_compnumbers` BIT,
  `flighted_minentries` SMALLINT,
  `diffpts_malefemale` BIT,
  `diffpts_eachdivision` BIT,
  `scoreonly_ifexceedqualtime` BIT,
  `score_fastestheatonly` BIT,
  `entrylimits_warn` BIT,
  `pointsbasedon_seedtime` BIT,
  `pointsfor_overachievers` BIT,
  `pointsfor_underachievers` BIT,
  `indmaxscorers_perteam` SMALLINT,
  `relmaxscorers_perteam` SMALLINT,
  `indtopmany_awards` SMALLINT,
  `reltopmany_awards` SMALLINT,
  `entrymax_total` SMALLINT,
  `indmax_perath` SMALLINT,
  `relmax_perath` SMALLINT,
  `foreign_getteampoints` BIT,
  `include_swimupsinteamscore` BIT,
  `enter_citizenof` BIT,
  `meet_meetstyle` SMALLINT,
  `flag_overachievers` BIT,
  `flag_underachievers` BIT,
  `scbd_punctuation` SMALLINT,
  `scbd_names` SMALLINT,
  `scbd_relaynames` SMALLINT,
  `scbd_cycle` BIT,
  `scbd_cycleseconds` SMALLINT,
  `copies_toprinter` SMALLINT,
  `report_headersonly` BIT,
  `autoinc_compno` BIT,
  `pentscoring_usedqtime` BIT,
  `swimmer_surcharge` DECIMAL(19,4),
  `directly_toprinter` BIT,
  `lastname_asinitial` BIT,
  `under_eventname` BIT,
  `suppress_Arelay` BIT,
  `Punct_recholders` BIT,
  `UCASE_recholders` BIT,
  `suppress_lsc` BIT,
  `showathlete_status` BIT,
  `open_lowage` SMALLINT,
  `useeventsex_teamscore` BIT,
  `suppress_smallx` BIT,
  `score_Arelayonly` BIT,
  `thirteenandover_assenior` BIT,
  `suppress_jd` BIT,
  `abcfinal_order` VARCHAR(3),
  `maxagefor_cfinal` SMALLINT,
  `Sanction_number` VARCHAR(20),
  `include_sanction` BIT,
  `special_points` SMALLINT,
  `countrelay_alt` BIT,
  `UseNonConforming_PoolFactor` BIT,
  `NonConforming_PoolFactor` FLOAT,
  `apnews_team` VARCHAR(1),
  `PointsAwarded_ForDQ` FLOAT,
  `PointsAwarded_ForScratch` FLOAT,
  `PointsAwarded_ForNT` FLOAT,
  `Enter_AthStat` BIT,
  `Show_secondclub` BIT,
  `firstinitial_fulllastname` BIT,
  `turnon_autobackup` BIT,
  `autobackup_interval` SMALLINT,
  `PointsAwarded_ForExh` BIT,
  `Use_AltTeamAbbr` BIT,
  `IsCanadian_Masters` BIT,
  `entry_msg` VARCHAR(80),
  `timedfinalnonconform_last` BIT,
  `referee_name` VARCHAR(30),
  `referee_homphone` VARCHAR(20),
  `referee_offphone` VARCHAR(20),
  `Meet_altitude` INT
);

#
# Dumping data for table 'Meet'
#

LOCK TABLES `Meet` WRITE;
INSERT INTO `Meet` VALUES('Nebraska State High School Championship', ' ', ' ', 'Devaney Center, Lincoln, NE', '2004-02-26', '2004-02-28', 6, 3, 1, 3, 0, 0, '2003-02-27', -1, 0, 0, 0, 0, 0, -1, 0, 0, 10, 3, 0, -1, -1, 0, 'LSY', 0, 0, 0, 0, 0, 0, 2, 1, 0, 'WTCH', 'NONE', 0, 0, 0, 'N', -1, 0, 0, 'ENGLISH', 0, -1, -1, 0, 0, 16, 0, 0, 0, 0, -1, 0, 0, 0, 0, 0, 6, 6, 4, 2, 3, -1, 0, 0, 1, 0, 0, 0, 2, 1, 0, 0, 3, -1, -1, 0, 0, -1, 0, 0, -1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'CBA', 0, ' ', 0, 0, 0, 0, 1.000000e+000, 'S', 0.000000e+000, -1.000000e+000, 1.000000e+000, 0, 0, 0, 0, 30, 0, 0, 0, "", 0, "", "", "", NULL);
UNLOCK TABLES;
ALTER TABLE `Meet` CHANGE `Meet_start` `Meet_start` DATE;
ALTER TABLE `Meet` CHANGE `Meet_end` `Meet_end` DATE;
ALTER TABLE `Meet` CHANGE `Calc_date` `Calc_date` DATE;

#
# Table structure for table 'Multiage'
#

DROP TABLE IF EXISTS `Multiage`;
CREATE TABLE `Multiage` (
  `event_ptr` INT,
  `low_age` SMALLINT,
  `high_age` SMALLINT,
  `Heats_infinal` VARCHAR(1)
);

#
# Dumping data for table 'Multiage'
#

LOCK TABLES `Multiage` WRITE;
UNLOCK TABLES;

#
# Table structure for table 'Records'
#

DROP TABLE IF EXISTS `Records`;
CREATE TABLE `Records` (
  `tag_ptr` INT,
  `tag_gender` VARCHAR(1),
  `tag_indrel` VARCHAR(1),
  `tag_dist` INT,
  `tag_stroke` VARCHAR(1),
  `low_age` SMALLINT,
  `high_Age` SMALLINT,
  `Record_month` SMALLINT,
  `Record_day` SMALLINT,
  `Record_year` SMALLINT,
  `Record_Holder` VARCHAR(30),
  `Record_Holderteam` VARCHAR(16),
  `Relay_Names` VARCHAR(50),
  `Record_Time` FLOAT,
  `Record_course` VARCHAR(1),
  INDEX `recordtag` (`tag_ptr`)
);

#
# Dumping data for table 'Records'
#

LOCK TABLES `Records` WRITE;
INSERT INTO `Records` VALUES(5, 'F', 'R', 200, 'E', 0, 109, 0, 0, 0, 'Omaha North', ' ', '                                                  ', 1.090600e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'R', 200, 'E', 0, 109, 0, 0, 0, 'Ralston', ' ', '                                                  ', 9.626000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 200, 'A', 0, 109, 0, 0, 0, 'Shandra Johnson, Omaha North', ' ', ' ', 1.107700e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 200, 'A', 0, 109, 0, 0, 0, 'Chuck Sharpe-Westside', ' ', ' ', 9.924000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 200, 'E', 0, 109, 0, 0, 0, 'Shandra Johnson-Omaha North', ' ', ' ', 1.236400e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 200, 'E', 0, 109, 0, 0, 0, 'P.J. Wiseman-Ralston', ' ', ' ', 1.113200e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 50, 'A', 0, 109, 0, 0, 0, 'Amy Tidball-Lincoln High', ' ', ' ', 2.357000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 50, 'A', 0, 109, 0, 0, 0, 'David Morrow-Norfolk', ' ', ' ', 2.055000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 1, 'A', 0, 109, 0, 0, 0, 'Jodi Janssen-Papillion LaVista', ' ', ' ', 4.758500e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 1, 'A', 0, 109, 0, 0, 0, 'Dave Keane-Westside', ' ', ' ', 5.747400e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 100, 'D', 0, 109, 0, 0, 0, 'Barb Harris-Lincoln High', ' ', ' ', 5.727000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 100, 'D', 0, 109, 0, 0, 0, 'David Lammel-Millard South', ' ', ' ', 4.934000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 100, 'A', 0, 109, 0, 0, 0, 'Amy Tidball-Lincoln High', ' ', ' ', 5.166000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 100, 'A', 0, 109, 0, 0, 0, 'Coley Stickels-Creighton Prep', ' ', ' ', 4.554000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 500, 'A', 0, 109, 0, 0, 0, 'Shandra Johnson-Omaha North', ' ', ' ', 2.941800e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 500, 'A', 0, 109, 0, 0, 0, 'Doug Humphrey-Westside', ' ', ' ', 2.754300e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'R', 200, 'A', 0, 109, 0, 0, 0, 'Lincoln East', ' ', '                                                  ', 9.900000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'R', 200, 'A', 0, 109, 0, 0, 0, 'Lincoln Southeast', ' ', '                                                  ', 8.616000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 100, 'B', 0, 109, 0, 0, 0, 'Ali Petersen-Omaha North', ' ', ' ', 5.688000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 100, 'B', 0, 109, 0, 0, 0, 'David Foster-Lincoln High', ' ', ' ', 5.095000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'I', 100, 'C', 0, 109, 0, 0, 0, 'Heather Schwab-Lincoln East', ' ', ' ', 6.437000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'I', 100, 'C', 0, 109, 0, 0, 0, 'David Anderson-Millard South', ' ', ' ', 5.610000e+001, 'Y');
INSERT INTO `Records` VALUES(5, 'F', 'R', 400, 'A', 0, 109, 0, 0, 0, 'Omaha Westside', ' ', '                                                  ', 2.149000e+002, 'Y');
INSERT INTO `Records` VALUES(5, 'M', 'R', 400, 'A', 0, 109, 0, 0, 0, 'Ralston', ' ', '                                                  ', 1.885500e+002, 'Y');
UNLOCK TABLES;

#
# Table structure for table 'RecordsbyEvent'
#

DROP TABLE IF EXISTS `RecordsbyEvent`;
CREATE TABLE `RecordsbyEvent` (
  `tag_ptr` INT,
  `event_ptr` INT,
  `low_age` SMALLINT,
  `high_Age` SMALLINT,
  `Record_month` SMALLINT,
  `Record_day` SMALLINT,
  `Record_year` SMALLINT,
  `Record_Holder` VARCHAR(30),
  `Record_Holderteam` VARCHAR(16),
  `Relay_Names` VARCHAR(50),
  `Record_Time` FLOAT,
  `Record_course` VARCHAR(1),
  `tag_gender` VARCHAR(1),
  `hide_me` BIT,
  INDEX `recordevt` (`event_ptr`)
);

#
# Dumping data for table 'RecordsbyEvent'
#

LOCK TABLES `RecordsbyEvent` WRITE;
INSERT INTO `RecordsbyEvent` VALUES(5, 1, 0, 109, NULL, NULL, 2003, 'Millard West', "", "", 1.063500e+002, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 2, 0, 109, 0, 0, 2002, 'Omaha Creighton Prep', ' ', ' ', 9.571000e+001, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 3, 0, 109, NULL, NULL, 2000, 'Katie Eckholt, Omaha Marian', "", "", 1.106500e+002, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 4, 0, 109, 0, 0, 1977, 'Chuck Sharpe, Omaha Westside', "", "", NULL, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 5, 0, 109, NULL, NULL, 1997, 'Shandra Johnson, Omaha North', "", "", NULL, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 6, 0, 109, NULL, NULL, 1991, 'PJ Wiseman, Ralston', "", "", NULL, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 7, 0, 109, 0, 0, 2001, 'Kate Eckholt, Omaha Marian', "", "", 2.320000e+001, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 8, 0, 109, 0, 0, 1997, 'David Morrow, Norfolk', "", "", 2.045000e+001, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 9, 0, 109, NULL, NULL, 1994, 'Jodi Janssen, Papillion-LaVis', "", "", 4.758500e+002, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 10, 0, 109, NULL, NULL, 1976, 'Dave Keane, Omaha Westside', "", "", 5.747400e+002, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 11, 0, 109, NULL, NULL, 2000, 'Miranda Shald, Millard North', "", "", 5.634000e+001, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 12, 0, 109, 0, 0, 1983, 'David Lammel, Millard South', "", "", NULL, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 13, 0, 109, 0, NULL, 2001, 'Katie Eckholt, Omaha Marian', "", "", 5.043000e+001, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 14, 0, 109, 0, 0, 1996, 'Coley Stickels, Omaha Cr Prep', "", "", 4.554000e+001, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 15, 0, 109, NULL, NULL, 1997, 'Shandra Johnson, Omaha North', "", "", NULL, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 16, 0, 109, NULL, NULL, 1991, 'Doug Humphrey, Omaha Westside', "", "", NULL, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 17, 0, 109, 0, 0, 2003, 'Omaha Marian', "", "", 9.503000e+001, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 18, 0, 109, 0, 0, 2000, 'Omaha Creighton Prep', "", "", 8.612000e+001, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 19, 0, 109, NULL, NULL, 1998, 'Ali Petersen, Omaha North', "", "", NULL, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 20, 0, 109, NULL, NULL, 1995, 'David Foster, Lincoln High', "", "", NULL, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 21, 0, 109, 0, 0, 2003, 'Jessie Bailis, Millard North', "", "", 6.286000e+001, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 22, 0, 109, 0, 0, 1998, 'David Anderson, Millard South', "", "", NULL, 'Y', 'M', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 23, 0, 109, 0, 0, 2001, 'Omaha Marian', "", "", 2.124300e+002, 'Y', 'F', 0);
INSERT INTO `RecordsbyEvent` VALUES(5, 24, 0, 109, NULL, NULL, 1992, 'Ralston', "", ' ', NULL, 'Y', 'M', 0);
UNLOCK TABLES;

#
# Table structure for table 'RecordTags'
#

DROP TABLE IF EXISTS `RecordTags`;
CREATE TABLE `RecordTags` (
  `tag_ptr` INT NOT NULL AUTO_INCREMENT,
  `tag_order` SMALLINT,
  `tag_name` VARCHAR(12),
  `tag_flag` VARCHAR(1),
  INDEX `tag_ptr` (`tag_ptr`)
);

#
# Dumping data for table 'RecordTags'
#

LOCK TABLES `RecordTags` WRITE;
INSERT INTO `RecordTags` VALUES(5, 1, 'NE St Record', '!');
UNLOCK TABLES;

#
# Table structure for table 'Regions'
#

DROP TABLE IF EXISTS `Regions`;
CREATE TABLE `Regions` (
  `Reg_no` INT,
  `Reg_abbr` VARCHAR(2),
  `Reg_name` VARCHAR(20),
  `fem_size` SMALLINT,
  `male_size` SMALLINT,
  `combined_size` SMALLINT
);

#
# Dumping data for table 'Regions'
#

LOCK TABLES `Regions` WRITE;
INSERT INTO `Regions` VALUES(1, 'NE', 'Northeast', NULL, NULL, NULL);
INSERT INTO `Regions` VALUES(2, 'SE', 'Southeast', NULL, NULL, NULL);
INSERT INTO `Regions` VALUES(3, 'NW', 'Northwest', NULL, NULL, NULL);
INSERT INTO `Regions` VALUES(4, 'SW', 'Southwest', NULL, NULL, NULL);
UNLOCK TABLES;

#
# Table structure for table 'Relay'
#

DROP TABLE IF EXISTS `Relay`;
CREATE TABLE `Relay` (
  `Event_ptr` INT,
  `Team_no` INT,
  `Team_ltr` VARCHAR(1),
  `Rel_age` SMALLINT,
  `Rel_sex` VARCHAR(1),
  `ActSeed_course` VARCHAR(1),
  `ActualSeed_time` FLOAT,
  `ConvSeed_course` VARCHAR(1),
  `ConvSeed_time` FLOAT,
  `Scr_stat` BIT,
  `Spec_stat` VARCHAR(1),
  `Dec_stat` VARCHAR(1),
  `Alt_stat` BIT,
  `Bonus_event` BIT,
  `Div_no` INT,
  `Ev_score` FLOAT,
  `dq_type` VARCHAR(1),
  `Pre_heat` SMALLINT,
  `Pre_lane` SMALLINT,
  `Pre_stat` VARCHAR(1),
  `Pre_Time` FLOAT,
  `Pre_course` VARCHAR(1),
  `Pre_heatplace` SMALLINT,
  `Pre_place` SMALLINT,
  `Pre_jdplace` SMALLINT,
  `Pre_exh` VARCHAR(1),
  `Pre_points` SMALLINT,
  `Pre_back1` FLOAT,
  `Pre_back2` FLOAT,
  `Pre_back3` FLOAT,
  `Fin_heat` SMALLINT,
  `Fin_lane` SMALLINT,
  `Fin_group` SMALLINT,
  `Fin_stat` VARCHAR(1),
  `Fin_Time` FLOAT,
  `Fin_course` VARCHAR(1),
  `Fin_heatplace` SMALLINT,
  `Fin_place` SMALLINT,
  `Fin_jdplace` SMALLINT,
  `Fin_ptsplace` SMALLINT,
  `Fin_exh` VARCHAR(1),
  `Fin_points` SMALLINT,
  `Fin_back1` FLOAT,
  `Fin_back2` FLOAT,
  `Fin_back3` FLOAT,
  `Sem_heat` SMALLINT,
  `Sem_lane` SMALLINT,
  `Sem_stat` VARCHAR(1),
  `Sem_Time` FLOAT,
  `Sem_course` VARCHAR(1),
  `Sem_heatplace` SMALLINT,
  `Sem_place` SMALLINT,
  `Sem_jdplace` SMALLINT,
  `Sem_exh` VARCHAR(1),
  `Sem_points` SMALLINT,
  `Sem_back1` FLOAT,
  `Sem_back2` FLOAT,
  `Sem_back3` FLOAT,
  `Swimoff_heat` SMALLINT,
  `Swimoff_lane` SMALLINT,
  `Swimoff_stat` VARCHAR(1),
  `Swimoff_Time` FLOAT,
  `Swimoff_course` VARCHAR(1),
  `Swimoff_heatplace` SMALLINT,
  `Swimoff_place` SMALLINT,
  `Swimoff_jdplace` SMALLINT,
  `Swimoff_points` SMALLINT,
  `Swimoff_back1` FLOAT,
  `Swimoff_back2` FLOAT,
  `Swimoff_back3` FLOAT,
  `JDEv_score` FLOAT,
  `Relay_no` INT NOT NULL AUTO_INCREMENT,
  `fin_jdheatplace` SMALLINT,
  `Seed_place` SMALLINT,
  `fin_heatltr` VARCHAR(1),
  INDEX `relevtptr` (`Event_ptr`),
  INDEX `relrelayno` (`Relay_no`),
  INDEX `relteamltr` (`Team_ltr`),
  INDEX `relteamno` (`Team_no`)
);

#
# Dumping data for table 'Relay'
#

LOCK TABLES `Relay` WRITE;
INSERT INTO `Relay` VALUES(1, 124, 'A', 0, 'F', 'Y', 1.243600e+002, 'Y', 1.243600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, 'Y', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 605, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 124, 'A', 0, 'M', 'Y', 9.847000e+001, 'Y', 9.847000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 606, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 124, 'A', 0, 'F', 'Y', 2.422300e+002, 'Y', 2.422300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 607, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 124, 'A', 0, 'M', 'Y', 2.313300e+002, 'Y', 2.313300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 608, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 125, 'A', 0, 'F', 'Y', 1.236200e+002, 'Y', 1.236200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 609, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 125, 'A', 0, 'M', 'Y', 1.125300e+002, 'Y', 1.125300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 610, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 125, 'A', 0, 'F', 'Y', 1.073800e+002, 'Y', 1.073800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 611, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 125, 'A', 0, 'M', 'Y', 9.987000e+001, 'Y', 9.987000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 612, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 125, 'A', 0, 'F', 'Y', 2.343600e+002, 'Y', 2.343600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 613, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 125, 'A', 0, 'M', 'Y', 2.169300e+002, 'Y', 2.169300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 614, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 126, 'A', 0, 'F', 'Y', 1.262400e+002, 'Y', 1.262400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, 'Y', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 615, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 126, 'A', 0, 'M', 'Y', 1.162200e+002, 'Y', 1.162200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 616, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 126, 'A', 0, 'F', 'Y', 1.145100e+002, 'Y', 1.145100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 617, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 126, 'A', 0, 'M', 'Y', 1.059000e+002, 'Y', 1.059000e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 618, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 126, 'A', 0, 'F', 'Y', 2.587100e+002, 'Y', 2.587100e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 619, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 126, 'A', 0, 'M', 'Y', 2.332400e+002, 'Y', 2.332400e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 620, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 127, 'A', 0, 'F', 'Y', 1.269600e+002, 'Y', 1.269600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 621, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 127, 'A', 0, 'M', 'Y', 1.116300e+002, 'Y', 1.116300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 622, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 127, 'A', 0, 'F', 'Y', 1.146300e+002, 'Y', 1.146300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 623, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 127, 'A', 0, 'M', 'Y', 9.986000e+001, 'Y', 9.986000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 624, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 127, 'A', 0, 'F', 'Y', 2.516100e+002, 'Y', 2.516100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 625, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 127, 'A', 0, 'M', 'Y', 2.136200e+002, 'Y', 2.136200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 626, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 128, 'A', 0, 'M', 'Y', 1.089500e+002, 'Y', 1.089500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 627, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 128, 'A', 0, 'M', 'Y', 9.852000e+001, 'Y', 9.852000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 628, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 128, 'A', 0, 'M', 'Y', 2.114000e+002, 'Y', 2.114000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 629, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 128, 'A', 0, 'F', 'Y', 2.566700e+002, 'Y', 2.566700e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 630, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 129, 'A', 0, 'F', 'Y', 1.175500e+002, 'Y', 1.175500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 631, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 129, 'A', 0, 'F', 'Y', 1.058200e+002, 'Y', 1.058200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 632, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 129, 'A', 0, 'M', 'Y', 9.243000e+001, 'Y', 9.243000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 633, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 129, 'A', 0, 'M', 'Y', 1.047500e+002, 'Y', 1.047500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 634, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 129, 'A', 0, 'F', 'Y', 2.333600e+002, 'Y', 2.333600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 635, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 129, 'A', 0, 'M', 'Y', 2.019700e+002, 'Y', 2.019700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 636, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 130, 'A', 0, 'F', 'Y', 1.215900e+002, 'Y', 1.215900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 637, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 130, 'A', 0, 'M', 'Y', 1.128000e+002, 'Y', 1.128000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 638, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 130, 'A', 0, 'F', 'Y', 1.050900e+002, 'Y', 1.050900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 639, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 130, 'A', 0, 'M', 'Y', 9.614000e+001, 'Y', 9.614000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 640, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 130, 'A', 0, 'F', 'Y', 2.337100e+002, 'Y', 2.337100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 641, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 130, 'A', 0, 'M', 'Y', 2.133100e+002, 'Y', 2.133100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 642, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 131, 'A', 0, 'F', 'Y', 1.279600e+002, 'Y', 1.279600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 643, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 131, 'A', 0, 'F', 'Y', 1.142400e+002, 'Y', 1.142400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 644, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 131, 'A', 0, 'M', 'Y', 1.148600e+002, 'Y', 1.148600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 645, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 131, 'A', 0, 'M', 'Y', 1.024300e+002, 'Y', 1.024300e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 646, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 131, 'A', 0, 'F', 'Y', 2.551100e+002, 'Y', 2.551100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 647, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 131, 'A', 0, 'M', 'Y', 2.286000e+002, 'Y', 2.286000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 648, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 132, 'A', 0, 'M', 'Y', 1.048600e+002, 'Y', 1.048600e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 649, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 132, 'A', 0, 'M', 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 650, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 133, 'A', 0, 'M', 'Y', 1.077300e+002, 'Y', 1.077300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 651, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 133, 'A', 0, 'M', 'Y', 9.535000e+001, 'Y', 9.535000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 652, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 133, 'A', 0, 'M', 'Y', 2.082800e+002, 'Y', 2.082800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 653, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 133, 'A', 0, 'F', 'Y', 1.163100e+002, 'Y', 1.163100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 654, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 133, 'A', 0, 'F', 'Y', 1.020600e+002, 'Y', 1.020600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 655, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 133, 'A', 0, 'F', 'Y', 2.257000e+002, 'Y', 2.257000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 656, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 134, 'A', 0, 'F', 'Y', 1.316500e+002, 'Y', 1.316500e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 657, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 134, 'A', 0, 'M', 'Y', 1.135100e+002, 'Y', 1.135100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 658, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 134, 'A', 0, 'F', 'Y', 1.105100e+002, 'Y', 1.105100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 659, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 134, 'A', 0, 'M', 'Y', 9.866000e+001, 'Y', 9.866000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 660, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 134, 'A', 0, 'F', 'Y', 2.509400e+002, 'Y', 2.509400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 661, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 134, 'A', 0, 'M', 'Y', 2.184200e+002, 'Y', 2.184200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 662, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 136, 'A', 0, 'F', 'Y', 1.025500e+002, 'Y', 1.025500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 663, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 136, 'A', 0, 'F', 'Y', 2.243300e+002, 'Y', 2.243300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 664, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 136, 'A', 0, 'F', 'Y', 1.106500e+002, 'Y', 1.106500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 665, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 136, 'A', 0, 'M', 'Y', 1.078300e+002, 'Y', 1.078300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 666, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 136, 'A', 0, 'M', 'Y', 9.234000e+001, 'Y', 9.234000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 667, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 136, 'A', 0, 'M', 'Y', 2.044200e+002, 'Y', 2.044200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 668, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 137, 'A', 0, 'F', 'Y', 1.181600e+002, 'Y', 1.181600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 669, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 137, 'A', 0, 'M', 'Y', 1.195900e+002, 'Y', 1.195900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 670, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 137, 'A', 0, 'F', 'Y', 1.067300e+002, 'Y', 1.067300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 671, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 137, 'A', 0, 'M', 'Y', 1.004400e+002, 'Y', 1.004400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 672, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 137, 'A', 0, 'F', 'Y', 2.272800e+002, 'Y', 2.272800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 673, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 137, 'A', 0, 'M', 'Y', 2.277300e+002, 'Y', 2.277300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 674, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 138, 'A', 0, 'F', 'Y', 1.202200e+002, 'Y', 1.202200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 675, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 138, 'A', 0, 'M', 'Y', 1.105900e+002, 'Y', 1.105900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 676, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 138, 'A', 0, 'F', 'Y', 1.088600e+002, 'Y', 1.088600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 677, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 138, 'A', 0, 'M', 'Y', 9.827000e+001, 'Y', 9.827000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 678, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 138, 'A', 0, 'F', 'Y', 2.399400e+002, 'Y', 2.399400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 679, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 138, 'A', 0, 'M', 'Y', 2.224800e+002, 'Y', 2.224800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 680, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 139, 'A', 0, 'F', 'Y', 1.293400e+002, 'Y', 1.293400e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 681, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 139, 'B', 0, 'F', 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 682, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 139, 'C', 0, 'F', 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 683, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 139, 'A', 0, 'M', 'Y', 1.099200e+002, 'Y', 1.099200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 684, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 139, 'A', 0, 'F', 'Y', 1.150100e+002, 'Y', 1.150100e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 685, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 139, 'A', 0, 'M', 'Y', 9.791000e+001, 'Y', 9.791000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 686, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 140, 'A', 0, 'F', 'Y', 1.167700e+002, 'Y', 1.167700e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 687, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 141, 'A', 0, 'M', 'Y', 1.058000e+002, 'Y', 1.058000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 688, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 141, 'A', 0, 'M', 'Y', 9.205000e+001, 'Y', 9.205000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 689, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 141, 'A', 0, 'M', 'Y', 2.058500e+002, 'Y', 2.058500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 690, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 141, 'A', 0, 'F', 'Y', 1.165400e+002, 'Y', 1.165400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 691, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 141, 'A', 0, 'F', 'Y', 1.070800e+002, 'Y', 1.070800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 692, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 141, 'A', 0, 'F', 'Y', 2.277200e+002, 'Y', 2.277200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 693, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 142, 'A', 0, 'F', 'Y', 1.226300e+002, 'Y', 1.226300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 694, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 142, 'A', 0, 'M', 'Y', 1.126300e+002, 'Y', 1.126300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 695, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 142, 'A', 0, 'F', 'Y', 1.089200e+002, 'Y', 1.089200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 696, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 142, 'A', 0, 'M', 'Y', 9.797000e+001, 'Y', 9.797000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 697, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 142, 'A', 0, 'F', 'Y', 2.486300e+002, 'Y', 2.486300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 698, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 142, 'A', 0, 'M', 'Y', 2.314100e+002, 'Y', 2.314100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 699, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 143, 'A', 0, 'M', 'Y', 1.016200e+002, 'Y', 1.016200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 700, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 143, 'A', 0, 'M', 'Y', 9.002000e+001, 'Y', 9.002000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 701, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 143, 'A', 0, 'M', 'Y', 1.979800e+002, 'Y', 1.979800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 702, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 144, 'A', 0, 'F', 'Y', 1.185700e+002, 'Y', 1.185700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 703, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 144, 'B', 0, 'F', 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 704, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 144, 'C', 0, 'F', 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 705, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 144, 'D', 0, 'F', 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 706, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 144, 'A', 0, 'F', 'Y', 1.068700e+002, 'Y', 1.068700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 707, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 144, 'A', 0, 'F', 'Y', 2.346700e+002, 'Y', 2.346700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 708, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 145, 'A', 0, 'F', 'Y', 1.086000e+002, 'Y', 1.086000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 709, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 145, 'B', 0, 'F', 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 710, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 145, 'C', 0, 'F', 'Y', 0.000000e+000, 'Y', 0.000000e+000, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 711, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 145, 'A', 0, 'F', 'Y', 9.739000e+001, 'Y', 9.739000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 712, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 145, 'A', 0, 'F', 'Y', 2.139100e+002, 'Y', 2.139100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 713, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 146, 'A', 0, 'M', 'Y', 2.264300e+002, 'Y', 2.264300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 714, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 146, 'A', 0, 'M', 'Y', 1.136400e+002, 'Y', 1.136400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 715, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 146, 'A', 0, 'M', 'Y', 9.894000e+001, 'Y', 9.894000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 716, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 146, 'A', 0, 'F', 'Y', 2.432900e+002, 'Y', 2.432900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 717, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 146, 'A', 0, 'F', 'Y', 1.220300e+002, 'Y', 1.220300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 718, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 146, 'A', 0, 'F', 'Y', 1.121800e+002, 'Y', 1.121800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 719, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 147, 'A', 0, 'M', 'Y', 1.091500e+002, 'Y', 1.091500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 720, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 147, 'A', 0, 'F', 'Y', 1.173200e+002, 'Y', 1.173200e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 721, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 147, 'A', 0, 'M', 'Y', 9.606000e+001, 'Y', 9.606000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 722, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 147, 'A', 0, 'M', 'Y', 2.183600e+002, 'Y', 2.183600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 723, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 149, 'A', 0, 'F', 'Y', 1.191600e+002, 'Y', 1.191600e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 724, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 149, 'A', 0, 'F', 'Y', 2.617400e+002, 'Y', 2.617400e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 725, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 149, 'A', 0, 'F', 'Y', 1.175900e+002, 'Y', 1.175900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 726, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 151, 'A', 0, 'F', 'Y', 1.152900e+002, 'Y', 1.152900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 727, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 151, 'A', 0, 'M', 'Y', 1.002100e+002, 'Y', 1.002100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 728, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 151, 'A', 0, 'F', 'Y', 1.034100e+002, 'Y', 1.034100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 729, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 151, 'A', 0, 'M', 'Y', 8.931000e+001, 'Y', 8.931000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 730, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 151, 'A', 0, 'F', 'Y', 2.280500e+002, 'Y', 2.280500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 731, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 151, 'A', 0, 'M', 'Y', 1.927800e+002, 'Y', 1.927800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 732, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 152, 'A', 0, 'F', 'Y', 1.170300e+002, 'Y', 1.170300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 733, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 152, 'A', 0, 'F', 'Y', 1.069800e+002, 'Y', 1.069800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 734, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 152, 'A', 0, 'F', 'Y', 2.312600e+002, 'Y', 2.312600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 735, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 152, 'A', 0, 'M', 'Y', 1.006500e+002, 'Y', 1.006500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 736, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 152, 'A', 0, 'M', 'Y', 9.134000e+001, 'Y', 9.134000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 737, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 152, 'A', 0, 'M', 'Y', 2.026100e+002, 'Y', 2.026100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 738, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 153, 'A', 0, 'F', 'Y', 1.172500e+002, 'Y', 1.172500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 739, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 153, 'A', 0, 'M', 'Y', 1.077900e+002, 'Y', 1.077900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 740, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 153, 'A', 0, 'M', 'Y', 9.346000e+001, 'Y', 9.346000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 741, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 153, 'A', 0, 'F', 'Y', 1.061600e+002, 'Y', 1.061600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 742, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 153, 'A', 0, 'F', 'Y', 2.367900e+002, 'Y', 2.367900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 743, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 153, 'A', 0, 'M', 'Y', 2.082600e+002, 'Y', 2.082600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 7, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 744, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 154, 'A', 0, 'F', 'Y', 1.199600e+002, 'Y', 1.199600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 3, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 745, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 154, 'A', 0, 'M', 'Y', 1.063300e+002, 'Y', 1.063300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 746, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 154, 'A', 0, 'F', 'Y', 1.055400e+002, 'Y', 1.055400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 747, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 154, 'A', 0, 'M', 'Y', 9.284000e+001, 'Y', 9.284000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 748, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 154, 'A', 0, 'F', 'Y', 2.291900e+002, 'Y', 2.291900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 749, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 154, 'A', 0, 'M', 'Y', 2.070200e+002, 'Y', 2.070200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 750, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 155, 'A', 0, 'F', 'Y', 1.291500e+002, 'Y', 1.291500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 751, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 155, 'A', 0, 'F', 'Y', 1.118300e+002, 'Y', 1.118300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 752, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 155, 'A', 0, 'F', 'Y', 2.541600e+002, 'Y', 2.541600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 9, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 753, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 156, 'A', 0, 'M', 'Y', 1.163900e+002, 'Y', 1.163900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 754, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 156, 'A', 0, 'M', 'Y', 1.004900e+002, 'Y', 1.004900e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 755, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 156, 'A', 0, 'M', 'Y', 2.318200e+002, 'Y', 2.318200e+002, -1, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 756, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 157, 'A', 0, 'F', 'Y', 1.158100e+002, 'Y', 1.158100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 757, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 157, 'A', 0, 'M', 'Y', 1.039100e+002, 'Y', 1.039100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 758, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 157, 'A', 0, 'F', 'Y', 1.017400e+002, 'Y', 1.017400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 759, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 157, 'A', 0, 'M', 'Y', 9.175000e+001, 'Y', 9.175000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 760, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 157, 'A', 0, 'F', 'Y', 2.252900e+002, 'Y', 2.252900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 761, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 157, 'A', 0, 'M', 'Y', 2.073500e+002, 'Y', 2.073500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 762, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 158, 'A', 0, 'F', 'Y', 1.242900e+002, 'Y', 1.242900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 3, 2, ' ', 0.000000e+000, 'Y', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 763, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 158, 'A', 0, 'M', 'Y', 1.132000e+002, 'Y', 1.132000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 764, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 158, 'A', 0, 'F', 'Y', 1.122800e+002, 'Y', 1.122800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 765, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 158, 'A', 0, 'M', 'Y', 9.945000e+001, 'Y', 9.945000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 2, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 766, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 158, 'A', 0, 'F', 'Y', 2.417900e+002, 'Y', 2.417900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 767, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 158, 'A', 0, 'M', 'Y', 2.222500e+002, 'Y', 2.222500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 8, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 768, 0, 0, "");
INSERT INTO `Relay` VALUES(1, 159, 'A', 0, 'F', 'Y', 1.106100e+002, 'Y', 1.106100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 769, 0, 0, "");
INSERT INTO `Relay` VALUES(17, 159, 'A', 0, 'F', 'Y', 1.000300e+002, 'Y', 1.000300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 770, 0, 0, "");
INSERT INTO `Relay` VALUES(23, 159, 'A', 0, 'F', 'Y', 2.228200e+002, 'Y', 2.228200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 5, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 771, 0, 0, "");
INSERT INTO `Relay` VALUES(2, 159, 'A', 0, 'M', 'Y', 1.040000e+002, 'Y', 1.040000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 2, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 772, 0, 0, "");
INSERT INTO `Relay` VALUES(18, 159, 'A', 0, 'M', 'Y', 9.312000e+001, 'Y', 9.312000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 4, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 773, 0, 0, "");
INSERT INTO `Relay` VALUES(24, 159, 'A', 0, 'M', 'Y', 2.044600e+002, 'Y', 2.044600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, ' ', 1, 6, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0, 0, ' ', 0.000000e+000, ' ', 0, 0, 0, ' ', 0, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, 774, 0, 0, "");
UNLOCK TABLES;

#
# Table structure for table 'RelayNames'
#

DROP TABLE IF EXISTS `RelayNames`;
CREATE TABLE `RelayNames` (
  `Event_ptr` INT,
  `Team_no` INT,
  `Team_ltr` VARCHAR(255),
  `Ath_no` INT,
  `Pos_no` SMALLINT,
  `Event_round` VARCHAR(1),
  `Relay_no` INT,
  INDEX `relathno` (`Ath_no`),
  INDEX `relnameteamltr` (`Team_ltr`),
  INDEX `relnameteamno` (`Team_no`),
  INDEX `relnamevtptr` (`Event_ptr`)
);

#
# Dumping data for table 'RelayNames'
#

LOCK TABLES `RelayNames` WRITE;
INSERT INTO `RelayNames` VALUES(23, 124, 'A', 2386, 5, 'P', 607);
INSERT INTO `RelayNames` VALUES(1, 124, 'A', 2387, 2, 'P', 605);
INSERT INTO `RelayNames` VALUES(23, 124, 'A', 2387, 2, 'P', 607);
INSERT INTO `RelayNames` VALUES(1, 124, 'A', 2388, 5, 'P', 605);
INSERT INTO `RelayNames` VALUES(18, 124, 'A', 2389, 3, 'P', 606);
INSERT INTO `RelayNames` VALUES(24, 124, 'A', 2389, 2, 'P', 608);
INSERT INTO `RelayNames` VALUES(18, 124, 'A', 2390, 4, 'P', 606);
INSERT INTO `RelayNames` VALUES(24, 124, 'A', 2390, 4, 'P', 608);
INSERT INTO `RelayNames` VALUES(18, 124, 'A', 2391, 2, 'P', 606);
INSERT INTO `RelayNames` VALUES(24, 124, 'A', 2391, 3, 'P', 608);
INSERT INTO `RelayNames` VALUES(1, 124, 'A', 2392, 4, 'P', 605);
INSERT INTO `RelayNames` VALUES(23, 124, 'A', 2392, 4, 'P', 607);
INSERT INTO `RelayNames` VALUES(1, 124, 'A', 2393, 3, 'P', 605);
INSERT INTO `RelayNames` VALUES(23, 124, 'A', 2393, 3, 'P', 607);
INSERT INTO `RelayNames` VALUES(1, 124, 'A', 2394, 1, 'P', 605);
INSERT INTO `RelayNames` VALUES(23, 124, 'A', 2394, 1, 'P', 607);
INSERT INTO `RelayNames` VALUES(18, 124, 'A', 2396, 5, 'P', 606);
INSERT INTO `RelayNames` VALUES(18, 124, 'A', 2397, 1, 'P', 606);
INSERT INTO `RelayNames` VALUES(24, 124, 'A', 2397, 1, 'P', 608);
INSERT INTO `RelayNames` VALUES(2, 125, 'A', 2398, 4, 'P', 610);
INSERT INTO `RelayNames` VALUES(18, 125, 'A', 2398, 5, 'P', 612);
INSERT INTO `RelayNames` VALUES(24, 125, 'A', 2398, 1, 'P', 614);
INSERT INTO `RelayNames` VALUES(17, 125, 'A', 2399, 1, 'P', 611);
INSERT INTO `RelayNames` VALUES(23, 125, 'A', 2399, 1, 'P', 613);
INSERT INTO `RelayNames` VALUES(2, 125, 'A', 2400, 5, 'P', 610);
INSERT INTO `RelayNames` VALUES(18, 125, 'A', 2400, 1, 'P', 612);
INSERT INTO `RelayNames` VALUES(24, 125, 'A', 2400, 5, 'P', 614);
INSERT INTO `RelayNames` VALUES(2, 125, 'A', 2401, 8, 'P', 610);
INSERT INTO `RelayNames` VALUES(18, 125, 'A', 2402, 6, 'P', 612);
INSERT INTO `RelayNames` VALUES(24, 125, 'A', 2402, 6, 'P', 614);
INSERT INTO `RelayNames` VALUES(1, 125, 'A', 2403, 8, 'P', 609);
INSERT INTO `RelayNames` VALUES(17, 125, 'A', 2403, 2, 'P', 611);
INSERT INTO `RelayNames` VALUES(23, 125, 'A', 2403, 2, 'P', 613);
INSERT INTO `RelayNames` VALUES(2, 125, 'A', 2404, 1, 'P', 610);
INSERT INTO `RelayNames` VALUES(18, 125, 'A', 2404, 8, 'P', 612);
INSERT INTO `RelayNames` VALUES(24, 125, 'A', 2404, 2, 'P', 614);
INSERT INTO `RelayNames` VALUES(2, 125, 'A', 2405, 6, 'P', 610);
INSERT INTO `RelayNames` VALUES(18, 125, 'A', 2405, 2, 'P', 612);
INSERT INTO `RelayNames` VALUES(24, 125, 'A', 2405, 3, 'P', 614);
INSERT INTO `RelayNames` VALUES(17, 125, 'A', 2406, 3, 'P', 611);
INSERT INTO `RelayNames` VALUES(23, 125, 'A', 2406, 3, 'P', 613);
INSERT INTO `RelayNames` VALUES(1, 125, 'A', 2407, 7, 'P', 609);
INSERT INTO `RelayNames` VALUES(23, 125, 'A', 2407, 5, 'P', 613);
INSERT INTO `RelayNames` VALUES(1, 125, 'A', 2408, 6, 'P', 609);
INSERT INTO `RelayNames` VALUES(17, 125, 'A', 2408, 5, 'P', 611);
INSERT INTO `RelayNames` VALUES(1, 125, 'A', 2409, 2, 'P', 609);
INSERT INTO `RelayNames` VALUES(1, 125, 'A', 2410, 4, 'P', 609);
INSERT INTO `RelayNames` VALUES(17, 125, 'A', 2410, 6, 'P', 611);
INSERT INTO `RelayNames` VALUES(23, 125, 'A', 2410, 6, 'P', 613);
INSERT INTO `RelayNames` VALUES(17, 125, 'A', 2411, 7, 'P', 611);
INSERT INTO `RelayNames` VALUES(23, 125, 'A', 2411, 7, 'P', 613);
INSERT INTO `RelayNames` VALUES(1, 125, 'A', 2412, 3, 'P', 609);
INSERT INTO `RelayNames` VALUES(17, 125, 'A', 2412, 8, 'P', 611);
INSERT INTO `RelayNames` VALUES(1, 125, 'A', 2413, 1, 'P', 609);
INSERT INTO `RelayNames` VALUES(23, 125, 'A', 2413, 8, 'P', 613);
INSERT INTO `RelayNames` VALUES(1, 125, 'A', 2414, 5, 'P', 609);
INSERT INTO `RelayNames` VALUES(17, 125, 'A', 2414, 4, 'P', 611);
INSERT INTO `RelayNames` VALUES(23, 125, 'A', 2414, 4, 'P', 613);
INSERT INTO `RelayNames` VALUES(2, 125, 'A', 2415, 3, 'P', 610);
INSERT INTO `RelayNames` VALUES(18, 125, 'A', 2415, 3, 'P', 612);
INSERT INTO `RelayNames` VALUES(24, 125, 'A', 2415, 7, 'P', 614);
INSERT INTO `RelayNames` VALUES(2, 125, 'A', 2416, 2, 'P', 610);
INSERT INTO `RelayNames` VALUES(18, 125, 'A', 2416, 7, 'P', 612);
INSERT INTO `RelayNames` VALUES(24, 125, 'A', 2416, 4, 'P', 614);
INSERT INTO `RelayNames` VALUES(2, 125, 'A', 2417, 7, 'P', 610);
INSERT INTO `RelayNames` VALUES(18, 125, 'A', 2417, 4, 'P', 612);
INSERT INTO `RelayNames` VALUES(24, 125, 'A', 2417, 8, 'P', 614);
INSERT INTO `RelayNames` VALUES(1, 126, 'A', 2420, 4, 'P', 615);
INSERT INTO `RelayNames` VALUES(17, 126, 'A', 2420, 3, 'P', 617);
INSERT INTO `RelayNames` VALUES(23, 126, 'A', 2420, 3, 'P', 619);
INSERT INTO `RelayNames` VALUES(2, 126, 'A', 2421, 5, 'P', 616);
INSERT INTO `RelayNames` VALUES(18, 126, 'A', 2421, 3, 'P', 618);
INSERT INTO `RelayNames` VALUES(24, 126, 'A', 2421, 3, 'P', 620);
INSERT INTO `RelayNames` VALUES(2, 126, 'A', 2422, 7, 'P', 616);
INSERT INTO `RelayNames` VALUES(18, 126, 'A', 2422, 5, 'P', 618);
INSERT INTO `RelayNames` VALUES(24, 126, 'A', 2422, 5, 'P', 620);
INSERT INTO `RelayNames` VALUES(24, 126, 'A', 2423, 6, 'P', 620);
INSERT INTO `RelayNames` VALUES(1, 126, 'A', 2424, 6, 'P', 615);
INSERT INTO `RelayNames` VALUES(1, 126, 'A', 2425, 7, 'P', 615);
INSERT INTO `RelayNames` VALUES(17, 126, 'A', 2425, 6, 'P', 617);
INSERT INTO `RelayNames` VALUES(23, 126, 'A', 2425, 6, 'P', 619);
INSERT INTO `RelayNames` VALUES(2, 126, 'A', 2426, 6, 'P', 616);
INSERT INTO `RelayNames` VALUES(1, 126, 'A', 2427, 1, 'P', 615);
INSERT INTO `RelayNames` VALUES(17, 126, 'A', 2427, 1, 'P', 617);
INSERT INTO `RelayNames` VALUES(23, 126, 'A', 2427, 1, 'P', 619);
INSERT INTO `RelayNames` VALUES(1, 126, 'A', 2428, 3, 'P', 615);
INSERT INTO `RelayNames` VALUES(17, 126, 'A', 2428, 2, 'P', 617);
INSERT INTO `RelayNames` VALUES(23, 126, 'A', 2428, 2, 'P', 619);
INSERT INTO `RelayNames` VALUES(2, 126, 'A', 2429, 3, 'P', 616);
INSERT INTO `RelayNames` VALUES(18, 126, 'A', 2429, 1, 'P', 618);
INSERT INTO `RelayNames` VALUES(24, 126, 'A', 2429, 1, 'P', 620);
INSERT INTO `RelayNames` VALUES(2, 126, 'A', 2430, 1, 'P', 616);
INSERT INTO `RelayNames` VALUES(18, 126, 'A', 2430, 4, 'P', 618);
INSERT INTO `RelayNames` VALUES(24, 126, 'A', 2430, 4, 'P', 620);
INSERT INTO `RelayNames` VALUES(1, 126, 'A', 2431, 5, 'P', 615);
INSERT INTO `RelayNames` VALUES(17, 126, 'A', 2431, 4, 'P', 617);
INSERT INTO `RelayNames` VALUES(23, 126, 'A', 2431, 5, 'P', 619);
INSERT INTO `RelayNames` VALUES(2, 126, 'A', 2432, 4, 'P', 616);
INSERT INTO `RelayNames` VALUES(18, 126, 'A', 2432, 2, 'P', 618);
INSERT INTO `RelayNames` VALUES(24, 126, 'A', 2432, 2, 'P', 620);
INSERT INTO `RelayNames` VALUES(1, 126, 'A', 2433, 8, 'P', 615);
INSERT INTO `RelayNames` VALUES(2, 126, 'A', 2434, 2, 'P', 616);
INSERT INTO `RelayNames` VALUES(18, 126, 'A', 2434, 6, 'P', 618);
INSERT INTO `RelayNames` VALUES(1, 126, 'A', 2435, 2, 'P', 615);
INSERT INTO `RelayNames` VALUES(17, 126, 'A', 2435, 5, 'P', 617);
INSERT INTO `RelayNames` VALUES(23, 126, 'A', 2435, 4, 'P', 619);
INSERT INTO `RelayNames` VALUES(2, 127, 'A', 2436, 6, 'P', 622);
INSERT INTO `RelayNames` VALUES(18, 127, 'A', 2436, 3, 'P', 624);
INSERT INTO `RelayNames` VALUES(24, 127, 'A', 2436, 6, 'P', 626);
INSERT INTO `RelayNames` VALUES(1, 127, 'A', 2437, 5, 'P', 621);
INSERT INTO `RelayNames` VALUES(17, 127, 'A', 2437, 1, 'P', 623);
INSERT INTO `RelayNames` VALUES(23, 127, 'A', 2437, 5, 'P', 625);
INSERT INTO `RelayNames` VALUES(2, 127, 'A', 2438, 3, 'P', 622);
INSERT INTO `RelayNames` VALUES(18, 127, 'A', 2438, 1, 'P', 624);
INSERT INTO `RelayNames` VALUES(24, 127, 'A', 2438, 1, 'P', 626);
INSERT INTO `RelayNames` VALUES(2, 127, 'A', 2439, 4, 'P', 622);
INSERT INTO `RelayNames` VALUES(18, 127, 'A', 2439, 4, 'P', 624);
INSERT INTO `RelayNames` VALUES(24, 127, 'A', 2439, 2, 'P', 626);
INSERT INTO `RelayNames` VALUES(1, 127, 'A', 2440, 2, 'P', 621);
INSERT INTO `RelayNames` VALUES(17, 127, 'A', 2440, 6, 'P', 623);
INSERT INTO `RelayNames` VALUES(23, 127, 'A', 2440, 1, 'P', 625);
INSERT INTO `RelayNames` VALUES(1, 127, 'A', 2441, 4, 'P', 621);
INSERT INTO `RelayNames` VALUES(17, 127, 'A', 2441, 4, 'P', 623);
INSERT INTO `RelayNames` VALUES(23, 127, 'A', 2441, 4, 'P', 625);
INSERT INTO `RelayNames` VALUES(1, 127, 'A', 2442, 3, 'P', 621);
INSERT INTO `RelayNames` VALUES(17, 127, 'A', 2442, 2, 'P', 623);
INSERT INTO `RelayNames` VALUES(23, 127, 'A', 2442, 6, 'P', 625);
INSERT INTO `RelayNames` VALUES(1, 127, 'A', 2443, 6, 'P', 621);
INSERT INTO `RelayNames` VALUES(17, 127, 'A', 2443, 3, 'P', 623);
INSERT INTO `RelayNames` VALUES(23, 127, 'A', 2443, 3, 'P', 625);
INSERT INTO `RelayNames` VALUES(2, 127, 'A', 2444, 1, 'P', 622);
INSERT INTO `RelayNames` VALUES(18, 127, 'A', 2444, 2, 'P', 624);
INSERT INTO `RelayNames` VALUES(24, 127, 'A', 2444, 5, 'P', 626);
INSERT INTO `RelayNames` VALUES(2, 127, 'A', 2445, 5, 'P', 622);
INSERT INTO `RelayNames` VALUES(18, 127, 'A', 2445, 6, 'P', 624);
INSERT INTO `RelayNames` VALUES(24, 127, 'A', 2445, 3, 'P', 626);
INSERT INTO `RelayNames` VALUES(1, 127, 'A', 2446, 1, 'P', 621);
INSERT INTO `RelayNames` VALUES(17, 127, 'A', 2446, 5, 'P', 623);
INSERT INTO `RelayNames` VALUES(23, 127, 'A', 2446, 2, 'P', 625);
INSERT INTO `RelayNames` VALUES(2, 127, 'A', 2447, 2, 'P', 622);
INSERT INTO `RelayNames` VALUES(18, 127, 'A', 2447, 5, 'P', 624);
INSERT INTO `RelayNames` VALUES(24, 127, 'A', 2447, 4, 'P', 626);
INSERT INTO `RelayNames` VALUES(23, 128, 'A', 2449, 1, 'P', 630);
INSERT INTO `RelayNames` VALUES(2, 128, 'A', 2450, 6, 'P', 627);
INSERT INTO `RelayNames` VALUES(18, 128, 'A', 2450, 3, 'P', 628);
INSERT INTO `RelayNames` VALUES(24, 128, 'A', 2450, 6, 'P', 629);
INSERT INTO `RelayNames` VALUES(2, 128, 'A', 2451, 5, 'P', 627);
INSERT INTO `RelayNames` VALUES(18, 128, 'A', 2451, 2, 'P', 628);
INSERT INTO `RelayNames` VALUES(24, 128, 'A', 2451, 2, 'P', 629);
INSERT INTO `RelayNames` VALUES(23, 128, 'A', 2452, 2, 'P', 630);
INSERT INTO `RelayNames` VALUES(23, 128, 'A', 2453, 6, 'P', 630);
INSERT INTO `RelayNames` VALUES(2, 128, 'A', 2454, 2, 'P', 627);
INSERT INTO `RelayNames` VALUES(18, 128, 'A', 2454, 5, 'P', 628);
INSERT INTO `RelayNames` VALUES(24, 128, 'A', 2454, 5, 'P', 629);
INSERT INTO `RelayNames` VALUES(23, 128, 'A', 2455, 4, 'P', 630);
INSERT INTO `RelayNames` VALUES(23, 128, 'A', 2456, 7, 'P', 630);
INSERT INTO `RelayNames` VALUES(2, 128, 'A', 2457, 7, 'P', 627);
INSERT INTO `RelayNames` VALUES(18, 128, 'A', 2457, 6, 'P', 628);
INSERT INTO `RelayNames` VALUES(23, 128, 'A', 2458, 5, 'P', 630);
INSERT INTO `RelayNames` VALUES(23, 128, 'A', 2459, 3, 'P', 630);
INSERT INTO `RelayNames` VALUES(2, 128, 'A', 2460, 1, 'P', 627);
INSERT INTO `RelayNames` VALUES(18, 128, 'A', 2460, 4, 'P', 628);
INSERT INTO `RelayNames` VALUES(24, 128, 'A', 2460, 3, 'P', 629);
INSERT INTO `RelayNames` VALUES(2, 128, 'A', 2462, 3, 'P', 627);
INSERT INTO `RelayNames` VALUES(24, 128, 'A', 2462, 4, 'P', 629);
INSERT INTO `RelayNames` VALUES(2, 128, 'A', 2463, 4, 'P', 627);
INSERT INTO `RelayNames` VALUES(18, 128, 'A', 2463, 1, 'P', 628);
INSERT INTO `RelayNames` VALUES(24, 128, 'A', 2463, 1, 'P', 629);
INSERT INTO `RelayNames` VALUES(1, 129, 'A', 2464, 4, 'P', 631);
INSERT INTO `RelayNames` VALUES(17, 129, 'A', 2464, 3, 'P', 632);
INSERT INTO `RelayNames` VALUES(23, 129, 'A', 2464, 2, 'P', 635);
INSERT INTO `RelayNames` VALUES(18, 129, 'A', 2465, 6, 'P', 633);
INSERT INTO `RelayNames` VALUES(2, 129, 'A', 2465, 4, 'P', 634);
INSERT INTO `RelayNames` VALUES(18, 129, 'A', 2466, 5, 'P', 633);
INSERT INTO `RelayNames` VALUES(2, 129, 'A', 2466, 3, 'P', 634);
INSERT INTO `RelayNames` VALUES(17, 129, 'A', 2467, 2, 'P', 632);
INSERT INTO `RelayNames` VALUES(23, 129, 'A', 2467, 7, 'P', 635);
INSERT INTO `RelayNames` VALUES(1, 129, 'A', 2468, 1, 'P', 631);
INSERT INTO `RelayNames` VALUES(23, 129, 'A', 2468, 3, 'P', 635);
INSERT INTO `RelayNames` VALUES(18, 129, 'A', 2469, 1, 'P', 633);
INSERT INTO `RelayNames` VALUES(24, 129, 'A', 2469, 1, 'P', 636);
INSERT INTO `RelayNames` VALUES(1, 129, 'A', 2470, 3, 'P', 631);
INSERT INTO `RelayNames` VALUES(17, 129, 'A', 2470, 5, 'P', 632);
INSERT INTO `RelayNames` VALUES(23, 129, 'A', 2470, 5, 'P', 635);
INSERT INTO `RelayNames` VALUES(18, 129, 'A', 2471, 4, 'P', 633);
INSERT INTO `RelayNames` VALUES(2, 129, 'A', 2471, 1, 'P', 634);
INSERT INTO `RelayNames` VALUES(24, 129, 'A', 2471, 4, 'P', 636);
INSERT INTO `RelayNames` VALUES(2, 129, 'A', 2472, 2, 'P', 634);
INSERT INTO `RelayNames` VALUES(24, 129, 'A', 2472, 5, 'P', 636);
INSERT INTO `RelayNames` VALUES(1, 129, 'A', 2473, 2, 'P', 631);
INSERT INTO `RelayNames` VALUES(17, 129, 'A', 2473, 1, 'P', 632);
INSERT INTO `RelayNames` VALUES(23, 129, 'A', 2473, 1, 'P', 635);
INSERT INTO `RelayNames` VALUES(18, 129, 'A', 2475, 3, 'P', 633);
INSERT INTO `RelayNames` VALUES(24, 129, 'A', 2475, 2, 'P', 636);
INSERT INTO `RelayNames` VALUES(1, 129, 'A', 2476, 5, 'P', 631);
INSERT INTO `RelayNames` VALUES(17, 129, 'A', 2476, 4, 'P', 632);
INSERT INTO `RelayNames` VALUES(23, 129, 'A', 2476, 4, 'P', 635);
INSERT INTO `RelayNames` VALUES(17, 129, 'A', 2478, 6, 'P', 632);
INSERT INTO `RelayNames` VALUES(23, 129, 'A', 2478, 6, 'P', 635);
INSERT INTO `RelayNames` VALUES(18, 129, 'A', 2479, 2, 'P', 633);
INSERT INTO `RelayNames` VALUES(24, 129, 'A', 2479, 3, 'P', 636);
INSERT INTO `RelayNames` VALUES(2, 130, 'A', 2480, 1, 'P', 638);
INSERT INTO `RelayNames` VALUES(18, 130, 'A', 2480, 1, 'P', 640);
INSERT INTO `RelayNames` VALUES(24, 130, 'A', 2480, 4, 'P', 642);
INSERT INTO `RelayNames` VALUES(1, 130, 'A', 2481, 1, 'P', 637);
INSERT INTO `RelayNames` VALUES(17, 130, 'A', 2481, 7, 'P', 639);
INSERT INTO `RelayNames` VALUES(1, 130, 'A', 2482, 2, 'P', 637);
INSERT INTO `RelayNames` VALUES(17, 130, 'A', 2482, 8, 'P', 639);
INSERT INTO `RelayNames` VALUES(1, 130, 'A', 2483, 3, 'P', 637);
INSERT INTO `RelayNames` VALUES(17, 130, 'A', 2483, 6, 'P', 639);
INSERT INTO `RelayNames` VALUES(23, 130, 'A', 2483, 6, 'P', 641);
INSERT INTO `RelayNames` VALUES(1, 130, 'A', 2484, 4, 'P', 637);
INSERT INTO `RelayNames` VALUES(17, 130, 'A', 2484, 5, 'P', 639);
INSERT INTO `RelayNames` VALUES(23, 130, 'A', 2484, 5, 'P', 641);
INSERT INTO `RelayNames` VALUES(2, 130, 'A', 2485, 2, 'P', 638);
INSERT INTO `RelayNames` VALUES(18, 130, 'A', 2485, 2, 'P', 640);
INSERT INTO `RelayNames` VALUES(24, 130, 'A', 2485, 3, 'P', 642);
INSERT INTO `RelayNames` VALUES(1, 130, 'A', 2486, 5, 'P', 637);
INSERT INTO `RelayNames` VALUES(17, 130, 'A', 2486, 4, 'P', 639);
INSERT INTO `RelayNames` VALUES(23, 130, 'A', 2486, 4, 'P', 641);
INSERT INTO `RelayNames` VALUES(2, 130, 'A', 2487, 3, 'P', 638);
INSERT INTO `RelayNames` VALUES(18, 130, 'A', 2487, 3, 'P', 640);
INSERT INTO `RelayNames` VALUES(24, 130, 'A', 2487, 5, 'P', 642);
INSERT INTO `RelayNames` VALUES(17, 130, 'A', 2488, 3, 'P', 639);
INSERT INTO `RelayNames` VALUES(23, 130, 'A', 2488, 3, 'P', 641);
INSERT INTO `RelayNames` VALUES(1, 130, 'A', 2489, 7, 'P', 637);
INSERT INTO `RelayNames` VALUES(23, 130, 'A', 2489, 8, 'P', 641);
INSERT INTO `RelayNames` VALUES(2, 130, 'A', 2490, 4, 'P', 638);
INSERT INTO `RelayNames` VALUES(18, 130, 'A', 2490, 4, 'P', 640);
INSERT INTO `RelayNames` VALUES(24, 130, 'A', 2490, 6, 'P', 642);
INSERT INTO `RelayNames` VALUES(23, 130, 'A', 2491, 7, 'P', 641);
INSERT INTO `RelayNames` VALUES(1, 130, 'A', 2492, 8, 'P', 637);
INSERT INTO `RelayNames` VALUES(2, 130, 'A', 2493, 5, 'P', 638);
INSERT INTO `RelayNames` VALUES(18, 130, 'A', 2493, 5, 'P', 640);
INSERT INTO `RelayNames` VALUES(24, 130, 'A', 2493, 2, 'P', 642);
INSERT INTO `RelayNames` VALUES(2, 130, 'A', 2494, 6, 'P', 638);
INSERT INTO `RelayNames` VALUES(18, 130, 'A', 2494, 6, 'P', 640);
INSERT INTO `RelayNames` VALUES(24, 130, 'A', 2494, 7, 'P', 642);
INSERT INTO `RelayNames` VALUES(17, 130, 'A', 2495, 2, 'P', 639);
INSERT INTO `RelayNames` VALUES(23, 130, 'A', 2495, 2, 'P', 641);
INSERT INTO `RelayNames` VALUES(2, 130, 'A', 2496, 7, 'P', 638);
INSERT INTO `RelayNames` VALUES(18, 130, 'A', 2496, 7, 'P', 640);
INSERT INTO `RelayNames` VALUES(24, 130, 'A', 2496, 1, 'P', 642);
INSERT INTO `RelayNames` VALUES(1, 130, 'A', 2497, 6, 'P', 637);
INSERT INTO `RelayNames` VALUES(17, 130, 'A', 2497, 1, 'P', 639);
INSERT INTO `RelayNames` VALUES(23, 130, 'A', 2497, 1, 'P', 641);
INSERT INTO `RelayNames` VALUES(2, 130, 'A', 2498, 8, 'P', 638);
INSERT INTO `RelayNames` VALUES(18, 130, 'A', 2498, 8, 'P', 640);
INSERT INTO `RelayNames` VALUES(24, 130, 'A', 2498, 8, 'P', 642);
INSERT INTO `RelayNames` VALUES(1, 131, 'A', 2501, 8, 'P', 643);
INSERT INTO `RelayNames` VALUES(17, 131, 'A', 2501, 8, 'P', 644);
INSERT INTO `RelayNames` VALUES(23, 131, 'A', 2501, 6, 'P', 647);
INSERT INTO `RelayNames` VALUES(1, 131, 'A', 2502, 1, 'P', 643);
INSERT INTO `RelayNames` VALUES(17, 131, 'A', 2502, 2, 'P', 644);
INSERT INTO `RelayNames` VALUES(23, 131, 'A', 2502, 2, 'P', 647);
INSERT INTO `RelayNames` VALUES(1, 131, 'A', 2503, 7, 'P', 643);
INSERT INTO `RelayNames` VALUES(17, 131, 'A', 2503, 3, 'P', 644);
INSERT INTO `RelayNames` VALUES(23, 131, 'A', 2503, 5, 'P', 647);
INSERT INTO `RelayNames` VALUES(1, 131, 'A', 2504, 6, 'P', 643);
INSERT INTO `RelayNames` VALUES(17, 131, 'A', 2504, 5, 'P', 644);
INSERT INTO `RelayNames` VALUES(23, 131, 'A', 2504, 3, 'P', 647);
INSERT INTO `RelayNames` VALUES(1, 131, 'A', 2505, 2, 'P', 643);
INSERT INTO `RelayNames` VALUES(1, 131, 'A', 2506, 5, 'P', 643);
INSERT INTO `RelayNames` VALUES(23, 131, 'A', 2506, 8, 'P', 647);
INSERT INTO `RelayNames` VALUES(17, 131, 'A', 2507, 6, 'P', 644);
INSERT INTO `RelayNames` VALUES(23, 131, 'A', 2507, 7, 'P', 647);
INSERT INTO `RelayNames` VALUES(17, 131, 'A', 2508, 7, 'P', 644);
INSERT INTO `RelayNames` VALUES(2, 131, 'A', 2509, 2, 'P', 645);
INSERT INTO `RelayNames` VALUES(18, 131, 'A', 2509, 8, 'P', 646);
INSERT INTO `RelayNames` VALUES(24, 131, 'A', 2509, 3, 'P', 648);
INSERT INTO `RelayNames` VALUES(2, 131, 'A', 2510, 3, 'P', 645);
INSERT INTO `RelayNames` VALUES(18, 131, 'A', 2510, 1, 'P', 646);
INSERT INTO `RelayNames` VALUES(24, 131, 'A', 2510, 2, 'P', 648);
INSERT INTO `RelayNames` VALUES(2, 131, 'A', 2511, 8, 'P', 645);
INSERT INTO `RelayNames` VALUES(18, 131, 'A', 2511, 3, 'P', 646);
INSERT INTO `RelayNames` VALUES(24, 131, 'A', 2511, 6, 'P', 648);
INSERT INTO `RelayNames` VALUES(2, 131, 'A', 2512, 7, 'P', 645);
INSERT INTO `RelayNames` VALUES(24, 131, 'A', 2512, 5, 'P', 648);
INSERT INTO `RelayNames` VALUES(18, 131, 'A', 2513, 7, 'P', 646);
INSERT INTO `RelayNames` VALUES(2, 131, 'A', 2514, 6, 'P', 645);
INSERT INTO `RelayNames` VALUES(18, 131, 'A', 2514, 2, 'P', 646);
INSERT INTO `RelayNames` VALUES(24, 131, 'A', 2514, 8, 'P', 648);
INSERT INTO `RelayNames` VALUES(2, 131, 'A', 2515, 5, 'P', 645);
INSERT INTO `RelayNames` VALUES(18, 131, 'A', 2515, 6, 'P', 646);
INSERT INTO `RelayNames` VALUES(24, 131, 'A', 2515, 7, 'P', 648);
INSERT INTO `RelayNames` VALUES(1, 131, 'A', 2516, 3, 'P', 643);
INSERT INTO `RelayNames` VALUES(17, 131, 'A', 2516, 4, 'P', 644);
INSERT INTO `RelayNames` VALUES(23, 131, 'A', 2516, 4, 'P', 647);
INSERT INTO `RelayNames` VALUES(1, 131, 'A', 2518, 4, 'P', 643);
INSERT INTO `RelayNames` VALUES(17, 131, 'A', 2518, 1, 'P', 644);
INSERT INTO `RelayNames` VALUES(23, 131, 'A', 2518, 1, 'P', 647);
INSERT INTO `RelayNames` VALUES(2, 131, 'A', 2519, 4, 'P', 645);
INSERT INTO `RelayNames` VALUES(18, 131, 'A', 2519, 4, 'P', 646);
INSERT INTO `RelayNames` VALUES(24, 131, 'A', 2519, 4, 'P', 648);
INSERT INTO `RelayNames` VALUES(2, 131, 'A', 2520, 1, 'P', 645);
INSERT INTO `RelayNames` VALUES(18, 131, 'A', 2520, 5, 'P', 646);
INSERT INTO `RelayNames` VALUES(24, 131, 'A', 2520, 1, 'P', 648);
INSERT INTO `RelayNames` VALUES(18, 132, 'A', 2521, 1, 'P', 649);
INSERT INTO `RelayNames` VALUES(18, 132, 'A', 2522, 2, 'P', 649);
INSERT INTO `RelayNames` VALUES(18, 132, 'A', 2523, 8, 'P', 649);
INSERT INTO `RelayNames` VALUES(18, 132, 'A', 2524, 4, 'P', 649);
INSERT INTO `RelayNames` VALUES(18, 132, 'A', 2525, 5, 'P', 649);
INSERT INTO `RelayNames` VALUES(18, 132, 'A', 2526, 6, 'P', 649);
INSERT INTO `RelayNames` VALUES(18, 132, 'A', 2527, 7, 'P', 649);
INSERT INTO `RelayNames` VALUES(18, 132, 'A', 2529, 3, 'P', 649);
INSERT INTO `RelayNames` VALUES(17, 133, 'A', 2530, 8, 'P', 655);
INSERT INTO `RelayNames` VALUES(1, 133, 'A', 2531, 5, 'P', 654);
INSERT INTO `RelayNames` VALUES(17, 133, 'A', 2531, 6, 'P', 655);
INSERT INTO `RelayNames` VALUES(23, 133, 'A', 2531, 6, 'P', 656);
INSERT INTO `RelayNames` VALUES(1, 133, 'A', 2532, 7, 'P', 654);
INSERT INTO `RelayNames` VALUES(17, 133, 'A', 2532, 7, 'P', 655);
INSERT INTO `RelayNames` VALUES(23, 133, 'A', 2532, 7, 'P', 656);
INSERT INTO `RelayNames` VALUES(1, 133, 'A', 2534, 8, 'P', 654);
INSERT INTO `RelayNames` VALUES(17, 133, 'A', 2534, 5, 'P', 655);
INSERT INTO `RelayNames` VALUES(23, 133, 'A', 2534, 3, 'P', 656);
INSERT INTO `RelayNames` VALUES(2, 133, 'A', 2535, 1, 'P', 651);
INSERT INTO `RelayNames` VALUES(18, 133, 'A', 2535, 6, 'P', 652);
INSERT INTO `RelayNames` VALUES(24, 133, 'A', 2535, 3, 'P', 653);
INSERT INTO `RelayNames` VALUES(1, 133, 'A', 2536, 4, 'P', 654);
INSERT INTO `RelayNames` VALUES(17, 133, 'A', 2536, 4, 'P', 655);
INSERT INTO `RelayNames` VALUES(23, 133, 'A', 2536, 4, 'P', 656);
INSERT INTO `RelayNames` VALUES(1, 133, 'A', 2537, 1, 'P', 654);
INSERT INTO `RelayNames` VALUES(17, 133, 'A', 2537, 3, 'P', 655);
INSERT INTO `RelayNames` VALUES(23, 133, 'A', 2537, 2, 'P', 656);
INSERT INTO `RelayNames` VALUES(2, 133, 'A', 2538, 6, 'P', 651);
INSERT INTO `RelayNames` VALUES(18, 133, 'A', 2538, 2, 'P', 652);
INSERT INTO `RelayNames` VALUES(24, 133, 'A', 2538, 4, 'P', 653);
INSERT INTO `RelayNames` VALUES(1, 133, 'A', 2539, 2, 'P', 654);
INSERT INTO `RelayNames` VALUES(17, 133, 'A', 2539, 2, 'P', 655);
INSERT INTO `RelayNames` VALUES(23, 133, 'A', 2539, 8, 'P', 656);
INSERT INTO `RelayNames` VALUES(1, 133, 'A', 2540, 6, 'P', 654);
INSERT INTO `RelayNames` VALUES(23, 133, 'A', 2540, 5, 'P', 656);
INSERT INTO `RelayNames` VALUES(2, 133, 'A', 2541, 4, 'P', 651);
INSERT INTO `RelayNames` VALUES(18, 133, 'A', 2541, 1, 'P', 652);
INSERT INTO `RelayNames` VALUES(24, 133, 'A', 2541, 1, 'P', 653);
INSERT INTO `RelayNames` VALUES(2, 133, 'A', 2543, 7, 'P', 651);
INSERT INTO `RelayNames` VALUES(18, 133, 'A', 2543, 7, 'P', 652);
INSERT INTO `RelayNames` VALUES(24, 133, 'A', 2543, 7, 'P', 653);
INSERT INTO `RelayNames` VALUES(2, 133, 'A', 2544, 5, 'P', 651);
INSERT INTO `RelayNames` VALUES(18, 133, 'A', 2544, 8, 'P', 652);
INSERT INTO `RelayNames` VALUES(24, 133, 'A', 2544, 8, 'P', 653);
INSERT INTO `RelayNames` VALUES(2, 133, 'A', 2545, 3, 'P', 651);
INSERT INTO `RelayNames` VALUES(18, 133, 'A', 2545, 5, 'P', 652);
INSERT INTO `RelayNames` VALUES(24, 133, 'A', 2545, 2, 'P', 653);
INSERT INTO `RelayNames` VALUES(2, 133, 'A', 2546, 8, 'P', 651);
INSERT INTO `RelayNames` VALUES(18, 133, 'A', 2546, 4, 'P', 652);
INSERT INTO `RelayNames` VALUES(24, 133, 'A', 2546, 5, 'P', 653);
INSERT INTO `RelayNames` VALUES(1, 133, 'A', 2547, 3, 'P', 654);
INSERT INTO `RelayNames` VALUES(17, 133, 'A', 2547, 1, 'P', 655);
INSERT INTO `RelayNames` VALUES(23, 133, 'A', 2547, 1, 'P', 656);
INSERT INTO `RelayNames` VALUES(2, 133, 'A', 2549, 2, 'P', 651);
INSERT INTO `RelayNames` VALUES(18, 133, 'A', 2549, 3, 'P', 652);
INSERT INTO `RelayNames` VALUES(24, 133, 'A', 2549, 6, 'P', 653);
INSERT INTO `RelayNames` VALUES(1, 134, 'A', 2550, 6, 'P', 657);
INSERT INTO `RelayNames` VALUES(23, 134, 'A', 2551, 7, 'P', 661);
INSERT INTO `RelayNames` VALUES(1, 134, 'A', 2552, 8, 'P', 657);
INSERT INTO `RelayNames` VALUES(17, 134, 'A', 2552, 5, 'P', 659);
INSERT INTO `RelayNames` VALUES(1, 134, 'A', 2553, 7, 'P', 657);
INSERT INTO `RelayNames` VALUES(17, 134, 'A', 2553, 6, 'P', 659);
INSERT INTO `RelayNames` VALUES(2, 134, 'A', 2554, 8, 'P', 658);
INSERT INTO `RelayNames` VALUES(18, 134, 'A', 2554, 5, 'P', 660);
INSERT INTO `RelayNames` VALUES(24, 134, 'A', 2554, 5, 'P', 662);
INSERT INTO `RelayNames` VALUES(1, 134, 'A', 2555, 3, 'P', 657);
INSERT INTO `RelayNames` VALUES(17, 134, 'A', 2555, 3, 'P', 659);
INSERT INTO `RelayNames` VALUES(23, 134, 'A', 2555, 6, 'P', 661);
INSERT INTO `RelayNames` VALUES(1, 134, 'A', 2556, 5, 'P', 657);
INSERT INTO `RelayNames` VALUES(17, 134, 'A', 2556, 2, 'P', 659);
INSERT INTO `RelayNames` VALUES(23, 134, 'A', 2556, 2, 'P', 661);
INSERT INTO `RelayNames` VALUES(23, 134, 'A', 2557, 8, 'P', 661);
INSERT INTO `RelayNames` VALUES(2, 134, 'A', 2558, 5, 'P', 658);
INSERT INTO `RelayNames` VALUES(24, 134, 'A', 2558, 8, 'P', 662);
INSERT INTO `RelayNames` VALUES(18, 134, 'A', 2559, 8, 'P', 660);
INSERT INTO `RelayNames` VALUES(1, 134, 'A', 2560, 2, 'P', 657);
INSERT INTO `RelayNames` VALUES(17, 134, 'A', 2560, 8, 'P', 659);
INSERT INTO `RelayNames` VALUES(23, 134, 'A', 2560, 3, 'P', 661);
INSERT INTO `RelayNames` VALUES(2, 134, 'A', 2561, 6, 'P', 658);
INSERT INTO `RelayNames` VALUES(18, 134, 'A', 2561, 7, 'P', 660);
INSERT INTO `RelayNames` VALUES(24, 134, 'A', 2561, 7, 'P', 662);
INSERT INTO `RelayNames` VALUES(17, 134, 'A', 2562, 7, 'P', 659);
INSERT INTO `RelayNames` VALUES(23, 134, 'A', 2562, 5, 'P', 661);
INSERT INTO `RelayNames` VALUES(2, 134, 'A', 2563, 1, 'P', 658);
INSERT INTO `RelayNames` VALUES(18, 134, 'A', 2563, 1, 'P', 660);
INSERT INTO `RelayNames` VALUES(24, 134, 'A', 2563, 3, 'P', 662);
INSERT INTO `RelayNames` VALUES(1, 134, 'A', 2564, 4, 'P', 657);
INSERT INTO `RelayNames` VALUES(17, 134, 'A', 2564, 1, 'P', 659);
INSERT INTO `RelayNames` VALUES(23, 134, 'A', 2564, 4, 'P', 661);
INSERT INTO `RelayNames` VALUES(2, 134, 'A', 2565, 3, 'P', 658);
INSERT INTO `RelayNames` VALUES(18, 134, 'A', 2565, 4, 'P', 660);
INSERT INTO `RelayNames` VALUES(24, 134, 'A', 2565, 2, 'P', 662);
INSERT INTO `RelayNames` VALUES(1, 134, 'A', 2566, 1, 'P', 657);
INSERT INTO `RelayNames` VALUES(17, 134, 'A', 2566, 4, 'P', 659);
INSERT INTO `RelayNames` VALUES(23, 134, 'A', 2566, 1, 'P', 661);
INSERT INTO `RelayNames` VALUES(2, 134, 'A', 2567, 4, 'P', 658);
INSERT INTO `RelayNames` VALUES(18, 134, 'A', 2567, 3, 'P', 660);
INSERT INTO `RelayNames` VALUES(24, 134, 'A', 2567, 1, 'P', 662);
INSERT INTO `RelayNames` VALUES(2, 134, 'A', 2569, 7, 'P', 658);
INSERT INTO `RelayNames` VALUES(18, 134, 'A', 2569, 6, 'P', 660);
INSERT INTO `RelayNames` VALUES(24, 134, 'A', 2569, 6, 'P', 662);
INSERT INTO `RelayNames` VALUES(2, 134, 'A', 2570, 2, 'P', 658);
INSERT INTO `RelayNames` VALUES(18, 134, 'A', 2570, 2, 'P', 660);
INSERT INTO `RelayNames` VALUES(24, 134, 'A', 2570, 4, 'P', 662);
INSERT INTO `RelayNames` VALUES(17, 136, 'A', 2572, 6, 'P', 663);
INSERT INTO `RelayNames` VALUES(1, 136, 'A', 2572, 8, 'P', 665);
INSERT INTO `RelayNames` VALUES(17, 136, 'A', 2573, 1, 'P', 663);
INSERT INTO `RelayNames` VALUES(1, 136, 'A', 2573, 2, 'P', 665);
INSERT INTO `RelayNames` VALUES(2, 136, 'A', 2574, 5, 'P', 666);
INSERT INTO `RelayNames` VALUES(18, 136, 'A', 2574, 6, 'P', 667);
INSERT INTO `RelayNames` VALUES(24, 136, 'A', 2574, 6, 'P', 668);
INSERT INTO `RelayNames` VALUES(2, 136, 'A', 2575, 8, 'P', 666);
INSERT INTO `RelayNames` VALUES(18, 136, 'A', 2575, 7, 'P', 667);
INSERT INTO `RelayNames` VALUES(24, 136, 'A', 2575, 7, 'P', 668);
INSERT INTO `RelayNames` VALUES(2, 136, 'A', 2576, 1, 'P', 666);
INSERT INTO `RelayNames` VALUES(18, 136, 'A', 2576, 8, 'P', 667);
INSERT INTO `RelayNames` VALUES(2, 136, 'A', 2577, 3, 'P', 666);
INSERT INTO `RelayNames` VALUES(24, 136, 'A', 2577, 8, 'P', 668);
INSERT INTO `RelayNames` VALUES(17, 136, 'A', 2578, 7, 'P', 663);
INSERT INTO `RelayNames` VALUES(18, 136, 'A', 2579, 1, 'P', 667);
INSERT INTO `RelayNames` VALUES(24, 136, 'A', 2579, 1, 'P', 668);
INSERT INTO `RelayNames` VALUES(17, 136, 'A', 2580, 4, 'P', 663);
INSERT INTO `RelayNames` VALUES(23, 136, 'A', 2580, 3, 'P', 664);
INSERT INTO `RelayNames` VALUES(1, 136, 'A', 2580, 4, 'P', 665);
INSERT INTO `RelayNames` VALUES(17, 136, 'A', 2581, 5, 'P', 663);
INSERT INTO `RelayNames` VALUES(23, 136, 'A', 2581, 1, 'P', 664);
INSERT INTO `RelayNames` VALUES(1, 136, 'A', 2581, 7, 'P', 665);
INSERT INTO `RelayNames` VALUES(23, 136, 'A', 2582, 8, 'P', 664);
INSERT INTO `RelayNames` VALUES(2, 136, 'A', 2583, 4, 'P', 666);
INSERT INTO `RelayNames` VALUES(18, 136, 'A', 2583, 5, 'P', 667);
INSERT INTO `RelayNames` VALUES(24, 136, 'A', 2583, 5, 'P', 668);
INSERT INTO `RelayNames` VALUES(23, 136, 'A', 2584, 4, 'P', 664);
INSERT INTO `RelayNames` VALUES(1, 136, 'A', 2584, 1, 'P', 665);
INSERT INTO `RelayNames` VALUES(17, 136, 'A', 2585, 3, 'P', 663);
INSERT INTO `RelayNames` VALUES(23, 136, 'A', 2585, 6, 'P', 664);
INSERT INTO `RelayNames` VALUES(1, 136, 'A', 2585, 6, 'P', 665);
INSERT INTO `RelayNames` VALUES(23, 136, 'A', 2586, 7, 'P', 664);
INSERT INTO `RelayNames` VALUES(2, 136, 'A', 2587, 2, 'P', 666);
INSERT INTO `RelayNames` VALUES(18, 136, 'A', 2588, 4, 'P', 667);
INSERT INTO `RelayNames` VALUES(24, 136, 'A', 2588, 4, 'P', 668);
INSERT INTO `RelayNames` VALUES(2, 136, 'A', 2589, 7, 'P', 666);
INSERT INTO `RelayNames` VALUES(18, 136, 'A', 2589, 2, 'P', 667);
INSERT INTO `RelayNames` VALUES(24, 136, 'A', 2589, 2, 'P', 668);
INSERT INTO `RelayNames` VALUES(2, 136, 'A', 2590, 6, 'P', 666);
INSERT INTO `RelayNames` VALUES(18, 136, 'A', 2590, 3, 'P', 667);
INSERT INTO `RelayNames` VALUES(24, 136, 'A', 2590, 3, 'P', 668);
INSERT INTO `RelayNames` VALUES(23, 136, 'A', 2591, 2, 'P', 664);
INSERT INTO `RelayNames` VALUES(1, 136, 'A', 2591, 3, 'P', 665);
INSERT INTO `RelayNames` VALUES(17, 136, 'A', 2592, 8, 'P', 663);
INSERT INTO `RelayNames` VALUES(17, 136, 'A', 2593, 2, 'P', 663);
INSERT INTO `RelayNames` VALUES(23, 136, 'A', 2593, 5, 'P', 664);
INSERT INTO `RelayNames` VALUES(1, 136, 'A', 2593, 5, 'P', 665);
INSERT INTO `RelayNames` VALUES(1, 137, 'A', 2603, 2, 'P', 669);
INSERT INTO `RelayNames` VALUES(17, 137, 'A', 2603, 3, 'P', 671);
INSERT INTO `RelayNames` VALUES(17, 137, 'A', 2604, 1, 'P', 671);
INSERT INTO `RelayNames` VALUES(23, 137, 'A', 2604, 6, 'P', 673);
INSERT INTO `RelayNames` VALUES(17, 137, 'A', 2605, 7, 'P', 671);
INSERT INTO `RelayNames` VALUES(23, 137, 'A', 2605, 5, 'P', 673);
INSERT INTO `RelayNames` VALUES(23, 137, 'A', 2606, 7, 'P', 673);
INSERT INTO `RelayNames` VALUES(2, 137, 'A', 2607, 6, 'P', 670);
INSERT INTO `RelayNames` VALUES(18, 137, 'A', 2607, 6, 'P', 672);
INSERT INTO `RelayNames` VALUES(24, 137, 'A', 2607, 7, 'P', 674);
INSERT INTO `RelayNames` VALUES(1, 137, 'A', 2608, 5, 'P', 669);
INSERT INTO `RelayNames` VALUES(1, 137, 'A', 2609, 8, 'P', 669);
INSERT INTO `RelayNames` VALUES(23, 137, 'A', 2610, 8, 'P', 673);
INSERT INTO `RelayNames` VALUES(17, 137, 'A', 2611, 8, 'P', 671);
INSERT INTO `RelayNames` VALUES(2, 137, 'A', 2612, 7, 'P', 670);
INSERT INTO `RelayNames` VALUES(18, 137, 'A', 2612, 7, 'P', 672);
INSERT INTO `RelayNames` VALUES(24, 137, 'A', 2612, 6, 'P', 674);
INSERT INTO `RelayNames` VALUES(1, 137, 'A', 2613, 7, 'P', 669);
INSERT INTO `RelayNames` VALUES(2, 137, 'A', 2614, 8, 'P', 670);
INSERT INTO `RelayNames` VALUES(18, 137, 'A', 2614, 8, 'P', 672);
INSERT INTO `RelayNames` VALUES(24, 137, 'A', 2614, 5, 'P', 674);
INSERT INTO `RelayNames` VALUES(2, 137, 'A', 2615, 1, 'P', 670);
INSERT INTO `RelayNames` VALUES(18, 137, 'A', 2615, 2, 'P', 672);
INSERT INTO `RelayNames` VALUES(24, 137, 'A', 2615, 2, 'P', 674);
INSERT INTO `RelayNames` VALUES(2, 137, 'A', 2616, 2, 'P', 670);
INSERT INTO `RelayNames` VALUES(18, 137, 'A', 2616, 3, 'P', 672);
INSERT INTO `RelayNames` VALUES(24, 137, 'A', 2616, 3, 'P', 674);
INSERT INTO `RelayNames` VALUES(2, 137, 'A', 2617, 4, 'P', 670);
INSERT INTO `RelayNames` VALUES(18, 137, 'A', 2617, 4, 'P', 672);
INSERT INTO `RelayNames` VALUES(24, 137, 'A', 2617, 4, 'P', 674);
INSERT INTO `RelayNames` VALUES(2, 137, 'A', 2618, 5, 'P', 670);
INSERT INTO `RelayNames` VALUES(18, 137, 'A', 2618, 5, 'P', 672);
INSERT INTO `RelayNames` VALUES(24, 137, 'A', 2618, 8, 'P', 674);
INSERT INTO `RelayNames` VALUES(1, 137, 'A', 2619, 1, 'P', 669);
INSERT INTO `RelayNames` VALUES(17, 137, 'A', 2619, 6, 'P', 671);
INSERT INTO `RelayNames` VALUES(23, 137, 'A', 2619, 1, 'P', 673);
INSERT INTO `RelayNames` VALUES(2, 137, 'A', 2620, 3, 'P', 670);
INSERT INTO `RelayNames` VALUES(18, 137, 'A', 2620, 1, 'P', 672);
INSERT INTO `RelayNames` VALUES(24, 137, 'A', 2620, 1, 'P', 674);
INSERT INTO `RelayNames` VALUES(1, 137, 'A', 2621, 4, 'P', 669);
INSERT INTO `RelayNames` VALUES(17, 137, 'A', 2621, 4, 'P', 671);
INSERT INTO `RelayNames` VALUES(23, 137, 'A', 2621, 3, 'P', 673);
INSERT INTO `RelayNames` VALUES(1, 137, 'A', 2622, 6, 'P', 669);
INSERT INTO `RelayNames` VALUES(17, 137, 'A', 2622, 2, 'P', 671);
INSERT INTO `RelayNames` VALUES(23, 137, 'A', 2622, 2, 'P', 673);
INSERT INTO `RelayNames` VALUES(1, 137, 'A', 2623, 3, 'P', 669);
INSERT INTO `RelayNames` VALUES(17, 137, 'A', 2623, 5, 'P', 671);
INSERT INTO `RelayNames` VALUES(23, 137, 'A', 2623, 4, 'P', 673);
INSERT INTO `RelayNames` VALUES(2, 138, 'A', 2624, 1, 'P', 676);
INSERT INTO `RelayNames` VALUES(18, 138, 'A', 2624, 2, 'P', 678);
INSERT INTO `RelayNames` VALUES(24, 138, 'A', 2624, 1, 'P', 680);
INSERT INTO `RelayNames` VALUES(1, 138, 'A', 2625, 6, 'P', 675);
INSERT INTO `RelayNames` VALUES(17, 138, 'A', 2625, 3, 'P', 677);
INSERT INTO `RelayNames` VALUES(23, 138, 'A', 2625, 6, 'P', 679);
INSERT INTO `RelayNames` VALUES(2, 138, 'A', 2626, 6, 'P', 676);
INSERT INTO `RelayNames` VALUES(18, 138, 'A', 2626, 5, 'P', 678);
INSERT INTO `RelayNames` VALUES(24, 138, 'A', 2626, 6, 'P', 680);
INSERT INTO `RelayNames` VALUES(1, 138, 'A', 2627, 7, 'P', 675);
INSERT INTO `RelayNames` VALUES(17, 138, 'A', 2627, 2, 'P', 677);
INSERT INTO `RelayNames` VALUES(23, 138, 'A', 2627, 7, 'P', 679);
INSERT INTO `RelayNames` VALUES(2, 138, 'A', 2628, 5, 'P', 676);
INSERT INTO `RelayNames` VALUES(18, 138, 'A', 2628, 1, 'P', 678);
INSERT INTO `RelayNames` VALUES(24, 138, 'A', 2628, 5, 'P', 680);
INSERT INTO `RelayNames` VALUES(2, 138, 'A', 2629, 2, 'P', 676);
INSERT INTO `RelayNames` VALUES(18, 138, 'A', 2629, 6, 'P', 678);
INSERT INTO `RelayNames` VALUES(24, 138, 'A', 2629, 4, 'P', 680);
INSERT INTO `RelayNames` VALUES(1, 138, 'A', 2630, 2, 'P', 675);
INSERT INTO `RelayNames` VALUES(17, 138, 'A', 2630, 1, 'P', 677);
INSERT INTO `RelayNames` VALUES(23, 138, 'A', 2630, 5, 'P', 679);
INSERT INTO `RelayNames` VALUES(1, 138, 'A', 2631, 3, 'P', 675);
INSERT INTO `RelayNames` VALUES(17, 138, 'A', 2631, 4, 'P', 677);
INSERT INTO `RelayNames` VALUES(23, 138, 'A', 2631, 4, 'P', 679);
INSERT INTO `RelayNames` VALUES(1, 138, 'A', 2632, 4, 'P', 675);
INSERT INTO `RelayNames` VALUES(17, 138, 'A', 2632, 5, 'P', 677);
INSERT INTO `RelayNames` VALUES(23, 138, 'A', 2632, 1, 'P', 679);
INSERT INTO `RelayNames` VALUES(1, 138, 'A', 2633, 5, 'P', 675);
INSERT INTO `RelayNames` VALUES(17, 138, 'A', 2633, 7, 'P', 677);
INSERT INTO `RelayNames` VALUES(23, 138, 'A', 2633, 2, 'P', 679);
INSERT INTO `RelayNames` VALUES(2, 138, 'A', 2634, 3, 'P', 676);
INSERT INTO `RelayNames` VALUES(18, 138, 'A', 2634, 3, 'P', 678);
INSERT INTO `RelayNames` VALUES(24, 138, 'A', 2634, 2, 'P', 680);
INSERT INTO `RelayNames` VALUES(2, 138, 'A', 2635, 4, 'P', 676);
INSERT INTO `RelayNames` VALUES(18, 138, 'A', 2635, 4, 'P', 678);
INSERT INTO `RelayNames` VALUES(24, 138, 'A', 2635, 3, 'P', 680);
INSERT INTO `RelayNames` VALUES(1, 138, 'A', 2636, 1, 'P', 675);
INSERT INTO `RelayNames` VALUES(17, 138, 'A', 2636, 6, 'P', 677);
INSERT INTO `RelayNames` VALUES(23, 138, 'A', 2636, 3, 'P', 679);
INSERT INTO `RelayNames` VALUES(2, 139, 'A', 2637, 4, 'P', 684);
INSERT INTO `RelayNames` VALUES(18, 139, 'A', 2637, 3, 'P', 686);
INSERT INTO `RelayNames` VALUES(1, 139, 'A', 2638, 3, 'P', 681);
INSERT INTO `RelayNames` VALUES(17, 139, 'A', 2638, 1, 'P', 685);
INSERT INTO `RelayNames` VALUES(1, 139, 'A', 2639, 4, 'P', 681);
INSERT INTO `RelayNames` VALUES(17, 139, 'A', 2639, 4, 'P', 685);
INSERT INTO `RelayNames` VALUES(1, 139, 'A', 2640, 1, 'P', 681);
INSERT INTO `RelayNames` VALUES(17, 139, 'A', 2640, 2, 'P', 685);
INSERT INTO `RelayNames` VALUES(1, 139, 'A', 2641, 2, 'P', 681);
INSERT INTO `RelayNames` VALUES(17, 139, 'A', 2641, 3, 'P', 685);
INSERT INTO `RelayNames` VALUES(2, 139, 'A', 2643, 3, 'P', 684);
INSERT INTO `RelayNames` VALUES(18, 139, 'A', 2643, 1, 'P', 686);
INSERT INTO `RelayNames` VALUES(2, 139, 'A', 2645, 1, 'P', 684);
INSERT INTO `RelayNames` VALUES(18, 139, 'A', 2645, 4, 'P', 686);
INSERT INTO `RelayNames` VALUES(2, 139, 'A', 2646, 2, 'P', 684);
INSERT INTO `RelayNames` VALUES(18, 139, 'A', 2646, 2, 'P', 686);
INSERT INTO `RelayNames` VALUES(17, 140, 'A', 2647, 1, 'P', 687);
INSERT INTO `RelayNames` VALUES(17, 140, 'A', 2648, 3, 'P', 687);
INSERT INTO `RelayNames` VALUES(17, 140, 'A', 2649, 4, 'P', 687);
INSERT INTO `RelayNames` VALUES(17, 140, 'A', 2650, 5, 'P', 687);
INSERT INTO `RelayNames` VALUES(17, 140, 'A', 2651, 2, 'P', 687);
INSERT INTO `RelayNames` VALUES(2, 141, 'A', 2653, 6, 'P', 688);
INSERT INTO `RelayNames` VALUES(23, 141, 'A', 2654, 8, 'P', 693);
INSERT INTO `RelayNames` VALUES(2, 141, 'A', 2655, 7, 'P', 688);
INSERT INTO `RelayNames` VALUES(17, 141, 'A', 2656, 8, 'P', 692);
INSERT INTO `RelayNames` VALUES(1, 141, 'A', 2657, 7, 'P', 691);
INSERT INTO `RelayNames` VALUES(2, 141, 'A', 2658, 8, 'P', 688);
INSERT INTO `RelayNames` VALUES(17, 141, 'A', 2659, 7, 'P', 692);
INSERT INTO `RelayNames` VALUES(18, 141, 'A', 2660, 8, 'P', 689);
INSERT INTO `RelayNames` VALUES(23, 141, 'A', 2661, 6, 'P', 693);
INSERT INTO `RelayNames` VALUES(18, 141, 'A', 2662, 1, 'P', 689);
INSERT INTO `RelayNames` VALUES(24, 141, 'A', 2662, 7, 'P', 690);
INSERT INTO `RelayNames` VALUES(17, 141, 'A', 2663, 1, 'P', 692);
INSERT INTO `RelayNames` VALUES(23, 141, 'A', 2663, 4, 'P', 693);
INSERT INTO `RelayNames` VALUES(2, 141, 'A', 2664, 1, 'P', 688);
INSERT INTO `RelayNames` VALUES(18, 141, 'A', 2664, 3, 'P', 689);
INSERT INTO `RelayNames` VALUES(1, 141, 'A', 2666, 5, 'P', 691);
INSERT INTO `RelayNames` VALUES(17, 141, 'A', 2666, 5, 'P', 692);
INSERT INTO `RelayNames` VALUES(23, 141, 'A', 2667, 7, 'P', 693);
INSERT INTO `RelayNames` VALUES(2, 141, 'A', 2668, 5, 'P', 688);
INSERT INTO `RelayNames` VALUES(18, 141, 'A', 2668, 5, 'P', 689);
INSERT INTO `RelayNames` VALUES(24, 141, 'A', 2668, 2, 'P', 690);
INSERT INTO `RelayNames` VALUES(1, 141, 'A', 2671, 6, 'P', 691);
INSERT INTO `RelayNames` VALUES(18, 141, 'A', 2672, 4, 'P', 689);
INSERT INTO `RelayNames` VALUES(24, 141, 'A', 2672, 6, 'P', 690);
INSERT INTO `RelayNames` VALUES(1, 141, 'A', 2673, 4, 'P', 691);
INSERT INTO `RelayNames` VALUES(17, 141, 'A', 2673, 4, 'P', 692);
INSERT INTO `RelayNames` VALUES(23, 141, 'A', 2673, 3, 'P', 693);
INSERT INTO `RelayNames` VALUES(18, 141, 'A', 2675, 6, 'P', 689);
INSERT INTO `RelayNames` VALUES(24, 141, 'A', 2675, 3, 'P', 690);
INSERT INTO `RelayNames` VALUES(24, 141, 'A', 2676, 8, 'P', 690);
INSERT INTO `RelayNames` VALUES(1, 141, 'A', 2677, 1, 'P', 691);
INSERT INTO `RelayNames` VALUES(23, 141, 'A', 2677, 2, 'P', 693);
INSERT INTO `RelayNames` VALUES(2, 141, 'A', 2678, 3, 'P', 688);
INSERT INTO `RelayNames` VALUES(1, 141, 'A', 2680, 8, 'P', 691);
INSERT INTO `RelayNames` VALUES(2, 141, 'A', 2681, 4, 'P', 688);
INSERT INTO `RelayNames` VALUES(18, 141, 'A', 2681, 7, 'P', 689);
INSERT INTO `RelayNames` VALUES(24, 141, 'A', 2681, 1, 'P', 690);
INSERT INTO `RelayNames` VALUES(2, 141, 'A', 2683, 2, 'P', 688);
INSERT INTO `RelayNames` VALUES(24, 141, 'A', 2683, 5, 'P', 690);
INSERT INTO `RelayNames` VALUES(1, 141, 'A', 2685, 2, 'P', 691);
INSERT INTO `RelayNames` VALUES(17, 141, 'A', 2685, 2, 'P', 692);
INSERT INTO `RelayNames` VALUES(18, 141, 'A', 2686, 2, 'P', 689);
INSERT INTO `RelayNames` VALUES(24, 141, 'A', 2686, 4, 'P', 690);
INSERT INTO `RelayNames` VALUES(17, 141, 'A', 2687, 3, 'P', 692);
INSERT INTO `RelayNames` VALUES(23, 141, 'A', 2687, 5, 'P', 693);
INSERT INTO `RelayNames` VALUES(1, 141, 'A', 2688, 3, 'P', 691);
INSERT INTO `RelayNames` VALUES(23, 141, 'A', 2688, 1, 'P', 693);
INSERT INTO `RelayNames` VALUES(17, 141, 'A', 2689, 6, 'P', 692);
INSERT INTO `RelayNames` VALUES(1, 142, 'A', 2690, 5, 'P', 694);
INSERT INTO `RelayNames` VALUES(17, 142, 'A', 2690, 6, 'P', 696);
INSERT INTO `RelayNames` VALUES(23, 142, 'A', 2690, 3, 'P', 698);
INSERT INTO `RelayNames` VALUES(23, 142, 'A', 2691, 5, 'P', 698);
INSERT INTO `RelayNames` VALUES(2, 142, 'A', 2692, 5, 'P', 695);
INSERT INTO `RelayNames` VALUES(18, 142, 'A', 2692, 5, 'P', 697);
INSERT INTO `RelayNames` VALUES(24, 142, 'A', 2692, 2, 'P', 699);
INSERT INTO `RelayNames` VALUES(2, 142, 'A', 2693, 4, 'P', 695);
INSERT INTO `RelayNames` VALUES(18, 142, 'A', 2693, 3, 'P', 697);
INSERT INTO `RelayNames` VALUES(24, 142, 'A', 2693, 3, 'P', 699);
INSERT INTO `RelayNames` VALUES(1, 142, 'A', 2694, 4, 'P', 694);
INSERT INTO `RelayNames` VALUES(17, 142, 'A', 2694, 3, 'P', 696);
INSERT INTO `RelayNames` VALUES(23, 142, 'A', 2694, 2, 'P', 698);
INSERT INTO `RelayNames` VALUES(1, 142, 'A', 2695, 6, 'P', 694);
INSERT INTO `RelayNames` VALUES(17, 142, 'A', 2695, 1, 'P', 696);
INSERT INTO `RelayNames` VALUES(23, 142, 'A', 2695, 4, 'P', 698);
INSERT INTO `RelayNames` VALUES(2, 142, 'A', 2698, 3, 'P', 695);
INSERT INTO `RelayNames` VALUES(18, 142, 'A', 2698, 1, 'P', 697);
INSERT INTO `RelayNames` VALUES(24, 142, 'A', 2698, 1, 'P', 699);
INSERT INTO `RelayNames` VALUES(1, 142, 'A', 2699, 3, 'P', 694);
INSERT INTO `RelayNames` VALUES(17, 142, 'A', 2699, 2, 'P', 696);
INSERT INTO `RelayNames` VALUES(23, 142, 'A', 2699, 6, 'P', 698);
INSERT INTO `RelayNames` VALUES(2, 142, 'A', 2701, 1, 'P', 695);
INSERT INTO `RelayNames` VALUES(18, 142, 'A', 2701, 2, 'P', 697);
INSERT INTO `RelayNames` VALUES(24, 142, 'A', 2701, 5, 'P', 699);
INSERT INTO `RelayNames` VALUES(2, 142, 'A', 2702, 2, 'P', 695);
INSERT INTO `RelayNames` VALUES(18, 142, 'A', 2702, 4, 'P', 697);
INSERT INTO `RelayNames` VALUES(24, 142, 'A', 2702, 4, 'P', 699);
INSERT INTO `RelayNames` VALUES(1, 142, 'A', 2703, 2, 'P', 694);
INSERT INTO `RelayNames` VALUES(17, 142, 'A', 2703, 5, 'P', 696);
INSERT INTO `RelayNames` VALUES(23, 142, 'A', 2703, 1, 'P', 698);
INSERT INTO `RelayNames` VALUES(1, 142, 'A', 2704, 1, 'P', 694);
INSERT INTO `RelayNames` VALUES(17, 142, 'A', 2704, 4, 'P', 696);
INSERT INTO `RelayNames` VALUES(24, 143, 'A', 2705, 7, 'P', 702);
INSERT INTO `RelayNames` VALUES(24, 143, 'A', 2706, 8, 'P', 702);
INSERT INTO `RelayNames` VALUES(2, 143, 'A', 2707, 8, 'P', 700);
INSERT INTO `RelayNames` VALUES(2, 143, 'A', 2709, 4, 'P', 700);
INSERT INTO `RelayNames` VALUES(18, 143, 'A', 2709, 4, 'P', 701);
INSERT INTO `RelayNames` VALUES(18, 143, 'A', 2710, 6, 'P', 701);
INSERT INTO `RelayNames` VALUES(2, 143, 'A', 2711, 3, 'P', 700);
INSERT INTO `RelayNames` VALUES(18, 143, 'A', 2711, 5, 'P', 701);
INSERT INTO `RelayNames` VALUES(24, 143, 'A', 2711, 5, 'P', 702);
INSERT INTO `RelayNames` VALUES(2, 143, 'A', 2712, 7, 'P', 700);
INSERT INTO `RelayNames` VALUES(18, 143, 'A', 2713, 3, 'P', 701);
INSERT INTO `RelayNames` VALUES(24, 143, 'A', 2713, 3, 'P', 702);
INSERT INTO `RelayNames` VALUES(18, 143, 'A', 2714, 2, 'P', 701);
INSERT INTO `RelayNames` VALUES(24, 143, 'A', 2714, 2, 'P', 702);
INSERT INTO `RelayNames` VALUES(18, 143, 'A', 2715, 1, 'P', 701);
INSERT INTO `RelayNames` VALUES(24, 143, 'A', 2715, 1, 'P', 702);
INSERT INTO `RelayNames` VALUES(2, 143, 'A', 2716, 1, 'P', 700);
INSERT INTO `RelayNames` VALUES(18, 143, 'A', 2716, 7, 'P', 701);
INSERT INTO `RelayNames` VALUES(24, 143, 'A', 2716, 4, 'P', 702);
INSERT INTO `RelayNames` VALUES(2, 143, 'A', 2717, 2, 'P', 700);
INSERT INTO `RelayNames` VALUES(18, 143, 'A', 2719, 8, 'P', 701);
INSERT INTO `RelayNames` VALUES(24, 143, 'A', 2720, 6, 'P', 702);
INSERT INTO `RelayNames` VALUES(2, 143, 'A', 2722, 6, 'P', 700);
INSERT INTO `RelayNames` VALUES(2, 143, 'A', 2723, 5, 'P', 700);
INSERT INTO `RelayNames` VALUES(1, 144, 'A', 2724, 8, 'P', 703);
INSERT INTO `RelayNames` VALUES(17, 144, 'A', 2724, 8, 'P', 707);
INSERT INTO `RelayNames` VALUES(23, 144, 'A', 2724, 8, 'P', 708);
INSERT INTO `RelayNames` VALUES(1, 144, 'A', 2725, 6, 'P', 703);
INSERT INTO `RelayNames` VALUES(17, 144, 'A', 2725, 7, 'P', 707);
INSERT INTO `RelayNames` VALUES(23, 144, 'A', 2725, 7, 'P', 708);
INSERT INTO `RelayNames` VALUES(1, 144, 'A', 2726, 1, 'P', 703);
INSERT INTO `RelayNames` VALUES(17, 144, 'A', 2726, 1, 'P', 707);
INSERT INTO `RelayNames` VALUES(23, 144, 'A', 2726, 1, 'P', 708);
INSERT INTO `RelayNames` VALUES(1, 144, 'A', 2727, 3, 'P', 703);
INSERT INTO `RelayNames` VALUES(17, 144, 'A', 2727, 2, 'P', 707);
INSERT INTO `RelayNames` VALUES(23, 144, 'A', 2727, 5, 'P', 708);
INSERT INTO `RelayNames` VALUES(1, 144, 'A', 2728, 4, 'P', 703);
INSERT INTO `RelayNames` VALUES(17, 144, 'A', 2728, 4, 'P', 707);
INSERT INTO `RelayNames` VALUES(23, 144, 'A', 2728, 3, 'P', 708);
INSERT INTO `RelayNames` VALUES(1, 144, 'A', 2729, 5, 'P', 703);
INSERT INTO `RelayNames` VALUES(17, 144, 'A', 2729, 3, 'P', 707);
INSERT INTO `RelayNames` VALUES(23, 144, 'A', 2729, 2, 'P', 708);
INSERT INTO `RelayNames` VALUES(1, 144, 'A', 2730, 2, 'P', 703);
INSERT INTO `RelayNames` VALUES(17, 144, 'A', 2730, 6, 'P', 707);
INSERT INTO `RelayNames` VALUES(23, 144, 'A', 2730, 4, 'P', 708);
INSERT INTO `RelayNames` VALUES(1, 144, 'A', 2731, 7, 'P', 703);
INSERT INTO `RelayNames` VALUES(17, 144, 'A', 2731, 5, 'P', 707);
INSERT INTO `RelayNames` VALUES(23, 144, 'A', 2731, 6, 'P', 708);
INSERT INTO `RelayNames` VALUES(1, 145, 'A', 2733, 7, 'P', 709);
INSERT INTO `RelayNames` VALUES(17, 145, 'A', 2734, 7, 'P', 712);
INSERT INTO `RelayNames` VALUES(23, 145, 'A', 2735, 7, 'P', 713);
INSERT INTO `RelayNames` VALUES(1, 145, 'A', 2736, 5, 'P', 709);
INSERT INTO `RelayNames` VALUES(17, 145, 'A', 2736, 1, 'P', 712);
INSERT INTO `RelayNames` VALUES(23, 145, 'A', 2736, 3, 'P', 713);
INSERT INTO `RelayNames` VALUES(1, 145, 'A', 2737, 1, 'P', 709);
INSERT INTO `RelayNames` VALUES(17, 145, 'A', 2737, 5, 'P', 712);
INSERT INTO `RelayNames` VALUES(23, 145, 'A', 2737, 4, 'P', 713);
INSERT INTO `RelayNames` VALUES(1, 145, 'A', 2739, 6, 'P', 709);
INSERT INTO `RelayNames` VALUES(23, 145, 'A', 2741, 8, 'P', 713);
INSERT INTO `RelayNames` VALUES(1, 145, 'A', 2742, 4, 'P', 709);
INSERT INTO `RelayNames` VALUES(17, 145, 'A', 2742, 4, 'P', 712);
INSERT INTO `RelayNames` VALUES(23, 145, 'A', 2742, 5, 'P', 713);
INSERT INTO `RelayNames` VALUES(23, 145, 'A', 2743, 6, 'P', 713);
INSERT INTO `RelayNames` VALUES(17, 145, 'A', 2744, 8, 'P', 712);
INSERT INTO `RelayNames` VALUES(1, 145, 'A', 2745, 8, 'P', 709);
INSERT INTO `RelayNames` VALUES(17, 145, 'A', 2746, 3, 'P', 712);
INSERT INTO `RelayNames` VALUES(23, 145, 'A', 2746, 1, 'P', 713);
INSERT INTO `RelayNames` VALUES(1, 145, 'A', 2747, 2, 'P', 709);
INSERT INTO `RelayNames` VALUES(17, 145, 'A', 2750, 6, 'P', 712);
INSERT INTO `RelayNames` VALUES(17, 145, 'A', 2752, 2, 'P', 712);
INSERT INTO `RelayNames` VALUES(23, 145, 'A', 2752, 2, 'P', 713);
INSERT INTO `RelayNames` VALUES(1, 145, 'A', 2754, 3, 'P', 709);
INSERT INTO `RelayNames` VALUES(23, 146, 'A', 2757, 5, 'P', 717);
INSERT INTO `RelayNames` VALUES(1, 146, 'A', 2757, 3, 'P', 718);
INSERT INTO `RelayNames` VALUES(17, 146, 'A', 2757, 7, 'P', 719);
INSERT INTO `RelayNames` VALUES(24, 146, 'A', 2758, 2, 'P', 714);
INSERT INTO `RelayNames` VALUES(2, 146, 'A', 2758, 5, 'P', 715);
INSERT INTO `RelayNames` VALUES(18, 146, 'A', 2758, 5, 'P', 716);
INSERT INTO `RelayNames` VALUES(24, 146, 'A', 2759, 6, 'P', 714);
INSERT INTO `RelayNames` VALUES(2, 146, 'A', 2759, 6, 'P', 715);
INSERT INTO `RelayNames` VALUES(18, 146, 'A', 2759, 6, 'P', 716);
INSERT INTO `RelayNames` VALUES(24, 146, 'A', 2760, 3, 'P', 714);
INSERT INTO `RelayNames` VALUES(2, 146, 'A', 2760, 4, 'P', 715);
INSERT INTO `RelayNames` VALUES(18, 146, 'A', 2760, 2, 'P', 716);
INSERT INTO `RelayNames` VALUES(23, 146, 'A', 2761, 4, 'P', 717);
INSERT INTO `RelayNames` VALUES(1, 146, 'A', 2761, 7, 'P', 718);
INSERT INTO `RelayNames` VALUES(17, 146, 'A', 2761, 5, 'P', 719);
INSERT INTO `RelayNames` VALUES(23, 146, 'A', 2762, 6, 'P', 717);
INSERT INTO `RelayNames` VALUES(1, 146, 'A', 2762, 2, 'P', 718);
INSERT INTO `RelayNames` VALUES(17, 146, 'A', 2762, 6, 'P', 719);
INSERT INTO `RelayNames` VALUES(24, 146, 'A', 2763, 7, 'P', 714);
INSERT INTO `RelayNames` VALUES(2, 146, 'A', 2763, 7, 'P', 715);
INSERT INTO `RelayNames` VALUES(18, 146, 'A', 2763, 7, 'P', 716);
INSERT INTO `RelayNames` VALUES(24, 146, 'A', 2764, 4, 'P', 714);
INSERT INTO `RelayNames` VALUES(2, 146, 'A', 2764, 2, 'P', 715);
INSERT INTO `RelayNames` VALUES(18, 146, 'A', 2764, 3, 'P', 716);
INSERT INTO `RelayNames` VALUES(23, 146, 'A', 2765, 7, 'P', 717);
INSERT INTO `RelayNames` VALUES(1, 146, 'A', 2765, 5, 'P', 718);
INSERT INTO `RelayNames` VALUES(17, 146, 'A', 2765, 4, 'P', 719);
INSERT INTO `RelayNames` VALUES(24, 146, 'A', 2766, 1, 'P', 714);
INSERT INTO `RelayNames` VALUES(2, 146, 'A', 2766, 1, 'P', 715);
INSERT INTO `RelayNames` VALUES(18, 146, 'A', 2766, 4, 'P', 716);
INSERT INTO `RelayNames` VALUES(23, 146, 'A', 2768, 2, 'P', 717);
INSERT INTO `RelayNames` VALUES(1, 146, 'A', 2768, 4, 'P', 718);
INSERT INTO `RelayNames` VALUES(17, 146, 'A', 2768, 2, 'P', 719);
INSERT INTO `RelayNames` VALUES(23, 146, 'A', 2769, 1, 'P', 717);
INSERT INTO `RelayNames` VALUES(1, 146, 'A', 2769, 1, 'P', 718);
INSERT INTO `RelayNames` VALUES(17, 146, 'A', 2769, 1, 'P', 719);
INSERT INTO `RelayNames` VALUES(23, 146, 'A', 2770, 3, 'P', 717);
INSERT INTO `RelayNames` VALUES(1, 146, 'A', 2770, 6, 'P', 718);
INSERT INTO `RelayNames` VALUES(17, 146, 'A', 2770, 3, 'P', 719);
INSERT INTO `RelayNames` VALUES(24, 146, 'A', 2771, 5, 'P', 714);
INSERT INTO `RelayNames` VALUES(2, 146, 'A', 2771, 3, 'P', 715);
INSERT INTO `RelayNames` VALUES(18, 146, 'A', 2771, 1, 'P', 716);
INSERT INTO `RelayNames` VALUES(17, 147, 'A', 2772, 3, 'P', 721);
INSERT INTO `RelayNames` VALUES(17, 147, 'A', 2773, 4, 'P', 721);
INSERT INTO `RelayNames` VALUES(2, 147, 'A', 2774, 6, 'P', 720);
INSERT INTO `RelayNames` VALUES(18, 147, 'A', 2774, 7, 'P', 722);
INSERT INTO `RelayNames` VALUES(24, 147, 'A', 2774, 7, 'P', 723);
INSERT INTO `RelayNames` VALUES(2, 147, 'A', 2775, 5, 'P', 720);
INSERT INTO `RelayNames` VALUES(18, 147, 'A', 2775, 5, 'P', 722);
INSERT INTO `RelayNames` VALUES(24, 147, 'A', 2775, 5, 'P', 723);
INSERT INTO `RelayNames` VALUES(2, 147, 'A', 2776, 7, 'P', 720);
INSERT INTO `RelayNames` VALUES(18, 147, 'A', 2776, 6, 'P', 722);
INSERT INTO `RelayNames` VALUES(24, 147, 'A', 2776, 6, 'P', 723);
INSERT INTO `RelayNames` VALUES(17, 147, 'A', 2777, 1, 'P', 721);
INSERT INTO `RelayNames` VALUES(17, 147, 'A', 2778, 2, 'P', 721);
INSERT INTO `RelayNames` VALUES(17, 147, 'A', 2779, 6, 'P', 721);
INSERT INTO `RelayNames` VALUES(17, 147, 'A', 2780, 5, 'P', 721);
INSERT INTO `RelayNames` VALUES(17, 147, 'A', 2781, 7, 'P', 721);
INSERT INTO `RelayNames` VALUES(2, 147, 'A', 2782, 1, 'P', 720);
INSERT INTO `RelayNames` VALUES(18, 147, 'A', 2782, 1, 'P', 722);
INSERT INTO `RelayNames` VALUES(24, 147, 'A', 2782, 1, 'P', 723);
INSERT INTO `RelayNames` VALUES(2, 147, 'A', 2783, 4, 'P', 720);
INSERT INTO `RelayNames` VALUES(18, 147, 'A', 2783, 4, 'P', 722);
INSERT INTO `RelayNames` VALUES(24, 147, 'A', 2783, 4, 'P', 723);
INSERT INTO `RelayNames` VALUES(2, 147, 'A', 2784, 3, 'P', 720);
INSERT INTO `RelayNames` VALUES(18, 147, 'A', 2784, 3, 'P', 722);
INSERT INTO `RelayNames` VALUES(24, 147, 'A', 2784, 3, 'P', 723);
INSERT INTO `RelayNames` VALUES(2, 147, 'A', 2785, 2, 'P', 720);
INSERT INTO `RelayNames` VALUES(18, 147, 'A', 2785, 2, 'P', 722);
INSERT INTO `RelayNames` VALUES(24, 147, 'A', 2785, 2, 'P', 723);
INSERT INTO `RelayNames` VALUES(23, 149, 'A', 2786, 1, 'P', 725);
INSERT INTO `RelayNames` VALUES(17, 149, 'A', 2786, 1, 'P', 726);
INSERT INTO `RelayNames` VALUES(23, 149, 'A', 2787, 5, 'P', 725);
INSERT INTO `RelayNames` VALUES(17, 149, 'A', 2787, 5, 'P', 726);
INSERT INTO `RelayNames` VALUES(23, 149, 'A', 2788, 3, 'P', 725);
INSERT INTO `RelayNames` VALUES(17, 149, 'A', 2788, 3, 'P', 726);
INSERT INTO `RelayNames` VALUES(23, 149, 'A', 2789, 2, 'P', 725);
INSERT INTO `RelayNames` VALUES(17, 149, 'A', 2789, 2, 'P', 726);
INSERT INTO `RelayNames` VALUES(23, 149, 'A', 2792, 4, 'P', 725);
INSERT INTO `RelayNames` VALUES(17, 149, 'A', 2792, 4, 'P', 726);
INSERT INTO `RelayNames` VALUES(1, 151, 'A', 2796, 2, 'P', 727);
INSERT INTO `RelayNames` VALUES(17, 151, 'A', 2796, 8, 'P', 729);
INSERT INTO `RelayNames` VALUES(23, 151, 'A', 2796, 6, 'P', 731);
INSERT INTO `RelayNames` VALUES(2, 151, 'A', 2797, 4, 'P', 728);
INSERT INTO `RelayNames` VALUES(18, 151, 'A', 2797, 1, 'P', 730);
INSERT INTO `RelayNames` VALUES(2, 151, 'A', 2798, 1, 'P', 728);
INSERT INTO `RelayNames` VALUES(24, 151, 'A', 2798, 1, 'P', 732);
INSERT INTO `RelayNames` VALUES(1, 151, 'A', 2801, 6, 'P', 727);
INSERT INTO `RelayNames` VALUES(1, 151, 'A', 2802, 5, 'P', 727);
INSERT INTO `RelayNames` VALUES(17, 151, 'A', 2802, 7, 'P', 729);
INSERT INTO `RelayNames` VALUES(17, 151, 'A', 2804, 6, 'P', 729);
INSERT INTO `RelayNames` VALUES(23, 151, 'A', 2804, 7, 'P', 731);
INSERT INTO `RelayNames` VALUES(2, 151, 'A', 2805, 6, 'P', 728);
INSERT INTO `RelayNames` VALUES(18, 151, 'A', 2805, 2, 'P', 730);
INSERT INTO `RelayNames` VALUES(24, 151, 'A', 2805, 6, 'P', 732);
INSERT INTO `RelayNames` VALUES(1, 151, 'A', 2806, 7, 'P', 727);
INSERT INTO `RelayNames` VALUES(17, 151, 'A', 2806, 4, 'P', 729);
INSERT INTO `RelayNames` VALUES(23, 151, 'A', 2806, 2, 'P', 731);
INSERT INTO `RelayNames` VALUES(24, 151, 'A', 2808, 8, 'P', 732);
INSERT INTO `RelayNames` VALUES(1, 151, 'A', 2810, 4, 'P', 727);
INSERT INTO `RelayNames` VALUES(17, 151, 'A', 2810, 5, 'P', 729);
INSERT INTO `RelayNames` VALUES(23, 151, 'A', 2810, 1, 'P', 731);
INSERT INTO `RelayNames` VALUES(2, 151, 'A', 2813, 2, 'P', 728);
INSERT INTO `RelayNames` VALUES(17, 151, 'A', 2814, 3, 'P', 729);
INSERT INTO `RelayNames` VALUES(23, 151, 'A', 2814, 4, 'P', 731);
INSERT INTO `RelayNames` VALUES(24, 151, 'A', 2815, 7, 'P', 732);
INSERT INTO `RelayNames` VALUES(2, 151, 'A', 2816, 7, 'P', 728);
INSERT INTO `RelayNames` VALUES(18, 151, 'A', 2816, 4, 'P', 730);
INSERT INTO `RelayNames` VALUES(24, 151, 'A', 2816, 2, 'P', 732);
INSERT INTO `RelayNames` VALUES(2, 151, 'A', 2819, 8, 'P', 728);
INSERT INTO `RelayNames` VALUES(18, 151, 'A', 2819, 8, 'P', 730);
INSERT INTO `RelayNames` VALUES(2, 151, 'A', 2820, 5, 'P', 728);
INSERT INTO `RelayNames` VALUES(18, 151, 'A', 2820, 3, 'P', 730);
INSERT INTO `RelayNames` VALUES(24, 151, 'A', 2820, 5, 'P', 732);
INSERT INTO `RelayNames` VALUES(1, 151, 'A', 2821, 8, 'P', 727);
INSERT INTO `RelayNames` VALUES(17, 151, 'A', 2821, 2, 'P', 729);
INSERT INTO `RelayNames` VALUES(23, 151, 'A', 2821, 3, 'P', 731);
INSERT INTO `RelayNames` VALUES(23, 151, 'A', 2822, 8, 'P', 731);
INSERT INTO `RelayNames` VALUES(1, 151, 'A', 2823, 1, 'P', 727);
INSERT INTO `RelayNames` VALUES(17, 151, 'A', 2823, 1, 'P', 729);
INSERT INTO `RelayNames` VALUES(18, 151, 'A', 2824, 5, 'P', 730);
INSERT INTO `RelayNames` VALUES(24, 151, 'A', 2824, 3, 'P', 732);
INSERT INTO `RelayNames` VALUES(2, 151, 'A', 2825, 3, 'P', 728);
INSERT INTO `RelayNames` VALUES(18, 151, 'A', 2825, 6, 'P', 730);
INSERT INTO `RelayNames` VALUES(24, 151, 'A', 2825, 4, 'P', 732);
INSERT INTO `RelayNames` VALUES(18, 151, 'A', 2826, 7, 'P', 730);
INSERT INTO `RelayNames` VALUES(1, 151, 'A', 2829, 3, 'P', 727);
INSERT INTO `RelayNames` VALUES(23, 151, 'A', 2829, 5, 'P', 731);
INSERT INTO `RelayNames` VALUES(2, 152, 'A', 2830, 7, 'P', 736);
INSERT INTO `RelayNames` VALUES(18, 152, 'A', 2830, 7, 'P', 737);
INSERT INTO `RelayNames` VALUES(24, 152, 'A', 2830, 7, 'P', 738);
INSERT INTO `RelayNames` VALUES(2, 152, 'A', 2831, 8, 'P', 736);
INSERT INTO `RelayNames` VALUES(18, 152, 'A', 2831, 8, 'P', 737);
INSERT INTO `RelayNames` VALUES(24, 152, 'A', 2831, 8, 'P', 738);
INSERT INTO `RelayNames` VALUES(1, 152, 'A', 2832, 5, 'P', 733);
INSERT INTO `RelayNames` VALUES(17, 152, 'A', 2832, 3, 'P', 734);
INSERT INTO `RelayNames` VALUES(23, 152, 'A', 2832, 6, 'P', 735);
INSERT INTO `RelayNames` VALUES(1, 152, 'A', 2833, 4, 'P', 733);
INSERT INTO `RelayNames` VALUES(17, 152, 'A', 2833, 4, 'P', 734);
INSERT INTO `RelayNames` VALUES(23, 152, 'A', 2833, 1, 'P', 735);
INSERT INTO `RelayNames` VALUES(2, 152, 'A', 2834, 3, 'P', 736);
INSERT INTO `RelayNames` VALUES(18, 152, 'A', 2834, 2, 'P', 737);
INSERT INTO `RelayNames` VALUES(24, 152, 'A', 2834, 6, 'P', 738);
INSERT INTO `RelayNames` VALUES(1, 152, 'A', 2835, 8, 'P', 733);
INSERT INTO `RelayNames` VALUES(17, 152, 'A', 2835, 7, 'P', 734);
INSERT INTO `RelayNames` VALUES(23, 152, 'A', 2835, 2, 'P', 735);
INSERT INTO `RelayNames` VALUES(1, 152, 'A', 2836, 2, 'P', 733);
INSERT INTO `RelayNames` VALUES(17, 152, 'A', 2836, 1, 'P', 734);
INSERT INTO `RelayNames` VALUES(23, 152, 'A', 2836, 8, 'P', 735);
INSERT INTO `RelayNames` VALUES(2, 152, 'A', 2838, 1, 'P', 736);
INSERT INTO `RelayNames` VALUES(18, 152, 'A', 2838, 5, 'P', 737);
INSERT INTO `RelayNames` VALUES(24, 152, 'A', 2838, 4, 'P', 738);
INSERT INTO `RelayNames` VALUES(23, 152, 'A', 2840, 3, 'P', 735);
INSERT INTO `RelayNames` VALUES(2, 152, 'A', 2841, 5, 'P', 736);
INSERT INTO `RelayNames` VALUES(18, 152, 'A', 2841, 3, 'P', 737);
INSERT INTO `RelayNames` VALUES(24, 152, 'A', 2841, 2, 'P', 738);
INSERT INTO `RelayNames` VALUES(1, 152, 'A', 2842, 1, 'P', 733);
INSERT INTO `RelayNames` VALUES(17, 152, 'A', 2842, 5, 'P', 734);
INSERT INTO `RelayNames` VALUES(23, 152, 'A', 2842, 4, 'P', 735);
INSERT INTO `RelayNames` VALUES(1, 152, 'A', 2843, 6, 'P', 733);
INSERT INTO `RelayNames` VALUES(17, 152, 'A', 2843, 8, 'P', 734);
INSERT INTO `RelayNames` VALUES(2, 152, 'A', 2844, 6, 'P', 736);
INSERT INTO `RelayNames` VALUES(18, 152, 'A', 2844, 4, 'P', 737);
INSERT INTO `RelayNames` VALUES(24, 152, 'A', 2844, 1, 'P', 738);
INSERT INTO `RelayNames` VALUES(1, 152, 'A', 2845, 3, 'P', 733);
INSERT INTO `RelayNames` VALUES(17, 152, 'A', 2845, 6, 'P', 734);
INSERT INTO `RelayNames` VALUES(23, 152, 'A', 2846, 5, 'P', 735);
INSERT INTO `RelayNames` VALUES(1, 152, 'A', 2847, 7, 'P', 733);
INSERT INTO `RelayNames` VALUES(17, 152, 'A', 2847, 2, 'P', 734);
INSERT INTO `RelayNames` VALUES(23, 152, 'A', 2847, 7, 'P', 735);
INSERT INTO `RelayNames` VALUES(2, 152, 'A', 2848, 2, 'P', 736);
INSERT INTO `RelayNames` VALUES(18, 152, 'A', 2848, 1, 'P', 737);
INSERT INTO `RelayNames` VALUES(24, 152, 'A', 2848, 5, 'P', 738);
INSERT INTO `RelayNames` VALUES(2, 152, 'A', 2849, 4, 'P', 736);
INSERT INTO `RelayNames` VALUES(18, 152, 'A', 2849, 6, 'P', 737);
INSERT INTO `RelayNames` VALUES(24, 152, 'A', 2849, 3, 'P', 738);
INSERT INTO `RelayNames` VALUES(1, 153, 'A', 2850, 7, 'P', 739);
INSERT INTO `RelayNames` VALUES(17, 153, 'A', 2850, 4, 'P', 742);
INSERT INTO `RelayNames` VALUES(23, 153, 'A', 2850, 5, 'P', 743);
INSERT INTO `RelayNames` VALUES(1, 153, 'A', 2851, 5, 'P', 739);
INSERT INTO `RelayNames` VALUES(2, 153, 'A', 2852, 4, 'P', 740);
INSERT INTO `RelayNames` VALUES(18, 153, 'A', 2852, 3, 'P', 741);
INSERT INTO `RelayNames` VALUES(24, 153, 'A', 2852, 3, 'P', 744);
INSERT INTO `RelayNames` VALUES(17, 153, 'A', 2855, 8, 'P', 742);
INSERT INTO `RelayNames` VALUES(23, 153, 'A', 2855, 6, 'P', 743);
INSERT INTO `RelayNames` VALUES(2, 153, 'A', 2856, 1, 'P', 740);
INSERT INTO `RelayNames` VALUES(18, 153, 'A', 2856, 6, 'P', 741);
INSERT INTO `RelayNames` VALUES(24, 153, 'A', 2856, 4, 'P', 744);
INSERT INTO `RelayNames` VALUES(1, 153, 'A', 2857, 4, 'P', 739);
INSERT INTO `RelayNames` VALUES(17, 153, 'A', 2857, 2, 'P', 742);
INSERT INTO `RelayNames` VALUES(23, 153, 'A', 2857, 3, 'P', 743);
INSERT INTO `RelayNames` VALUES(2, 153, 'A', 2859, 7, 'P', 740);
INSERT INTO `RelayNames` VALUES(18, 153, 'A', 2859, 5, 'P', 741);
INSERT INTO `RelayNames` VALUES(24, 153, 'A', 2859, 7, 'P', 744);
INSERT INTO `RelayNames` VALUES(2, 153, 'A', 2860, 3, 'P', 740);
INSERT INTO `RelayNames` VALUES(18, 153, 'A', 2860, 8, 'P', 741);
INSERT INTO `RelayNames` VALUES(24, 153, 'A', 2860, 5, 'P', 744);
INSERT INTO `RelayNames` VALUES(2, 153, 'A', 2861, 8, 'P', 740);
INSERT INTO `RelayNames` VALUES(18, 153, 'A', 2861, 7, 'P', 741);
INSERT INTO `RelayNames` VALUES(24, 153, 'A', 2861, 8, 'P', 744);
INSERT INTO `RelayNames` VALUES(1, 153, 'A', 2863, 3, 'P', 739);
INSERT INTO `RelayNames` VALUES(17, 153, 'A', 2863, 1, 'P', 742);
INSERT INTO `RelayNames` VALUES(23, 153, 'A', 2863, 1, 'P', 743);
INSERT INTO `RelayNames` VALUES(1, 153, 'A', 2865, 8, 'P', 739);
INSERT INTO `RelayNames` VALUES(17, 153, 'A', 2865, 3, 'P', 742);
INSERT INTO `RelayNames` VALUES(23, 153, 'A', 2865, 2, 'P', 743);
INSERT INTO `RelayNames` VALUES(2, 153, 'A', 2866, 2, 'P', 740);
INSERT INTO `RelayNames` VALUES(18, 153, 'A', 2866, 1, 'P', 741);
INSERT INTO `RelayNames` VALUES(24, 153, 'A', 2866, 1, 'P', 744);
INSERT INTO `RelayNames` VALUES(2, 153, 'A', 2867, 6, 'P', 740);
INSERT INTO `RelayNames` VALUES(18, 153, 'A', 2867, 2, 'P', 741);
INSERT INTO `RelayNames` VALUES(24, 153, 'A', 2867, 6, 'P', 744);
INSERT INTO `RelayNames` VALUES(1, 153, 'A', 2868, 6, 'P', 739);
INSERT INTO `RelayNames` VALUES(17, 153, 'A', 2868, 6, 'P', 742);
INSERT INTO `RelayNames` VALUES(23, 153, 'A', 2868, 7, 'P', 743);
INSERT INTO `RelayNames` VALUES(1, 153, 'A', 2869, 2, 'P', 739);
INSERT INTO `RelayNames` VALUES(17, 153, 'A', 2869, 7, 'P', 742);
INSERT INTO `RelayNames` VALUES(23, 153, 'A', 2869, 4, 'P', 743);
INSERT INTO `RelayNames` VALUES(2, 153, 'A', 2870, 5, 'P', 740);
INSERT INTO `RelayNames` VALUES(18, 153, 'A', 2870, 4, 'P', 741);
INSERT INTO `RelayNames` VALUES(24, 153, 'A', 2870, 2, 'P', 744);
INSERT INTO `RelayNames` VALUES(1, 153, 'A', 2873, 1, 'P', 739);
INSERT INTO `RelayNames` VALUES(17, 153, 'A', 2873, 5, 'P', 742);
INSERT INTO `RelayNames` VALUES(23, 153, 'A', 2873, 8, 'P', 743);
INSERT INTO `RelayNames` VALUES(23, 154, 'A', 2874, 7, 'P', 749);
INSERT INTO `RelayNames` VALUES(18, 154, 'A', 2875, 8, 'P', 748);
INSERT INTO `RelayNames` VALUES(24, 154, 'A', 2875, 7, 'P', 750);
INSERT INTO `RelayNames` VALUES(1, 154, 'A', 2876, 2, 'P', 745);
INSERT INTO `RelayNames` VALUES(17, 154, 'A', 2876, 1, 'P', 747);
INSERT INTO `RelayNames` VALUES(23, 154, 'A', 2876, 5, 'P', 749);
INSERT INTO `RelayNames` VALUES(2, 154, 'A', 2877, 5, 'P', 746);
INSERT INTO `RelayNames` VALUES(18, 154, 'A', 2877, 3, 'P', 748);
INSERT INTO `RelayNames` VALUES(24, 154, 'A', 2877, 2, 'P', 750);
INSERT INTO `RelayNames` VALUES(1, 154, 'A', 2878, 4, 'P', 745);
INSERT INTO `RelayNames` VALUES(17, 154, 'A', 2878, 2, 'P', 747);
INSERT INTO `RelayNames` VALUES(23, 154, 'A', 2878, 6, 'P', 749);
INSERT INTO `RelayNames` VALUES(2, 154, 'A', 2879, 6, 'P', 746);
INSERT INTO `RelayNames` VALUES(18, 154, 'A', 2879, 1, 'P', 748);
INSERT INTO `RelayNames` VALUES(24, 154, 'A', 2879, 1, 'P', 750);
INSERT INTO `RelayNames` VALUES(1, 154, 'A', 2880, 8, 'P', 745);
INSERT INTO `RelayNames` VALUES(17, 154, 'A', 2880, 3, 'P', 747);
INSERT INTO `RelayNames` VALUES(23, 154, 'A', 2880, 2, 'P', 749);
INSERT INTO `RelayNames` VALUES(17, 154, 'A', 2881, 8, 'P', 747);
INSERT INTO `RelayNames` VALUES(2, 154, 'A', 2882, 2, 'P', 746);
INSERT INTO `RelayNames` VALUES(24, 154, 'A', 2882, 8, 'P', 750);
INSERT INTO `RelayNames` VALUES(1, 154, 'A', 2883, 7, 'P', 745);
INSERT INTO `RelayNames` VALUES(2, 154, 'A', 2884, 7, 'P', 746);
INSERT INTO `RelayNames` VALUES(18, 154, 'A', 2884, 5, 'P', 748);
INSERT INTO `RelayNames` VALUES(2, 154, 'A', 2885, 4, 'P', 746);
INSERT INTO `RelayNames` VALUES(18, 154, 'A', 2885, 2, 'P', 748);
INSERT INTO `RelayNames` VALUES(24, 154, 'A', 2885, 3, 'P', 750);
INSERT INTO `RelayNames` VALUES(2, 154, 'A', 2886, 8, 'P', 746);
INSERT INTO `RelayNames` VALUES(18, 154, 'A', 2886, 4, 'P', 748);
INSERT INTO `RelayNames` VALUES(24, 154, 'A', 2886, 4, 'P', 750);
INSERT INTO `RelayNames` VALUES(2, 154, 'A', 2887, 3, 'P', 746);
INSERT INTO `RelayNames` VALUES(1, 154, 'A', 2889, 6, 'P', 745);
INSERT INTO `RelayNames` VALUES(17, 154, 'A', 2889, 6, 'P', 747);
INSERT INTO `RelayNames` VALUES(23, 154, 'A', 2889, 8, 'P', 749);
INSERT INTO `RelayNames` VALUES(1, 154, 'A', 2890, 5, 'P', 745);
INSERT INTO `RelayNames` VALUES(17, 154, 'A', 2890, 4, 'P', 747);
INSERT INTO `RelayNames` VALUES(23, 154, 'A', 2890, 1, 'P', 749);
INSERT INTO `RelayNames` VALUES(1, 154, 'A', 2891, 1, 'P', 745);
INSERT INTO `RelayNames` VALUES(17, 154, 'A', 2891, 5, 'P', 747);
INSERT INTO `RelayNames` VALUES(23, 154, 'A', 2891, 4, 'P', 749);
INSERT INTO `RelayNames` VALUES(18, 154, 'A', 2892, 6, 'P', 748);
INSERT INTO `RelayNames` VALUES(24, 154, 'A', 2892, 6, 'P', 750);
INSERT INTO `RelayNames` VALUES(2, 154, 'A', 2893, 1, 'P', 746);
INSERT INTO `RelayNames` VALUES(18, 154, 'A', 2893, 7, 'P', 748);
INSERT INTO `RelayNames` VALUES(24, 154, 'A', 2893, 5, 'P', 750);
INSERT INTO `RelayNames` VALUES(1, 154, 'A', 2894, 3, 'P', 745);
INSERT INTO `RelayNames` VALUES(17, 154, 'A', 2894, 7, 'P', 747);
INSERT INTO `RelayNames` VALUES(23, 154, 'A', 2894, 3, 'P', 749);
INSERT INTO `RelayNames` VALUES(23, 155, 'A', 2895, 4, 'P', 753);
INSERT INTO `RelayNames` VALUES(1, 155, 'A', 2896, 2, 'P', 751);
INSERT INTO `RelayNames` VALUES(17, 155, 'A', 2896, 2, 'P', 752);
INSERT INTO `RelayNames` VALUES(23, 155, 'A', 2896, 3, 'P', 753);
INSERT INTO `RelayNames` VALUES(1, 155, 'A', 2897, 1, 'P', 751);
INSERT INTO `RelayNames` VALUES(17, 155, 'A', 2897, 3, 'P', 752);
INSERT INTO `RelayNames` VALUES(23, 155, 'A', 2897, 1, 'P', 753);
INSERT INTO `RelayNames` VALUES(1, 155, 'A', 2898, 4, 'P', 751);
INSERT INTO `RelayNames` VALUES(17, 155, 'A', 2898, 4, 'P', 752);
INSERT INTO `RelayNames` VALUES(23, 155, 'A', 2898, 2, 'P', 753);
INSERT INTO `RelayNames` VALUES(1, 155, 'A', 2899, 3, 'P', 751);
INSERT INTO `RelayNames` VALUES(17, 155, 'A', 2899, 1, 'P', 752);
INSERT INTO `RelayNames` VALUES(2, 156, 'A', 2900, 7, 'P', 754);
INSERT INTO `RelayNames` VALUES(2, 156, 'A', 2901, 8, 'P', 754);
INSERT INTO `RelayNames` VALUES(18, 156, 'A', 2901, 8, 'P', 755);
INSERT INTO `RelayNames` VALUES(24, 156, 'A', 2901, 6, 'P', 756);
INSERT INTO `RelayNames` VALUES(24, 156, 'A', 2902, 7, 'P', 756);
INSERT INTO `RelayNames` VALUES(2, 156, 'A', 2903, 3, 'P', 754);
INSERT INTO `RelayNames` VALUES(18, 156, 'A', 2903, 5, 'P', 755);
INSERT INTO `RelayNames` VALUES(24, 156, 'A', 2903, 1, 'P', 756);
INSERT INTO `RelayNames` VALUES(18, 156, 'A', 2904, 1, 'P', 755);
INSERT INTO `RelayNames` VALUES(24, 156, 'A', 2904, 3, 'P', 756);
INSERT INTO `RelayNames` VALUES(2, 156, 'A', 2905, 6, 'P', 754);
INSERT INTO `RelayNames` VALUES(18, 156, 'A', 2905, 7, 'P', 755);
INSERT INTO `RelayNames` VALUES(24, 156, 'A', 2905, 8, 'P', 756);
INSERT INTO `RelayNames` VALUES(2, 156, 'A', 2906, 5, 'P', 754);
INSERT INTO `RelayNames` VALUES(18, 156, 'A', 2906, 2, 'P', 755);
INSERT INTO `RelayNames` VALUES(24, 156, 'A', 2906, 2, 'P', 756);
INSERT INTO `RelayNames` VALUES(2, 156, 'A', 2908, 4, 'P', 754);
INSERT INTO `RelayNames` VALUES(18, 156, 'A', 2908, 4, 'P', 755);
INSERT INTO `RelayNames` VALUES(24, 156, 'A', 2908, 4, 'P', 756);
INSERT INTO `RelayNames` VALUES(2, 156, 'A', 2909, 1, 'P', 754);
INSERT INTO `RelayNames` VALUES(18, 156, 'A', 2909, 3, 'P', 755);
INSERT INTO `RelayNames` VALUES(24, 156, 'A', 2909, 5, 'P', 756);
INSERT INTO `RelayNames` VALUES(2, 156, 'A', 2910, 2, 'P', 754);
INSERT INTO `RelayNames` VALUES(18, 156, 'A', 2910, 6, 'P', 755);
INSERT INTO `RelayNames` VALUES(18, 157, 'A', 2912, 8, 'P', 760);
INSERT INTO `RelayNames` VALUES(1, 157, 'A', 2913, 6, 'P', 757);
INSERT INTO `RelayNames` VALUES(17, 157, 'A', 2913, 1, 'P', 759);
INSERT INTO `RelayNames` VALUES(23, 157, 'A', 2913, 1, 'P', 761);
INSERT INTO `RelayNames` VALUES(17, 157, 'A', 2916, 6, 'P', 759);
INSERT INTO `RelayNames` VALUES(1, 157, 'A', 2919, 1, 'P', 757);
INSERT INTO `RelayNames` VALUES(17, 157, 'A', 2919, 2, 'P', 759);
INSERT INTO `RelayNames` VALUES(2, 157, 'A', 2921, 3, 'P', 758);
INSERT INTO `RelayNames` VALUES(18, 157, 'A', 2921, 1, 'P', 760);
INSERT INTO `RelayNames` VALUES(24, 157, 'A', 2921, 8, 'P', 762);
INSERT INTO `RelayNames` VALUES(2, 157, 'A', 2922, 6, 'P', 758);
INSERT INTO `RelayNames` VALUES(18, 157, 'A', 2922, 7, 'P', 760);
INSERT INTO `RelayNames` VALUES(24, 157, 'A', 2922, 7, 'P', 762);
INSERT INTO `RelayNames` VALUES(2, 157, 'A', 2923, 2, 'P', 758);
INSERT INTO `RelayNames` VALUES(24, 157, 'A', 2923, 3, 'P', 762);
INSERT INTO `RelayNames` VALUES(17, 157, 'A', 2924, 7, 'P', 759);
INSERT INTO `RelayNames` VALUES(2, 157, 'A', 2925, 4, 'P', 758);
INSERT INTO `RelayNames` VALUES(18, 157, 'A', 2925, 6, 'P', 760);
INSERT INTO `RelayNames` VALUES(24, 157, 'A', 2925, 6, 'P', 762);
INSERT INTO `RelayNames` VALUES(1, 157, 'A', 2930, 2, 'P', 757);
INSERT INTO `RelayNames` VALUES(17, 157, 'A', 2930, 5, 'P', 759);
INSERT INTO `RelayNames` VALUES(23, 157, 'A', 2930, 5, 'P', 761);
INSERT INTO `RelayNames` VALUES(2, 157, 'A', 2939, 7, 'P', 758);
INSERT INTO `RelayNames` VALUES(18, 157, 'A', 2939, 2, 'P', 760);
INSERT INTO `RelayNames` VALUES(24, 157, 'A', 2939, 4, 'P', 762);
INSERT INTO `RelayNames` VALUES(2, 157, 'A', 2940, 8, 'P', 758);
INSERT INTO `RelayNames` VALUES(18, 157, 'A', 2940, 3, 'P', 760);
INSERT INTO `RelayNames` VALUES(24, 157, 'A', 2940, 2, 'P', 762);
INSERT INTO `RelayNames` VALUES(23, 157, 'A', 2947, 8, 'P', 761);
INSERT INTO `RelayNames` VALUES(1, 157, 'A', 2949, 5, 'P', 757);
INSERT INTO `RelayNames` VALUES(1, 157, 'A', 2950, 3, 'P', 757);
INSERT INTO `RelayNames` VALUES(17, 157, 'A', 2950, 8, 'P', 759);
INSERT INTO `RelayNames` VALUES(23, 157, 'A', 2950, 3, 'P', 761);
INSERT INTO `RelayNames` VALUES(1, 157, 'A', 2954, 4, 'P', 757);
INSERT INTO `RelayNames` VALUES(17, 157, 'A', 2954, 3, 'P', 759);
INSERT INTO `RelayNames` VALUES(23, 157, 'A', 2954, 2, 'P', 761);
INSERT INTO `RelayNames` VALUES(2, 157, 'A', 2957, 5, 'P', 758);
INSERT INTO `RelayNames` VALUES(18, 157, 'A', 2957, 5, 'P', 760);
INSERT INTO `RelayNames` VALUES(24, 157, 'A', 2957, 1, 'P', 762);
INSERT INTO `RelayNames` VALUES(23, 157, 'A', 2958, 7, 'P', 761);
INSERT INTO `RelayNames` VALUES(1, 157, 'A', 2961, 8, 'P', 757);
INSERT INTO `RelayNames` VALUES(17, 157, 'A', 2961, 4, 'P', 759);
INSERT INTO `RelayNames` VALUES(23, 157, 'A', 2961, 4, 'P', 761);
INSERT INTO `RelayNames` VALUES(2, 157, 'A', 2962, 1, 'P', 758);
INSERT INTO `RelayNames` VALUES(18, 157, 'A', 2962, 4, 'P', 760);
INSERT INTO `RelayNames` VALUES(24, 157, 'A', 2962, 5, 'P', 762);
INSERT INTO `RelayNames` VALUES(1, 157, 'A', 2968, 7, 'P', 757);
INSERT INTO `RelayNames` VALUES(23, 157, 'A', 2971, 6, 'P', 761);
INSERT INTO `RelayNames` VALUES(24, 158, 'A', 2972, 8, 'P', 768);
INSERT INTO `RelayNames` VALUES(1, 158, 'A', 2973, 7, 'P', 763);
INSERT INTO `RelayNames` VALUES(17, 158, 'A', 2973, 6, 'P', 765);
INSERT INTO `RelayNames` VALUES(2, 158, 'A', 2974, 4, 'P', 764);
INSERT INTO `RelayNames` VALUES(18, 158, 'A', 2974, 1, 'P', 766);
INSERT INTO `RelayNames` VALUES(24, 158, 'A', 2974, 1, 'P', 768);
INSERT INTO `RelayNames` VALUES(2, 158, 'A', 2975, 5, 'P', 764);
INSERT INTO `RelayNames` VALUES(18, 158, 'A', 2975, 5, 'P', 766);
INSERT INTO `RelayNames` VALUES(24, 158, 'A', 2975, 5, 'P', 768);
INSERT INTO `RelayNames` VALUES(2, 158, 'A', 2976, 6, 'P', 764);
INSERT INTO `RelayNames` VALUES(18, 158, 'A', 2976, 2, 'P', 766);
INSERT INTO `RelayNames` VALUES(24, 158, 'A', 2976, 6, 'P', 768);
INSERT INTO `RelayNames` VALUES(23, 158, 'A', 2977, 8, 'P', 767);
INSERT INTO `RelayNames` VALUES(2, 158, 'A', 2978, 7, 'P', 764);
INSERT INTO `RelayNames` VALUES(18, 158, 'A', 2978, 3, 'P', 766);
INSERT INTO `RelayNames` VALUES(24, 158, 'A', 2978, 7, 'P', 768);
INSERT INTO `RelayNames` VALUES(1, 158, 'A', 2979, 8, 'P', 763);
INSERT INTO `RelayNames` VALUES(17, 158, 'A', 2979, 5, 'P', 765);
INSERT INTO `RelayNames` VALUES(23, 158, 'A', 2979, 5, 'P', 767);
INSERT INTO `RelayNames` VALUES(2, 158, 'A', 2980, 8, 'P', 764);
INSERT INTO `RelayNames` VALUES(1, 158, 'A', 2981, 6, 'P', 763);
INSERT INTO `RelayNames` VALUES(17, 158, 'A', 2981, 7, 'P', 765);
INSERT INTO `RelayNames` VALUES(23, 158, 'A', 2981, 6, 'P', 767);
INSERT INTO `RelayNames` VALUES(1, 158, 'A', 2982, 5, 'P', 763);
INSERT INTO `RelayNames` VALUES(17, 158, 'A', 2982, 8, 'P', 765);
INSERT INTO `RelayNames` VALUES(23, 158, 'A', 2982, 7, 'P', 767);
INSERT INTO `RelayNames` VALUES(2, 158, 'A', 2983, 3, 'P', 764);
INSERT INTO `RelayNames` VALUES(18, 158, 'A', 2983, 4, 'P', 766);
INSERT INTO `RelayNames` VALUES(24, 158, 'A', 2983, 2, 'P', 768);
INSERT INTO `RelayNames` VALUES(18, 158, 'A', 2984, 8, 'P', 766);
INSERT INTO `RelayNames` VALUES(1, 158, 'A', 2985, 1, 'P', 763);
INSERT INTO `RelayNames` VALUES(17, 158, 'A', 2985, 1, 'P', 765);
INSERT INTO `RelayNames` VALUES(23, 158, 'A', 2985, 1, 'P', 767);
INSERT INTO `RelayNames` VALUES(1, 158, 'A', 2987, 3, 'P', 763);
INSERT INTO `RelayNames` VALUES(17, 158, 'A', 2987, 2, 'P', 765);
INSERT INTO `RelayNames` VALUES(23, 158, 'A', 2987, 2, 'P', 767);
INSERT INTO `RelayNames` VALUES(1, 158, 'A', 2988, 2, 'P', 763);
INSERT INTO `RelayNames` VALUES(17, 158, 'A', 2988, 3, 'P', 765);
INSERT INTO `RelayNames` VALUES(23, 158, 'A', 2988, 3, 'P', 767);
INSERT INTO `RelayNames` VALUES(2, 158, 'A', 2989, 2, 'P', 764);
INSERT INTO `RelayNames` VALUES(18, 158, 'A', 2989, 6, 'P', 766);
INSERT INTO `RelayNames` VALUES(24, 158, 'A', 2989, 3, 'P', 768);
INSERT INTO `RelayNames` VALUES(2, 158, 'A', 2990, 1, 'P', 764);
INSERT INTO `RelayNames` VALUES(18, 158, 'A', 2990, 7, 'P', 766);
INSERT INTO `RelayNames` VALUES(24, 158, 'A', 2990, 4, 'P', 768);
INSERT INTO `RelayNames` VALUES(1, 158, 'A', 2991, 4, 'P', 763);
INSERT INTO `RelayNames` VALUES(17, 158, 'A', 2991, 4, 'P', 765);
INSERT INTO `RelayNames` VALUES(23, 158, 'A', 2991, 4, 'P', 767);
INSERT INTO `RelayNames` VALUES(2, 147, 'A', 2992, 8, 'P', 720);
INSERT INTO `RelayNames` VALUES(18, 147, 'A', 2992, 8, 'P', 722);
INSERT INTO `RelayNames` VALUES(24, 147, 'A', 2992, 8, 'P', 723);
INSERT INTO `RelayNames` VALUES(17, 147, 'A', 2993, 8, 'P', 721);
INSERT INTO `RelayNames` VALUES(1, 159, 'A', 2994, 2, 'P', 769);
INSERT INTO `RelayNames` VALUES(17, 159, 'A', 2994, 3, 'P', 770);
INSERT INTO `RelayNames` VALUES(23, 159, 'A', 2994, 5, 'P', 771);
INSERT INTO `RelayNames` VALUES(1, 159, 'A', 2995, 1, 'P', 769);
INSERT INTO `RelayNames` VALUES(17, 159, 'A', 2995, 2, 'P', 770);
INSERT INTO `RelayNames` VALUES(23, 159, 'A', 2995, 6, 'P', 771);
INSERT INTO `RelayNames` VALUES(1, 159, 'A', 2996, 3, 'P', 769);
INSERT INTO `RelayNames` VALUES(17, 159, 'A', 2996, 8, 'P', 770);
INSERT INTO `RelayNames` VALUES(23, 159, 'A', 2996, 2, 'P', 771);
INSERT INTO `RelayNames` VALUES(2, 159, 'A', 2997, 3, 'P', 772);
INSERT INTO `RelayNames` VALUES(18, 159, 'A', 2997, 5, 'P', 773);
INSERT INTO `RelayNames` VALUES(24, 159, 'A', 2997, 3, 'P', 774);
INSERT INTO `RelayNames` VALUES(2, 159, 'A', 2998, 5, 'P', 772);
INSERT INTO `RelayNames` VALUES(18, 159, 'A', 2998, 6, 'P', 773);
INSERT INTO `RelayNames` VALUES(24, 159, 'A', 2998, 5, 'P', 774);
INSERT INTO `RelayNames` VALUES(2, 159, 'A', 2999, 6, 'P', 772);
INSERT INTO `RelayNames` VALUES(18, 159, 'A', 2999, 4, 'P', 773);
INSERT INTO `RelayNames` VALUES(24, 159, 'A', 2999, 2, 'P', 774);
INSERT INTO `RelayNames` VALUES(1, 159, 'A', 3000, 5, 'P', 769);
INSERT INTO `RelayNames` VALUES(17, 159, 'A', 3000, 5, 'P', 770);
INSERT INTO `RelayNames` VALUES(1, 159, 'A', 3001, 6, 'P', 769);
INSERT INTO `RelayNames` VALUES(17, 159, 'A', 3001, 6, 'P', 770);
INSERT INTO `RelayNames` VALUES(2, 159, 'A', 3002, 7, 'P', 772);
INSERT INTO `RelayNames` VALUES(18, 159, 'A', 3002, 1, 'P', 773);
INSERT INTO `RelayNames` VALUES(24, 159, 'A', 3002, 4, 'P', 774);
INSERT INTO `RelayNames` VALUES(23, 159, 'A', 3006, 7, 'P', 771);
INSERT INTO `RelayNames` VALUES(1, 159, 'A', 3007, 7, 'P', 769);
INSERT INTO `RelayNames` VALUES(17, 159, 'A', 3007, 7, 'P', 770);
INSERT INTO `RelayNames` VALUES(23, 159, 'A', 3007, 3, 'P', 771);
INSERT INTO `RelayNames` VALUES(1, 159, 'A', 3010, 4, 'P', 769);
INSERT INTO `RelayNames` VALUES(17, 159, 'A', 3010, 4, 'P', 770);
INSERT INTO `RelayNames` VALUES(23, 159, 'A', 3010, 8, 'P', 771);
INSERT INTO `RelayNames` VALUES(2, 159, 'A', 3011, 2, 'P', 772);
INSERT INTO `RelayNames` VALUES(18, 159, 'A', 3011, 3, 'P', 773);
INSERT INTO `RelayNames` VALUES(24, 159, 'A', 3011, 6, 'P', 774);
INSERT INTO `RelayNames` VALUES(1, 159, 'A', 3012, 8, 'P', 769);
INSERT INTO `RelayNames` VALUES(17, 159, 'A', 3012, 1, 'P', 770);
INSERT INTO `RelayNames` VALUES(23, 159, 'A', 3012, 1, 'P', 771);
INSERT INTO `RelayNames` VALUES(2, 159, 'A', 3013, 8, 'P', 772);
INSERT INTO `RelayNames` VALUES(18, 159, 'A', 3013, 2, 'P', 773);
INSERT INTO `RelayNames` VALUES(24, 159, 'A', 3013, 7, 'P', 774);
INSERT INTO `RelayNames` VALUES(23, 159, 'A', 3015, 4, 'P', 771);
INSERT INTO `RelayNames` VALUES(18, 159, 'A', 3016, 8, 'P', 773);
INSERT INTO `RelayNames` VALUES(2, 159, 'A', 3017, 1, 'P', 772);
INSERT INTO `RelayNames` VALUES(24, 159, 'A', 3017, 8, 'P', 774);
INSERT INTO `RelayNames` VALUES(2, 159, 'A', 3018, 4, 'P', 772);
INSERT INTO `RelayNames` VALUES(18, 159, 'A', 3018, 7, 'P', 773);
INSERT INTO `RelayNames` VALUES(24, 159, 'A', 3018, 1, 'P', 774);
UNLOCK TABLES;

#
# Table structure for table 'Scoring'
#

DROP TABLE IF EXISTS `Scoring`;
CREATE TABLE `Scoring` (
  `score_divno` SMALLINT,
  `score_sex` VARCHAR(1),
  `score_place` SMALLINT,
  `ind_score` FLOAT,
  `rel_score` FLOAT,
  INDEX `scordiv` (`score_divno`, `score_sex`, `score_place`)
);

#
# Dumping data for table 'Scoring'
#

LOCK TABLES `Scoring` WRITE;
INSERT INTO `Scoring` VALUES(0, 'F', 1, 2.000000e+001, 4.000000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 2, 1.700000e+001, 3.400000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 3, 1.600000e+001, 3.200000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 4, 1.500000e+001, 3.000000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 5, 1.400000e+001, 2.800000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 6, 1.300000e+001, 2.600000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 7, 1.200000e+001, 2.400000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 8, 1.100000e+001, 2.200000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 9, 9.000000e+000, 1.800000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 10, 7.000000e+000, 1.400000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 11, 6.000000e+000, 1.200000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 12, 5.000000e+000, 1.000000e+001);
INSERT INTO `Scoring` VALUES(0, 'F', 13, 4.000000e+000, 8.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 14, 3.000000e+000, 6.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 15, 2.000000e+000, 4.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 16, 1.000000e+000, 2.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 17, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 18, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 19, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 20, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 21, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 22, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 23, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 24, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 25, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 26, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 27, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 28, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 29, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 30, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 1, 1.600000e+001, 3.200000e+001);
INSERT INTO `Scoring` VALUES(0, 'M', 2, 1.300000e+001, 2.600000e+001);
INSERT INTO `Scoring` VALUES(0, 'M', 3, 1.200000e+001, 2.400000e+001);
INSERT INTO `Scoring` VALUES(0, 'M', 4, 1.100000e+001, 2.200000e+001);
INSERT INTO `Scoring` VALUES(0, 'M', 5, 1.000000e+001, 2.000000e+001);
INSERT INTO `Scoring` VALUES(0, 'M', 6, 9.000000e+000, 1.800000e+001);
INSERT INTO `Scoring` VALUES(0, 'M', 7, 7.000000e+000, 1.400000e+001);
INSERT INTO `Scoring` VALUES(0, 'M', 8, 5.000000e+000, 1.000000e+001);
INSERT INTO `Scoring` VALUES(0, 'M', 9, 4.000000e+000, 8.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 10, 3.000000e+000, 6.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 11, 2.000000e+000, 4.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 12, 1.000000e+000, 2.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 13, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 14, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 15, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 16, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 17, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 18, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 19, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 20, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 21, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 22, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 23, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 24, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 25, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 26, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 27, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 28, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 29, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 30, 0.000000e+000, 0.000000e+000);
UNLOCK TABLES;

#
# Table structure for table 'ScoringImprovement'
#

DROP TABLE IF EXISTS `ScoringImprovement`;
CREATE TABLE `ScoringImprovement` (
  `list_no` SMALLINT,
  `diff_lowtime` FLOAT,
  `diff_hightime` FLOAT,
  `pt_score` FLOAT,
  `swim_score` FLOAT,
  INDEX `listno` (`list_no`)
);

#
# Dumping data for table 'ScoringImprovement'
#

LOCK TABLES `ScoringImprovement` WRITE;
INSERT INTO `ScoringImprovement` VALUES(1, -9.900000e+001, -5.010000e+000, 0.000000e+000, 1.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(2, -5.000000e+000, -1.010000e+000, 1.000000e+000, 1.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(3, -1.000000e+000, 0.000000e+000, 2.000000e+000, 1.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(4, 1.000000e-002, 1.000000e+000, 3.000000e+000, 1.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(5, 1.010000e+000, 2.000000e+000, 4.000000e+000, 1.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(6, 2.010000e+000, 9.900000e+001, 5.000000e+000, 1.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(7, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(8, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(9, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0.000000e+000);
INSERT INTO `ScoringImprovement` VALUES(10, 0.000000e+000, 0.000000e+000, 0.000000e+000, 0.000000e+000);
UNLOCK TABLES;

#
# Table structure for table 'Session'
#

DROP TABLE IF EXISTS `Session`;
CREATE TABLE `Session` (
  `Sess_no` SMALLINT,
  `Sess_ltr` VARCHAR(1),
  `Sess_ptr` INT NOT NULL AUTO_INCREMENT,
  `Sess_day` SMALLINT,
  `Sess_starttime` INT,
  `Sess_entrymax` SMALLINT,
  `Sess_name` VARCHAR(60),
  `Sess_interval` SMALLINT,
  `Sess_course` VARCHAR(1),
  PRIMARY KEY (`Sess_ptr`)
);

#
# Dumping data for table 'Session'
#

LOCK TABLES `Session` WRITE;
INSERT INTO `Session` VALUES(1, ' ', 1, 2, 39600, 4, 'Friday Prelims', 30, 'Y');
INSERT INTO `Session` VALUES(2, ' ', 2, 3, 43200, 4, 'Saturday Finals', 30, 'Y');
INSERT INTO `Session` VALUES(3, ' ', 3, 1, 34200, 3, 'Diving', 15, 'Y');
UNLOCK TABLES;

#
# Table structure for table 'Sessitem'
#

DROP TABLE IF EXISTS `Sessitem`;
CREATE TABLE `Sessitem` (
  `Sess_order` INT,
  `Sess_ptr` INT,
  `Event_ptr` INT,
  `Sess_rnd` VARCHAR(1),
  `Rept_type` VARCHAR(1),
  `Delay_seconds` INT,
  `Alt_With` BIT,
  `Timed_finalheats` SMALLINT,
  `EventTo_AlternateWith` INT,
  INDEX `sessevtptr` (`Event_ptr`),
  INDEX `sessptr` (`Sess_ptr`)
);

#
# Dumping data for table 'Sessitem'
#

LOCK TABLES `Sessitem` WRITE;
INSERT INTO `Sessitem` VALUES(1, 1, 1, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(1, 2, 1, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(2, 1, 2, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(2, 2, 2, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(3, 1, 3, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(3, 2, 3, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(4, 1, 4, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(4, 2, 4, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(5, 1, 5, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(5, 2, 5, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(6, 1, 6, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(6, 2, 6, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(7, 1, 7, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(7, 2, 7, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(8, 1, 8, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(8, 2, 8, 'F', 'H', 1200, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(1, 3, 9, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(2, 3, 10, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(9, 1, 11, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(9, 2, 11, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(10, 1, 12, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(10, 2, 12, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(11, 1, 13, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(11, 2, 13, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(12, 1, 14, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(12, 2, 14, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(13, 1, 15, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(13, 2, 15, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(14, 1, 16, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(14, 2, 16, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(15, 1, 17, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(15, 2, 17, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(16, 1, 18, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(16, 2, 18, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(17, 1, 19, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(17, 2, 19, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(18, 1, 20, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(18, 2, 20, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(19, 1, 21, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(19, 2, 21, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(20, 1, 22, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(20, 2, 22, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(21, 1, 23, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(21, 2, 23, 'F', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(22, 1, 24, 'P', 'H', 0, 0, 0, NULL);
INSERT INTO `Sessitem` VALUES(22, 2, 24, 'F', 'H', 0, 0, 0, NULL);
UNLOCK TABLES;

#
# Table structure for table 'Split'
#

DROP TABLE IF EXISTS `Split`;
CREATE TABLE `Split` (
  `Event_ptr` INT,
  `Ath_no` INT,
  `Team_no` INT,
  `Team_ltr` VARCHAR(1),
  `Rnd_ltr` VARCHAR(1),
  `Split_no` SMALLINT,
  `Split_Time` FLOAT,
  `Relay_no` INT,
  INDEX `athsplit` (`Event_ptr`, `Ath_no`, `Rnd_ltr`, `Split_no`),
  INDEX `relaynosplit` (`Relay_no`, `Rnd_ltr`, `Split_no`),
  INDEX `relsplit` (`Event_ptr`, `Team_no`, `Team_ltr`, `Rnd_ltr`, `Split_no`)
);

#
# Dumping data for table 'Split'
#

LOCK TABLES `Split` WRITE;
UNLOCK TABLES;

#
# Table structure for table 'StdLanes'
#

DROP TABLE IF EXISTS `StdLanes`;
CREATE TABLE `StdLanes` (
  `tot_lanes` SMALLINT NOT NULL,
  `order_01` SMALLINT,
  `order_02` SMALLINT,
  `order_03` SMALLINT,
  `order_04` SMALLINT,
  `order_05` SMALLINT,
  `order_06` SMALLINT,
  `order_07` SMALLINT,
  `order_08` SMALLINT,
  `order_09` SMALLINT,
  `order_10` SMALLINT,
  PRIMARY KEY (`tot_lanes`)
);

#
# Dumping data for table 'StdLanes'
#

LOCK TABLES `StdLanes` WRITE;
INSERT INTO `StdLanes` VALUES(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(2, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(3, 2, 3, 1, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(4, 2, 3, 1, 4, 0, 0, 0, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(5, 3, 4, 2, 5, 1, 0, 0, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(6, 5, 6, 4, 7, 3, 8, 0, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(7, 4, 5, 3, 6, 2, 7, 1, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(8, 5, 6, 4, 7, 3, 8, 2, 9, 0, 0);
INSERT INTO `StdLanes` VALUES(9, 5, 6, 4, 7, 3, 8, 2, 9, 1, 0);
INSERT INTO `StdLanes` VALUES(10, 5, 6, 4, 7, 3, 8, 2, 9, 0, 10);
UNLOCK TABLES;

#
# Table structure for table 'TagNames'
#

DROP TABLE IF EXISTS `TagNames`;
CREATE TABLE `TagNames` (
  `tag_ptr` INT NOT NULL AUTO_INCREMENT,
  `tag_name` VARCHAR(4),
  `for_scoring` BIT,
  `for_entryqual` BIT,
  `for_timestd` BIT,
  INDEX `tag_ptr` (`tag_ptr`)
);

#
# Dumping data for table 'TagNames'
#

LOCK TABLES `TagNames` WRITE;
INSERT INTO `TagNames` VALUES(4, 'AUTO', 0, 0, 0);
INSERT INTO `TagNames` VALUES(5, 'SEC', 0, -1, 0);
UNLOCK TABLES;

#
# Table structure for table 'Team'
#

DROP TABLE IF EXISTS `Team`;
CREATE TABLE `Team` (
  `Team_no` INT NOT NULL AUTO_INCREMENT,
  `Team_name` VARCHAR(30),
  `Team_short` VARCHAR(16),
  `Team_abbr` VARCHAR(5),
  `Team_stat` VARCHAR(1),
  `Team_lsc` VARCHAR(2),
  `Team_div` SMALLINT,
  `Team_region` SMALLINT,
  `Team_head` VARCHAR(30),
  `Team_asst` VARCHAR(30),
  `Team_addr1` VARCHAR(30),
  `Team_addr2` VARCHAR(30),
  `Team_city` VARCHAR(30),
  `Team_prov` VARCHAR(30),
  `Team_state` VARCHAR(2),
  `Team_zip` VARCHAR(10),
  `Team_cntry` VARCHAR(3),
  `Team_daytele` VARCHAR(20),
  `Team_evetele` VARCHAR(20),
  `Team_faxtele` VARCHAR(20),
  `Team_c3` VARCHAR(30),
  `Team_c4` VARCHAR(30),
  `Team_c5` VARCHAR(30),
  `Team_c6` VARCHAR(30),
  `Team_c7` VARCHAR(30),
  `Team_c8` VARCHAR(30),
  `Team_c9` VARCHAR(30),
  `Team_c10` VARCHAR(30),
  `team_statenew` VARCHAR(3),
  `Team_altabbr` VARCHAR(5),
  `team_email` VARCHAR(36),
  INDEX `teamabbr` (`Team_abbr`),
  PRIMARY KEY (`Team_no`)
);

#
# Dumping data for table 'Team'
#

LOCK TABLES `Team` WRITE;
INSERT INTO `Team` VALUES(124, 'Columbus                      ', "", 'COLUM', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NEE', 'COLUM', "");
INSERT INTO `Team` VALUES(125, 'Beatrice                      ', "", 'BEATR', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'BEATR', "");
INSERT INTO `Team` VALUES(126, 'Bellevue East                 ', "", 'BELLE', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'BELLE', "");
INSERT INTO `Team` VALUES(127, 'Grand Island                  ', 'Grand Island    ', 'GRISL', "", '  ', 0, 0, 'Jami Schmitt                  ', 'Brian Jensen                  ', "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NEE', 'GRISL', "");
INSERT INTO `Team` VALUES(128, 'Hastings                      ', "", 'HAST ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NEE', 'HAST ', "");
INSERT INTO `Team` VALUES(129, 'Kearney                       ', "", 'KEARN', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'KEARN', "");
INSERT INTO `Team` VALUES(130, 'Lincoln High                  ', "", 'LH   ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'LH   ', "");
INSERT INTO `Team` VALUES(131, 'Lincoln Northeast             ', "", 'LNE  ', "", '  ', 0, 0, 'Ed Muller                     ', "", 'Ed Muller                     ', '2635 North 63rd Street        ', 'Lincoln                       ', "", "", '68507     ', 'USA', '402-436-1303        ', '402-464-4350        ', "", "", "", "", "", "", "", "", "", 'NEE', 'LNE  ', 'emuller@lps.org               ');
INSERT INTO `Team` VALUES(132, 'Lincoln North Star            ', "", 'LNS  ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'LNS  ', "");
INSERT INTO `Team` VALUES(133, 'Lincoln Southeast             ', "", 'LSE  ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'LSE  ', "");
INSERT INTO `Team` VALUES(134, 'Lincoln Southwest             ', "", 'LSW  ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'LSW  ', "");
INSERT INTO `Team` VALUES(135, 'McCook                        ', "", 'MCCK ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NEE', 'MCCK ', "");
INSERT INTO `Team` VALUES(136, 'Millard North                 ', "", 'MILLN', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NEE', 'MILLN', "");
INSERT INTO `Team` VALUES(137, 'Millard South                 ', "", 'MILLS', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NEE', 'MILLS', "");
INSERT INTO `Team` VALUES(138, 'Norfolk                       ', "", 'NRFLK', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'NRFLK', "");
INSERT INTO `Team` VALUES(139, 'North Platte                  ', "", 'NPLTT', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'NPLTT', "");
INSERT INTO `Team` VALUES(140, 'Omaha Benson                  ', "", 'OMBEN', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'OMBEN', "");
INSERT INTO `Team` VALUES(141, 'Omaha Burke                   ', "", 'OMBRK', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'OMBRK', "");
INSERT INTO `Team` VALUES(142, 'Omaha Central                 ', "", 'OMCEN', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'OMCEN', "");
INSERT INTO `Team` VALUES(143, 'Omaha Creighton Prep          ', "", 'OMCRP', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'OMCRP', "");
INSERT INTO `Team` VALUES(144, 'Omaha Duchesne Academy        ', "", 'OMDUC', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'OMDUC', "");
INSERT INTO `Team` VALUES(145, 'Omaha Marian                  ', "", 'OMMAR', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'OMMAR', "");
INSERT INTO `Team` VALUES(146, 'Omaha North                   ', "", 'OMNO ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NEE', 'OMNO ', "");
INSERT INTO `Team` VALUES(147, 'Omaha Northwest', ' ', 'OMNW', ' ', '  ', 0, 0, ' ', ' ', '                              ', '                              ', '                              ', '                              ', "", '          ', '   ', '                    ', '                    ', '                    ', "", "", "", "", "", "", "", "", 'NE ', 'OMNW', '                    ');
INSERT INTO `Team` VALUES(149, 'Omaha Roncalli/Omaha B-T      ', ' ', 'RONBT', ' ', '  ', 0, 0, ' ', ' ', '                              ', '                              ', '                              ', '                              ', "", '          ', 'USA', '                    ', '                    ', '                    ', "", "", "", "", "", "", "", "", 'NE ', 'RONBT', '                    ');
INSERT INTO `Team` VALUES(150, 'Omaha Skutt Catholic          ', "", 'OMSKU', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'OMSKU', "");
INSERT INTO `Team` VALUES(151, 'Omaha Westside/Elkhorn', 'Westside-Elkhorn', 'OMWE', ' ', '  ', 0, 0, 'Doug Krecklow                 ', 'Lisa Ellis                    ', 'Westside High School          ', '8701 Pacific Street           ', 'Omaha                         ', '                              ', "", '          ', 'USA', '402-343-2770        ', '                    ', '                    ', "", "", "", "", "", "", "", "", 'NCC', 'OMWE', '                    ');
INSERT INTO `Team` VALUES(152, 'Papillion-LaVista', ' ', 'PLV', ' ', '  ', 0, 0, 'Lynn Weaver                   ', 'Jamie Blinn                   ', '                              ', '                              ', '                              ', '                              ', "", '          ', 'USA', '                    ', '                    ', '                    ', "", "", "", "", "", "", "", "", '--', 'PLV', '                    ');
INSERT INTO `Team` VALUES(153, 'Ralston/Omaha Gross', 'RALSTON/GROSS   ', 'RALGR', ' ', '  ', 0, 0, 'DOCKER J. HARTFIELD           ', 'LARRY HILL                    ', 'RALSTON HIGH/GROSS            ', '8989 PARK DRIVE               ', 'OMAHA                         ', '                              ', "", '68127     ', 'USA', '402-898-3567        ', '402-492-8956        ', '                    ', "", "", "", "", "", "", "", "", 'NEE', 'RALGR', '                    ');
INSERT INTO `Team` VALUES(154, 'Scottsbluff', ' ', 'SBLF', ' ', '  ', 0, 0, ' ', ' ', '                              ', '                              ', '                              ', '                              ', "", '          ', '   ', '                    ', '                    ', '                    ', "", "", "", "", "", "", "", "", '  ', 'SBLF', '                    ');
INSERT INTO `Team` VALUES(155, 'South Sioux City              ', "", 'SOSC ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NEE', 'SOSC ', "");
INSERT INTO `Team` VALUES(156, 'Fremont                       ', "", 'FRMNT', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'FRMNT', "");
INSERT INTO `Team` VALUES(157, 'Lincoln East', 'LE              ', 'LE', ' ', '  ', 0, 0, ' ', ' ', '                              ', '                              ', '                              ', '                              ', "", '          ', '   ', '                    ', '                    ', '                    ', "", "", "", "", "", "", "", "", 'NEE', 'LE', '                    ');
INSERT INTO `Team` VALUES(158, 'Bellevue West                 ', "", 'BELLW', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'BELLW', "");
INSERT INTO `Team` VALUES(159, 'Millard West                  ', "", 'MILLW', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", 'NE ', 'MILLW', "");
UNLOCK TABLES;

#
# Table structure for table 'TimeStd'
#

DROP TABLE IF EXISTS `TimeStd`;
CREATE TABLE `TimeStd` (
  `tag_ptr` INT,
  `tag_gender` VARCHAR(1),
  `tag_indrel` VARCHAR(1),
  `tag_dist` INT,
  `tag_stroke` VARCHAR(1),
  `low_age` SMALLINT,
  `high_Age` SMALLINT,
  `tag_time` FLOAT,
  `tag_course` VARCHAR(1),
  INDEX `timestdtag` (`tag_ptr`)
);

#
# Dumping data for table 'TimeStd'
#

LOCK TABLES `TimeStd` WRITE;
INSERT INTO `TimeStd` VALUES(4, 'F', 'I', 50, 'A', 0, 109, 2.616000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'I', 50, 'A', 0, 109, 2.336000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'I', 100, 'A', 0, 109, 5.754000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'I', 100, 'A', 0, 109, 5.149000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'I', 100, 'B', 0, 109, 6.571000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'I', 100, 'B', 0, 109, 6.045000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'I', 100, 'C', 0, 109, 7.443000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'I', 100, 'C', 0, 109, 6.669000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'I', 100, 'D', 0, 109, 6.535000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'I', 100, 'D', 0, 109, 5.733000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'I', 200, 'A', 0, 109, 1.260700e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'R', 200, 'A', 0, 109, 1.091800e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'I', 200, 'A', 0, 109, 1.118700e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'R', 200, 'A', 0, 109, 9.934000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'I', 200, 'E', 0, 109, 1.432700e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'R', 200, 'E', 0, 109, 1.233800e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'I', 200, 'E', 0, 109, 1.293800e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'R', 200, 'E', 0, 109, 1.140400e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'R', 400, 'A', 0, 109, 2.449900e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'R', 400, 'A', 0, 109, 2.234000e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'F', 'I', 500, 'A', 0, 109, 3.412900e+002, 'Y');
INSERT INTO `TimeStd` VALUES(4, 'M', 'I', 500, 'A', 0, 109, 3.142700e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'I', 50, 'A', 0, 109, 2.825000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'I', 50, 'A', 0, 109, 2.523000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'I', 100, 'A', 0, 109, 6.215000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'I', 100, 'A', 0, 109, 5.561000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'I', 100, 'B', 0, 109, 7.097000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'I', 100, 'B', 0, 109, 6.528000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'I', 100, 'C', 0, 109, 8.039000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'I', 100, 'C', 0, 109, 7.202000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'I', 100, 'D', 0, 109, 7.058000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'I', 100, 'D', 0, 109, 6.192000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'I', 200, 'A', 0, 109, 1.361500e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'R', 200, 'A', 0, 109, 1.179100e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'I', 200, 'A', 0, 109, 1.208200e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'R', 200, 'A', 0, 109, 1.072800e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'I', 200, 'E', 0, 109, 1.547300e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'R', 200, 'E', 0, 109, 1.332500e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'I', 200, 'E', 0, 109, 1.397300e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'R', 200, 'E', 0, 109, 1.231700e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'R', 400, 'A', 0, 109, 2.645900e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'R', 400, 'A', 0, 109, 2.412800e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'F', 'I', 500, 'A', 0, 109, 3.685900e+002, 'Y');
INSERT INTO `TimeStd` VALUES(5, 'M', 'I', 500, 'A', 0, 109, 3.394100e+002, 'Y');
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS = 1;
