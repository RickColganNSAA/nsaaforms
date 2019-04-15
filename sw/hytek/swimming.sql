
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
INSERT INTO `AltScoring` VALUES(0, 'M', 1, 1.600000e+001, 3.200000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 2, 1.300000e+001, 2.600000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 3, 1.200000e+001, 2.400000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 4, 1.100000e+001, 2.200000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 5, 1.000000e+001, 2.000000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 6, 9.000000e+000, 1.800000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 7, 7.000000e+000, 1.400000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 8, 5.000000e+000, 1.000000e+001);
INSERT INTO `AltScoring` VALUES(0, 'M', 9, 4.000000e+000, 8.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 10, 3.000000e+000, 6.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 11, 2.000000e+000, 4.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 12, 1.000000e+000, 2.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 13, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 14, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 15, 0.000000e+000, 0.000000e+000);
INSERT INTO `AltScoring` VALUES(0, 'M', 16, 0.000000e+000, 0.000000e+000);
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
  `Home_statenew` VARCHAR(3),
  `Home_zip` VARCHAR(10),
  `Home_cntry` VARCHAR(3),
  `Home_daytele` VARCHAR(20),
  `Home_evetele` VARCHAR(20),
  `Home_faxtele` VARCHAR(20),
  `Home_email` VARCHAR(50),
  `Citizen_of` VARCHAR(3),
  `Picture_bmp` VARCHAR(30),
  `second_club` VARCHAR(16),
  PRIMARY KEY (`Ath_no`),
  INDEX `athteam` (`Team_no`),
  INDEX `idnum` (`Reg_no`),
  INDEX `lastname` (`Last_name`)
);

#
# Dumping data for table 'Athlete'
#

LOCK TABLES `Athlete` WRITE;
INSERT INTO `Athlete` VALUES(1, 'Addison             ', 'Shawn               ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 1, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(2, 'Alfieri             ', 'Sydney              ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 2, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(3, 'Baber               ', 'Emilie              ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 3, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(4, 'Baker               ', 'Alexander           ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 4, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(5, 'Baker               ', 'Nicholas            ', ' ', 'M', NULL, 2, 'SR', 0, '              ', ' ', NULL, 5, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(6, 'Ballard             ', 'Adam                ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 6, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(7, 'Behrens             ', 'Sara                ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 7, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(8, 'Bevington           ', 'Samantha            ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 8, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(9, 'Bonacci             ', 'Nicholas            ', ' ', 'M', NULL, 2, 'SO', 0, '              ', ' ', NULL, 9, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(10, 'Bruner              ', 'Joey                ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 10, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(11, 'Burklund            ', 'Zachary             ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 11, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(12, 'Call                ', 'Cameron             ', ' ', 'M', NULL, 2, 'JR', 0, '              ', ' ', NULL, 12, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(13, 'Charboneau          ', 'Kristin             ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 13, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(14, 'Coffey              ', 'Morgan              ', ' ', 'F', NULL, 2, 'SO', 0, '              ', ' ', NULL, 14, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(15, 'Conyers             ', 'Jessica             ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 15, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(16, 'DeGeorge            ', 'Breanna             ', ' ', 'F', NULL, 2, 'SR', 0, '              ', ' ', NULL, 16, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(17, 'Dixson              ', 'Sarah               ', ' ', 'F', NULL, 2, 'SR', 0, '              ', ' ', NULL, 17, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(18, 'Duckworth           ', 'Lacy                ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 18, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(19, 'Glesmann            ', 'Jordan              ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 19, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(20, 'Hericks             ', 'Krista              ', ' ', 'F', NULL, 2, 'SO', 0, '              ', ' ', NULL, 20, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(21, 'Higgins             ', 'Ryne                ', ' ', 'M', NULL, 2, 'SO', 0, '              ', ' ', NULL, 21, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(22, 'Hogan               ', 'Rachel              ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 22, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(23, 'Holmes              ', 'Kelley              ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 23, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(24, 'Hook                ', 'Sara                ', ' ', 'F', NULL, 2, 'SO', 0, '              ', ' ', NULL, 24, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(25, 'House               ', 'Autumn              ', ' ', 'F', NULL, 2, 'SO', 0, '              ', ' ', NULL, 25, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(26, 'Houston             ', 'Zachary             ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 26, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(27, 'Juarez-Ferris       ', 'Zachary             ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 27, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(28, 'Kohles              ', 'Tyler               ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 28, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(29, 'Laakso              ', 'Amanda              ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 29, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(30, 'Lawton              ', 'Chad                ', ' ', 'M', NULL, 2, 'JR', 0, '              ', ' ', NULL, 30, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(31, 'Lehn                ', 'John                ', ' ', 'M', NULL, 2, 'SO', 0, '              ', ' ', NULL, 31, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(32, 'Lehn                ', 'Sarah               ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 32, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(33, 'Leko                ', 'Adam                ', ' ', 'M', NULL, 2, 'SO', 0, '              ', ' ', NULL, 33, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(34, 'Lezanic             ', 'Courtney            ', ' ', 'F', NULL, 2, 'SR', 0, '              ', ' ', NULL, 34, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(35, 'Lopez               ', 'Chelsea             ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 35, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(36, 'Mansur              ', 'Mitchell            ', ' ', 'M', NULL, 2, 'SO', 0, '              ', ' ', NULL, 36, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(37, 'Martin              ', 'Rebeca              ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 37, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(38, 'Nelson              ', 'Michael             ', ' ', 'M', NULL, 2, 'JR', 0, '              ', ' ', NULL, 38, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(39, 'Orn                 ', 'Bethany             ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 39, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(40, 'Paasch              ', 'Jacob               ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 40, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(41, 'Pearce              ', 'Phillip             ', ' ', 'M', NULL, 2, 'SR', 0, '              ', ' ', NULL, 41, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(42, 'Peters              ', 'Ellery              ', ' ', 'F', NULL, 2, 'SR', 0, '              ', ' ', NULL, 42, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(43, 'Plaza               ', 'Kathryne            ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 43, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(44, 'Pohlman             ', 'Carly               ', ' ', 'F', NULL, 2, 'SO', 0, '              ', ' ', NULL, 44, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(45, 'Quandt              ', 'Brent               ', ' ', 'M', NULL, 2, 'SR', 0, '              ', ' ', NULL, 45, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(46, 'Quandt              ', 'Tara                ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 46, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(47, 'Ratliff             ', 'Orrin               ', ' ', 'M', NULL, 2, 'SR', 0, '              ', ' ', NULL, 47, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(48, 'Rhynalds            ', 'Maranda             ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 48, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(49, 'Schuetz             ', 'Heather             ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 49, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(50, 'Scott               ', 'Hayden              ', ' ', 'M', NULL, 2, 'SO', 0, '              ', ' ', NULL, 50, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(51, 'Sheley              ', 'Caitlin             ', ' ', 'F', NULL, 2, 'SR', 0, '              ', ' ', NULL, 51, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(52, 'Slater              ', 'Amber               ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 52, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(53, 'Staub               ', 'Ashley              ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 53, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(54, 'Szurpicki           ', 'Trent               ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 54, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(55, 'Taylor              ', 'Lauren              ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 55, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(56, 'Trickler            ', 'Jeffrey             ', ' ', 'M', NULL, 2, 'JR', 0, '              ', ' ', NULL, 56, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(57, 'Ueberrhein          ', 'Tessa               ', ' ', 'F', NULL, 2, 'FR', 0, '              ', ' ', NULL, 57, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(58, 'Urlacher            ', 'Justin              ', ' ', 'M', NULL, 2, 'SO', 0, '              ', ' ', NULL, 58, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(59, 'Vanicek             ', 'Stpehanie           ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 59, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(60, 'Way                 ', 'Allison             ', ' ', 'F', NULL, 2, 'SR', 0, '              ', ' ', NULL, 60, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(61, 'Williamson          ', 'Trevor              ', ' ', 'M', NULL, 2, 'SO', 0, '              ', ' ', NULL, 61, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(62, 'Winter              ', 'Jessica             ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 62, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(63, 'Wragge              ', 'Clare               ', ' ', 'F', NULL, 2, 'JR', 0, '              ', ' ', NULL, 63, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(64, 'Wragge              ', 'Jacob               ', ' ', 'M', NULL, 2, 'SR', 0, '              ', ' ', NULL, 64, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(65, 'Zbylut              ', 'Derek               ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 65, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(66, 'Zirbel              ', 'Nathaniel           ', ' ', 'M', NULL, 2, 'FR', 0, '              ', ' ', NULL, 66, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(67, 'Allely              ', 'Jonathan            ', ' ', 'M', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(68, 'Anderson            ', 'Elizabeth           ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(69, 'Arciniegas          ', 'Daniela             ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(70, 'Bailis              ', 'Laura               ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(71, 'Barnes              ', 'Tyler               ', ' ', 'M', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(72, 'Belk                ', 'Matthew             ', ' ', 'M', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(73, 'Bennett             ', 'Emily               ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(74, 'Bertsch             ', 'Jade                ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(75, 'Buckley             ', 'Raynee              ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(76, 'Byman               ', 'Brett               ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(77, 'Caudle              ', 'Malinda             ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(78, 'Clark               ', 'Justin              ', ' ', 'M', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(79, 'Clodfelter          ', 'Angela              ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(80, 'Duell               ', 'Katelyn             ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(81, 'Ensor               ', 'Kevin               ', ' ', 'M', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(82, 'Fischer             ', 'Evan                ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(83, 'Fisher              ', 'Chelsea             ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(84, 'Fox                 ', 'Victoria            ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(85, 'Gidley              ', 'Sarah               ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(86, 'Grobeck             ', 'Jill                ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(87, 'Hatter              ', 'Haley               ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(88, 'Huang               ', 'Jeremy              ', ' ', 'M', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(89, 'Jia                 ', 'Amy                 ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(90, 'Johnson             ', 'Lindsay             ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(91, 'Katelman            ', 'Michael             ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(92, 'Klasna              ', 'Megan               ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(93, 'Knott               ', 'Zachary             ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(94, 'Kofoed              ', 'Justin              ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(95, 'Kruger              ', 'Sarah               ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(96, 'Marty               ', 'Elizabeth           ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(97, 'Mattes              ', 'Theodoric           ', ' ', 'M', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(98, 'Mitsuya             ', 'Tomoki              ', ' ', 'M', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(99, 'Morris              ', 'Jessica             ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(100, 'Moser               ', 'Elena               ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(101, 'Muckey              ', 'Erin                ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(102, 'Ohrt                ', 'Kalen               ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(103, 'Parrish             ', 'Jillian             ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(104, 'Reerink             ', 'David               ', ' ', 'M', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(105, 'Reese               ', 'Justin              ', ' ', 'M', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(106, 'Roberts             ', 'Sean                ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(107, 'Schoreit            ', 'Bradley             ', ' ', 'M', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(108, 'Schreiber           ', 'Spencer             ', ' ', 'M', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(109, 'Shaffer             ', 'Scott               ', ' ', 'M', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(110, 'Shull               ', 'Taylar              ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(111, 'Slingwine           ', 'Brittany            ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(112, 'Smith               ', 'Megan               ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(113, 'Stephenson          ', 'Christina           ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(114, 'Tesarek             ', 'Lisa                ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(115, 'Tomek               ', 'Chelsea             ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(116, 'Trant               ', 'Duke                ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(117, 'Triba               ', 'Samantha            ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(118, 'Tsushba             ', 'Amina               ', ' ', 'F', NULL, 3, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(119, 'Tucker              ', 'Miriam              ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(120, 'Vanderveen          ', 'Natalie             ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(121, 'Vest                ', 'Brianna             ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(122, 'Vest                ', 'Jeremy              ', ' ', 'M', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(123, 'Vetter              ', 'Danielle            ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(124, 'Weaver              ', 'Angela              ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(125, 'Williams            ', 'Jonah               ', ' ', 'M', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(126, 'Wood                ', 'Allison             ', ' ', 'F', NULL, 3, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(127, 'Yearsley            ', 'Sarah               ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(128, 'Yenney              ', 'Chase               ', ' ', 'M', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(129, 'Yong                ', 'Candace             ', ' ', 'F', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(130, 'Zach                ', 'Janelle             ', ' ', 'F', NULL, 3, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(131, 'Zhang               ', 'Jason               ', ' ', 'M', NULL, 3, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(132, 'Abbott              ', 'Abraham             ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(133, 'Born                ', 'Elisa               ', ' ', 'F', NULL, 4, 'SR', 0, '              ', ' ', NULL, 113, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(134, 'Borytsky            ', 'Mark                ', ' ', 'M', NULL, 4, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(135, 'Briggs              ', 'Zachary             ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(136, 'Bruce               ', 'Kelly               ', ' ', 'F', NULL, 4, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(137, 'Carmichael          ', 'James               ', ' ', 'M', NULL, 4, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(138, 'Creighton           ', 'Brianna             ', ' ', 'F', NULL, 4, 'SR', 0, '              ', ' ', NULL, 117, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(139, 'Cunningham          ', 'Shawn               ', ' ', 'M', NULL, 4, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(140, 'Garrison            ', 'Jordan              ', ' ', 'F', NULL, 4, 'FR', 0, '              ', ' ', NULL, 124, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(141, 'Gillespie           ', 'Lindsey             ', ' ', 'F', NULL, 4, 'SO', 0, '              ', ' ', NULL, 118, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(142, 'Gloria              ', 'William             ', ' ', 'M', NULL, 4, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(143, 'Goff                ', 'Kayleyne            ', ' ', 'F', NULL, 4, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(144, 'Graber              ', 'Leslie              ', ' ', 'M', NULL, 4, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(145, 'Gregory             ', 'Kelly               ', ' ', 'F', NULL, 4, 'SO', 0, '              ', ' ', NULL, 128, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(146, 'Hanson              ', 'Christopher         ', ' ', 'M', NULL, 4, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(147, 'Hoffman             ', 'Mary                ', ' ', 'F', NULL, 4, 'SO', 0, '              ', ' ', NULL, 119, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(148, 'Hoover              ', 'Alison              ', ' ', 'F', NULL, 4, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(149, 'Kosch               ', 'Jessie              ', ' ', 'F', NULL, 4, 'JR', 0, '              ', ' ', NULL, 120, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(150, 'Koziel              ', 'Zachary             ', ' ', 'M', NULL, 4, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(151, 'Laux                ', 'Daniel              ', ' ', 'M', NULL, 4, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(152, 'McCool              ', 'Paul                ', ' ', 'M', NULL, 4, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(153, 'McCormick           ', 'Joshua              ', ' ', 'M', NULL, 4, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(154, 'McGlynn             ', 'Casey               ', ' ', 'F', NULL, 4, 'FR', 0, '              ', ' ', NULL, 125, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(155, 'McMullan            ', 'Kelsey              ', ' ', 'F', NULL, 4, 'SO', 0, '              ', ' ', NULL, 121, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(156, 'Mireles             ', 'Nathaniel           ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(157, 'Monnier             ', 'William             ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(158, 'Nesiba              ', 'Michelle            ', ' ', 'F', NULL, 4, 'SO', 0, '              ', ' ', NULL, 122, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(159, 'Nicholson           ', 'Jeremy              ', ' ', 'M', NULL, 4, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(160, 'Ramos               ', 'Adrienne            ', ' ', 'F', NULL, 4, 'JR', 0, '              ', ' ', NULL, 123, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(161, 'Schneider           ', 'Joshua              ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(162, 'Schrader            ', 'Ty                  ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(163, 'Steele              ', 'Jennifer            ', ' ', 'F', NULL, 4, 'JR', 0, '              ', ' ', NULL, 116, 'Jenny               ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(164, 'Stenner             ', 'Leilani             ', ' ', 'F', NULL, 4, 'FR', 0, '              ', ' ', NULL, 126, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(165, 'Stimson             ', 'David               ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(166, 'Stough              ', 'Sydney              ', ' ', 'F', NULL, 4, 'SO', 0, '              ', ' ', NULL, 127, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(167, 'Strachota           ', 'Kurt                ', ' ', 'M', NULL, 4, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(168, 'Swanson             ', 'Sean                ', ' ', 'M', NULL, 4, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(169, 'Wiles               ', 'Tracy               ', ' ', 'F', NULL, 4, 'SR', 0, '              ', ' ', NULL, 114, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(170, 'Williamson          ', 'Eric                ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(171, 'Yoder               ', 'Banjamin            ', ' ', 'M', NULL, 4, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(172, 'Babbe               ', 'Wayne               ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(173, 'Brown               ', 'Terrence            ', ' ', 'M', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(174, 'Burton              ', 'James               ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(175, 'Cherubin            ', 'Alexander           ', ' ', 'M', NULL, 5, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(176, 'Clement             ', 'Krista              ', ' ', 'F', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(177, 'Collins             ', 'Gregory             ', ' ', 'M', NULL, 5, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(178, 'Conway              ', 'Brianna             ', ' ', 'F', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(179, 'Coultas             ', 'Grant               ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(180, 'Donavon             ', 'Robert              ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(181, 'Dunkleman           ', 'Matthew             ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(182, 'Grange              ', 'Margaret            ', ' ', 'F', NULL, 5, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(183, 'Guericke            ', 'Dustin              ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(184, 'Gutierrez           ', 'Aaron               ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(185, 'Hascall             ', 'Michael             ', ' ', 'M', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(186, 'Holden              ', 'Justin              ', ' ', 'M', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(187, 'Hopkins             ', 'George              ', ' ', 'M', NULL, 5, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(188, 'Huey                ', 'Aubrey              ', ' ', 'F', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(189, 'Huey                ', 'Autumn              ', ' ', 'F', NULL, 5, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(190, 'Husone              ', 'Sydney              ', ' ', 'F', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(191, 'Johnson             ', 'Sabrina             ', ' ', 'F', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(192, 'Kilgore             ', 'Kristen             ', ' ', 'F', NULL, 5, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(193, 'Kruger              ', 'Aaron               ', ' ', 'M', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(194, 'McCain              ', 'Elizabeth           ', ' ', 'F', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(195, 'McCain              ', 'Jessica             ', ' ', 'F', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(196, 'Moran               ', 'Sarah               ', ' ', 'F', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(197, 'Peck                ', 'Alexander           ', ' ', 'M', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(198, 'Pelletier           ', 'Stephanie           ', ' ', 'F', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(199, 'Phillips            ', 'Angela              ', ' ', 'F', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(200, 'Porter              ', 'Ashton              ', ' ', 'F', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(201, 'Purington           ', 'Gage                ', ' ', 'M', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(202, 'Reck                ', 'Caitlin             ', ' ', 'F', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(203, 'Reynolds            ', 'Tyler               ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(204, 'Rieke               ', 'Brody               ', ' ', 'M', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(205, 'Riggles             ', 'Jordyn              ', ' ', 'F', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(206, 'Severin             ', 'Kelsey              ', ' ', 'F', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(207, 'Shapland            ', 'Amanda              ', ' ', 'F', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(208, 'Shapland            ', 'Autumn              ', ' ', 'F', NULL, 5, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(209, 'Smith               ', 'Philip              ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(210, 'Smith               ', 'Shawn               ', ' ', 'M', NULL, 5, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(211, 'Smits               ', 'Sara                ', ' ', 'F', NULL, 5, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(212, 'Spicer              ', 'Erin                ', ' ', 'F', NULL, 5, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(213, 'Tesar               ', 'Cole                ', ' ', 'M', NULL, 5, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(214, 'Arlt                ', 'Abbey               ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(215, 'Augustine           ', 'Chelsea             ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(216, 'Beam                ', 'Dalton              ', ' ', 'M', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(217, 'Boeding             ', 'Anna                ', ' ', 'F', NULL, 6, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(218, 'Burger              ', 'Whitney             ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(219, 'Busse               ', 'Janessa             ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(220, 'Connolly            ', 'Kelly               ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(221, 'Danforth            ', 'Jonathan            ', ' ', 'M', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(222, 'Gearhart            ', 'Brittany            ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(223, 'Green               ', 'Shelly              ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(224, 'Harrington          ', 'Meggie              ', ' ', 'F', NULL, 6, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(225, 'Hitz                ', 'Austin              ', ' ', 'M', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(226, 'Hoeft               ', 'Jennifer            ', ' ', 'F', NULL, 6, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(227, 'Hutson              ', 'Sarah               ', ' ', 'F', NULL, 6, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(228, 'Irby                ', 'Katie               ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(229, 'Jackson             ', 'Moe                 ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(230, 'Jackson             ', 'Sarah               ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(231, 'Jacobson            ', 'Kristen             ', ' ', 'F', NULL, 6, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(232, 'Johnson             ', 'Jodi                ', ' ', 'F', NULL, 6, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(233, 'Kuehler             ', 'Daniel              ', ' ', 'M', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(234, 'Lawrence            ', 'Kendra              ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(235, 'Lindquist           ', 'Benjamin            ', ' ', 'M', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(236, 'Lindquist           ', 'Daniel              ', ' ', 'M', NULL, 6, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(237, 'Lord                ', 'Samantha            ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(238, 'Menchaca            ', 'Kristen             ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(239, 'Mueller             ', 'Natalia             ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(240, 'Mueller             ', 'Nathan              ', ' ', 'M', NULL, 6, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(241, 'Picard              ', 'Veronica            ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(242, 'Ryan                ', 'Cassandra           ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(243, 'Schoening           ', 'Jenna               ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(244, 'Seipel              ', 'Bethany             ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(245, 'Sharman             ', 'Derek               ', ' ', 'M', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(246, 'Sharman             ', 'Jared               ', ' ', 'M', NULL, 6, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(247, 'Slizoski            ', 'Macie               ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(248, 'Smith               ', 'Katherine           ', ' ', 'F', NULL, 6, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(249, 'Sobotka             ', 'Nikki               ', ' ', 'F', NULL, 6, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(250, 'Sutherland          ', 'Andrea              ', ' ', 'F', NULL, 6, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(251, 'Tate                ', 'Zachary             ', ' ', 'M', NULL, 6, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(252, 'Avery               ', 'William             ', ' ', 'M', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(253, 'Babcock             ', 'Donald              ', ' ', 'M', NULL, 7, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(254, 'Babcock             ', 'Robert              ', ' ', 'M', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(255, 'Babcock             ', 'Thomas              ', ' ', 'M', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(256, 'Bice                ', 'Cheyenne            ', ' ', 'F', NULL, 7, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(257, 'Case-Ruchala        ', 'Celeste             ', ' ', 'F', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(258, 'Cheever             ', 'Adam                ', ' ', 'M', NULL, 7, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(259, 'Collier             ', 'Carolyn             ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(260, 'Dolan               ', 'Erin                ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(261, 'Ebers               ', 'Steven              ', ' ', 'M', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(262, 'Eihusen             ', 'Kelli               ', ' ', 'F', NULL, 7, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(263, 'Ernst               ', 'Hilary              ', ' ', 'F', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(264, 'Ficke               ', 'Hattie              ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(265, 'Frederick           ', 'Adam                ', ' ', 'M', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(266, 'Frederick           ', 'Andrea              ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(267, 'Ganz                ', 'Stephen             ', ' ', 'M', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(268, 'Henry               ', 'Aaron               ', ' ', 'M', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(269, 'Heyen               ', 'Patrick             ', ' ', 'M', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(270, 'Hilderbrand         ', 'Brooke              ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(271, 'Hinrichs            ', 'Emma                ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(272, 'Hottovy             ', 'Heather             ', ' ', 'F', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(273, 'Johnson             ', 'Jessica             ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(274, 'Knobel              ', 'Shelby              ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(275, 'Kockerbeck          ', 'Carolin             ', ' ', 'F', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(276, 'Kottas              ', 'Mandy               ', ' ', 'F', NULL, 7, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(277, 'Krieger             ', 'Jennifer            ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(278, 'Lamphere            ', 'John                ', ' ', 'M', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(279, 'Lewicki             ', 'Natalia             ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(280, 'Loschen             ', 'Jeffery             ', ' ', 'M', NULL, 7, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(281, 'McIntyre            ', 'Lindsey             ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(282, 'Mejia               ', 'Brooke              ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(283, 'Monk                ', 'Amanda              ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(284, 'Nelson              ', 'Dylan               ', ' ', 'M', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(285, 'Nelson              ', 'Katie               ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(286, 'Ohs                 ', 'Matthew             ', ' ', 'M', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(287, 'Pappas              ', 'Nicole              ', ' ', 'F', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(288, 'Schwarting          ', 'Alyssa              ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(289, 'Skarp               ', 'Nicole              ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(290, 'Sughroue            ', 'Lily                ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(291, 'Thacker-Lynn        ', 'Seth                ', ' ', 'M', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(292, 'Torske              ', 'Kayla               ', ' ', 'F', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(293, 'Tucker              ', 'Sarah               ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(294, 'Tuttle              ', 'Carson              ', ' ', 'M', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(295, 'Tuttle              ', 'Kristin             ', ' ', 'F', NULL, 7, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(296, 'Verbenko            ', 'Georgiy             ', ' ', 'M', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(297, 'Weiss               ', 'Molly               ', ' ', 'F', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(298, 'Wekesser            ', 'Amanda              ', ' ', 'F', NULL, 7, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(299, 'Wekesser            ', 'Lauren              ', ' ', 'F', NULL, 7, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(300, 'West                ', 'Nathaniel           ', ' ', 'M', NULL, 7, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(301, 'Benton              ', 'John                ', ' ', 'M', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(302, 'Bornhoft            ', 'Jordann             ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, 'Jordie              ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(303, 'Brown               ', 'Joshua              ', ' ', 'M', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(304, 'Christiansen        ', 'Kelsey              ', ' ', 'F', NULL, 8, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(305, 'Conway              ', 'Amanda              ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(306, 'Couillard           ', 'Kylie               ', ' ', 'F', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(307, 'Eversoll            ', 'Madeline            ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(308, 'Fenello             ', 'Taylor              ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(309, 'Grosshans           ', 'Sean                ', ' ', 'M', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(310, 'Guenther            ', 'Heather             ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(311, 'Hahne               ', 'Mattson             ', ' ', 'M', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(312, 'Hakel               ', 'Scott               ', ' ', 'M', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(313, 'Hansen              ', 'Stacie              ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(314, 'Hassebrook          ', 'Kim                 ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(315, 'Hoffmeyer           ', 'Blake               ', ' ', 'M', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(316, 'Hogan               ', 'Alexander           ', ' ', 'M', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, 'Alex                ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(317, 'Holechek            ', 'Samantha            ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, 'Sam                 ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(318, 'Howerter            ', 'Taylor              ', ' ', 'M', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(319, 'Johnson             ', 'Alexander           ', ' ', 'M', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, 'Alex                ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(320, 'Johnson             ', 'Dustin              ', ' ', 'M', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(321, 'Jones               ', 'Jamie               ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(322, 'Jones               ', 'Kari                ', ' ', 'F', NULL, 8, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(323, 'Kaltenberger        ', 'Megan               ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(324, 'Kocher              ', 'Philip              ', ' ', 'M', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(325, 'Kontor              ', 'Broc                ', ' ', 'M', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(326, 'Lausten             ', 'Morgan              ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(327, 'Leblanc             ', 'Olivia              ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(328, 'Lester              ', 'Katherine           ', ' ', 'F', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, 'Katie               ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(329, 'Luke                ', 'Logan               ', ' ', 'M', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(330, 'Marvin              ', 'Elyse               ', ' ', 'F', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, 'Ellie               ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(331, 'May                 ', 'Nicholas            ', ' ', 'M', NULL, 8, 'SR', 0, '              ', ' ', NULL, 0, 'Nick                ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(332, 'Meister             ', 'Samuel              ', ' ', 'M', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(333, 'Mills               ', 'Thomas              ', ' ', 'M', NULL, 8, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(334, 'Moore               ', 'Breanna             ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(335, 'Moore               ', 'Kamala              ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(336, 'Morten              ', 'Alex                ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(337, 'Murphy              ', 'Aaron               ', ' ', 'M', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(338, 'Murphy              ', 'Sean                ', ' ', 'M', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(339, 'Nissen              ', 'Justin              ', ' ', 'M', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(340, 'Patt                ', 'Michaela            ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(341, 'Prieb               ', 'Brittany            ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(342, 'Rajkumar            ', 'Nevin               ', ' ', 'M', NULL, 8, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(343, 'Rogers              ', 'Alissa              ', ' ', 'F', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(344, 'Ryan                ', 'Meghan              ', ' ', 'F', NULL, 8, 'SR', 0, '              ', ' ', NULL, 0, 'Meg                 ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(345, 'Sabin               ', 'Carli               ', ' ', 'F', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(346, 'Schell              ', 'Faith               ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(347, 'Sheridan            ', 'Jenay               ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(348, 'Spataro             ', 'Channiyel           ', ' ', 'F', NULL, 8, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(349, 'Spataro             ', 'Solomon             ', ' ', 'M', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(350, 'Suelter             ', 'Courtney            ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(351, 'Sutton              ', 'Logan               ', ' ', 'M', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(352, 'Taylor              ', 'Jordan              ', ' ', 'M', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(353, 'Troxel              ', 'Andrea              ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(354, 'Troxel              ', 'Kellie              ', ' ', 'F', NULL, 8, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(355, 'Troxel              ', 'Tyler               ', ' ', 'M', NULL, 8, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(356, 'Walter              ', 'Chelsea             ', ' ', 'F', NULL, 8, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(357, 'Wanner              ', 'Celeste             ', ' ', 'F', NULL, 8, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(358, 'Appleby             ', 'Alyssa              ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(359, 'Arntz               ', 'Kaitlin             ', ' ', 'F', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(360, 'Blackstone          ', 'Samuel              ', ' ', 'M', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(361, 'Blanton             ', 'Tammy               ', ' ', 'F', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(362, 'Bray                ', 'Jordan              ', ' ', 'M', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(363, 'Butsyak             ', 'Marko               ', ' ', 'M', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(364, 'Cadio               ', 'Aaron               ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(365, 'Campbell            ', 'Anna                ', ' ', 'F', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(366, 'Carlson             ', 'Caitlin             ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(367, 'Chamberlain         ', 'Megan               ', ' ', 'F', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(368, 'Christensen         ', 'Bianca              ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(369, 'Friesen             ', 'Kristen             ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(370, 'Gregory             ', 'Jill                ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(371, 'Grogan              ', 'Lauren              ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(372, 'Grohn               ', 'Emilee              ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(373, 'Hanson              ', 'Elliott             ', ' ', 'M', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(374, 'Hesselink           ', 'Adam                ', ' ', 'M', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(375, 'Hindmarsh           ', 'Jill                ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(376, 'Howard              ', 'Anna                ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(377, 'Howard              ', 'Kevin               ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(378, 'Illg                ', 'Ellen               ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(379, 'Kaeter              ', 'Sophie              ', ' ', 'F', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(380, 'Klaiber             ', 'Colleen             ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(381, 'Klaiber             ', 'Dawn                ', ' ', 'F', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(382, 'Knapp               ', 'Tyler               ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(383, 'Knight              ', 'Kelly               ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(384, 'Koris               ', 'Kylie               ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(385, 'Masterson           ', 'Brett               ', ' ', 'M', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(386, 'McClay              ', 'Sean                ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(387, 'McCrady             ', 'Alyssa              ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(388, 'Merkel              ', 'Lydia               ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(389, 'Meuret              ', 'Jenna               ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(390, 'Murphy              ', 'Caitlin             ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(391, 'Neal                ', 'Andrew              ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(392, 'Palmer              ', 'Rebecca             ', ' ', 'F', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(393, 'Papatyi             ', 'Meagan              ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(394, 'Pengilly            ', 'Molly               ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(395, 'Pengilly            ', 'Teresa              ', ' ', 'F', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(396, 'Peterson            ', 'Carl                ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(397, 'Raffett             ', 'Adam                ', ' ', 'M', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(398, 'Raffety             ', 'Nathan              ', ' ', 'M', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(399, 'Rice                ', 'Rebecca             ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(400, 'Ritter              ', 'Ian                 ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(401, 'Rounds              ', 'Katherine           ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(402, 'Sacco               ', 'Hannah              ', ' ', 'F', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(403, 'Schneider           ', 'Eric                ', ' ', 'M', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(404, 'Shapland            ', 'Justin              ', ' ', 'M', NULL, 9, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(405, 'Sheppard            ', 'Alex                ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(406, 'Sirotkin            ', 'Emily               ', ' ', 'F', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(407, 'Sirotkin            ', 'Sarah               ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(408, 'Smith               ', 'Andria              ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(409, 'Smith               ', 'Brian               ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(410, 'Smutny              ', 'Jennifer            ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(411, 'Springer            ', 'Jacob               ', ' ', 'M', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(412, 'Stauffer            ', 'Kayla               ', ' ', 'F', NULL, 9, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(413, 'Stauffer            ', 'Seth                ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(414, 'Twight              ', 'Daniel              ', ' ', 'M', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(415, 'Van Wetering        ', 'Agostino            ', ' ', 'M', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(416, 'Ward                ', 'William             ', ' ', 'M', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(417, 'Weihl               ', 'Kari                ', ' ', 'F', NULL, 9, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(418, 'Wu                  ', 'Bin bin             ', ' ', 'M', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(419, 'Yee                 ', 'Christine           ', ' ', 'F', NULL, 9, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(420, 'Blum                ', 'Alicia              ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(421, 'Bolin               ', 'Danielle            ', ' ', 'F', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(422, 'Coronado            ', 'Hector              ', ' ', 'M', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(423, 'Deuel               ', 'Britton             ', ' ', 'M', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(424, 'Dinslage            ', 'Tyler               ', ' ', 'M', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(425, 'Felber              ', 'Calan               ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(426, 'Greenough           ', 'Trevor              ', ' ', 'M', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(427, 'Hoesing             ', 'Rebecca             ', ' ', 'F', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(428, 'Huwaldt             ', 'Sara                ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(429, 'Johnson             ', 'Natasha             ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(430, 'Kruger              ', 'Franziska           ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(431, 'Langel              ', 'Josh                ', ' ', 'M', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(432, 'Lechner             ', 'Jared               ', ' ', 'M', NULL, 10, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(433, 'Levi                ', 'Chad                ', ' ', 'M', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(434, 'Lutt                ', 'Ashley              ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(435, 'Maier               ', 'Kaitlyn             ', ' ', 'F', NULL, 10, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(436, 'Maier               ', 'Luke                ', ' ', 'M', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(437, 'Morse               ', 'Rebecca             ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(438, 'Nelson              ', 'Morgan              ', ' ', 'F', NULL, 10, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(439, 'Olenich             ', 'Katelyn             ', ' ', 'F', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(440, 'Olenich             ', 'Sara                ', ' ', 'F', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(441, 'Pickinpaugh         ', 'Hilary              ', ' ', 'F', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(442, 'Portugal            ', 'Luc                 ', ' ', 'M', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(443, 'Pruss               ', 'AJ                  ', ' ', 'M', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(444, 'Pruss               ', 'Mitchell            ', ' ', 'M', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(445, 'Sanders             ', 'Kyle                ', ' ', 'M', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(446, 'Schlake             ', 'Lesley              ', ' ', 'F', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(447, 'Schroeder           ', 'Carlie              ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(448, 'Schukei             ', 'Tony                ', ' ', 'M', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(449, 'Skoglund            ', 'Sam                 ', ' ', 'M', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(450, 'Spahn               ', 'Tyler               ', ' ', 'M', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(451, 'Vinke               ', 'Isabell             ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(452, 'Wemhoff             ', 'Kyle                ', ' ', 'M', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(453, 'Wiedeman            ', 'Brittney            ', ' ', 'F', NULL, 10, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(454, 'Williams            ', 'Cyra                ', ' ', 'F', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(455, 'Wolff               ', 'Marty               ', ' ', 'M', NULL, 10, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(456, 'Wyant               ', 'James               ', ' ', 'M', NULL, 10, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(457, 'Allee               ', 'Ashley              ', ' ', 'F', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(458, 'Benak               ', 'Bridget             ', ' ', 'F', NULL, 11, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(459, 'Brester             ', 'Samuel              ', ' ', 'M', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(460, 'Burt                ', 'Cody                ', ' ', 'M', NULL, 11, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(461, 'Carruthers          ', 'Kyle                ', ' ', 'M', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(462, 'Correa              ', 'Noah                ', ' ', 'M', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(463, 'Ederer              ', 'Marcus              ', ' ', 'M', NULL, 11, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(464, 'Ellison             ', 'Jeremy              ', ' ', 'M', NULL, 11, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(465, 'Elsasser            ', 'Nicholas            ', ' ', 'M', NULL, 11, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(466, 'Evans               ', 'Neali               ', ' ', 'F', NULL, 11, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(467, 'Fisher              ', 'Michael             ', ' ', 'M', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(468, 'Hara                ', 'Tomasz              ', ' ', 'M', NULL, 11, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(469, 'Hernandez           ', 'Sonja               ', ' ', 'F', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(470, 'Honig               ', 'Angela              ', ' ', 'F', NULL, 11, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(471, 'Jansen              ', 'Andrew              ', ' ', 'M', NULL, 11, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(472, 'Jones               ', 'Jenna               ', ' ', 'F', NULL, 11, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(473, 'Larsons             ', 'Rebecca             ', ' ', 'F', NULL, 11, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(474, 'Marin               ', 'Nicole              ', ' ', 'F', NULL, 11, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(475, 'Morin               ', 'Kelsey              ', ' ', 'F', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(476, 'Navarro             ', 'Nicholas            ', ' ', 'M', NULL, 11, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(477, 'Ortiz Gutierrez     ', 'Bayron              ', ' ', 'M', NULL, 11, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(478, 'Ortiz Gutierrez     ', 'Osvaldo             ', ' ', 'M', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(479, 'Rubert              ', 'Breno               ', ' ', 'M', NULL, 11, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(480, 'Smolinski           ', 'Bethany             ', ' ', 'F', NULL, 11, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(481, 'Stopak              ', 'Corey               ', ' ', 'M', NULL, 11, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(482, 'Stopak              ', 'Kellie              ', ' ', 'F', NULL, 11, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(483, 'Suggs               ', 'Stephanie           ', ' ', 'F', NULL, 11, '  ', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(484, 'Susnjar             ', 'Brandi              ', ' ', 'F', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(485, 'Tadic               ', 'Ivan                ', ' ', 'M', NULL, 11, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(486, 'Wakefield           ', 'Sara                ', ' ', 'F', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(487, 'Welnda-Landholm     ', 'Benjamen            ', ' ', 'M', NULL, 11, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(488, 'Yagodinski          ', 'Gina                ', ' ', 'F', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(489, 'Zyla                ', 'Christopher         ', ' ', 'M', NULL, 11, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(490, 'Adams               ', 'Dana                ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(491, 'Anderson            ', 'Clayton             ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(492, 'Arciniegas          ', 'Santiago            ', ' ', 'M', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(493, 'Barnett             ', 'Bryan               ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(494, 'Baumann             ', 'Carly               ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(495, 'Beasley             ', 'Laura               ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(496, 'Bechdolt            ', 'Amy                 ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(497, 'Berry               ', 'James               ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(498, 'Biodrowski          ', 'Jessica             ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(499, 'Brown               ', 'Joshua              ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(500, 'Cameron             ', 'Christopher         ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(501, 'Cederberg           ', 'Gina                ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(502, 'Christiansen        ', 'Brandon             ', ' ', 'M', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(503, 'Colin               ', 'Felipe              ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(504, 'Daigle              ', 'Adrienne            ', ' ', 'F', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(505, 'DeJong              ', 'Brianna             ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(506, 'Drake               ', 'Courtney            ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(507, 'Durrie              ', 'Scott               ', ' ', 'M', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(508, 'Eldridge            ', 'James               ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(509, 'Evans               ', 'Emma                ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(510, 'Evans               ', 'Rebecca             ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(511, 'Fahrlander          ', 'Jacob               ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(512, 'Friis               ', 'Kyle                ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(513, 'Gofta               ', 'Kiley               ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(514, 'Harris              ', 'Ryan                ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(515, 'Haskett             ', 'Anthony             ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(516, 'Hirchert            ', 'Kevin               ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(517, 'Hoff                ', 'Steven              ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(518, 'Isildak             ', 'Berk                ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(519, 'Kalantjakos         ', 'Timothy             ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(520, 'Kirby               ', 'Aaron               ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(521, 'Knight              ', 'Paige               ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(522, 'Lebbert             ', 'Riley               ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(523, 'Locke               ', 'Abby                ', ' ', 'F', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(524, 'Lubbert             ', 'Brent               ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(525, 'Marshall            ', 'Michaela            ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(526, 'Marshall            ', 'Samantha            ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(527, 'Martens             ', 'Joshua              ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(528, 'McCann              ', 'Alexandria          ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(529, 'Miller              ', 'Marcus              ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(530, 'Miller              ', 'Max                 ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(531, 'Moertl              ', 'Julia               ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(532, 'Morgan              ', 'Brooke              ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(533, 'Naughton            ', 'Andrea              ', ' ', 'F', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(534, 'Nelson              ', 'Carolyn             ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(535, 'Obradovich          ', 'Christopher         ', ' ', 'M', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(536, 'Odinas              ', 'Catherine           ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(537, 'Opheim              ', 'Stephanie           ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(538, 'Phillips            ', 'Braedon             ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(539, 'Pittack             ', 'John                ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(540, 'Prost               ', 'Timothy             ', ' ', 'M', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(541, 'Sanchez             ', 'Fatima              ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(542, 'Sanchez             ', 'Maria               ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(543, 'Schmitt             ', 'Neal                ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(544, 'Schmoker            ', 'Sarah               ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(545, 'Sexson              ', 'Gabrielle           ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(546, 'Sigmon              ', 'John                ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(547, 'Snipes              ', 'Nelson              ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(548, 'Sproedt             ', 'Beate               ', ' ', 'F', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(549, 'Tarlton             ', 'Ivan                ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(550, 'Thue                ', 'Brittany            ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(551, 'Trubnikov           ', 'David               ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(552, 'Tweedy              ', 'Shane               ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(553, 'Vail                ', 'Stephanie           ', ' ', 'F', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(554, 'Vernon              ', 'Hamilton            ', ' ', 'M', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(555, 'Walther             ', 'Stephanie           ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(556, 'Watson              ', 'Allex               ', ' ', 'F', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(557, 'Werthmann           ', 'Bradley             ', ' ', 'M', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(558, 'Westcott            ', 'Ashley              ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(559, 'Whelan              ', 'Joseph              ', ' ', 'M', NULL, 12, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(560, 'White               ', 'Aimee               ', ' ', 'F', NULL, 12, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(561, 'Williams            ', 'Jessica             ', ' ', 'F', NULL, 12, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(562, 'Zuniga-Pinto        ', 'Stephanie           ', ' ', 'F', NULL, 12, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(563, 'Alfaro              ', 'Elizabeth           ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(564, 'Anderson            ', 'Lindsey             ', ' ', 'F', NULL, 13, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(565, 'Arrington           ', 'Elaine              ', ' ', 'F', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(566, 'Barrientos          ', 'Emilio              ', ' ', 'M', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(567, 'Baumann             ', 'Margaret            ', ' ', 'F', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(568, 'Bissell             ', 'Trevor              ', ' ', 'M', NULL, 13, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(569, 'Bottger             ', 'Dana                ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(570, 'Bottger             ', 'Erin                ', ' ', 'F', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(571, 'Brown               ', 'Jennifer            ', ' ', 'F', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(572, 'Bruner              ', 'Brittany            ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(573, 'Buller              ', 'Bailey              ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(574, 'Cardenas            ', 'Jose                ', ' ', 'M', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(575, 'Cobb                ', 'Bonnie              ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(576, 'Danielson           ', 'Ian                 ', ' ', 'M', NULL, 13, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(577, 'Danielson           ', 'Sara                ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(578, 'Dilocker            ', 'Bailey              ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(579, 'Dittman             ', 'Jared               ', ' ', 'M', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(580, 'Farrell             ', 'Caitlin             ', ' ', 'F', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(581, 'Freeman             ', 'Susan               ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(582, 'Genovesi            ', 'Angelica            ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(583, 'Hallgren            ', 'Sara                ', ' ', 'F', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(584, 'Hansen              ', 'Caitlin             ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(585, 'Hansen              ', 'Claire              ', ' ', 'F', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(586, 'Hartig              ', 'Jane                ', ' ', 'F', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(587, 'Hartig              ', 'Katherine           ', ' ', 'F', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(588, 'Hubbard             ', 'Alexandra           ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(589, 'Huddle              ', 'Brianna             ', ' ', 'F', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(590, 'Huetter             ', 'Stephanie           ', ' ', 'F', NULL, 13, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(591, 'Jasnowski           ', 'Julie               ', ' ', 'F', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(592, 'Johnson             ', 'John                ', ' ', 'M', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(593, 'Kasady              ', 'Sonia               ', ' ', 'F', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(594, 'Kielion             ', 'Joel                ', ' ', 'M', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(595, 'Kielion             ', 'Scott               ', ' ', 'M', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(596, 'Kraft               ', 'Laura               ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(597, 'Kroeger             ', 'Andrew              ', ' ', 'M', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(598, 'Larson              ', 'Andrew              ', ' ', 'M', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(599, 'Malloy              ', 'Olivia              ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(600, 'Meyer               ', 'Gregory             ', ' ', 'M', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(601, 'Miller              ', 'Carl                ', ' ', 'M', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(602, 'Niebaum             ', 'Hannah              ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(603, 'Seefus              ', 'Jacob               ', ' ', 'M', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(604, 'Shepherd            ', 'Michael             ', ' ', 'M', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(605, 'Stanford            ', 'Nicole              ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(606, 'Stoffel             ', 'Robert              ', ' ', 'M', NULL, 13, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(607, 'Stoffel             ', 'Ryan                ', ' ', 'M', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(608, 'Stoler              ', 'Madisen             ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(609, 'Taylor              ', 'Andrew              ', ' ', 'M', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(610, 'Thomas              ', 'Benjamin            ', ' ', 'M', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(611, 'Vacha               ', 'Sara                ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(612, 'Vandewark           ', 'Maria               ', ' ', 'F', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(613, 'Walsh               ', 'Michael             ', ' ', 'M', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(614, 'Wang                ', 'Shuona              ', ' ', 'F', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(615, 'Wegner              ', 'Michael             ', ' ', 'M', NULL, 13, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(616, 'Woolard             ', 'Rachel              ', ' ', 'F', NULL, 13, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(617, 'Zhang               ', 'Mai                 ', ' ', 'M', NULL, 13, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(618, 'Ashby               ', 'Andrew              ', ' ', 'M', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(619, 'Bach                ', 'Maggie              ', ' ', 'F', NULL, 14, 'SR', 0, '              ', ' ', NULL, 69, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(620, 'Bach                ', 'Sam                 ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(621, 'Bassett             ', 'Adam                ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(622, 'Bennett             ', 'Joshua              ', ' ', 'M', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(623, 'Bunde               ', 'Ali                 ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 70, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(624, 'Carney              ', 'Tyler               ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(625, 'Cole                ', 'Zachary             ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(626, 'Connelly            ', 'Emily               ', ' ', 'F', NULL, 14, 'SR', 0, '              ', ' ', NULL, 71, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(627, 'D\'Adamio            ', 'Stefano             ', ' ', 'M', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(628, 'Danahy              ', 'Kelly               ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(629, 'Daugherty           ', 'Garett              ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(630, 'Daugherty           ', 'Kristin             ', ' ', 'F', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(631, 'Ediger              ', 'Nolan               ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(632, 'Eppert              ', 'Taylor              ', ' ', 'M', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(633, 'Finke               ', 'Dain                ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(634, 'Forgey              ', 'Derek               ', ' ', 'M', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(635, 'Fox                 ', 'Rebecca             ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(636, 'Franti              ', 'Tanner              ', ' ', 'M', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(637, 'Froehlich           ', 'Jared               ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(638, 'Geier               ', 'Jonathan            ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(639, 'Genrich             ', 'Nic                 ', ' ', 'M', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(640, 'Grubbe              ', 'Alyce               ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(641, 'Hinds               ', 'Logan               ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(642, 'Hubbell             ', 'Kylie               ', ' ', 'F', NULL, 14, 'SO', 0, '              ', ' ', NULL, 72, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(643, 'Johnson             ', 'Sydney              ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(644, 'Kiddoo              ', 'Samuel              ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(645, 'Koerner             ', 'Kaila               ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(646, 'Kohl                ', 'Karlie              ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 68, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(647, 'Kubicek             ', 'Michael             ', ' ', 'M', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(648, 'Kurtz               ', 'Terra               ', ' ', 'F', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(649, 'Lander              ', 'Taylor              ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(650, 'Latsch              ', 'Amanda              ', ' ', 'F', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(651, 'Lee                 ', 'Stephanie           ', ' ', 'F', NULL, 14, 'SO', 0, '              ', ' ', NULL, 73, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(652, 'Lehman              ', 'Alexis              ', ' ', 'F', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(653, 'Mann                ', 'Spud                ', ' ', 'M', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(654, 'Mar                 ', 'Kaleb               ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(655, 'Masters             ', 'Christina           ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 74, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(656, 'Merritt             ', 'Jerr                ', ' ', 'M', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(657, 'Messbarger          ', 'Jon                 ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(658, 'Morton              ', 'Amy                 ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 75, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(659, 'Morton              ', 'Brandi              ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(660, 'Mota                ', 'Lauren              ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 76, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(661, 'Mota                ', 'Lindsey             ', ' ', 'F', NULL, 14, 'SO', 0, '              ', ' ', NULL, 77, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(662, 'Moyer               ', 'Annie               ', ' ', 'F', NULL, 14, 'SR', 0, '              ', ' ', NULL, 78, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(663, 'Mueller             ', 'Rodney              ', ' ', 'M', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(664, 'Mueller             ', 'Rosalie             ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 79, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(665, 'Murman              ', 'Craig               ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(666, 'Olson               ', 'Michael             ', ' ', 'M', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(667, 'Ottemann            ', 'Brendan             ', ' ', 'M', NULL, 14, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(668, 'Ottemann            ', 'Corbin              ', ' ', 'M', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(669, 'Peitzmeier          ', 'Jordon              ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(670, 'Petersen            ', 'Jennifer            ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 80, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(671, 'Phillips            ', 'Breanna             ', ' ', 'F', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(672, 'Poppert             ', 'Kati                ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 81, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(673, 'Potter              ', 'Kylie               ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 82, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(674, 'Rathjen             ', 'Ashley              ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 83, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(675, 'Reichenbach         ', 'Claire              ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(676, 'Russell             ', 'Kyrie               ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 84, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(677, 'Rye                 ', 'Jon                 ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(678, 'Samuelson           ', 'Logan               ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(679, 'Schultze            ', 'Devin               ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 85, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(680, 'Shipman             ', 'Elizabeth           ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 86, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(681, 'Slawinski           ', 'Peter               ', ' ', 'M', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(682, 'Sneed               ', 'Avery               ', ' ', 'F', NULL, 14, 'SR', 0, '              ', ' ', NULL, 87, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(683, 'Stander             ', 'Michaela            ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(684, 'Steinberger         ', 'Katherine           ', ' ', 'F', NULL, 14, 'SO', 0, '              ', ' ', NULL, 88, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(685, 'Stoki               ', 'Cally               ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(686, 'Unzicker            ', 'Ashley              ', ' ', 'F', NULL, 14, 'FR', 0, '              ', ' ', NULL, 89, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(687, 'Unzicker            ', 'Sydney              ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 90, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(688, 'Voigt               ', 'Peter               ', ' ', 'M', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(689, 'Wertz               ', 'Julie               ', ' ', 'F', NULL, 14, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(690, 'Woldt               ', 'Weston              ', ' ', 'M', NULL, 14, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(691, 'Alabata             ', 'Jacqueline          ', ' ', 'F', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(692, 'Baude               ', 'Christine           ', ' ', 'F', NULL, 15, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(693, 'BAUMERT             ', 'CHRISTOPHER         ', ' ', 'M', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(694, 'Bertolino           ', 'Lindsay             ', ' ', 'F', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(695, 'Bilbrey             ', 'Caroline            ', ' ', 'F', NULL, 15, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(696, 'Bolton              ', 'Geoffrey            ', ' ', 'M', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(697, 'Bowden              ', 'Jessica             ', ' ', 'F', NULL, 15, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(698, 'Buelt               ', 'Eliza               ', ' ', 'F', NULL, 15, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(699, 'Campbell            ', 'James               ', ' ', 'M', NULL, 15, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(700, 'Dascalos            ', 'Emily               ', ' ', 'F', NULL, 15, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(701, 'Daul                ', 'Ambrosia            ', ' ', 'F', NULL, 15, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(702, 'DIXON               ', 'JALYN               ', ' ', 'F', NULL, 15, '  ', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(703, 'Florianczyk         ', 'Iga                 ', ' ', 'F', NULL, 15, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(704, 'GREGORY             ', 'THOMAS              ', ' ', 'M', NULL, 15, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(705, 'Healey              ', 'Ralph               ', ' ', 'M', NULL, 15, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(706, 'Hwangpo             ', 'Simon               ', ' ', 'M', NULL, 15, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(707, 'Inungaray           ', 'Christina           ', ' ', 'F', NULL, 15, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(708, 'Kaslon-Dixon        ', 'Jalyn               ', ' ', 'F', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(709, 'Ketcham             ', 'Kelsey              ', ' ', 'F', NULL, 15, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(710, 'Ketcham             ', 'Lauren              ', ' ', 'F', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(711, 'Krysl               ', 'Ryan                ', ' ', 'M', NULL, 15, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(712, 'MCDEMOTT            ', 'ERIN                ', ' ', 'F', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(713, 'Nilsson             ', 'Andrew              ', ' ', 'M', NULL, 15, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(714, 'Parks               ', 'Brittany            ', ' ', 'F', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(715, 'Paul                ', 'Chelsea             ', ' ', 'F', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(716, 'Roberts             ', 'Patrick             ', ' ', 'M', NULL, 15, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(717, 'Scheuber            ', 'Theresa             ', ' ', 'F', NULL, 15, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(718, 'Scott               ', 'Taylor              ', ' ', 'F', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(719, 'Shonka              ', 'Ryan                ', ' ', 'M', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(720, 'STAHLNECKER         ', 'ANDREW              ', ' ', 'M', NULL, 15, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(721, 'Stanford            ', 'Russell             ', ' ', 'M', NULL, 15, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(722, 'STEIN               ', 'JACOB               ', ' ', 'M', NULL, 15, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(723, 'Armstrong           ', 'Alexa               ', ' ', 'F', NULL, 16, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(724, 'Bass                ', 'Andriana            ', ' ', 'F', NULL, 16, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(725, 'Belmont             ', 'Jacob               ', 'B', 'M', NULL, 16, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(726, 'Benak               ', 'Staci               ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 91, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(727, 'Bertino             ', 'Nina                ', ' ', 'F', NULL, 16, 'SR', 0, '              ', ' ', NULL, 95, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(728, 'Conforte            ', 'Esteban             ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(729, 'Cook                ', 'Brandon             ', ' ', 'M', NULL, 16, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(730, 'Cuva                ', 'Allison             ', ' ', 'F', NULL, 16, 'SR', 0, '              ', ' ', NULL, 96, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(731, 'Cuva                ', 'Andrew              ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(732, 'Dornbush            ', 'Kylee               ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 98, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(733, 'Dostal              ', 'Ryan                ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(734, 'Drozda              ', 'Cameron             ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(735, 'Dwornicki           ', 'Anna                ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(736, 'Fischer             ', 'Amelie              ', ' ', 'F', NULL, 16, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(737, 'Fox                 ', 'Jamie               ', ' ', 'F', NULL, 16, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(738, 'Frantz              ', 'Allison             ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(739, 'Greco               ', 'Kaitlan             ', ' ', 'F', NULL, 16, 'FR', 0, '              ', ' ', NULL, 110, 'Kaity               ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(740, 'Hickey              ', 'Katherine           ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 92, 'KATHY               ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(741, 'Hilger              ', 'Meghan              ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 100, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(742, 'Holdcroft           ', 'Richard             ', ' ', 'M', NULL, 16, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(743, 'Jorgensen           ', 'Travis              ', ' ', 'M', NULL, 16, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(744, 'Killian             ', 'Ashley              ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 93, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(745, 'Kirkland            ', 'Patrick             ', ' ', 'M', NULL, 16, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(746, 'Kirkland', 'Sarah', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 101, ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '   ', "", ' ');
INSERT INTO `Athlete` VALUES(747, 'Koraleski           ', 'Joshua              ', ' ', 'M', NULL, 16, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(748, 'Koraleski           ', 'Keri                ', ' ', 'F', NULL, 16, 'SR', 0, '              ', ' ', NULL, 97, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(749, 'Kraus               ', 'Jacob               ', ' ', 'M', NULL, 16, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(750, 'Kruse               ', 'Kayla               ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 107, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(751, 'Lafferty            ', 'Dustin              ', ' ', 'M', NULL, 16, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(752, 'Manero              ', 'Bret                ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(753, 'Mass                ', 'Tyler               ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(754, 'O\'Brien             ', 'Christine           ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 108, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(755, 'Patrick             ', 'Morgan              ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(756, 'Petersen            ', 'Chelsea             ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 99, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(757, 'Peterson            ', 'Kathleen            ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 94, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(758, 'Porter              ', 'Jessica             ', ' ', 'F', NULL, 16, 'FR', 0, '              ', ' ', NULL, 109, 'JESSIE              ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(759, 'Quint               ', 'Katherine           ', ' ', 'F', NULL, 16, 'FR', 0, '              ', ' ', NULL, 111, 'Katie               ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(760, 'Ryan                ', 'Patrick             ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(761, 'Schrack             ', 'Donald              ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(762, 'Sears               ', 'Breanna             ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 103, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(763, 'Senger              ', 'Allison             ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 106, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(764, 'Shanahan            ', 'Mitchell            ', ' ', 'M', NULL, 16, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(765, 'Shupe               ', 'Thomas              ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(766, 'Trudell             ', 'Brandon             ', ' ', 'M', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(767, 'Vanornam            ', 'Patricia            ', ' ', 'F', NULL, 16, 'FR', 0, '              ', ' ', NULL, 112, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(768, 'Willers             ', 'Miranda             ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(769, 'Wing                ', 'Stacey              ', ' ', 'F', NULL, 16, 'JR', 0, '              ', ' ', NULL, 104, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(770, 'Young               ', 'Brittany            ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(771, 'Zagozda             ', 'Rachel              ', ' ', 'F', NULL, 16, 'SO', 0, '              ', ' ', NULL, 102, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(772, 'Bain                ', 'Jared               ', ' ', 'M', NULL, 17, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(773, 'Bogatz              ', 'Brittani            ', ' ', 'F', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(774, 'Carter              ', 'Vincent             ', ' ', 'M', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(775, 'Clark               ', 'Graham              ', ' ', 'M', NULL, 17, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(776, 'Clifford            ', 'Bradley             ', ' ', 'M', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(777, 'Concannon           ', 'Daniel              ', ' ', 'M', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(778, 'Cook                ', 'Chelsea             ', ' ', 'F', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(779, 'Dawkins             ', 'David               ', ' ', 'M', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(780, 'Garder              ', 'Jason               ', ' ', 'M', NULL, 17, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(781, 'Harris              ', 'Marie               ', ' ', 'F', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(782, 'Jack                ', 'Melisa              ', ' ', 'F', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(783, 'Johnson             ', 'Kathleen            ', ' ', 'F', NULL, 17, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(784, 'Jones               ', 'Alex                ', ' ', 'M', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(785, 'Keeling             ', 'Amanda              ', ' ', 'F', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(786, 'Koslosky            ', 'Matthew             ', ' ', 'M', NULL, 17, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(787, 'Kozol               ', 'Carolyn             ', ' ', 'F', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(788, 'Leisure             ', 'Jessica             ', ' ', 'F', NULL, 17, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(789, 'Leonardo            ', 'Gina                ', ' ', 'F', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(790, 'Madsen              ', 'Michelle            ', ' ', 'F', NULL, 17, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(791, 'Mattern             ', 'Neal                ', ' ', 'M', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(792, 'Morgan              ', 'Benjamin            ', ' ', 'M', NULL, 17, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(793, 'Moua                ', 'Koua                ', ' ', 'M', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(794, 'Mount               ', 'Cameron             ', ' ', 'M', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(795, 'Ourada              ', 'Jillian             ', ' ', 'F', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(796, 'Pass                ', 'Callista            ', ' ', 'F', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(797, 'Pass                ', 'Kylin               ', ' ', 'F', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(798, 'Petersen            ', 'Jens                ', ' ', 'M', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(799, 'Powers              ', 'Trevor              ', ' ', 'M', NULL, 17, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(800, 'Schuermann          ', 'Andi                ', ' ', 'F', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(801, 'Summerfelt          ', 'Kristen             ', ' ', 'F', NULL, 17, 'JR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(802, 'Watson              ', 'Logan               ', ' ', 'F', NULL, 17, 'FR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(803, 'Williams            ', 'Allison             ', ' ', 'F', NULL, 17, 'SO', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(804, 'Wilson              ', 'Daniel              ', ' ', 'M', NULL, 17, 'SR', 0, '              ', ' ', NULL, 0, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(805, 'Pfeiffer', 'Halee', ' ', 'F', NULL, 10, '  ', 0, ' ', ' ', NULL, 67, ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', "", ' ');
INSERT INTO `Athlete` VALUES(806, 'Lane                ', 'Kelly               ', ' ', 'F', '1990-02-14', 16, 'SO', 15, '              ', ' ', NULL, 105, ' ', "", "", "", "", "", "", "", "", "", "", "", '   ', "", "");
INSERT INTO `Athlete` VALUES(807, 'Smith               ', 'Erika               ', ' ', 'F', '1988-12-08', 4, 'SR', 17, '              ', ' ', NULL, 115, ' ', "", "", "", "", "", "", "", "", "", "", "", 'USA', "", "");
INSERT INTO `Athlete` VALUES(808, 'Colburn', 'Jessica', ' ', 'F', NULL, 7, '  ', 0, ' ', ' ', NULL, 129, ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', "", ' ');
INSERT INTO `Athlete` VALUES(810, 'Moyer', 'Annie', ' ', 'F', NULL, 4, '  ', 0, ' ', ' ', NULL, 130, ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', "", ' ');
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
  `Fin_jdheatplace` SMALLINT,
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
  `Seed_place` SMALLINT,
  `fin_heatltr` VARCHAR(1),
  INDEX `entathno` (`Ath_no`),
  INDEX `entevtptr` (`Event_ptr`)
);

#
# Dumping data for table 'Entry'
#

LOCK TABLES `Entry` WRITE;
INSERT INTO `Entry` VALUES(7, 14, 'Y', 5.893000e+001, 'Y', 5.893000e+001, 0, ' ', ' ', 0, 0, 0, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 6, NULL, ' ', 5.933000e+001, 'Y', 4, 0, 11, NULL, 0, "", NULL, 5.938000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 14, 'Y', 6.702000e+001, 'Y', 6.702000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 5, NULL, ' ', 6.964000e+001, 'Y', 5, 0, 15, NULL, 0, "", NULL, 6.971000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 16, 'Y', 2.855000e+001, 'Y', 2.855000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 2.810000e+001, 'Y', 1, 0, 21, NULL, 0, "", NULL, 2.812000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 16, 'Y', 6.188000e+001, 'Y', 6.188000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 3, NULL, ' ', 6.099000e+001, 'Y', 1, 0, 18, NULL, 0, "", NULL, 6.085000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 17, 'Y', 1.523200e+002, 'Y', 1.523200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 1.528200e+002, 'Y', 4, 0, 33, NULL, 0, "", NULL, 1.526800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 23, 'Y', 8.020000e+001, 'Y', 8.020000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 8.016000e+001, 'Y', 3, 0, 36, NULL, 0, "", NULL, 8.000000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 24, 'Y', 7.633000e+001, 'Y', 7.633000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 7.696000e+001, 'Y', 3, 0, 32, NULL, 0, "", NULL, 7.695000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 34, 'Y', 1.466200e+002, 'Y', 1.466200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 1.488100e+002, 'Y', 4, 0, 28, NULL, 0, "", NULL, 1.486800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 43, 'Y', 3.056000e+001, 'Y', 3.056000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 3.309000e+001, 'Y', 5, 0, 45, NULL, 0, "", NULL, 3.309000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 43, 'Y', 9.014000e+001, 'Y', 9.014000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 8.819000e+001, 'Y', 3, 0, 34, NULL, 0, "", NULL, 8.812000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 44, 'Y', 2.685000e+001, 'Y', 2.685000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 6, NULL, ' ', 2.800000e+001, 'Y', 5, 0, 17, NULL, 0, "", NULL, 2.800000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 44, 'Y', 7.100000e+001, 'Y', 7.100000e+001, 0, ' ', ' ', 0, 0, 0, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 2, NULL, ' ', 7.186000e+001, 'Y', 3, 0, 3, NULL, 0, "", NULL, 7.174000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 46, 'Y', 2.703000e+001, 'Y', 2.703000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 3, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 46, 'Y', 5.940000e+001, 'Y', 5.940000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 4, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 57, ' ', 0.000000e+000, ' ', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 2, NULL, 'Q', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 60, 'Y', 2.518000e+001, 'Y', 2.518000e+001, 0, ' ', ' ', 0, 0, 0, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 10, 4, NULL, ' ', 2.551000e+001, 'Y', 1, 0, 1, NULL, 0, "", NULL, 2.542000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 60, 'Y', 5.415000e+001, 'Y', 5.415000e+001, 0, ' ', ' ', 0, 0, 0, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 10, 2, NULL, ' ', 5.458000e+001, 'Y', 2, 0, 2, NULL, 0, "", NULL, 5.523000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 68, 'Y', 2.620000e+001, 'Y', 2.620000e+001, 0, ' ', ' ', 0, 0, 0, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 10, 6, NULL, ' ', 2.655000e+001, 'Y', 5, 0, 5, NULL, 0, "", NULL, 2.648000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 68, 'Y', 5.699000e+001, 'Y', 5.699000e+001, 0, ' ', ' ', 0, 0, 0, 6.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 10, 6, NULL, ' ', 5.801000e+001, 'Y', 6, 0, 7, NULL, 0, "", NULL, 5.797000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 70, 'Y', 7.400000e+001, 'Y', 7.400000e+001, 0, ' ', ' ', 0, 0, 0, 6.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 3, NULL, ' ', 7.442000e+001, 'Y', 2, 0, 7, NULL, 0, "", NULL, 7.437000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 77, 'Y', 6.450000e+001, 'Y', 6.450000e+001, 0, ' ', ' ', 0, 0, 0, 7.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 6, NULL, ' ', 6.516000e+001, 'Y', 6, 0, 7, NULL, 0, "", NULL, 6.505000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 83, 'Y', 1.305500e+002, 'Y', 1.305500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 2, NULL, ' ', 1.340200e+002, 'Y', 4, 0, 20, NULL, 0, "", NULL, 1.340200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 83, 'Y', 7.899000e+001, 'Y', 7.899000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 8.163000e+001, 'Y', 4, 0, 20, NULL, 0, "", NULL, 8.142000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 84, 'Y', 7.580000e+001, 'Y', 7.580000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 3, NULL, ' ', 8.025000e+001, 'Y', 5, 0, 18, NULL, 0, "", NULL, 8.016000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(8, 84, 'Y', 3.499000e+002, 'Y', 3.499000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 3.577800e+002, 'Y', 5, 0, 14, NULL, 0, "", NULL, 3.583200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 85, 'Y', 6.600000e+001, 'Y', 6.600000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 6, NULL, ' ', 6.655000e+001, 'Y', 5, 0, 13, NULL, 0, "", NULL, 6.667000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 85, 'Y', 6.705000e+001, 'Y', 6.705000e+001, 0, ' ', ' ', 0, 0, 0, 5.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 1, NULL, ' ', 6.808000e+001, 'Y', 2, 0, 8, NULL, 0, "", NULL, 6.809000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 89, 'Y', 5.950000e+001, 'Y', 5.950000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 2, NULL, ' ', 6.100000e+001, 'Y', 4, 0, 19, NULL, 0, "", NULL, 6.122000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 90, 'Y', 1.488500e+002, 'Y', 1.488500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 1.614500e+002, 'Y', 6, 0, 28, NULL, 0, "", NULL, 1.612500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 90, 'Y', 3.649900e+002, 'Y', 3.649900e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 3.589200e+002, 'Y', 2, 0, 15, NULL, 0, "", NULL, 3.588100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 92, 'Y', 1.479500e+002, 'Y', 1.479500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 1.497700e+002, 'Y', 4, 0, 17, NULL, 0, "", NULL, 1.496400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 92, 'Y', 6.600000e+001, 'Y', 6.600000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 1, NULL, ' ', 6.935000e+001, 'Y', 6, 0, 16, NULL, 0, "", NULL, 6.933000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(8, 96, 'Y', 3.450000e+002, 'Y', 3.450000e+002, 0, ' ', ' ', 0, 0, 0, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 3.446300e+002, 'Y', 1, 0, 5, NULL, 0, "", NULL, 3.444300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 96, 'Y', 1.324500e+002, 'Y', 1.324500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 6, NULL, ' ', 1.334100e+002, 'Y', 3, 0, 19, NULL, 0, "", NULL, 1.332400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 102, 'Y', 1.439500e+002, 'Y', 1.439500e+002, 0, ' ', ' ', 0, 0, 0, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 5, NULL, ' ', 1.432100e+002, 'Y', 3, 0, 9, NULL, 0, "", NULL, 1.433100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 102, 'Y', 7.401000e+001, 'Y', 7.401000e+001, 0, ' ', ' ', 0, 0, 0, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 4, NULL, ' ', 7.219000e+001, 'Y', 1, 0, 4, NULL, 0, "", NULL, 7.335000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 103, 'Y', 6.099000e+001, 'Y', 6.099000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 4, NULL, ' ', 6.275000e+001, 'Y', 4, 0, 24, NULL, 0, "", NULL, 6.259000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 111, 'Y', 1.315000e+002, 'Y', 1.315000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 1, NULL, ' ', 1.322400e+002, 'Y', 2, 0, 15, NULL, 0, "", NULL, 1.322100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(8, 111, 'Y', 3.501200e+002, 'Y', 3.501200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 3.574100e+002, 'Y', 4, 0, 13, NULL, 0, "", NULL, 3.574700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 113, 'Y', 1.438000e+002, 'Y', 1.438000e+002, 0, ' ', ' ', 0, 0, 0, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 4, NULL, ' ', 1.436900e+002, 'Y', 4, 0, 10, NULL, 0, "", NULL, 1.435900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 113, 'Y', 6.200000e+001, 'Y', 6.200000e+001, 0, ' ', ' ', 0, 0, 0, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 4, NULL, ' ', 6.172000e+001, 'Y', 1, 0, 1, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 114, ' ', 0.000000e+000, ' ', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 4, NULL, ' ', 2.599500e+002, 'Y', 6, 0, 6, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 115, 'Y', 1.189900e+002, 'Y', 1.189900e+002, 0, ' ', ' ', 0, 0, 0, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 3, NULL, ' ', 1.196700e+002, 'Y', 2, 0, 2, NULL, 0, "", NULL, 1.196000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 115, 'Y', 6.099000e+001, 'Y', 6.099000e+001, 0, ' ', ' ', 0, 0, 0, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 3, NULL, ' ', 6.251000e+001, 'Y', 2, 0, 2, NULL, 0, "", NULL, 6.224000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 117, 'Y', 7.295000e+001, 'Y', 7.295000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 7.076000e+001, 'Y', 1, 0, 20, NULL, 0, "", NULL, 7.067000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 117, 'Y', 2.705000e+001, 'Y', 2.705000e+001, 0, ' ', ' ', 0, 0, 0, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 4, NULL, ' ', 2.748000e+001, 'Y', 1, 0, 10, NULL, 0, "", NULL, 2.725000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 123, 'Y', 5.790000e+001, 'Y', 5.790000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 2, NULL, ' ', 6.019000e+001, 'Y', 6, 0, 13, NULL, 0, "", NULL, 6.080000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 123, 'Y', 2.680000e+001, 'Y', 2.680000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 5, NULL, ' ', 2.817000e+001, 'Y', 6, 0, 22, NULL, 0, "", NULL, 2.802000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 126, 'Y', 2.602000e+001, 'Y', 2.602000e+001, 0, ' ', ' ', 0, 0, 0, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 10, 1, NULL, ' ', 2.658000e+001, 'Y', 6, 0, 6, NULL, 0, "", NULL, 2.660000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 126, 'Y', 6.205000e+001, 'Y', 6.205000e+001, 0, ' ', ' ', 0, 0, 0, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 4, NULL, ' ', 6.250000e+001, 'Y', 2, 0, 2, NULL, 0, "", NULL, 6.246000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 127, ' ', 0.000000e+000, ' ', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 1.950500e+002, 'Y', 10, 0, 10, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 133, 'Y', 1.416500e+002, 'Y', 1.416500e+002, 0, ' ', ' ', 0, 0, 0, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 1, NULL, ' ', 1.413700e+002, 'Y', 4, 0, 4, NULL, 0, "", NULL, 1.411700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 133, 'Y', 6.463000e+001, 'Y', 6.463000e+001, 0, ' ', ' ', 0, 0, 0, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 6, NULL, ' ', 6.505000e+001, 'Y', 5, 0, 5, NULL, 0, "", NULL, 6.519000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 138, 'Y', 2.760000e+001, 'Y', 2.760000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 1, NULL, ' ', 2.887000e+001, 'Y', 6, 0, 27, NULL, 0, "", NULL, 2.853000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 138, 'Y', 6.177000e+001, 'Y', 6.177000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 1, NULL, ' ', 6.293000e+001, 'Y', 5, 0, 26, NULL, 0, "", NULL, 6.289000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 140, 'Y', 5.328000e+001, 'Y', 5.328000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 1, NULL, ' ', 4.328000e+001, 'Y', 4, 0, 51, NULL, 0, "", NULL, 4.298000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 141, 'Y', 3.770000e+001, 'Y', 3.770000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 2, NULL, ' ', 3.595000e+001, 'Y', 2, 0, 49, NULL, 0, "", NULL, 3.584000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 141, 'Y', 9.500000e+001, 'Y', 9.500000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 1, NULL, ' ', 9.681000e+001, 'Y', 6, 0, 51, NULL, 0, "", NULL, 9.677000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 145, 'Y', 1.880000e+002, 'Y', 1.880000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 2, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 145, 'Y', 8.160000e+001, 'Y', 8.160000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 3, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 147, 'Y', 1.333400e+002, 'Y', 1.333400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 1.326400e+002, 'Y', 5, 0, 18, NULL, 0, "", NULL, 1.325200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(8, 147, 'Y', 3.390000e+002, 'Y', 3.390000e+002, 0, ' ', ' ', 0, 0, 0, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 3.425000e+002, 'Y', 4, 0, 4, NULL, 0, "", NULL, 3.425900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 149, 'Y', 1.535900e+002, 'Y', 1.535900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 1.506400e+002, 'Y', 3, 0, 31, NULL, 0, "", NULL, 1.506400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 149, 'Y', 9.388000e+001, 'Y', 9.388000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 9.409000e+001, 'Y', 5, 0, 41, NULL, 0, "", NULL, 9.420000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 154, 'Y', 8.170000e+001, 'Y', 8.170000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 7.951000e+001, 'Y', 1, 0, 47, NULL, 0, ' ', NULL, 7.977000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 155, 'Y', 8.309000e+001, 'Y', 8.309000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 4, NULL, 'Q', 8.418000e+001, 'Y', 0, 0, 0, NULL, 0, "", NULL, 8.417000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 155, 'Y', 9.500000e+001, 'Y', 9.500000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 1, NULL, ' ', 1.006000e+002, 'Y', 6, 0, 42, NULL, 0, "", NULL, 1.004900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 158, 'Y', 6.459000e+001, 'Y', 6.459000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 6.371000e+001, 'Y', 1, 0, 27, NULL, 0, "", NULL, 6.453000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 158, 'Y', 7.572000e+001, 'Y', 7.572000e+001, 0, ' ', ' ', 0, 0, 0, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 1, NULL, ' ', 7.584000e+001, 'Y', 3, 0, 10, NULL, 0, "", NULL, 7.577000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 160, 'Y', 1.820000e+002, 'Y', 1.820000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 6, NULL, 'Q', 2.028800e+002, 'Y', 0, 0, 0, NULL, 0, "", NULL, 2.029200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 160, 'Y', 8.561000e+001, 'Y', 8.561000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 8.379000e+001, 'Y', 4, 0, 44, NULL, 0, "", NULL, 8.375000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 163, 'Y', 2.077700e+002, 'Y', 2.077700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 5, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 164, 'Y', 1.540200e+002, 'Y', 1.540200e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 1.540900e+002, 'Y', 1, 0, 24, NULL, 0, "", NULL, 1.540900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 164, 'Y', 7.051000e+001, 'Y', 7.051000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 6, NULL, ' ', 7.058000e+001, 'Y', 5, 0, 18, NULL, 0, "", NULL, 7.084000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 166, 'Y', 1.050000e+002, 'Y', 1.050000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 5, NULL, ' ', 1.095300e+002, 'Y', 4, 0, 43, NULL, 0, "", NULL, 1.096300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 169, 'Y', 2.743000e+001, 'Y', 2.743000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 2, NULL, ' ', 2.852000e+001, 'Y', 5, 0, 25, NULL, 0, "", NULL, 2.874000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 178, 'Y', 1.290000e+002, 'Y', 1.290000e+002, 0, "", "", 0, 0, NULL, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 6, NULL, ' ', 1.293400e+002, 'Y', 4, 0, 9, NULL, 0, "", NULL, 1.292200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 178, 'Y', 3.420000e+002, 'Y', 3.420000e+002, 0, "", "", 0, 0, NULL, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 3.400800e+002, 'Y', 3, 0, 3, NULL, 0, "", NULL, 3.399300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 182, 'Y', 2.740000e+001, 'Y', 2.740000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 4, NULL, ' ', 2.802000e+001, 'Y', 3, 0, 19, NULL, 0, "", NULL, 2.792000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 182, 'Y', 6.250000e+001, 'Y', 6.250000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 2, NULL, ' ', 6.268000e+001, 'Y', 2, 0, 22, NULL, 0, "", NULL, 6.353000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 188, 'Y', 1.900000e+002, 'Y', 1.900000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 2, NULL, ' ', 1.643000e+002, 'Y', 3, 0, 41, NULL, 0, "", NULL, 1.634000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 188, 'Y', 9.500000e+001, 'Y', 9.500000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 6, NULL, ' ', 8.658000e+001, 'Y', 5, 0, 48, NULL, 0, "", NULL, 8.665000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 190, 'Y', 3.050000e+001, 'Y', 3.050000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 191, 'Y', 3.500000e+001, 'Y', 3.500000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 194, 'Y', 1.540000e+002, 'Y', 1.540000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 1.500800e+002, 'Y', 3, 0, 19, NULL, 0, "", NULL, 1.500300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 194, 'Y', 7.400000e+001, 'Y', 7.400000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 7.231000e+001, 'Y', 2, 0, 24, NULL, 0, "", NULL, 7.220000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 195, 'Y', 1.380000e+002, 'Y', 1.380000e+002, 0, "", "", 0, 0, NULL, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 1.310500e+002, 'Y', 1, 0, 12, NULL, 0, "", NULL, 1.308800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 195, 'Y', 3.790000e+002, 'Y', 3.790000e+002, 0, "", "", 0, 0, NULL, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, ' ', 3.550600e+002, 'Y', 1, 0, 9, NULL, 0, "", NULL, 3.550300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 198, 'Y', 3.000000e+001, 'Y', 3.000000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 198, 'Y', 6.900000e+001, 'Y', 6.900000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 200, 'Y', 1.440000e+002, 'Y', 1.440000e+002, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 1, NULL, ' ', 1.443100e+002, 'Y', 5, 0, 11, NULL, 0, "", NULL, 1.441600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 200, 'Y', 6.600000e+001, 'Y', 6.600000e+001, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 5, NULL, ' ', 6.619000e+001, 'Y', 3, 0, 11, NULL, 0, "", NULL, 6.609000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 202, 'Y', 1.800000e+002, 'Y', 1.800000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 4, NULL, ' ', 1.641400e+002, 'Y', 2, 0, 40, NULL, 0, "", NULL, 1.641500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 205, 'Y', 7.800000e+001, 'Y', 7.800000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 7.448000e+001, 'Y', 2, 0, 45, NULL, 0, "", NULL, 7.542000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 205, 'Y', 1.020000e+002, 'Y', 1.020000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 2, NULL, ' ', 9.058000e+001, 'Y', 2, 0, 37, NULL, 0, "", NULL, 9.056000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 206, 'Y', 7.000000e+001, 'Y', 7.000000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 6.943000e+001, 'Y', 2, 0, 37, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 206, 'Y', 9.500000e+001, 'Y', 9.500000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, ' ', 9.030000e+001, 'Y', 3, 0, 36, NULL, 0, "", NULL, 9.037000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 211, 'Y', 7.200000e+001, 'Y', 7.200000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 7.230000e+001, 'Y', 5, 0, 23, NULL, 0, "", NULL, 7.227000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 211, 'Y', 7.200000e+001, 'Y', 7.200000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 5, NULL, ' ', 7.069000e+001, 'Y', 3, 0, 19, NULL, 0, "", NULL, 7.079000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 214, 'Y', 9.736000e+001, 'Y', 9.736000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 9.227000e+001, 'Y', 3, 0, 39, NULL, 0, "", NULL, 9.227000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 217, 'Y', 2.791000e+001, 'Y', 2.791000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 4, NULL, ' ', 2.809000e+001, 'Y', 2, 0, 20, NULL, 0, "", NULL, 2.799000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 217, 'Y', 7.258000e+001, 'Y', 7.258000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 6, NULL, ' ', 7.300000e+001, 'Y', 6, 0, 26, NULL, 0, "", NULL, 7.310000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 220, 'Y', 9.225000e+001, 'Y', 9.225000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 9.323000e+001, 'Y', 4, 0, 40, NULL, 0, "", NULL, 9.283000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 223, 'Y', 1.737900e+002, 'Y', 1.737900e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, ' ', 1.757500e+002, 'Y', 6, 0, 44, NULL, 0, "", NULL, 1.753600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 226, 'Y', 1.612800e+002, 'Y', 1.612800e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 1.595500e+002, 'Y', 4, 0, 37, NULL, 0, "", NULL, 1.593900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 226, 'Y', 1.911100e+002, 'Y', 1.911100e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 6, NULL, 'Q', 1.864700e+002, 'Y', 0, 0, 0, NULL, 0, "", NULL, 1.862200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 227, 'Y', 6.946000e+001, 'Y', 6.946000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 6.945000e+001, 'Y', 5, 0, 38, NULL, 0, "", NULL, 6.936000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 229, 'Y', 7.567000e+001, 'Y', 7.567000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 7.356000e+001, 'Y', 4, 0, 27, NULL, 0, "", NULL, 7.350000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 230, 'Y', 7.250000e+001, 'Y', 7.250000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 7.217000e+001, 'Y', 5, 0, 42, NULL, 0, "", NULL, 7.217000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 230, 'Y', 8.707000e+001, 'Y', 8.707000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 8.725000e+001, 'Y', 2, 0, 31, NULL, 0, "", NULL, 8.730000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 231, 'Y', 1.588000e+002, 'Y', 1.588000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 1.544300e+002, 'Y', 2, 0, 25, NULL, 0, "", NULL, 1.543100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 231, 'Y', 3.589300e+002, 'Y', 3.589300e+002, 0, "", "", 0, 0, NULL, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 3.557800e+002, 'Y', 2, 0, 10, NULL, 0, "", NULL, 3.557100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 232, 'Y', 8.936000e+001, 'Y', 8.936000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 4, NULL, ' ', 8.296000e+001, 'Y', 1, 0, 43, NULL, 0, "", NULL, 8.270000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 238, 'Y', 3.136000e+001, 'Y', 3.136000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 3.077000e+001, 'Y', 2, 0, 37, NULL, 0, "", NULL, 3.032000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 238, 'Y', 7.017000e+001, 'Y', 7.017000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 2, NULL, ' ', 7.044000e+001, 'Y', 4, 0, 41, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 243, 'Y', 3.522000e+001, 'Y', 3.522000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 3.591000e+001, 'Y', 1, 0, 48, NULL, 0, "", NULL, 3.576000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 243, 'Y', 8.051000e+001, 'Y', 8.051000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, ' ', 8.311000e+001, 'Y', 5, 0, 50, NULL, 0, "", NULL, 8.213000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 244, 'Y', 1.037200e+002, 'Y', 1.037200e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 2, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 249, 'Y', 1.486900e+002, 'Y', 1.486900e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 1.455800e+002, 'Y', 1, 0, 26, NULL, 0, "", NULL, 1.454900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 249, 'Y', 8.724000e+001, 'Y', 8.724000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, ' ', 8.094000e+001, 'Y', 1, 0, 28, NULL, 0, "", NULL, 8.092000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 257, 'Y', 1.386000e+002, 'Y', 1.386000e+002, 0, "", "", 0, 0, NULL, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 2, NULL, ' ', 1.361500e+002, 'Y', 3, 0, 3, NULL, 0, "", NULL, 1.362200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 257, 'Y', 5.667000e+001, 'Y', 5.667000e+001, 0, "", "", 0, 0, NULL, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 10, 1, NULL, ' ', 5.697000e+001, 'Y', 5, 0, 5, NULL, 0, "", NULL, 5.698000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 259, 'Y', 1.276000e+002, 'Y', 1.276000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 2, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 259, 'Y', 6.849000e+001, 'Y', 6.849000e+001, -1, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 0, 0, NULL, ' ', 0.000000e+000, "", 0, 0, 0, 0, NULL, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.000000e+000, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 260, 'Y', 1.331200e+002, 'Y', 1.331200e+002, 0, "", "", 0, 0, NULL, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 3, NULL, ' ', 1.358500e+002, 'Y', 2, 0, 2, NULL, 0, "", NULL, 1.358400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 260, 'Y', 7.221000e+001, 'Y', 7.221000e+001, 0, "", "", 0, 0, NULL, 6.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 1, NULL, ' ', 7.442000e+001, 'Y', 6, 0, 7, NULL, 0, "", NULL, 7.438000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 262, 'Y', 1.395000e+002, 'Y', 1.395000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 1.455000e+002, 'Y', 3, 0, 25, NULL, 0, "", NULL, 1.455800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 262, 'Y', 7.621000e+001, 'Y', 7.621000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 2, NULL, ' ', 7.709000e+001, 'Y', 2, 0, 13, NULL, 0, "", NULL, 7.691000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 263, 'Y', 1.455000e+002, 'Y', 1.455000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 1.500200e+002, 'Y', 5, 0, 29, NULL, 0, "", NULL, 1.499500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 266, 'Y', 3.741000e+002, 'Y', 3.741000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 3.707700e+002, 'Y', 5, 0, 20, NULL, 0, "", NULL, 3.707300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 266, 'Y', 8.141000e+001, 'Y', 8.141000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 8.690000e+001, 'Y', 6, 0, 30, NULL, 0, "", NULL, 8.711000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(1, 273, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 7.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 17, NULL, ' ', 2.299500e+002, 'Y', 7, 0, 7, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 274, 'Y', 2.710000e+001, 'Y', 2.710000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 2, NULL, ' ', 2.778000e+001, 'Y', 3, 0, 14, NULL, 0, "", NULL, 2.825000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 274, 'Y', 3.594000e+002, 'Y', 3.594000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 2, NULL, ' ', 3.600900e+002, 'Y', 4, 0, 16, NULL, 0, "", NULL, 3.601900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(1, 277, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 12, NULL, 'Q', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 279, 'Y', 1.385500e+002, 'Y', 1.385500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 1.504300e+002, 'Y', 6, 0, 30, NULL, 0, "", NULL, 1.501900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 279, 'Y', 7.963000e+001, 'Y', 7.963000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 7.922000e+001, 'Y', 1, 0, 15, NULL, 0, "", NULL, 7.983000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 282, 'Y', 2.980000e+001, 'Y', 2.980000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 3.101000e+001, 'Y', 4, 0, 40, NULL, 0, "", NULL, 3.058000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 282, 'Y', 7.951000e+001, 'Y', 7.951000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 1, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 283, 'Y', 2.810000e+001, 'Y', 2.810000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 2, NULL, ' ', 2.979000e+001, 'Y', 6, 0, 32, NULL, 0, "", NULL, 3.037000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 285, 'Y', 6.530000e+001, 'Y', 6.530000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 1, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 287, 'Y', 2.510000e+001, 'Y', 2.510000e+001, 0, "", "", 0, 0, NULL, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 10, 3, NULL, ' ', 2.577000e+001, 'Y', 3, 0, 3, NULL, 0, "", NULL, 2.582000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 287, 'Y', 5.540000e+001, 'Y', 5.540000e+001, 0, "", "", 0, 0, NULL, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 10, 5, NULL, ' ', 5.624000e+001, 'Y', 4, 0, 4, NULL, 0, "", NULL, 5.630000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 288, 'Y', 1.757500e+002, 'Y', 1.757500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 1.761100e+002, 'Y', 4, 0, 34, NULL, 0, "", NULL, 1.759300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 288, 'Y', 8.120000e+001, 'Y', 8.120000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 8.193000e+001, 'Y', 5, 0, 41, NULL, 0, "", NULL, 8.168000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 289, 'Y', 1.704500e+002, 'Y', 1.704500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 1.675400e+002, 'Y', 1, 0, 30, NULL, 0, "", NULL, 1.673900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(1, 292, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 5, NULL, ' ', 4.118000e+002, 'Y', 1, 0, 1, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 295, 'Y', 7.411000e+001, 'Y', 7.411000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 2, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 297, 'Y', 7.640000e+001, 'Y', 7.640000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 7.462000e+001, 'Y', 1, 0, 29, NULL, 0, "", NULL, 7.451000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 298, 'Y', 7.594000e+001, 'Y', 7.594000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 8.186000e+001, 'Y', 6, 0, 40, NULL, 0, "", NULL, 8.193000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 299, 'Y', 8.151000e+001, 'Y', 8.151000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 9.748000e+001, 'Y', 4, 0, 33, NULL, 0, "", NULL, 9.762000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 299, 'Y', 7.840000e+001, 'Y', 7.840000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 7.845000e+001, 'Y', 4, 0, 33, NULL, 0, "", NULL, 7.830000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 359, 'Y', 1.338000e+002, 'Y', 1.338000e+002, 0, "", "", 0, 0, NULL, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 4, NULL, ' ', 1.287500e+002, 'Y', 1, 0, 1, NULL, 0, "", NULL, 1.286900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 359, 'Y', 5.388000e+001, 'Y', 5.388000e+001, 0, "", "", 0, 0, NULL, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 10, 3, NULL, ' ', 5.388000e+001, 'Y', 1, 0, 1, NULL, 0, "", NULL, 5.388000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 368, 'Y', 1.232800e+002, 'Y', 1.232800e+002, 0, "", "", 0, 0, NULL, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 5, NULL, ' ', 1.252800e+002, 'Y', 4, 0, 4, NULL, 0, "", NULL, 1.252900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 368, 'Y', 3.370000e+002, 'Y', 3.370000e+002, 0, "", "", 0, 0, NULL, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 3.254700e+002, 'Y', 2, 0, 2, NULL, 0, "", NULL, 3.253600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 372, 'Y', 3.083000e+001, 'Y', 3.083000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 2.895000e+001, 'Y', 1, 0, 28, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 372, 'Y', 3.671000e+002, 'Y', 3.671000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 3.703500e+002, 'Y', 4, 0, 19, NULL, 0, "", NULL, 3.700900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 378, 'Y', 1.463200e+002, 'Y', 1.463200e+002, 0, "", "", 0, 0, NULL, 7.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 1.428300e+002, 'Y', 1, 0, 7, NULL, 0, "", NULL, 1.427200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 378, 'Y', 6.452000e+001, 'Y', 6.452000e+001, 0, "", "", 0, 0, NULL, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 9, 1, NULL, ' ', 6.514000e+001, 'Y', 6, 0, 6, NULL, 0, "", NULL, 6.512000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 379, 'Y', 2.730000e+001, 'Y', 2.730000e+001, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 3, NULL, ' ', 2.756000e+001, 'Y', 1, 0, 11, NULL, 0, "", NULL, 2.761000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 379, 'Y', 7.575000e+001, 'Y', 7.575000e+001, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 6, NULL, ' ', 7.595000e+001, 'Y', 4, 0, 11, NULL, 0, "", NULL, 7.615000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 380, 'Y', 2.645000e+001, 'Y', 2.645000e+001, 0, "", "", 0, 0, NULL, 7.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 9, 4, NULL, ' ', 2.702000e+001, 'Y', 1, 0, 7, NULL, 0, "", NULL, 2.696000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 381, 'Y', 6.767000e+001, 'Y', 6.767000e+001, 0, "", "", 0, 0, NULL, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 6.545000e+001, 'Y', 1, 0, 9, NULL, 0, "", NULL, 6.541000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 381, 'Y', 7.218000e+001, 'Y', 7.218000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 1, NULL, ' ', 6.920000e+001, 'Y', 2, 0, 13, NULL, 0, "", NULL, 6.918000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 384, 'Y', 2.783000e+001, 'Y', 2.783000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 3, NULL, ' ', 2.789000e+001, 'Y', 1, 0, 15, NULL, 0, "", NULL, 2.778000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(1, 387, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 15, NULL, 'Q', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 390, 'Y', 1.505500e+002, 'Y', 1.505500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 1.461400e+002, 'Y', 1, 0, 15, NULL, 0, "", NULL, 1.460900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 390, 'Y', 6.538000e+001, 'Y', 6.538000e+001, 0, "", "", 0, 0, NULL, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 4, NULL, ' ', 6.436000e+001, 'Y', 1, 0, 5, NULL, 0, "", NULL, 6.426000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 394, 'Y', 1.250400e+002, 'Y', 1.250400e+002, 0, "", "", 0, 0, NULL, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 1, NULL, ' ', 1.267200e+002, 'Y', 5, 0, 6, NULL, 0, "", NULL, 1.268100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 394, 'Y', 5.748000e+001, 'Y', 5.748000e+001, 0, "", "", 0, 0, NULL, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 9, 3, NULL, ' ', 5.707000e+001, 'Y', 1, 0, 6, NULL, 0, "", NULL, 5.699000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 399, 'Y', 1.524600e+002, 'Y', 1.524600e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 1.508000e+002, 'Y', 4, 0, 20, NULL, 0, "", NULL, 1.507700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 399, 'Y', 6.828000e+001, 'Y', 6.828000e+001, 0, "", "", 0, 0, NULL, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 2, NULL, ' ', 6.822000e+001, 'Y', 1, 0, 10, NULL, 0, "", NULL, 6.812000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 401, 'Y', 7.342000e+001, 'Y', 7.342000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 7.151000e+001, 'Y', 4, 0, 20, NULL, 0, "", NULL, 7.162000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 401, 'Y', 7.011000e+001, 'Y', 7.011000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 1, NULL, ' ', 7.133000e+001, 'Y', 6, 0, 21, NULL, 0, "", NULL, 7.145000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 402, 'Y', 6.317000e+001, 'Y', 6.317000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 1, NULL, ' ', 6.421000e+001, 'Y', 5, 0, 28, NULL, 0, "", NULL, 6.417000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 402, 'Y', 8.617000e+001, 'Y', 8.617000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 8.408000e+001, 'Y', 5, 0, 27, NULL, 0, "", NULL, 8.409000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 406, 'Y', 1.335100e+002, 'Y', 1.335100e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 1.324900e+002, 'Y', 4, 0, 17, NULL, 0, "", NULL, 1.324200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 406, 'Y', 3.598300e+002, 'Y', 3.598300e+002, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 3.571000e+002, 'Y', 3, 0, 11, NULL, 0, "", NULL, 3.569900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 407, 'Y', 8.176000e+001, 'Y', 8.176000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 8.202000e+001, 'Y', 1, 0, 21, NULL, 0, "", NULL, 8.192000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 410, 'Y', 1.299000e+002, 'Y', 1.299000e+002, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 3, NULL, ' ', 1.303800e+002, 'Y', 1, 0, 11, NULL, 0, "", NULL, 1.303900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 410, 'Y', 6.093000e+001, 'Y', 6.093000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 3, NULL, ' ', 6.066000e+001, 'Y', 1, 0, 16, NULL, 0, "", NULL, 6.039000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 412, 'Y', 7.027000e+001, 'Y', 7.027000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 7.128000e+001, 'Y', 2, 0, 18, NULL, 0, "", NULL, 7.125000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 412, 'Y', 7.773000e+001, 'Y', 7.773000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 6, NULL, 'Q', 7.748000e+001, 'Y', 0, 0, 0, NULL, 0, "", NULL, 7.755000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 417, 'Y', 3.527500e+002, 'Y', 3.527500e+002, 0, "", "", 0, 0, NULL, 5.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 3.540800e+002, 'Y', 2, 0, 8, NULL, 0, "", NULL, 3.541500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 421, 'Y', 1.274100e+002, 'Y', 1.274100e+002, 0, "", "", 0, 0, NULL, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 3, NULL, ' ', 1.258000e+002, 'Y', 1, 0, 5, NULL, 0, "", NULL, 1.258300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 421, 'Y', 5.787000e+001, 'Y', 5.787000e+001, 0, "", "", 0, 0, NULL, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 9, 4, NULL, ' ', 5.873000e+001, 'Y', 3, 0, 10, NULL, 0, "", NULL, 5.863000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 425, 'Y', 3.539000e+001, 'Y', 3.539000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 4, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 425, 'Y', 8.239000e+001, 'Y', 8.239000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 4, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, ' ', NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 427, 'Y', 1.309900e+002, 'Y', 1.309900e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 5, NULL, ' ', 1.342200e+002, 'Y', 5, 0, 21, NULL, 0, "", NULL, 1.341600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 427, 'Y', 6.598000e+001, 'Y', 6.598000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 4, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 427, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 6.825000e+001, 'Y', 4, 0, 15, NULL, 0, "", NULL, 6.816000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "");
INSERT INTO `Entry` VALUES(1, 429, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 5.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 1, NULL, ' ', 2.147000e+002, 'Y', 8, 0, 8, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 430, 'Y', 8.408000e+001, 'Y', 8.408000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 5, NULL, ' ', 7.970000e+001, 'Y', 2, 0, 48, NULL, 0, "", NULL, 7.966000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 430, 'Y', 8.994000e+001, 'Y', 8.994000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 9.187000e+001, 'Y', 5, 0, 38, NULL, 0, "", NULL, 9.187000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 435, 'Y', 7.408000e+001, 'Y', 7.408000e+001, 0, "", "", 0, 0, NULL, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 5, NULL, ' ', 7.608000e+001, 'Y', 5, 0, 12, NULL, 0, "", NULL, 7.612000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 435, 'Y', 1.509800e+002, 'Y', 1.509800e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 1.532600e+002, 'Y', 6, 0, 23, NULL, 0, "", NULL, 1.531600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 437, 'Y', 1.903800e+002, 'Y', 1.903800e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 1, NULL, ' ', 1.807100e+002, 'Y', 2, 0, 36, NULL, 0, "", NULL, 1.808200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 437, 'Y', 8.926000e+001, 'Y', 8.926000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 8.630000e+001, 'Y', 4, 0, 47, NULL, 0, "", NULL, 8.624000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 439, 'Y', 1.765700e+002, 'Y', 1.765700e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 1, NULL, ' ', 1.770300e+002, 'Y', 5, 0, 35, NULL, 0, "", NULL, 1.770200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 439, 'Y', 7.408000e+001, 'Y', 7.408000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 2, NULL, ' ', 8.328000e+001, 'Y', 6, 0, 24, NULL, 0, "", NULL, 8.317000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 440, 'Y', 2.762000e+001, 'Y', 2.762000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 6, NULL, ' ', 2.823000e+001, 'Y', 4, 0, 23, NULL, 0, "", NULL, 2.831000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 440, 'Y', 6.131000e+001, 'Y', 6.131000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 5, NULL, ' ', 6.207000e+001, 'Y', 3, 0, 20, NULL, 0, "", NULL, 6.198000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 446, 'Y', 6.267000e+001, 'Y', 6.267000e+001, 0, "", "", 0, 0, NULL, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 2, NULL, ' ', 6.402000e+001, 'Y', 4, 0, 4, NULL, 0, "", NULL, 6.449000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 446, 'Y', 6.578000e+001, 'Y', 6.578000e+001, 0, "", "", 0, 0, NULL, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 3, NULL, ' ', 6.809000e+001, 'Y', 3, 0, 9, NULL, 0, "", NULL, 6.795000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 447, 'Y', 1.756500e+002, 'Y', 1.756500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 1.713200e+002, 'Y', 2, 0, 31, NULL, 0, "", NULL, 1.714500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 447, 'Y', 7.896000e+001, 'Y', 7.896000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 8.079000e+001, 'Y', 5, 0, 38, NULL, 0, "", NULL, 8.061000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 453, 'Y', 1.609200e+002, 'Y', 1.609200e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 1.598000e+002, 'Y', 5, 0, 39, NULL, 0, "", NULL, 1.595900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 453, 'Y', 8.855000e+001, 'Y', 8.855000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, ' ', 9.398000e+001, 'Y', 6, 0, 50, NULL, 0, ' ', NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 458, 'Y', 1.740000e+002, 'Y', 1.740000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 1.793200e+002, 'Y', 5, 0, 45, NULL, 0, "", NULL, 1.791000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 458, 'Y', 7.800000e+001, 'Y', 7.800000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 8.224000e+001, 'Y', 4, 0, 49, NULL, 0, "", NULL, 8.207000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 466, 'Y', 3.010000e+001, 'Y', 3.010000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 2.997000e+001, 'Y', 1, 0, 33, NULL, 0, "", NULL, 2.981000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 470, 'Y', 9.400000e+001, 'Y', 9.400000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 5, NULL, ' ', 8.561000e+001, 'Y', 3, 0, 46, NULL, 0, "", NULL, 8.414000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 472, 'Y', 3.290000e+001, 'Y', 3.290000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 3.193000e+001, 'Y', 2, 0, 42, NULL, 0, "", NULL, 3.175000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 475, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 1, NULL, ' ', 1.717100e+002, 'Y', 4, 0, 43, NULL, 0, "", NULL, 1.718100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 483, 'Y', 7.700000e+001, 'Y', 7.700000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 7.771000e+001, 'Y', 3, 0, 46, NULL, 0, "", NULL, 7.763000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 484, 'Y', 8.375000e+001, 'Y', 8.375000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 8.252000e+001, 'Y', 3, 0, 42, NULL, 0, "", NULL, 8.247000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 486, 'Y', 2.840000e+001, 'Y', 2.840000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 6, NULL, ' ', 2.931000e+001, 'Y', 5, 0, 30, NULL, 0, "", NULL, 2.948000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 488, 'Y', 3.360000e+001, 'Y', 3.360000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 3.565000e+001, 'Y', 4, 0, 46, NULL, 0, "", NULL, 3.565000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 488, 'Y', 8.300000e+001, 'Y', 8.300000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 2, NULL, ' ', 9.084000e+001, 'Y', 3, 0, 51, NULL, 0, "", NULL, 9.037000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 494, 'Y', 1.437600e+002, 'Y', 1.437600e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 3, NULL, ' ', 1.473200e+002, 'Y', 6, 0, 16, NULL, 0, "", NULL, 1.473000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 494, 'Y', 7.762000e+001, 'Y', 7.762000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 1, NULL, ' ', 7.934000e+001, 'Y', 4, 0, 16, NULL, 0, "", NULL, 7.917000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 496, 'Y', 1.390300e+002, 'Y', 1.390300e+002, 0, "", "", 0, 0, NULL, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 5, NULL, ' ', 1.415300e+002, 'Y', 5, 0, 5, NULL, 0, "", NULL, 1.415400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 496, 'Y', 6.304000e+001, 'Y', 6.304000e+001, 0, "", "", 0, 0, NULL, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 9, 2, NULL, ' ', 6.368000e+001, 'Y', 3, 0, 3, NULL, 0, "", NULL, 6.372000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 504, 'Y', 3.554500e+002, 'Y', 3.554500e+002, 0, "", "", 0, 0, NULL, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 3.511600e+002, 'Y', 1, 0, 6, NULL, 0, "", NULL, 3.510600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 504, 'Y', 6.805000e+001, 'Y', 6.805000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 6.737000e+001, 'Y', 3, 0, 14, NULL, 0, "", NULL, 6.732000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 505, 'Y', 2.814000e+001, 'Y', 2.814000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 5, NULL, ' ', 2.848000e+001, 'Y', 3, 0, 24, NULL, 0, "", NULL, 2.841000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 505, 'Y', 6.204000e+001, 'Y', 6.204000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 4, NULL, ' ', 6.271000e+001, 'Y', 3, 0, 23, NULL, 0, "", NULL, 6.278000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 509, 'Y', 3.642300e+002, 'Y', 3.642300e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 3.740900e+002, 'Y', 6, 0, 21, NULL, 0, "", NULL, 3.741800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 509, 'Y', 7.292000e+001, 'Y', 7.292000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 7.453000e+001, 'Y', 5, 0, 28, NULL, 0, "", NULL, 7.455000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 510, 'Y', 1.334200e+002, 'Y', 1.334200e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 1.319600e+002, 'Y', 2, 0, 13, NULL, 0, "", NULL, 1.316700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 510, 'Y', 7.092000e+001, 'Y', 7.092000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 7.018000e+001, 'Y', 1, 0, 17, NULL, 0, "", NULL, 6.952000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(1, 521, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 8, NULL, ' ', 1.816500e+002, 'Y', 11, 0, 11, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 523, 'Y', 7.985000e+001, 'Y', 7.985000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 7.652000e+001, 'Y', 1, 0, 30, NULL, 0, "", NULL, 7.638000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 526, 'Y', 1.475300e+002, 'Y', 1.475300e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 1.426100e+002, 'Y', 2, 0, 24, NULL, 0, "", NULL, 1.427500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 528, 'Y', 2.727000e+001, 'Y', 2.727000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 6, NULL, ' ', 2.800000e+001, 'Y', 4, 0, 17, NULL, 0, "", NULL, 2.805000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 528, 'Y', 7.609000e+001, 'Y', 7.609000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 4, NULL, ' ', 7.825000e+001, 'Y', 3, 0, 14, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 531, 'Y', 7.343000e+001, 'Y', 7.343000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 7.670000e+001, 'Y', 3, 0, 26, NULL, 0, "", NULL, 7.650000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 533, 'Y', 2.549000e+001, 'Y', 2.549000e+001, 0, "", "", 0, 0, NULL, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 10, 2, NULL, ' ', 2.568000e+001, 'Y', 2, 0, 2, NULL, 0, "", NULL, 2.602000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 533, 'Y', 5.408000e+001, 'Y', 5.408000e+001, 0, "", "", 0, 0, NULL, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 10, 4, NULL, ' ', 5.516000e+001, 'Y', 3, 0, 3, NULL, 0, "", NULL, 5.511000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 534, 'Y', 1.208300e+002, 'Y', 1.208300e+002, 0, "", "", 0, 0, NULL, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 4, NULL, ' ', 1.192200e+002, 'Y', 1, 0, 1, NULL, 0, "", NULL, 1.190300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 534, 'Y', 3.257900e+002, 'Y', 3.257900e+002, 0, "", "", 0, 0, NULL, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 3.241500e+002, 'Y', 1, 0, 1, NULL, 0, "", NULL, 3.240300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(1, 541, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 9, NULL, 'Q', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 550, 'Y', 1.533000e+002, 'Y', 1.533000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 1.498900e+002, 'Y', 2, 0, 18, NULL, 0, "", NULL, 1.498100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 550, 'Y', 8.005000e+001, 'Y', 8.005000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 8.001000e+001, 'Y', 2, 0, 17, NULL, 0, "", NULL, 7.990000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 553, 'Y', 1.274800e+002, 'Y', 1.274800e+002, 0, "", "", 0, 0, NULL, 5.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 4, NULL, ' ', 1.293100e+002, 'Y', 3, 0, 8, NULL, 0, "", NULL, 1.292100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 553, 'Y', 5.883000e+001, 'Y', 5.883000e+001, 0, "", "", 0, 0, NULL, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 9, 1, NULL, ' ', 5.960000e+001, 'Y', 5, 0, 12, NULL, 0, "", NULL, 5.962000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 556, 'Y', 6.582000e+001, 'Y', 6.582000e+001, 0, "", "", 0, 0, NULL, 5.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 2, NULL, ' ', 6.544000e+001, 'Y', 2, 0, 8, NULL, 0, "", NULL, 6.606000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 556, 'Y', 7.712000e+001, 'Y', 7.712000e+001, 0, "", "", 0, 0, NULL, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 5, NULL, ' ', 7.503000e+001, 'Y', 1, 0, 9, NULL, 0, "", NULL, 7.500000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(1, 558, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 7, NULL, ' ', 3.223500e+002, 'Y', 4, 0, 4, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 561, 'Y', 2.681000e+001, 'Y', 2.681000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 9, 1, NULL, ' ', 2.769000e+001, 'Y', 4, 0, 13, NULL, 0, "", NULL, 2.770000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 561, 'Y', 6.022000e+001, 'Y', 6.022000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 5, NULL, ' ', 6.065000e+001, 'Y', 3, 0, 15, NULL, 0, "", NULL, 6.063000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 564, 'Y', 2.903000e+001, 'Y', 2.903000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 2.886000e+001, 'Y', 2, 0, 26, NULL, 0, "", NULL, 2.884000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 564, 'Y', 6.420000e+001, 'Y', 6.420000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 6.438000e+001, 'Y', 2, 0, 29, NULL, 0, "", NULL, 6.448000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 567, 'Y', 8.257000e+001, 'Y', 8.257000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 8.294000e+001, 'Y', 2, 0, 22, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 569, 'Y', 8.288000e+001, 'Y', 8.288000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 8.012000e+001, 'Y', 2, 0, 35, NULL, 0, "", NULL, 8.015000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 570, 'Y', 8.251000e+001, 'Y', 8.251000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 8.088000e+001, 'Y', 4, 0, 39, NULL, 0, "", NULL, 8.088000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 572, 'Y', 1.621600e+002, 'Y', 1.621600e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 1.596100e+002, 'Y', 3, 0, 26, NULL, 0, "", NULL, 1.595700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 572, 'Y', 3.755900e+002, 'Y', 3.755900e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 1, NULL, ' ', 3.856200e+002, 'Y', 6, 0, 22, NULL, 0, "", NULL, 3.855600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 573, 'Y', 8.768000e+001, 'Y', 8.768000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 9.203000e+001, 'Y', 2, 0, 32, NULL, 0, "", NULL, 9.196000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 583, 'Y', 1.441100e+002, 'Y', 1.441100e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 1.445800e+002, 'Y', 3, 0, 13, NULL, 0, "", NULL, 1.444800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 583, 'Y', 6.438000e+001, 'Y', 6.438000e+001, 0, "", "", 0, 0, NULL, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 1, NULL, ' ', 6.450000e+001, 'Y', 5, 0, 6, NULL, 0, "", NULL, 6.444000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 584, 'Y', 3.023000e+001, 'Y', 3.023000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 3.060000e+001, 'Y', 3, 0, 35, NULL, 0, "", NULL, 3.105000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 584, 'Y', 6.567000e+001, 'Y', 6.567000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 7.014000e+001, 'Y', 5, 0, 40, NULL, 0, "", NULL, 7.014000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 585, 'Y', 1.274100e+002, 'Y', 1.274100e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 6, NULL, ' ', 1.320700e+002, 'Y', 6, 0, 14, NULL, 0, "", NULL, 1.319600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 585, 'Y', 6.089000e+001, 'Y', 6.089000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 6, NULL, ' ', 6.062000e+001, 'Y', 2, 0, 14, NULL, 0, "", NULL, 6.064000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 586, 'Y', 8.755000e+001, 'Y', 8.755000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 8.528000e+001, 'Y', 1, 0, 28, NULL, 0, "", NULL, 8.593000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 587, 'Y', 7.345000e+001, 'Y', 7.345000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 7.286000e+001, 'Y', 6, 0, 44, NULL, 0, "", NULL, 7.258000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 590, 'Y', 3.430000e+001, 'Y', 3.430000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 1, NULL, ' ', 3.583000e+001, 'Y', 5, 0, 47, NULL, 0, "", NULL, 3.583000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 599, 'Y', 3.083000e+001, 'Y', 3.083000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 3.080000e+001, 'Y', 3, 0, 38, NULL, 0, "", NULL, 3.050000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 599, 'Y', 7.672000e+001, 'Y', 7.672000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 7.436000e+001, 'Y', 2, 0, 25, NULL, 0, "", NULL, 7.437000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 619, 'Y', 1.213000e+002, 'Y', 1.213000e+002, 0, ' ', ' ', 0, 0, 0, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 2, NULL, ' ', 1.210300e+002, 'Y', 3, 0, 3, NULL, 0, "", NULL, 1.209200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 619, 'Y', 5.866000e+001, 'Y', 5.866000e+001, 0, ' ', ' ', 0, 0, 0, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 3, NULL, ' ', 5.938000e+001, 'Y', 1, 0, 1, NULL, 0, "", NULL, 5.932000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 623, 'Y', 7.406000e+001, 'Y', 7.406000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 7.241000e+001, 'Y', 3, 0, 25, NULL, 0, "", NULL, 7.214000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 626, 'Y', 2.678000e+001, 'Y', 2.678000e+001, 0, ' ', ' ', 0, 0, 0, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 2, NULL, ' ', 2.728000e+001, 'Y', 3, 0, 9, NULL, 0, "", NULL, 2.762000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 626, 'Y', 6.320000e+001, 'Y', 6.320000e+001, 0, ' ', ' ', 0, 0, 0, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 5, NULL, ' ', 6.407000e+001, 'Y', 4, 0, 4, NULL, 0, "", NULL, 6.410000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 642, 'Y', 1.341000e+002, 'Y', 1.341000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 1.322700e+002, 'Y', 3, 0, 16, NULL, 0, "", NULL, 1.321600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 642, 'Y', 6.106000e+001, 'Y', 6.106000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 2, NULL, ' ', 6.090000e+001, 'Y', 2, 0, 17, NULL, 0, "", NULL, 6.135000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 651, 'Y', 0.000000e+000, 'Y', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 10, NULL, ' ', 3.085500e+002, 'Y', 5, 0, 5, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 658, 'Y', 2.720000e+001, 'Y', 2.720000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 1, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 658, 'Y', 6.178000e+001, 'Y', 6.178000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 6, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 660, 'Y', 8.106000e+001, 'Y', 8.106000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 8.355000e+001, 'Y', 5, 0, 25, NULL, 0, "", NULL, 8.338000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 661, 'Y', 1.600600e+002, 'Y', 1.600600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 2, NULL, ' ', 1.614200e+002, 'Y', 4, 0, 27, NULL, 0, "", NULL, 1.614600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 661, 'Y', 6.506000e+001, 'Y', 6.506000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 6.484000e+001, 'Y', 3, 0, 30, NULL, 0, "", NULL, 6.483000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 662, 'Y', 0.000000e+000, 'Y', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 19, NULL, ' ', 3.597000e+002, 'Y', 2, 0, 2, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 664, 'Y', 1.360600e+002, 'Y', 1.360600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 1.380800e+002, 'Y', 6, 0, 22, NULL, 0, "", NULL, 1.379400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 664, 'Y', 7.406000e+001, 'Y', 7.406000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 7.298000e+001, 'Y', 1, 0, 24, NULL, 0, "", NULL, 7.300000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 670, 'Y', 2.856000e+001, 'Y', 2.856000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 2.931000e+001, 'Y', 3, 0, 30, NULL, 0, "", NULL, 2.901000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 672, 'Y', 0.000000e+000, 'Y', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 16, NULL, ' ', 3.488000e+002, 'Y', 3, 0, 3, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 673, 'Y', 2.710000e+001, 'Y', 2.710000e+001, 0, ' ', ' ', 0, 0, 0, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 5, NULL, ' ', 2.760000e+001, 'Y', 2, 0, 12, NULL, 0, "", NULL, 2.754000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 673, 'Y', 7.366000e+001, 'Y', 7.366000e+001, 0, ' ', ' ', 0, 0, 0, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 6, NULL, ' ', 7.362000e+001, 'Y', 5, 0, 6, NULL, 0, "", NULL, 7.361000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(8, 674, 'Y', 3.710600e+002, 'Y', 3.710600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 3.651600e+002, 'Y', 3, 0, 17, NULL, 0, "", NULL, 3.651400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 674, 'Y', 8.100000e+001, 'Y', 8.100000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 8.070000e+001, 'Y', 3, 0, 19, NULL, 0, "", NULL, 8.059000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 676, 'Y', 6.306000e+001, 'Y', 6.306000e+001, 0, ' ', ' ', 0, 0, 0, 1.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 5, NULL, ' ', 6.329000e+001, 'Y', 3, 0, 3, NULL, 0, "", NULL, 6.315000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 676, 'Y', 6.875000e+001, 'Y', 6.875000e+001, 0, ' ', ' ', 0, 0, 0, 1.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 3, NULL, ' ', 7.002000e+001, 'Y', 1, 0, 1, NULL, 0, "", NULL, 6.988000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 679, 'Y', 6.306000e+001, 'Y', 6.306000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 5, NULL, ' ', 6.281000e+001, 'Y', 4, 0, 25, NULL, 0, "", NULL, 6.273000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 679, 'Y', 7.150000e+001, 'Y', 7.150000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 2, NULL, ' ', 7.147000e+001, 'Y', 4, 0, 22, NULL, 0, "", NULL, 7.138000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 680, 'Y', 7.306000e+001, 'Y', 7.306000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 7.748000e+001, 'Y', 6, 0, 27, NULL, 0, "", NULL, 7.755000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 682, 'Y', 1.305000e+002, 'Y', 1.305000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 4, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(8, 682, 'Y', 3.465000e+002, 'Y', 3.465000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 4, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 684, 'Y', 1.430600e+002, 'Y', 1.430600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 6, NULL, ' ', 1.457200e+002, 'Y', 6, 0, 14, NULL, 0, "", NULL, 1.457400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(8, 684, 'Y', 3.540500e+002, 'Y', 3.540500e+002, 0, ' ', ' ', 0, 0, 0, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 3.572000e+002, 'Y', 3, 0, 12, NULL, 0, "", NULL, 3.572200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 686, 'Y', 1.661000e+002, 'Y', 1.661000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 1.646200e+002, 'Y', 5, 0, 29, NULL, 0, "", NULL, 1.645800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 687, 'Y', 1.520600e+002, 'Y', 1.520600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 1.520600e+002, 'Y', 5, 0, 22, NULL, 0, "", NULL, 1.519500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 687, 'Y', 6.706000e+001, 'Y', 6.706000e+001, 0, ' ', ' ', 0, 0, 0, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 6, NULL, ' ', 6.846000e+001, 'Y', 4, 0, 11, NULL, 0, "", NULL, 6.855000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 691, 'Y', 8.600000e+001, 'Y', 8.600000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 8.323000e+001, 'Y', 3, 0, 23, NULL, 0, "", NULL, 8.319000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 692, 'Y', 8.390000e+001, 'Y', 8.390000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 8.299000e+001, 'Y', 2, 0, 30, NULL, 0, "", NULL, 8.180000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 694, 'Y', 5.900000e+001, 'Y', 5.900000e+001, 0, "", "", 0, 0, NULL, 6.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 3, NULL, ' ', 5.801000e+001, 'Y', 1, 0, 7, NULL, 0, "", NULL, 5.798000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 695, 'Y', 2.900000e+001, 'Y', 2.900000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 695, 'Y', 6.350000e+001, 'Y', 6.350000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 6, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 697, 'Y', 6.380000e+001, 'Y', 6.380000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 6.498000e+001, 'Y', 4, 0, 31, NULL, 0, "", NULL, 6.483000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 697, 'Y', 7.116000e+001, 'Y', 7.116000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 4, NULL, ' ', 7.180000e+001, 'Y', 5, 0, 23, NULL, 0, "", NULL, 7.248000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 698, 'Y', 2.590000e+001, 'Y', 2.590000e+001, 0, "", "", 0, 0, NULL, 1.100000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 10, 5, NULL, ' ', 2.622000e+001, 'Y', 4, 0, 4, NULL, 0, "", NULL, 2.617000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 700, 'Y', 1.496800e+002, 'Y', 1.496800e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 1.514600e+002, 'Y', 5, 0, 21, NULL, 0, "", NULL, 1.513900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 700, 'Y', 7.056000e+001, 'Y', 7.056000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 7.142000e+001, 'Y', 3, 0, 19, NULL, 0, "", NULL, 7.136000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 701, 'Y', 3.300000e+001, 'Y', 3.300000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 3.226000e+001, 'Y', 3, 0, 43, NULL, 0, "", NULL, 3.259000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 703, 'Y', 9.100000e+001, 'Y', 9.100000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 8.755000e+001, 'Y', 2, 0, 33, NULL, 0, "", NULL, 8.844000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 709, 'Y', 7.090000e+001, 'Y', 7.090000e+001, 0, "", "", 0, 0, NULL, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 3, NULL, ' ', 6.858000e+001, 'Y', 1, 0, 12, NULL, 0, "", NULL, 6.905000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 709, 'Y', 1.283500e+002, 'Y', 1.283500e+002, 0, "", "", 0, 0, NULL, 7.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 1, NULL, ' ', 1.285600e+002, 'Y', 2, 0, 7, NULL, 0, "", NULL, 1.284900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 710, 'Y', 5.800000e+001, 'Y', 5.800000e+001, 0, "", "", 0, 0, NULL, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 9, 5, NULL, ' ', 5.852000e+001, 'Y', 2, 0, 9, NULL, 0, "", NULL, 5.841000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 714, 'Y', 8.371000e+001, 'Y', 8.371000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 8.398000e+001, 'Y', 4, 0, 26, NULL, 0, "", NULL, 8.377000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 715, 'Y', 3.150000e+001, 'Y', 3.150000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 3.124000e+001, 'Y', 4, 0, 41, NULL, 0, "", NULL, 3.130000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 715, 'Y', 8.500000e+001, 'Y', 8.500000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 7.946000e+001, 'Y', 1, 0, 34, NULL, 0, ' ', NULL, 7.934000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 718, 'Y', 7.800000e+001, 'Y', 7.800000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 7.666000e+001, 'Y', 2, 0, 31, NULL, 0, "", NULL, 7.657000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(1, 723, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 13, NULL, 'Q', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 727, 'Y', 3.627300e+002, 'Y', 3.627300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 3.683200e+002, 'Y', 5, 0, 18, NULL, 0, "", NULL, 3.682800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 727, 'Y', 6.777000e+001, 'Y', 6.777000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 4, NULL, ' ', 7.002000e+001, 'Y', 3, 0, 16, NULL, 0, "", NULL, 7.105000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 730, 'Y', 1.833500e+002, 'Y', 1.833500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 1.865800e+002, 'Y', 3, 0, 37, NULL, 0, "", NULL, 1.865400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 732, 'Y', 3.001000e+001, 'Y', 3.001000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 3.023000e+001, 'Y', 2, 0, 34, NULL, 0, "", NULL, 3.031000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 732, 'Y', 6.933000e+001, 'Y', 6.933000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 7.199000e+001, 'Y', 5, 0, 21, NULL, 0, "", NULL, 7.191000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 738, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 11, NULL, ' ', 1.268500e+002, 'Y', 13, 0, 13, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 739, 'Y', 0.000000e+000, 'Y', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 6, NULL, ' ', 1.595000e+002, 'Y', 1, 0, 36, NULL, 0, "", NULL, 1.567600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 739, 'Y', 3.033000e+001, 'Y', 3.033000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 3.068000e+001, 'Y', 4, 0, 36, NULL, 0, "", NULL, 3.051000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 741, 'Y', 8.655000e+001, 'Y', 8.655000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 1, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 741, 'Y', 7.810000e+001, 'Y', 7.810000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 5, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 746, 'Y', 1.283300e+002, 'Y', 1.283300e+002, 0, ' ', ' ', 0, 0, 0, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 5, NULL, ' ', 1.301300e+002, 'Y', 5, 0, 10, NULL, 0, "", NULL, 1.300800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(8, 746, 'Y', 3.417700e+002, 'Y', 3.417700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 5, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 748, 'Y', 1.701300e+002, 'Y', 1.701300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 1.748400e+002, 'Y', 3, 0, 33, NULL, 0, "", NULL, 1.748000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 748, 'Y', 8.527000e+001, 'Y', 8.527000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 5, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 748, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 18, NULL, ' ', 1.608000e+002, 'Y', 12, 0, 12, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 750, 'Y', 1.691800e+002, 'Y', 1.691800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 1.527000e+002, 'Y', 1, 0, 32, NULL, 0, "", NULL, 1.526300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 750, 'Y', 6.621000e+001, 'Y', 6.621000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 6.759000e+001, 'Y', 2, 0, 33, NULL, 0, "", NULL, 6.751000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 754, 'Y', 6.723000e+001, 'Y', 6.723000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 6.799000e+001, 'Y', 4, 0, 35, NULL, 0, "", NULL, 6.793000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 755, "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 6, NULL, 'Q', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 756, 'Y', 1.440900e+002, 'Y', 1.440900e+002, 0, ' ', ' ', 0, 0, 0, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 1.443400e+002, 'Y', 2, 0, 12, NULL, 0, "", NULL, 1.444400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 756, 'Y', 6.990000e+001, 'Y', 6.990000e+001, 0, ' ', ' ', 0, 0, 0, 1.300000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 4, NULL, ' ', 7.009000e+001, 'Y', 2, 0, 2, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(4, 758, 'Y', 1.678600e+002, 'Y', 1.678600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 6, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(11, 758, 'Y', 9.033000e+001, 'Y', 9.033000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 6, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 759, 'Y', 8.422000e+001, 'Y', 8.422000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 8.059000e+001, 'Y', 2, 0, 37, NULL, 0, "", NULL, 8.062000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 762, 'Y', 2.643000e+001, 'Y', 2.643000e+001, 0, ' ', ' ', 0, 0, 0, 5.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 9, 3, NULL, ' ', 2.714000e+001, 'Y', 2, 0, 8, NULL, 0, "", NULL, 2.721000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 762, 'Y', 6.522000e+001, 'Y', 6.522000e+001, 0, ' ', ' ', 0, 0, 0, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 6, 3, NULL, ' ', 6.624000e+001, 'Y', 4, 0, 12, NULL, 0, "", NULL, 6.612000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(5, 763, 'Y', 2.743000e+001, 'Y', 2.743000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 5, NULL, ' ', 2.793000e+001, 'Y', 2, 0, 16, NULL, 0, "", NULL, 2.782000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(10, 763, 'Y', 6.728000e+001, 'Y', 6.728000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 7, 3, NULL, ' ', 6.922000e+001, 'Y', 2, 0, 14, NULL, 0, "", NULL, 6.898000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(6, 769, 'Y', 8.561000e+001, 'Y', 8.561000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 8.628000e+001, 'Y', 3, 0, 31, NULL, 0, "", NULL, 8.632000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 771, 'Y', 6.975000e+001, 'Y', 6.975000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 6.884000e+001, 'Y', 1, 0, 36, NULL, 0, "", NULL, 6.877000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 773, 'Y', 1.666000e+002, 'Y', 1.666000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 1.589500e+002, 'Y', 3, 0, 35, NULL, 0, "", NULL, 1.588300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 773, 'Y', 9.977000e+001, 'Y', 9.977000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 4, NULL, ' ', 8.653000e+001, 'Y', 1, 0, 29, NULL, 0, "", NULL, 8.686000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 778, 'Y', 1.862000e+002, 'Y', 1.862000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 4, NULL, ' ', 1.719300e+002, 'Y', 1, 0, 32, NULL, 0, "", NULL, 1.713700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 778, 'Y', 9.490000e+001, 'Y', 9.490000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 4, NULL, ' ', 8.126000e+001, 'Y', 1, 0, 29, NULL, 0, "", NULL, 8.128000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 781, 'Y', 1.438000e+002, 'Y', 1.438000e+002, 0, "", "", 0, 0, NULL, 9.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 2, NULL, ' ', 1.424600e+002, 'Y', 1, 0, 6, NULL, 0, "", NULL, 1.442200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 781, 'Y', 7.210000e+001, 'Y', 7.210000e+001, 0, "", "", 0, 0, NULL, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 5, NULL, ' ', 7.250000e+001, 'Y', 4, 0, 5, NULL, 0, "", NULL, 7.236000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 782, 'Y', 1.569000e+002, 'Y', 1.569000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 1.645700e+002, 'Y', 6, 0, 42, NULL, 0, "", NULL, 1.643600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 782, 'Y', 7.650000e+001, 'Y', 7.650000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 7.263000e+001, 'Y', 1, 0, 43, NULL, 0, "", NULL, 7.251000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 783, 'Y', 1.440000e+002, 'Y', 1.440000e+002, 0, "", "", 0, 0, NULL, 5.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 6, NULL, ' ', 1.430500e+002, 'Y', 2, 0, 8, NULL, 0, "", NULL, 1.429700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 783, 'Y', 6.669000e+001, 'Y', 6.669000e+001, 0, "", "", 0, 0, NULL, 7.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 8, 2, NULL, ' ', 6.807000e+001, 'Y', 1, 0, 7, NULL, 0, "", NULL, 6.810000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 785, 'Y', 4.240000e+001, 'Y', 4.240000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 5, NULL, ' ', 4.120000e+001, 'Y', 3, 0, 50, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(4, 787, 'Y', 1.889000e+002, 'Y', 1.889000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 5, NULL, ' ', 1.886500e+002, 'Y', 4, 0, 38, NULL, 0, "", NULL, 1.888200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 787, 'Y', 9.200000e+001, 'Y', 9.200000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 2, NULL, ' ', 8.489000e+001, 'Y', 2, 0, 45, NULL, 0, "", NULL, 8.480000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 788, 'Y', 8.930000e+001, 'Y', 8.930000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 2, NULL, ' ', 8.828000e+001, 'Y', 4, 0, 35, NULL, 0, "", NULL, 8.803000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 789, 'Y', 1.517000e+002, 'Y', 1.517000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 2, NULL, ' ', 1.595600e+002, 'Y', 5, 0, 38, NULL, 0, "", NULL, 1.595500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 789, 'Y', 6.960000e+001, 'Y', 6.960000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 6.709000e+001, 'Y', 1, 0, 32, NULL, 0, "", NULL, 6.721000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 790, 'Y', 6.900000e+001, 'Y', 6.900000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 6.766000e+001, 'Y', 3, 0, 34, NULL, 0, "", NULL, 6.759000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 795, 'Y', 3.160000e+001, 'Y', 3.160000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 3.080000e+001, 'Y', 1, 0, 38, NULL, 0, "", NULL, 3.094000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(11, 795, 'Y', 9.040000e+001, 'Y', 9.040000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 8.745000e+001, 'Y', 1, 0, 32, NULL, 0, "", NULL, 8.740000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 796, 'Y', 2.830000e+001, 'Y', 2.830000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 6, 1, NULL, ' ', 2.898000e+001, 'Y', 4, 0, 29, NULL, 0, "", NULL, 2.893000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(8, 796, 'Y', 3.442000e+002, 'Y', 3.442000e+002, 0, "", "", 0, 0, NULL, 7.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 3.520000e+002, 'Y', 5, 0, 7, NULL, 0, "", NULL, 3.520700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 797, 'Y', 6.610000e+001, 'Y', 6.610000e+001, 0, "", "", 0, 0, NULL, 3.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 6.614000e+001, 'Y', 2, 0, 10, NULL, 0, "", NULL, 6.613000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 797, 'Y', 6.898000e+001, 'Y', 6.898000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 7, 5, NULL, ' ', 7.040000e+001, 'Y', 4, 0, 17, NULL, 0, "", NULL, 7.037000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(6, 800, 'Y', 6.790000e+001, 'Y', 6.790000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 7.215000e+001, 'Y', 6, 0, 22, NULL, 0, "", NULL, 7.215000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 801, 'Y', 1.485000e+002, 'Y', 1.485000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 1.456700e+002, 'Y', 2, 0, 27, NULL, 0, "", NULL, 1.454000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(7, 802, 'Y', 7.940000e+001, 'Y', 7.940000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 1, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 803, 'Y', 3.120000e+001, 'Y', 3.120000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 3.291000e+001, 'Y', 5, 0, 44, NULL, 0, "", NULL, 3.295000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 803, 'Y', 8.640000e+001, 'Y', 8.640000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 1, NULL, ' ', 8.659000e+001, 'Y', 5, 0, 49, NULL, 0, ' ', NULL, 8.691000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(5, 805, 'Y', 3.094000e+001, 'Y', 3.094000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 2, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(10, 805, 'Y', 8.114000e+001, 'Y', 8.114000e+001, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 2, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
INSERT INTO `Entry` VALUES(3, 806, 'Y', 1.387100e+002, 'Y', 1.387100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 1.387300e+002, 'Y', 1, 0, 23, NULL, 0, "", NULL, 1.386800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 806, 'Y', 6.075000e+001, 'Y', 6.075000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 8, 1, NULL, ' ', 6.241000e+001, 'Y', 5, 0, 21, NULL, 0, "", NULL, 6.238000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(3, 807, 'Y', 1.699900e+002, 'Y', 1.699900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 1, NULL, ' ', 1.582400e+002, 'Y', 2, 0, 34, NULL, 0, "", NULL, 1.580400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(7, 807, 'Y', 7.480000e+001, 'Y', 7.480000e+001, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 6.953000e+001, 'Y', 3, 0, 39, NULL, 0, "", NULL, 6.971000e+001, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, ' ');
INSERT INTO `Entry` VALUES(1, 808, 'Y', 0.000000e+000, 'Y', 0.000000e+000, 0, ' ', "", 0, 0, NULL, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 14, NULL, ' ', 2.068500e+002, 'Y', 9, 0, 9, NULL, 0, ' ', NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ');
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
INSERT INTO `Event` VALUES(1, ' ', 1, 'I', 'G', 'F', 0.000000e+000, 'F', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 6);
INSERT INTO `Event` VALUES(2, ' ', 2, 'R', 'G', 'F', 2.000000e+002, 'E', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(3, ' ', 3, 'I', 'G', 'F', 2.000000e+002, 'A', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(4, ' ', 4, 'I', 'G', 'F', 2.000000e+002, 'E', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(5, ' ', 5, 'I', 'G', 'F', 5.000000e+001, 'A', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(6, ' ', 6, 'I', 'G', 'F', 1.000000e+002, 'D', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(7, ' ', 7, 'I', 'G', 'F', 1.000000e+002, 'A', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(8, ' ', 8, 'I', 'G', 'F', 5.000000e+002, 'A', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(9, ' ', 9, 'R', 'G', 'F', 2.000000e+002, 'A', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(10, ' ', 10, 'I', 'G', 'F', 1.000000e+002, 'B', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(11, ' ', 11, 'I', 'G', 'F', 1.000000e+002, 'C', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
INSERT INTO `Event` VALUES(12, ' ', 12, 'R', 'G', 'F', 4.000000e+002, 'A', 0, 109, 0, 'S', 1, 6, 6, 'A', 0, 'A', -1, 0, 'A', 'A', -1, NULL, NULL, "", "", "", "", 0, 0, ' ', ' ', '0', ' ', 0, 0, 0);
UNLOCK TABLES;

#
# Table structure for table 'Masters'
#

DROP TABLE IF EXISTS `Masters`;
CREATE TABLE `Masters` (
  `timer_type` SMALLINT DEFAULT 0,
  `pool_name` VARCHAR(45),
  `pool_city` VARCHAR(20),
  `pool_state` VARCHAR(3),
  `pool_lmsc` VARCHAR(3),
  `Meet_type` SMALLINT DEFAULT 0,
  `ref_name` VARCHAR(40),
  `sub_name` VARCHAR(40),
  `sub_address` VARCHAR(40),
  `sub_city` VARCHAR(20),
  `sub_state` VARCHAR(3),
  `sub_zip` VARCHAR(10),
  `sub_phone` VARCHAR(20),
  `sub_email` VARCHAR(50),
  `sendto_name` VARCHAR(40),
  `sendto_address` VARCHAR(40),
  `sendto_city` VARCHAR(20),
  `sendto_state` VARCHAR(3),
  `sendto_zip` VARCHAR(10),
  `sendto_email` VARCHAR(50)
);

#
# Dumping data for table 'Masters'
#

LOCK TABLES `Masters` WRITE;
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
  `ucase_recholders` BIT,
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
  `Sanction_number` VARCHAR(17),
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
  `Meet_altitude` INT,
  `Read_Only` BIT,
  `masters_indlowage` SMALLINT,
  `masters_rellowage` SMALLINT
);

#
# Dumping data for table 'Meet'
#

LOCK TABLES `Meet` WRITE;
INSERT INTO `Meet` VALUES('Millard North Invite', "", "", 'Millard North ', '2006-01-13', '2006-01-14', 6, 3, 1, 3, 0, 0, '2006-01-13', -1, 0, 0, 0, 0, -1, -1, -1, 0, 6, 3, 0, -1, -1, 0, 'YLS', 0, 0, 0, 0, 0, 0, 2, 1, 0, "", "", -1, 0, 0, 'N', -1, 0, 0, 'ENGLISH', 0, -1, 0, 0, 0, 16, 0, 0, 0, 0, -1, 0, 0, 0, NULL, NULL, 6, 6, NULL, NULL, NULL, -1, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, -1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'CBA', 0, "", 0, 0, 0, 0, 1.000000e+000, 'S', 0.000000e+000, -1.000000e+000, 1.000000e+000, -1, 0, 0, 0, 30, 0, 0, 0, "", 0, "", "", "", 0, 0, 18, 72);
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
  `Fin_jdheatplace` SMALLINT,
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
INSERT INTO `Relay` VALUES(2, 17, 'A', 0, 'F', 'Y', 1.197000e+002, 'Y', 1.197000e+002, 0, "", "", 0, 0, NULL, 1.400000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 1.231700e+002, 'Y', 6, 0, 7, NULL, 0, "", NULL, 1.231300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 17, 'A', 0, 'F', 'Y', 1.099900e+002, 'Y', 1.099900e+002, 0, "", "", 0, 0, NULL, 8.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 1.127600e+002, 'Y', 3, 0, 9, NULL, 0, "", NULL, 1.129100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 17, 'A', 0, 'F', 'Y', 2.580000e+002, 'Y', 2.580000e+002, 0, "", "", 0, 0, NULL, 8.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 2.661500e+002, 'Y', 4, 0, 12, NULL, 0, ' ', NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 17, 'B', 0, 'F', 'Y', 1.517900e+002, 'Y', 1.517900e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 2, NULL, ' ', 1.458400e+002, 'Y', 2, 0, 19, NULL, 0, "", NULL, 1.457000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 17, 'B', 0, 'F', 'Y', 1.238100e+002, 'Y', 1.238100e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 1.259500e+002, 'Y', 4, 0, 22, NULL, 0, "", NULL, 1.260700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 17, 'B', 0, 'F', 'Y', 3.154000e+002, 'Y', 3.154000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 3.002400e+002, 'Y', 2, 0, 16, NULL, 0, "", NULL, 3.002700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 5, 'A', 0, 'F', 'Y', 1.260000e+002, 'Y', 1.260000e+002, 0, "", "", 0, 0, NULL, 6.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 1.268400e+002, 'Y', 1, 0, 10, NULL, 0, "", NULL, 1.266900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 5, 'A', 0, 'F', 'Y', 1.180000e+002, 'Y', 1.180000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 1.206200e+002, 'Y', 5, 0, 16, NULL, 0, "", NULL, 1.206100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 5, 'A', 0, 'F', 'Y', 2.500000e+002, 'Y', 2.500000e+002, 0, "", "", 0, 0, NULL, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 2.443900e+002, 'Y', 1, 0, 8, NULL, 0, ' ', NULL, 2.701800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 2, 'A', 0, 'F', 'Y', 1.146600e+002, 'Y', 1.146600e+002, 0, ' ', ' ', 0, 0, 0, 2.400000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 1.175100e+002, 'Y', 3, 0, 3, NULL, 0, "", NULL, 1.173000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, ' ');
INSERT INTO `Relay` VALUES(2, 2, 'B', 0, 'F', 'Y', 1.388800e+002, 'Y', 1.388800e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 1, NULL, ' ', 1.416200e+002, 'Y', 2, 0, 16, NULL, 0, "", NULL, 1.416000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 0, ' ');
INSERT INTO `Relay` VALUES(9, 2, 'A', 0, 'F', 'Y', 1.048900e+002, 'Y', 1.048900e+002, 0, ' ', ' ', 0, 0, 0, 2.400000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 1.059700e+002, 'Y', 3, 0, 3, NULL, 0, "", NULL, 1.059800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 0, ' ');
INSERT INTO `Relay` VALUES(9, 2, 'B', 0, 'F', 'Y', 1.260400e+002, 'Y', 1.260400e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 1, NULL, ' ', 1.230000e+002, 'Y', 3, 0, 20, NULL, 0, "", NULL, 1.227800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 0, ' ');
INSERT INTO `Relay` VALUES(12, 2, 'A', 0, 'F', 'Y', 2.622200e+002, 'Y', 2.622200e+002, 0, ' ', ' ', 0, 0, 0, 6.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 2.668200e+002, 'Y', 5, 0, 13, NULL, 0, ' ', NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 0, ' ');
INSERT INTO `Relay` VALUES(2, 6, 'A', 0, 'F', 'Y', 1.329200e+002, 'Y', 1.329200e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 6, NULL, ' ', 1.352200e+002, 'Y', 3, 0, 13, NULL, 0, "", NULL, 1.351600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 6, 'A', 0, 'F', 'Y', 1.202800e+002, 'Y', 1.202800e+002, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 1.147300e+002, 'Y', 1, 0, 12, NULL, 0, "", NULL, 1.146300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 15, 'A', 0, 'F', 'Y', 1.220000e+002, 'Y', 1.220000e+002, 0, "", "", 0, 0, NULL, 8.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 1.248500e+002, 'Y', 3, 0, 9, NULL, 0, "", NULL, 1.247600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 15, 'A', 0, 'F', 'Y', 1.080000e+002, 'Y', 1.080000e+002, 0, "", "", 0, 0, NULL, 1.400000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 1.083900e+002, 'Y', 1, 0, 7, NULL, 0, "", NULL, 1.082300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 15, 'A', 0, 'F', 'Y', 2.360000e+002, 'Y', 2.360000e+002, 0, "", "", 0, 0, NULL, 2.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 2.350700e+002, 'Y', 5, 0, 5, NULL, 0, "", NULL, 2.351400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 15, 'B', 0, 'F', "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 5, NULL, ' ', 1.365400e+002, 'Y', 1, 0, 14, NULL, 0, ' ', NULL, 1.365400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 15, 'B', 0, 'F', "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 5, NULL, ' ', 1.229500e+002, 'Y', 1, 0, 19, NULL, 0, "", NULL, 1.227600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 15, 'B', 0, 'F', "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 2, NULL, ' ', 2.769000e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 2.767600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 13, 'A', 0, 'F', 'Y', 1.370300e+002, 'Y', 1.370300e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 2, NULL, ' ', 1.376100e+002, 'Y', 1, 0, 15, NULL, 0, "", NULL, 1.375500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 13, 'A', 0, 'F', 'Y', 1.164700e+002, 'Y', 1.164700e+002, 0, "", "", 0, 0, NULL, 6.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 1.129600e+002, 'Y', 1, 0, 10, NULL, 0, "", NULL, 1.129800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 24, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 13, 'A', 0, 'F', 'Y', 2.467700e+002, 'Y', 2.467700e+002, 0, "", "", 0, 0, NULL, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 2.503100e+002, 'Y', 2, 0, 9, NULL, 0, ' ', NULL, 2.502900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 13, 'B', 0, 'F', 'Y', 1.370500e+002, 'Y', 1.370500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 1.470200e+002, 'Y', 5, 0, 20, NULL, 0, "", NULL, 1.467900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 13, 'B', 0, 'F', 'Y', 1.237000e+002, 'Y', 1.237000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 1.224900e+002, 'Y', 2, 0, 17, NULL, 0, "", NULL, 1.224000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 27, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 13, 'B', 0, 'F', 'Y', 2.828000e+002, 'Y', 2.828000e+002, 0, "", "", 0, 0, NULL, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 2.959400e+002, 'Y', 1, 0, 15, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 10, 'A', 0, 'F', 'Y', 1.214100e+002, 'Y', 1.214100e+002, 0, "", "", 0, 0, NULL, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 1.241500e+002, 'Y', 2, 0, 8, NULL, 0, "", NULL, 1.241900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 29, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 10, 'A', 0, 'F', 'Y', 1.150000e+002, 'Y', 1.150000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 1.181100e+002, 'Y', 4, 0, 15, NULL, 0, "", NULL, 1.180500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 10, 'A', 0, 'F', 'Y', 2.404900e+002, 'Y', 2.404900e+002, 0, "", "", 0, 0, NULL, 1.800000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 2, NULL, ' ', 2.419900e+002, 'Y', 1, 0, 6, NULL, 0, "", NULL, 2.419900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 31, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 10, 'B', 0, 'F', 'Y', 1.340500e+002, 'Y', 1.340500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, 'Q', 1.470600e+002, 'Y', 0, 0, 0, NULL, 0, "", NULL, 1.453900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 10, 'B', 0, 'F', 'Y', 1.280000e+002, 'Y', 1.280000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 11, 'A', 0, 'F', 'Y', 1.418800e+002, 'Y', 1.418800e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 6, NULL, ' ', 1.440000e+002, 'Y', 3, 0, 17, NULL, 0, "", NULL, 1.439200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 11, 'A', 0, 'F', 'Y', 1.290500e+002, 'Y', 1.290500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 1.239600e+002, 'Y', 2, 0, 21, NULL, 0, "", NULL, 1.239600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 11, 'A', 0, 'F', 'Y', 3.261800e+002, 'Y', 3.261800e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 1, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 11, 'B', 0, 'F', 'Y', 1.465300e+002, 'Y', 1.465300e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 3, NULL, ' ', 1.669700e+002, 'Y', 4, 0, 22, NULL, 0, "", NULL, 1.667000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 11, 'B', 0, 'F', 'Y', 1.353300e+002, 'Y', 1.353300e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 4, NULL, ' ', 1.467300e+002, 'Y', 3, 0, 23, NULL, 0, "", NULL, 1.466500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 38, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 11, 'B', 0, 'F', 'Y', 3.281100e+002, 'Y', 3.281100e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 3, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 39, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 9, 'A', 0, 'F', 'Y', 1.150000e+002, 'Y', 1.150000e+002, 0, "", "", 0, 0, NULL, 3.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 1.151000e+002, 'Y', 1, 0, 1, NULL, 0, "", NULL, 1.149700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 9, 'B', 0, 'F', 'Y', 1.220000e+002, 'Y', 1.220000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 1.235800e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 1.235400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 41, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 9, 'A', 0, 'F', 'Y', 1.059000e+002, 'Y', 1.059000e+002, 0, "", "", 0, 0, NULL, 2.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 1.063100e+002, 'Y', 5, 0, 5, NULL, 0, "", NULL, 1.062700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 9, 'B', 0, 'F', 'Y', 1.130000e+002, 'Y', 1.130000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 1, NULL, ' ', 1.127800e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 1.126900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 43, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 9, 'A', 0, 'F', 'Y', 2.265000e+002, 'Y', 2.265000e+002, 0, "", "", 0, 0, NULL, 3.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 4, NULL, ' ', 2.245200e+002, 'Y', 1, 0, 1, NULL, 0, "", NULL, 2.246800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 9, 'B', 0, 'F', 'Y', 2.500000e+002, 'Y', 2.500000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 2.491000e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 2.490800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 45, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 7, 'A', 0, 'F', 'Y', 1.278200e+002, 'Y', 1.278200e+002, 0, "", "", 0, 0, NULL, 2.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 2, NULL, ' ', 1.303300e+002, 'Y', 2, 0, 12, NULL, 0, "", NULL, 1.302200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 46, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 7, 'A', 0, 'F', 'Y', 1.037000e+002, 'Y', 1.037000e+002, 0, "", "", 0, 0, NULL, 2.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 1.063000e+002, 'Y', 4, 0, 4, NULL, 0, "", NULL, 1.062100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 47, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 7, 'A', 0, 'F', 'Y', 2.251000e+002, 'Y', 2.251000e+002, 0, "", "", 0, 0, NULL, 2.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 3, NULL, ' ', 2.344900e+002, 'Y', 4, 0, 4, NULL, 0, "", NULL, 2.344100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 48, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 7, 'B', 0, 'F', 'Y', 1.362100e+002, 'Y', 1.362100e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 4, NULL, ' ', 1.452500e+002, 'Y', 4, 0, 18, NULL, 0, "", NULL, 1.450700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 49, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 7, 'B', 0, 'F', 'Y', 1.164000e+002, 'Y', 1.164000e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 1.227200e+002, 'Y', 6, 0, 18, NULL, 0, "", NULL, 1.226500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 7, 'B', 0, 'F', 'Y', 2.640100e+002, 'Y', 2.640100e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 2, 3, NULL, ' ', 2.701500e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 2.701800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 51, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 12, 'A', 0, 'F', 'Y', 1.194300e+002, 'Y', 1.194300e+002, 0, "", "", 0, 0, NULL, 2.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 1.210300e+002, 'Y', 4, 0, 4, NULL, 0, "", NULL, 1.209500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 52, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 12, 'B', 0, 'F', 'Y', 1.256600e+002, 'Y', 1.256600e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 3, 3, NULL, ' ', 1.272400e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 1.270900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 53, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 12, 'A', 0, 'F', 'Y', 1.049100e+002, 'Y', 1.049100e+002, 0, "", "", 0, 0, NULL, 3.200000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 1.043800e+002, 'Y', 1, 0, 1, NULL, 0, "", NULL, 1.044200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 54, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 12, 'B', 0, 'F', "", NULL, "", NULL, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 1, 2, NULL, ' ', 1.086400e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 1.085400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 12, 'A', 0, 'F', 'Y', 2.281600e+002, 'Y', 2.281600e+002, 0, "", "", 0, 0, NULL, 2.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 2.282400e+002, 'Y', 2, 0, 2, NULL, 0, "", NULL, 2.281500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 56, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 12, 'B', 0, 'F', 'Y', 2.439500e+002, 'Y', 2.439500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 2.483800e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 2.483900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 57, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 14, 'A', 0, 'F', 'Y', 1.200600e+002, 'Y', 1.200600e+002, 0, ' ', ' ', 0, 0, 0, 2.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 1.210900e+002, 'Y', 1, 0, 5, NULL, 0, "", NULL, 1.210100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 58, 0, ' ');
INSERT INTO `Relay` VALUES(2, 14, 'B', 0, 'F', 'Y', 1.290600e+002, 'Y', 1.290600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 1.291900e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 1.291600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 59, 0, ' ');
INSERT INTO `Relay` VALUES(9, 14, 'A', 0, 'F', 'Y', 1.059200e+002, 'Y', 1.059200e+002, 0, ' ', ' ', 0, 0, 0, 1.800000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 6, NULL, ' ', 1.074200e+002, 'Y', 6, 0, 6, NULL, 0, "", NULL, 1.074000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 60, 0, ' ');
INSERT INTO `Relay` VALUES(9, 14, 'B', 0, 'F', 'Y', 1.150600e+002, 'Y', 1.150600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 1.175600e+002, 'Y', 3, 0, 14, NULL, 0, "", NULL, 1.174600e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 61, 0, ' ');
INSERT INTO `Relay` VALUES(12, 14, 'A', 0, 'F', 'Y', 2.340600e+002, 'Y', 2.340600e+002, 0, ' ', ' ', 0, 0, 0, 1.400000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 1, NULL, ' ', 2.438000e+002, 'Y', 6, 0, 7, NULL, 0, ' ', NULL, 2.437700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 62, 0, ' ');
INSERT INTO `Relay` VALUES(12, 14, 'B', 0, 'F', 'Y', 2.520600e+002, 'Y', 2.520600e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 2, NULL, ' ', 2.559000e+002, 'Y', 3, 0, 11, NULL, 0, ' ', NULL, 3.002700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 63, 0, ' ');
INSERT INTO `Relay` VALUES(2, 16, 'A', 0, 'F', 'Y', 1.160800e+002, 'Y', 1.160800e+002, 0, ' ', ' ', 0, 0, 0, 1.800000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 1.211600e+002, 'Y', 5, 0, 6, NULL, 0, "", NULL, 1.211900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 64, 0, ' ');
INSERT INTO `Relay` VALUES(2, 16, 'B', 0, 'F', 'Y', 1.303500e+002, 'Y', 1.303500e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 1, NULL, ' ', 1.790800e+002, 'Y', 4, 0, 23, NULL, 0, "", NULL, 1.788500e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 65, 0, ' ');
INSERT INTO `Relay` VALUES(9, 16, 'A', 0, 'F', 'Y', 1.076600e+002, 'Y', 1.076600e+002, 0, ' ', ' ', 0, 0, 0, 1.000000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 1.111900e+002, 'Y', 2, 0, 8, NULL, 0, "", NULL, 1.110700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 66, 0, ' ');
INSERT INTO `Relay` VALUES(9, 16, 'B', 0, 'F', 'Y', 1.131900e+002, 'Y', 1.131900e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 1.159100e+002, 'Y', 4, 0, 13, NULL, 0, "", NULL, 1.159700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 67, 0, ' ');
INSERT INTO `Relay` VALUES(12, 16, 'A', 0, 'F', 'Y', 2.363300e+002, 'Y', 2.363300e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 3, NULL, 'Q', 2.709300e+002, 'Y', 0, 0, 0, NULL, 0, ' ', NULL, 2.707700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 68, 0, ' ');
INSERT INTO `Relay` VALUES(12, 16, 'B', 0, 'F', 'Y', 2.525700e+002, 'Y', 2.525700e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 5, NULL, ' ', 2.785800e+002, 'Y', 6, 0, 14, NULL, 0, ' ', NULL, 3.023800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 69, 0, ' ');
INSERT INTO `Relay` VALUES(12, 16, 'C', 0, 'F', 'Y', 0.000000e+000, 'Y', 0.000000e+000, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 4, NULL, 'R', 0.000000e+000, 'Y', 0, 0, 0, NULL, 0, "", NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 70, 0, ' ');
INSERT INTO `Relay` VALUES(2, 4, 'A', 0, 'F', 'Y', 1.256400e+002, 'Y', 1.256400e+002, 0, ' ', ' ', 0, 0, 0, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 4, 6, NULL, ' ', 1.270100e+002, 'Y', 4, 0, 11, NULL, 0, "", NULL, 1.267700e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 71, 0, ' ');
INSERT INTO `Relay` VALUES(2, 4, 'B', 0, 'F', 'Y', 1.516100e+002, 'Y', 1.516100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 1, 4, NULL, ' ', 1.568200e+002, 'Y', 3, 0, 21, NULL, 0, "", NULL, 1.567300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 72, 0, ' ');
INSERT INTO `Relay` VALUES(9, 4, 'A', 0, 'F', 'Y', 1.160300e+002, 'Y', 1.160300e+002, 0, ' ', ' ', 0, 0, 0, 4.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 2, NULL, ' ', 1.138400e+002, 'Y', 2, 0, 11, NULL, 0, "", NULL, 1.138200e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 73, 0, ' ');
INSERT INTO `Relay` VALUES(9, 4, 'B', 0, 'F', 'Y', 1.250000e+002, 'Y', 1.250000e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 1.531300e+002, 'Y', 5, 0, 24, NULL, 0, "", NULL, 1.532400e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 74, 0, ' ');
INSERT INTO `Relay` VALUES(12, 4, 'A', 0, 'F', 'Y', 2.514900e+002, 'Y', 2.514900e+002, 0, ' ', ' ', 0, 0, 0, 1.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 3, 4, NULL, ' ', 2.533700e+002, 'Y', 2, 0, 10, NULL, 0, ' ', NULL, 0.000000e+000, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 75, 0, ' ');
INSERT INTO `Relay` VALUES(12, 4, 'B', 0, 'F', 'Y', 3.256100e+002, 'Y', 3.256100e+002, 0, ' ', ' ', 0, 0, 0, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, ' ', NULL, NULL, NULL, NULL, 2, 5, NULL, ' ', 3.027500e+002, 'Y', 3, 0, 17, NULL, 0, "", NULL, 3.023800e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 76, 0, ' ');
INSERT INTO `Relay` VALUES(2, 3, 'A', 0, 'F', 'Y', 1.150500e+002, 'Y', 1.150500e+002, 0, "", "", 0, 0, NULL, 2.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 2, NULL, ' ', 1.156300e+002, 'Y', 2, 0, 2, NULL, 0, "", NULL, 1.156100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 77, NULL, ' ');
INSERT INTO `Relay` VALUES(2, 3, 'B', 0, 'F', 'Y', 1.198500e+002, 'Y', 1.198500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 3, NULL, ' ', 1.241000e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 1.239100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 78, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 3, 'A', 0, 'F', 'Y', 1.054400e+002, 'Y', 1.054400e+002, 0, "", "", 0, 0, NULL, 2.600000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 1.049400e+002, 'Y', 2, 0, 2, NULL, 0, "", NULL, 1.049300e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 79, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 3, 'A', 0, 'F', 'Y', 2.314700e+002, 'Y', 2.314700e+002, 0, "", "", 0, 0, NULL, 2.400000e+001, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 5, 5, NULL, ' ', 2.333400e+002, 'Y', 3, 0, 3, NULL, 0, "", NULL, 2.333000e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 80, NULL, ' ');
INSERT INTO `Relay` VALUES(9, 3, 'B', 0, 'F', 'Y', 1.115500e+002, 'Y', 1.115500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 5, NULL, ' ', 1.143000e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 1.142100e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 81, NULL, ' ');
INSERT INTO `Relay` VALUES(12, 3, 'B', 0, 'F', 'Y', 2.385500e+002, 'Y', 2.385500e+002, 0, "", "", 0, 0, NULL, 0.000000e+000, "", NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, 4, 4, NULL, ' ', 2.528300e+002, 'Y', 0, 0, 0, NULL, 0, 'X', NULL, 2.536900e+002, 0.000000e+000, 0.000000e+000, NULL, NULL, "", NULL, "", NULL, NULL, NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, "", NULL, "", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 82, NULL, ' ');
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
INSERT INTO `RelayNames` VALUES(2, 2, 'A', 14, 1, 'F', 10);
INSERT INTO `RelayNames` VALUES(9, 2, 'A', 14, 1, 'F', 12);
INSERT INTO `RelayNames` VALUES(2, 2, 'B', 16, 4, 'F', 11);
INSERT INTO `RelayNames` VALUES(9, 2, 'A', 16, 5, 'F', 12);
INSERT INTO `RelayNames` VALUES(12, 2, 'A', 16, 1, 'F', 14);
INSERT INTO `RelayNames` VALUES(2, 2, 'A', 17, 6, 'F', 10);
INSERT INTO `RelayNames` VALUES(9, 2, 'B', 17, 3, 'F', 13);
INSERT INTO `RelayNames` VALUES(12, 2, 'A', 17, 3, 'F', 14);
INSERT INTO `RelayNames` VALUES(2, 2, 'B', 23, 2, 'F', 11);
INSERT INTO `RelayNames` VALUES(9, 2, 'B', 23, 2, 'F', 13);
INSERT INTO `RelayNames` VALUES(12, 2, 'A', 23, 5, 'F', 14);
INSERT INTO `RelayNames` VALUES(2, 2, 'B', 24, 1, 'F', 11);
INSERT INTO `RelayNames` VALUES(9, 2, 'B', 24, 1, 'F', 13);
INSERT INTO `RelayNames` VALUES(12, 2, 'A', 24, 2, 'F', 14);
INSERT INTO `RelayNames` VALUES(2, 2, 'A', 34, 5, 'F', 10);
INSERT INTO `RelayNames` VALUES(9, 2, 'B', 34, 4, 'F', 13);
INSERT INTO `RelayNames` VALUES(12, 2, 'A', 34, 4, 'F', 14);
INSERT INTO `RelayNames` VALUES(2, 2, 'B', 43, 3, 'F', 11);
INSERT INTO `RelayNames` VALUES(9, 2, 'A', 43, 6, 'F', 12);
INSERT INTO `RelayNames` VALUES(12, 2, 'A', 43, 6, 'F', 14);
INSERT INTO `RelayNames` VALUES(2, 2, 'A', 44, 2, 'F', 10);
INSERT INTO `RelayNames` VALUES(9, 2, 'A', 44, 2, 'F', 12);
INSERT INTO `RelayNames` VALUES(2, 2, 'A', 46, 4, 'F', 10);
INSERT INTO `RelayNames` VALUES(9, 2, 'A', 46, 3, 'F', 12);
INSERT INTO `RelayNames` VALUES(2, 2, 'A', 60, 3, 'F', 10);
INSERT INTO `RelayNames` VALUES(9, 2, 'A', 60, 4, 'F', 12);
INSERT INTO `RelayNames` VALUES(2, 3, 'A', 68, 4, 'F', 77);
INSERT INTO `RelayNames` VALUES(9, 3, 'A', 68, 4, 'F', 79);
INSERT INTO `RelayNames` VALUES(9, 3, 'B', 69, 2, 'F', 81);
INSERT INTO `RelayNames` VALUES(2, 3, 'A', 70, 2, 'F', 77);
INSERT INTO `RelayNames` VALUES(9, 3, 'B', 75, 3, 'F', 81);
INSERT INTO `RelayNames` VALUES(2, 3, 'B', 83, 3, 'F', 78);
INSERT INTO `RelayNames` VALUES(9, 3, 'B', 83, 4, 'F', 81);
INSERT INTO `RelayNames` VALUES(2, 3, 'B', 84, 2, 'F', 78);
INSERT INTO `RelayNames` VALUES(12, 3, 'B', 84, 1, 'F', 82);
INSERT INTO `RelayNames` VALUES(2, 3, 'B', 85, 1, 'F', 78);
INSERT INTO `RelayNames` VALUES(12, 3, 'B', 85, 2, 'F', 82);
INSERT INTO `RelayNames` VALUES(12, 3, 'B', 92, 3, 'F', 82);
INSERT INTO `RelayNames` VALUES(12, 3, 'A', 102, 2, 'F', 80);
INSERT INTO `RelayNames` VALUES(9, 3, 'A', 102, 2, 'F', 79);
INSERT INTO `RelayNames` VALUES(12, 3, 'B', 111, 4, 'F', 82);
INSERT INTO `RelayNames` VALUES(2, 3, 'A', 113, 1, 'F', 77);
INSERT INTO `RelayNames` VALUES(9, 3, 'A', 113, 3, 'F', 79);
INSERT INTO `RelayNames` VALUES(9, 3, 'A', 115, 1, 'F', 79);
INSERT INTO `RelayNames` VALUES(12, 3, 'A', 115, 4, 'F', 80);
INSERT INTO `RelayNames` VALUES(9, 3, 'B', 117, 1, 'F', 81);
INSERT INTO `RelayNames` VALUES(12, 3, 'A', 123, 3, 'F', 80);
INSERT INTO `RelayNames` VALUES(2, 3, 'B', 123, 4, 'F', 78);
INSERT INTO `RelayNames` VALUES(2, 3, 'A', 126, 3, 'F', 77);
INSERT INTO `RelayNames` VALUES(12, 3, 'A', 126, 1, 'F', 80);
INSERT INTO `RelayNames` VALUES(2, 4, 'A', 133, 1, 'F', 71);
INSERT INTO `RelayNames` VALUES(9, 4, 'A', 133, 1, 'F', 73);
INSERT INTO `RelayNames` VALUES(9, 4, 'A', 138, 2, 'F', 73);
INSERT INTO `RelayNames` VALUES(12, 4, 'A', 138, 1, 'F', 75);
INSERT INTO `RelayNames` VALUES(9, 4, 'B', 140, 1, 'F', 74);
INSERT INTO `RelayNames` VALUES(2, 4, 'B', 145, 3, 'F', 72);
INSERT INTO `RelayNames` VALUES(12, 4, 'A', 147, 2, 'F', 75);
INSERT INTO `RelayNames` VALUES(2, 4, 'B', 149, 4, 'F', 72);
INSERT INTO `RelayNames` VALUES(12, 4, 'B', 149, 1, 'F', 76);
INSERT INTO `RelayNames` VALUES(9, 4, 'B', 154, 2, 'F', 74);
INSERT INTO `RelayNames` VALUES(2, 4, 'B', 155, 2, 'F', 72);
INSERT INTO `RelayNames` VALUES(12, 4, 'B', 155, 2, 'F', 76);
INSERT INTO `RelayNames` VALUES(2, 4, 'A', 158, 2, 'F', 71);
INSERT INTO `RelayNames` VALUES(9, 4, 'A', 158, 3, 'F', 73);
INSERT INTO `RelayNames` VALUES(2, 4, 'B', 160, 1, 'F', 72);
INSERT INTO `RelayNames` VALUES(12, 4, 'B', 160, 3, 'F', 76);
INSERT INTO `RelayNames` VALUES(9, 4, 'B', 163, 3, 'F', 74);
INSERT INTO `RelayNames` VALUES(2, 4, 'A', 164, 3, 'F', 71);
INSERT INTO `RelayNames` VALUES(12, 4, 'A', 164, 3, 'F', 75);
INSERT INTO `RelayNames` VALUES(9, 4, 'B', 166, 4, 'F', 74);
INSERT INTO `RelayNames` VALUES(2, 4, 'A', 169, 4, 'F', 71);
INSERT INTO `RelayNames` VALUES(9, 4, 'A', 169, 4, 'F', 73);
INSERT INTO `RelayNames` VALUES(12, 4, 'A', 169, 4, 'F', 75);
INSERT INTO `RelayNames` VALUES(12, 5, 'A', 178, 3, 'F', 9);
INSERT INTO `RelayNames` VALUES(2, 5, 'A', 178, 1, 'F', 7);
INSERT INTO `RelayNames` VALUES(2, 5, 'A', 182, 4, 'F', 7);
INSERT INTO `RelayNames` VALUES(12, 5, 'A', 182, 2, 'F', 9);
INSERT INTO `RelayNames` VALUES(2, 5, 'A', 194, 3, 'F', 7);
INSERT INTO `RelayNames` VALUES(9, 5, 'A', 194, 1, 'F', 8);
INSERT INTO `RelayNames` VALUES(9, 5, 'A', 195, 3, 'F', 8);
INSERT INTO `RelayNames` VALUES(2, 5, 'A', 200, 2, 'F', 7);
INSERT INTO `RelayNames` VALUES(12, 5, 'A', 200, 1, 'F', 9);
INSERT INTO `RelayNames` VALUES(9, 5, 'A', 206, 2, 'F', 8);
INSERT INTO `RelayNames` VALUES(9, 5, 'A', 211, 4, 'F', 8);
INSERT INTO `RelayNames` VALUES(12, 5, 'A', 211, 4, 'F', 9);
INSERT INTO `RelayNames` VALUES(2, 6, 'A', 217, 4, 'F', 15);
INSERT INTO `RelayNames` VALUES(9, 6, 'A', 217, 1, 'F', 16);
INSERT INTO `RelayNames` VALUES(9, 6, 'A', 227, 3, 'F', 16);
INSERT INTO `RelayNames` VALUES(2, 6, 'A', 229, 1, 'F', 15);
INSERT INTO `RelayNames` VALUES(2, 6, 'A', 230, 2, 'F', 15);
INSERT INTO `RelayNames` VALUES(2, 6, 'A', 231, 3, 'F', 15);
INSERT INTO `RelayNames` VALUES(9, 6, 'A', 231, 4, 'F', 16);
INSERT INTO `RelayNames` VALUES(9, 6, 'A', 249, 2, 'F', 16);
INSERT INTO `RelayNames` VALUES(9, 7, 'A', 257, 1, 'F', 47);
INSERT INTO `RelayNames` VALUES(12, 7, 'A', 257, 2, 'F', 48);
INSERT INTO `RelayNames` VALUES(2, 7, 'A', 259, 3, 'F', 46);
INSERT INTO `RelayNames` VALUES(12, 7, 'A', 259, 3, 'F', 48);
INSERT INTO `RelayNames` VALUES(9, 7, 'A', 260, 4, 'F', 47);
INSERT INTO `RelayNames` VALUES(12, 7, 'A', 260, 4, 'F', 48);
INSERT INTO `RelayNames` VALUES(2, 7, 'A', 262, 2, 'F', 46);
INSERT INTO `RelayNames` VALUES(2, 7, 'B', 263, 2, 'F', 49);
INSERT INTO `RelayNames` VALUES(2, 7, 'A', 274, 4, 'F', 46);
INSERT INTO `RelayNames` VALUES(9, 7, 'A', 274, 3, 'F', 47);
INSERT INTO `RelayNames` VALUES(9, 7, 'B', 282, 4, 'F', 50);
INSERT INTO `RelayNames` VALUES(2, 7, 'B', 283, 4, 'F', 49);
INSERT INTO `RelayNames` VALUES(9, 7, 'B', 283, 2, 'F', 50);
INSERT INTO `RelayNames` VALUES(9, 7, 'B', 285, 1, 'F', 50);
INSERT INTO `RelayNames` VALUES(12, 7, 'B', 285, 1, 'F', 51);
INSERT INTO `RelayNames` VALUES(9, 7, 'A', 287, 2, 'F', 47);
INSERT INTO `RelayNames` VALUES(12, 7, 'A', 287, 1, 'F', 48);
INSERT INTO `RelayNames` VALUES(2, 7, 'B', 288, 1, 'F', 49);
INSERT INTO `RelayNames` VALUES(9, 7, 'B', 289, 3, 'F', 50);
INSERT INTO `RelayNames` VALUES(2, 7, 'B', 295, 3, 'F', 49);
INSERT INTO `RelayNames` VALUES(12, 7, 'B', 295, 2, 'F', 51);
INSERT INTO `RelayNames` VALUES(2, 7, 'A', 297, 1, 'F', 46);
INSERT INTO `RelayNames` VALUES(12, 7, 'B', 297, 4, 'F', 51);
INSERT INTO `RelayNames` VALUES(12, 7, 'B', 298, 3, 'F', 51);
INSERT INTO `RelayNames` VALUES(2, 9, 'A', 359, 2, 'F', 40);
INSERT INTO `RelayNames` VALUES(12, 9, 'A', 359, 4, 'F', 44);
INSERT INTO `RelayNames` VALUES(2, 9, 'A', 368, 3, 'F', 40);
INSERT INTO `RelayNames` VALUES(12, 9, 'A', 368, 2, 'F', 44);
INSERT INTO `RelayNames` VALUES(2, 9, 'A', 378, 1, 'F', 40);
INSERT INTO `RelayNames` VALUES(9, 9, 'A', 378, 2, 'F', 42);
INSERT INTO `RelayNames` VALUES(9, 9, 'A', 379, 3, 'F', 42);
INSERT INTO `RelayNames` VALUES(2, 9, 'A', 380, 4, 'F', 40);
INSERT INTO `RelayNames` VALUES(9, 9, 'A', 380, 4, 'F', 42);
INSERT INTO `RelayNames` VALUES(12, 9, 'A', 380, 1, 'F', 44);
INSERT INTO `RelayNames` VALUES(2, 9, 'B', 384, 4, 'F', 41);
INSERT INTO `RelayNames` VALUES(9, 9, 'B', 384, 4, 'F', 43);
INSERT INTO `RelayNames` VALUES(12, 9, 'B', 384, 2, 'F', 45);
INSERT INTO `RelayNames` VALUES(2, 9, 'B', 390, 3, 'F', 41);
INSERT INTO `RelayNames` VALUES(9, 9, 'B', 390, 1, 'F', 43);
INSERT INTO `RelayNames` VALUES(9, 9, 'A', 394, 1, 'F', 42);
INSERT INTO `RelayNames` VALUES(12, 9, 'A', 394, 3, 'F', 44);
INSERT INTO `RelayNames` VALUES(2, 9, 'B', 399, 1, 'F', 41);
INSERT INTO `RelayNames` VALUES(12, 9, 'B', 399, 1, 'F', 45);
INSERT INTO `RelayNames` VALUES(9, 9, 'B', 402, 3, 'F', 43);
INSERT INTO `RelayNames` VALUES(12, 9, 'B', 406, 4, 'F', 45);
INSERT INTO `RelayNames` VALUES(9, 9, 'B', 410, 2, 'F', 43);
INSERT INTO `RelayNames` VALUES(2, 9, 'B', 412, 2, 'F', 41);
INSERT INTO `RelayNames` VALUES(12, 9, 'B', 417, 3, 'F', 45);
INSERT INTO `RelayNames` VALUES(2, 10, 'A', 421, 4, 'F', 29);
INSERT INTO `RelayNames` VALUES(12, 10, 'A', 421, 4, 'F', 31);
INSERT INTO `RelayNames` VALUES(2, 10, 'A', 427, 3, 'F', 29);
INSERT INTO `RelayNames` VALUES(12, 10, 'A', 427, 3, 'F', 31);
INSERT INTO `RelayNames` VALUES(9, 10, 'B', 430, 3, 'F', 33);
INSERT INTO `RelayNames` VALUES(2, 10, 'A', 435, 2, 'F', 29);
INSERT INTO `RelayNames` VALUES(9, 10, 'A', 435, 4, 'F', 30);
INSERT INTO `RelayNames` VALUES(9, 10, 'B', 437, 2, 'F', 33);
INSERT INTO `RelayNames` VALUES(9, 10, 'A', 439, 3, 'F', 30);
INSERT INTO `RelayNames` VALUES(2, 10, 'B', 439, 2, 'F', 32);
INSERT INTO `RelayNames` VALUES(9, 10, 'A', 440, 1, 'F', 30);
INSERT INTO `RelayNames` VALUES(12, 10, 'A', 440, 2, 'F', 31);
INSERT INTO `RelayNames` VALUES(2, 10, 'A', 446, 1, 'F', 29);
INSERT INTO `RelayNames` VALUES(12, 10, 'A', 446, 1, 'F', 31);
INSERT INTO `RelayNames` VALUES(9, 10, 'A', 447, 2, 'F', 30);
INSERT INTO `RelayNames` VALUES(2, 10, 'B', 447, 3, 'F', 32);
INSERT INTO `RelayNames` VALUES(2, 10, 'B', 453, 4, 'F', 32);
INSERT INTO `RelayNames` VALUES(9, 10, 'B', 453, 4, 'F', 33);
INSERT INTO `RelayNames` VALUES(12, 11, 'A', 458, 4, 'F', 36);
INSERT INTO `RelayNames` VALUES(9, 11, 'B', 458, 3, 'F', 38);
INSERT INTO `RelayNames` VALUES(2, 11, 'A', 466, 3, 'F', 34);
INSERT INTO `RelayNames` VALUES(9, 11, 'A', 466, 4, 'F', 35);
INSERT INTO `RelayNames` VALUES(12, 11, 'B', 466, 4, 'F', 39);
INSERT INTO `RelayNames` VALUES(2, 11, 'B', 470, 1, 'F', 37);
INSERT INTO `RelayNames` VALUES(12, 11, 'B', 470, 1, 'F', 39);
INSERT INTO `RelayNames` VALUES(2, 11, 'A', 472, 4, 'F', 34);
INSERT INTO `RelayNames` VALUES(9, 11, 'A', 472, 1, 'F', 35);
INSERT INTO `RelayNames` VALUES(12, 11, 'A', 472, 1, 'F', 36);
INSERT INTO `RelayNames` VALUES(12, 11, 'A', 475, 3, 'F', 36);
INSERT INTO `RelayNames` VALUES(2, 11, 'B', 475, 2, 'F', 37);
INSERT INTO `RelayNames` VALUES(9, 11, 'B', 475, 1, 'F', 38);
INSERT INTO `RelayNames` VALUES(12, 11, 'A', 482, 2, 'F', 36);
INSERT INTO `RelayNames` VALUES(2, 11, 'B', 482, 3, 'F', 37);
INSERT INTO `RelayNames` VALUES(9, 11, 'B', 482, 2, 'F', 38);
INSERT INTO `RelayNames` VALUES(9, 11, 'B', 483, 4, 'F', 38);
INSERT INTO `RelayNames` VALUES(12, 11, 'B', 483, 3, 'F', 39);
INSERT INTO `RelayNames` VALUES(2, 11, 'A', 484, 1, 'F', 34);
INSERT INTO `RelayNames` VALUES(9, 11, 'A', 484, 3, 'F', 35);
INSERT INTO `RelayNames` VALUES(12, 11, 'B', 484, 2, 'F', 39);
INSERT INTO `RelayNames` VALUES(2, 11, 'A', 486, 2, 'F', 34);
INSERT INTO `RelayNames` VALUES(9, 11, 'A', 486, 2, 'F', 35);
INSERT INTO `RelayNames` VALUES(2, 11, 'B', 488, 4, 'F', 37);
INSERT INTO `RelayNames` VALUES(2, 12, 'B', 494, 2, 'F', 53);
INSERT INTO `RelayNames` VALUES(12, 12, 'B', 494, 4, 'F', 57);
INSERT INTO `RelayNames` VALUES(2, 12, 'A', 496, 1, 'F', 52);
INSERT INTO `RelayNames` VALUES(12, 12, 'A', 496, 2, 'F', 56);
INSERT INTO `RelayNames` VALUES(2, 12, 'B', 504, 3, 'F', 53);
INSERT INTO `RelayNames` VALUES(12, 12, 'B', 504, 1, 'F', 57);
INSERT INTO `RelayNames` VALUES(2, 12, 'B', 505, 4, 'F', 53);
INSERT INTO `RelayNames` VALUES(9, 12, 'B', 505, 2, 'F', 55);
INSERT INTO `RelayNames` VALUES(2, 12, 'B', 509, 1, 'F', 53);
INSERT INTO `RelayNames` VALUES(12, 12, 'B', 509, 2, 'F', 57);
INSERT INTO `RelayNames` VALUES(9, 12, 'B', 510, 3, 'F', 55);
INSERT INTO `RelayNames` VALUES(12, 12, 'B', 510, 3, 'F', 57);
INSERT INTO `RelayNames` VALUES(2, 12, 'A', 528, 2, 'F', 52);
INSERT INTO `RelayNames` VALUES(9, 12, 'B', 528, 1, 'F', 55);
INSERT INTO `RelayNames` VALUES(9, 12, 'A', 533, 1, 'F', 54);
INSERT INTO `RelayNames` VALUES(12, 12, 'A', 533, 1, 'F', 56);
INSERT INTO `RelayNames` VALUES(9, 12, 'A', 534, 4, 'F', 54);
INSERT INTO `RelayNames` VALUES(12, 12, 'A', 534, 4, 'F', 56);
INSERT INTO `RelayNames` VALUES(9, 12, 'A', 553, 3, 'F', 54);
INSERT INTO `RelayNames` VALUES(12, 12, 'A', 553, 3, 'F', 56);
INSERT INTO `RelayNames` VALUES(2, 12, 'A', 556, 3, 'F', 52);
INSERT INTO `RelayNames` VALUES(9, 12, 'B', 556, 4, 'F', 55);
INSERT INTO `RelayNames` VALUES(2, 12, 'A', 561, 4, 'F', 52);
INSERT INTO `RelayNames` VALUES(9, 12, 'A', 561, 2, 'F', 54);
INSERT INTO `RelayNames` VALUES(9, 13, 'A', 564, 1, 'F', 24);
INSERT INTO `RelayNames` VALUES(12, 13, 'A', 564, 3, 'F', 25);
INSERT INTO `RelayNames` VALUES(2, 13, 'A', 567, 3, 'F', 23);
INSERT INTO `RelayNames` VALUES(9, 13, 'A', 567, 3, 'F', 24);
INSERT INTO `RelayNames` VALUES(2, 13, 'B', 569, 1, 'F', 26);
INSERT INTO `RelayNames` VALUES(12, 13, 'B', 569, 1, 'F', 28);
INSERT INTO `RelayNames` VALUES(2, 13, 'A', 570, 1, 'F', 23);
INSERT INTO `RelayNames` VALUES(12, 13, 'B', 570, 2, 'F', 28);
INSERT INTO `RelayNames` VALUES(12, 13, 'A', 572, 2, 'F', 25);
INSERT INTO `RelayNames` VALUES(9, 13, 'B', 572, 1, 'F', 27);
INSERT INTO `RelayNames` VALUES(2, 13, 'B', 573, 4, 'F', 26);
INSERT INTO `RelayNames` VALUES(9, 13, 'B', 573, 4, 'F', 27);
INSERT INTO `RelayNames` VALUES(2, 13, 'B', 581, 2, 'F', 26);
INSERT INTO `RelayNames` VALUES(9, 13, 'A', 583, 2, 'F', 24);
INSERT INTO `RelayNames` VALUES(12, 13, 'A', 583, 1, 'F', 25);
INSERT INTO `RelayNames` VALUES(2, 13, 'A', 584, 4, 'F', 23);
INSERT INTO `RelayNames` VALUES(9, 13, 'A', 584, 4, 'F', 24);
INSERT INTO `RelayNames` VALUES(12, 13, 'A', 585, 4, 'F', 25);
INSERT INTO `RelayNames` VALUES(9, 13, 'B', 585, 2, 'F', 27);
INSERT INTO `RelayNames` VALUES(2, 13, 'B', 586, 3, 'F', 26);
INSERT INTO `RelayNames` VALUES(12, 13, 'B', 586, 4, 'F', 28);
INSERT INTO `RelayNames` VALUES(12, 13, 'B', 587, 3, 'F', 28);
INSERT INTO `RelayNames` VALUES(2, 13, 'A', 599, 2, 'F', 23);
INSERT INTO `RelayNames` VALUES(9, 13, 'B', 599, 3, 'F', 27);
INSERT INTO `RelayNames` VALUES(9, 14, 'A', 619, 4, 'F', 60);
INSERT INTO `RelayNames` VALUES(12, 14, 'A', 619, 4, 'F', 62);
INSERT INTO `RelayNames` VALUES(2, 14, 'B', 623, 7, 'F', 59);
INSERT INTO `RelayNames` VALUES(9, 14, 'A', 623, 5, 'F', 60);
INSERT INTO `RelayNames` VALUES(12, 14, 'B', 623, 2, 'F', 63);
INSERT INTO `RelayNames` VALUES(2, 14, 'A', 626, 1, 'F', 58);
INSERT INTO `RelayNames` VALUES(9, 14, 'A', 626, 1, 'F', 60);
INSERT INTO `RelayNames` VALUES(2, 14, 'A', 642, 4, 'F', 58);
INSERT INTO `RelayNames` VALUES(12, 14, 'A', 642, 1, 'F', 62);
INSERT INTO `RelayNames` VALUES(2, 14, 'B', 646, 8, 'F', 59);
INSERT INTO `RelayNames` VALUES(9, 14, 'B', 646, 6, 'F', 61);
INSERT INTO `RelayNames` VALUES(12, 14, 'B', 646, 5, 'F', 63);
INSERT INTO `RelayNames` VALUES(2, 14, 'B', 655, 6, 'F', 59);
INSERT INTO `RelayNames` VALUES(9, 14, 'A', 655, 6, 'F', 60);
INSERT INTO `RelayNames` VALUES(12, 14, 'A', 655, 5, 'F', 62);
INSERT INTO `RelayNames` VALUES(2, 14, 'B', 658, 3, 'F', 59);
INSERT INTO `RelayNames` VALUES(12, 14, 'B', 658, 4, 'F', 63);
INSERT INTO `RelayNames` VALUES(2, 14, 'B', 660, 5, 'F', 59);
INSERT INTO `RelayNames` VALUES(9, 14, 'B', 660, 4, 'F', 61);
INSERT INTO `RelayNames` VALUES(2, 14, 'A', 661, 7, 'F', 58);
INSERT INTO `RelayNames` VALUES(9, 14, 'A', 661, 7, 'F', 60);
INSERT INTO `RelayNames` VALUES(12, 14, 'A', 661, 6, 'F', 62);
INSERT INTO `RelayNames` VALUES(2, 14, 'A', 664, 5, 'F', 58);
INSERT INTO `RelayNames` VALUES(9, 14, 'B', 664, 2, 'F', 61);
INSERT INTO `RelayNames` VALUES(12, 14, 'B', 664, 3, 'F', 63);
INSERT INTO `RelayNames` VALUES(2, 14, 'A', 670, 6, 'F', 58);
INSERT INTO `RelayNames` VALUES(9, 14, 'B', 670, 1, 'F', 61);
INSERT INTO `RelayNames` VALUES(12, 14, 'A', 670, 7, 'F', 62);
INSERT INTO `RelayNames` VALUES(2, 14, 'A', 673, 2, 'F', 58);
INSERT INTO `RelayNames` VALUES(9, 14, 'A', 673, 2, 'F', 60);
INSERT INTO `RelayNames` VALUES(9, 14, 'A', 674, 8, 'F', 60);
INSERT INTO `RelayNames` VALUES(12, 14, 'A', 674, 8, 'F', 62);
INSERT INTO `RelayNames` VALUES(2, 14, 'A', 676, 3, 'F', 58);
INSERT INTO `RelayNames` VALUES(9, 14, 'A', 676, 3, 'F', 60);
INSERT INTO `RelayNames` VALUES(2, 14, 'B', 679, 4, 'F', 59);
INSERT INTO `RelayNames` VALUES(12, 14, 'B', 679, 1, 'F', 63);
INSERT INTO `RelayNames` VALUES(9, 14, 'B', 680, 7, 'F', 61);
INSERT INTO `RelayNames` VALUES(12, 14, 'B', 680, 6, 'F', 63);
INSERT INTO `RelayNames` VALUES(9, 14, 'B', 682, 3, 'F', 61);
INSERT INTO `RelayNames` VALUES(12, 14, 'A', 682, 2, 'F', 62);
INSERT INTO `RelayNames` VALUES(2, 14, 'B', 684, 2, 'F', 59);
INSERT INTO `RelayNames` VALUES(12, 14, 'A', 684, 3, 'F', 62);
INSERT INTO `RelayNames` VALUES(9, 14, 'B', 686, 5, 'F', 61);
INSERT INTO `RelayNames` VALUES(12, 14, 'B', 686, 7, 'F', 63);
INSERT INTO `RelayNames` VALUES(2, 14, 'B', 687, 1, 'F', 59);
INSERT INTO `RelayNames` VALUES(9, 14, 'B', 687, 8, 'F', 61);
INSERT INTO `RelayNames` VALUES(12, 14, 'B', 687, 8, 'F', 63);
INSERT INTO `RelayNames` VALUES(9, 15, 'B', 691, 1, 'F', 21);
INSERT INTO `RelayNames` VALUES(9, 15, 'B', 692, 4, 'F', 21);
INSERT INTO `RelayNames` VALUES(12, 15, 'B', 692, 2, 'F', 22);
INSERT INTO `RelayNames` VALUES(2, 15, 'A', 694, 2, 'F', 17);
INSERT INTO `RelayNames` VALUES(9, 15, 'A', 694, 3, 'F', 18);
INSERT INTO `RelayNames` VALUES(12, 15, 'A', 694, 2, 'F', 19);
INSERT INTO `RelayNames` VALUES(12, 15, 'B', 695, 1, 'F', 22);
INSERT INTO `RelayNames` VALUES(2, 15, 'B', 697, 1, 'F', 20);
INSERT INTO `RelayNames` VALUES(9, 15, 'B', 697, 2, 'F', 21);
INSERT INTO `RelayNames` VALUES(2, 15, 'A', 698, 1, 'F', 17);
INSERT INTO `RelayNames` VALUES(9, 15, 'A', 698, 1, 'F', 18);
INSERT INTO `RelayNames` VALUES(12, 15, 'A', 698, 1, 'F', 19);
INSERT INTO `RelayNames` VALUES(2, 15, 'A', 700, 3, 'F', 17);
INSERT INTO `RelayNames` VALUES(9, 15, 'A', 700, 2, 'F', 18);
INSERT INTO `RelayNames` VALUES(9, 15, 'B', 701, 3, 'F', 21);
INSERT INTO `RelayNames` VALUES(12, 15, 'B', 701, 4, 'F', 22);
INSERT INTO `RelayNames` VALUES(9, 15, 'A', 709, 4, 'F', 18);
INSERT INTO `RelayNames` VALUES(12, 15, 'A', 709, 3, 'F', 19);
INSERT INTO `RelayNames` VALUES(2, 15, 'A', 710, 4, 'F', 17);
INSERT INTO `RelayNames` VALUES(12, 15, 'A', 710, 4, 'F', 19);
INSERT INTO `RelayNames` VALUES(2, 15, 'B', 714, 2, 'F', 20);
INSERT INTO `RelayNames` VALUES(2, 15, 'B', 715, 4, 'F', 20);
INSERT INTO `RelayNames` VALUES(2, 15, 'B', 718, 3, 'F', 20);
INSERT INTO `RelayNames` VALUES(12, 15, 'B', 718, 3, 'F', 22);
INSERT INTO `RelayNames` VALUES(12, 16, 'A', 727, 3, 'F', 68);
INSERT INTO `RelayNames` VALUES(9, 16, 'A', 727, 3, 'F', 66);
INSERT INTO `RelayNames` VALUES(12, 16, 'B', 730, 1, 'F', 69);
INSERT INTO `RelayNames` VALUES(9, 16, 'B', 739, 1, 'F', 67);
INSERT INTO `RelayNames` VALUES(2, 16, 'B', 740, 3, 'F', 65);
INSERT INTO `RelayNames` VALUES(2, 16, 'B', 744, 2, 'F', 65);
INSERT INTO `RelayNames` VALUES(9, 16, 'A', 746, 2, 'F', 66);
INSERT INTO `RelayNames` VALUES(2, 16, 'A', 746, 4, 'F', 64);
INSERT INTO `RelayNames` VALUES(9, 16, 'B', 748, 4, 'F', 67);
INSERT INTO `RelayNames` VALUES(12, 16, 'B', 748, 3, 'F', 69);
INSERT INTO `RelayNames` VALUES(9, 16, 'B', 750, 2, 'F', 67);
INSERT INTO `RelayNames` VALUES(2, 16, 'B', 750, 1, 'F', 65);
INSERT INTO `RelayNames` VALUES(12, 16, 'B', 754, 2, 'F', 69);
INSERT INTO `RelayNames` VALUES(2, 16, 'A', 756, 2, 'F', 64);
INSERT INTO `RelayNames` VALUES(9, 16, 'B', 757, 3, 'F', 67);
INSERT INTO `RelayNames` VALUES(12, 16, 'B', 757, 8, 'F', 69);
INSERT INTO `RelayNames` VALUES(2, 16, 'B', 757, 4, 'F', 65);
INSERT INTO `RelayNames` VALUES(12, 16, 'A', 759, 4, 'F', 68);
INSERT INTO `RelayNames` VALUES(2, 16, 'A', 762, 3, 'F', 64);
INSERT INTO `RelayNames` VALUES(9, 16, 'A', 762, 1, 'F', 66);
INSERT INTO `RelayNames` VALUES(2, 16, 'A', 763, 1, 'F', 64);
INSERT INTO `RelayNames` VALUES(9, 16, 'A', 763, 4, 'F', 66);
INSERT INTO `RelayNames` VALUES(12, 16, 'A', 763, 8, 'F', 68);
INSERT INTO `RelayNames` VALUES(12, 16, 'B', 769, 4, 'F', 69);
INSERT INTO `RelayNames` VALUES(12, 16, 'A', 771, 2, 'F', 68);
INSERT INTO `RelayNames` VALUES(12, 17, 'B', 773, 3, 'F', 6);
INSERT INTO `RelayNames` VALUES(12, 17, 'B', 778, 2, 'F', 6);
INSERT INTO `RelayNames` VALUES(2, 17, 'A', 781, 2, 'F', 1);
INSERT INTO `RelayNames` VALUES(9, 17, 'A', 781, 2, 'F', 2);
INSERT INTO `RelayNames` VALUES(9, 17, 'B', 782, 3, 'F', 5);
INSERT INTO `RelayNames` VALUES(2, 17, 'A', 783, 1, 'F', 1);
INSERT INTO `RelayNames` VALUES(9, 17, 'A', 783, 4, 'F', 2);
INSERT INTO `RelayNames` VALUES(12, 17, 'B', 785, 1, 'F', 6);
INSERT INTO `RelayNames` VALUES(2, 17, 'B', 787, 1, 'F', 4);
INSERT INTO `RelayNames` VALUES(2, 17, 'B', 788, 4, 'F', 4);
INSERT INTO `RelayNames` VALUES(12, 17, 'A', 789, 2, 'F', 3);
INSERT INTO `RelayNames` VALUES(9, 17, 'B', 789, 4, 'F', 5);
INSERT INTO `RelayNames` VALUES(12, 17, 'B', 790, 4, 'F', 6);
INSERT INTO `RelayNames` VALUES(2, 17, 'B', 795, 2, 'F', 4);
INSERT INTO `RelayNames` VALUES(2, 17, 'A', 796, 4, 'F', 1);
INSERT INTO `RelayNames` VALUES(9, 17, 'A', 796, 1, 'F', 2);
INSERT INTO `RelayNames` VALUES(2, 17, 'A', 797, 3, 'F', 1);
INSERT INTO `RelayNames` VALUES(12, 17, 'A', 797, 1, 'F', 3);
INSERT INTO `RelayNames` VALUES(9, 17, 'A', 800, 3, 'F', 2);
INSERT INTO `RelayNames` VALUES(12, 17, 'A', 800, 4, 'F', 3);
INSERT INTO `RelayNames` VALUES(12, 17, 'A', 801, 3, 'F', 3);
INSERT INTO `RelayNames` VALUES(9, 17, 'B', 801, 1, 'F', 5);
INSERT INTO `RelayNames` VALUES(9, 17, 'B', 802, 2, 'F', 5);
INSERT INTO `RelayNames` VALUES(2, 17, 'B', 803, 3, 'F', 4);
INSERT INTO `RelayNames` VALUES(2, 10, 'B', 805, 1, 'F', 32);
INSERT INTO `RelayNames` VALUES(9, 10, 'B', 805, 1, 'F', 33);
INSERT INTO `RelayNames` VALUES(12, 16, 'A', 806, 1, 'F', 68);
INSERT INTO `RelayNames` VALUES(12, 4, 'B', 807, 4, 'F', 76);
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
INSERT INTO `Scoring` VALUES(0, 'F', 31, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 32, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 33, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 34, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 35, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 36, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 37, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 38, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 39, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'F', 40, 0.000000e+000, 0.000000e+000);
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
INSERT INTO `Scoring` VALUES(0, 'M', 31, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 32, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 33, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 34, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 35, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 36, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 37, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 38, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 39, 0.000000e+000, 0.000000e+000);
INSERT INTO `Scoring` VALUES(0, 'M', 40, 0.000000e+000, 0.000000e+000);
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
  INDEX `sessevtrnd` (`Event_ptr`, `Sess_rnd`),
  INDEX `sessptr` (`Sess_ptr`)
);

#
# Dumping data for table 'Sessitem'
#

LOCK TABLES `Sessitem` WRITE;
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
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 4.056000e+001, 4);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 4.459000e+001, 37);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.967000e+001, 72);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.397000e+001, 20);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.755000e+001, 11);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.811000e+001, 23);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 4.103000e+001, 32);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.763000e+001, 49);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.826000e+001, 26);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.839000e+001, 34);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.722000e+001, 65);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.425000e+001, 46);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.513000e+001, 53);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.284000e+001, 7);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.317000e+001, 59);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.535000e+001, 15);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.138000e+001, 41);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.189000e+001, 29);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.173000e+001, 78);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.051000e+001, 58);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.176000e+001, 17);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.170000e+001, 71);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.076000e+001, 52);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 2.935000e+001, 77);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.114000e+001, 10);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.069000e+001, 40);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.237000e+001, 64);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 1, 3.196000e+001, 1);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 7.918000e+001, 4);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 9.161000e+001, 37);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 8.334000e+001, 72);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 7.175000e+001, 20);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 7.681000e+001, 11);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 7.516000e+001, 23);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 8.089000e+001, 32);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 8.059000e+001, 49);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 7.769000e+001, 26);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 7.951000e+001, 34);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 8.105000e+001, 65);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.994000e+001, 46);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.938000e+001, 53);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.970000e+001, 7);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.850000e+001, 59);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 7.484000e+001, 15);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.677000e+001, 41);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.719000e+001, 29);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.725000e+001, 78);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.438000e+001, 58);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.668000e+001, 17);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.723000e+001, 71);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.558000e+001, 52);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.246000e+001, 77);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.416000e+001, 10);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.161000e+001, 40);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.530000e+001, 64);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 2, 6.523000e+001, 1);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.144400e+002, 4);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.321800e+002, 37);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.198400e+002, 72);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.066600e+002, 20);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.138600e+002, 11);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.079000e+002, 23);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.149400e+002, 32);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.160100e+002, 49);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.124300e+002, 26);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.115100e+002, 34);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.322100e+002, 65);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.029300e+002, 46);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.957000e+001, 53);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.998000e+001, 7);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.009400e+002, 59);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 1.072800e+002, 15);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.559000e+001, 41);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.767000e+001, 29);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.687000e+001, 78);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.323000e+001, 58);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.756000e+001, 17);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.920000e+001, 71);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.430000e+001, 52);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.020000e+001, 77);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.055000e+001, 10);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 8.922000e+001, 40);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.366000e+001, 64);
INSERT INTO `Split` VALUES(2, 0, NULL, "", 'F', 3, 9.491000e+001, 1);
INSERT INTO `Split` VALUES(3, 17, NULL, "", 'F', 1, 3.443000e+001, 0);
INSERT INTO `Split` VALUES(3, 17, NULL, "", 'F', 2, 7.269000e+001, 0);
INSERT INTO `Split` VALUES(3, 17, NULL, "", 'F', 3, 1.122400e+002, 0);
INSERT INTO `Split` VALUES(3, 34, NULL, "", 'F', 1, 3.358000e+001, 0);
INSERT INTO `Split` VALUES(3, 34, NULL, "", 'F', 2, 7.097000e+001, 0);
INSERT INTO `Split` VALUES(3, 34, NULL, "", 'F', 3, 1.098500e+002, 0);
INSERT INTO `Split` VALUES(3, 83, NULL, "", 'F', 1, 3.090000e+001, 0);
INSERT INTO `Split` VALUES(3, 83, NULL, "", 'F', 2, 6.460000e+001, 0);
INSERT INTO `Split` VALUES(3, 83, NULL, "", 'F', 3, 9.921000e+001, 0);
INSERT INTO `Split` VALUES(3, 96, NULL, "", 'F', 1, 3.050000e+001, 0);
INSERT INTO `Split` VALUES(3, 96, NULL, "", 'F', 2, 6.443000e+001, 0);
INSERT INTO `Split` VALUES(3, 96, NULL, "", 'F', 3, 9.949000e+001, 0);
INSERT INTO `Split` VALUES(3, 111, NULL, "", 'F', 1, 3.058000e+001, 0);
INSERT INTO `Split` VALUES(3, 111, NULL, "", 'F', 2, 6.366000e+001, 0);
INSERT INTO `Split` VALUES(3, 111, NULL, "", 'F', 3, 9.812000e+001, 0);
INSERT INTO `Split` VALUES(3, 115, NULL, "", 'F', 1, 2.821000e+001, 0);
INSERT INTO `Split` VALUES(3, 115, NULL, "", 'F', 2, 5.867000e+001, 0);
INSERT INTO `Split` VALUES(3, 115, NULL, "", 'F', 3, 8.891000e+001, 0);
INSERT INTO `Split` VALUES(3, 147, NULL, "", 'F', 1, 3.108000e+001, 0);
INSERT INTO `Split` VALUES(3, 147, NULL, "", 'F', 2, 6.477000e+001, 0);
INSERT INTO `Split` VALUES(3, 147, NULL, "", 'F', 3, 9.904000e+001, 0);
INSERT INTO `Split` VALUES(3, 149, NULL, "", 'F', 1, 3.527000e+001, 0);
INSERT INTO `Split` VALUES(3, 149, NULL, "", 'F', 2, 7.272000e+001, 0);
INSERT INTO `Split` VALUES(3, 149, NULL, "", 'F', 3, 1.123300e+002, 0);
INSERT INTO `Split` VALUES(3, 163, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 163, NULL, "", 'F', 2, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 163, NULL, "", 'F', 3, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 178, NULL, "", 'F', 1, 2.970000e+001, 0);
INSERT INTO `Split` VALUES(3, 178, NULL, "", 'F', 2, 6.220000e+001, 0);
INSERT INTO `Split` VALUES(3, 178, NULL, "", 'F', 3, 9.603000e+001, 0);
INSERT INTO `Split` VALUES(3, 188, NULL, "", 'F', 1, 3.619000e+001, 0);
INSERT INTO `Split` VALUES(3, 188, NULL, "", 'F', 2, 7.780000e+001, 0);
INSERT INTO `Split` VALUES(3, 188, NULL, "", 'F', 3, 1.217200e+002, 0);
INSERT INTO `Split` VALUES(3, 195, NULL, "", 'F', 1, 3.020000e+001, 0);
INSERT INTO `Split` VALUES(3, 195, NULL, "", 'F', 2, 6.347000e+001, 0);
INSERT INTO `Split` VALUES(3, 195, NULL, "", 'F', 3, 9.749000e+001, 0);
INSERT INTO `Split` VALUES(3, 202, NULL, "", 'F', 1, 3.602000e+001, 0);
INSERT INTO `Split` VALUES(3, 202, NULL, "", 'F', 2, 7.787000e+001, 0);
INSERT INTO `Split` VALUES(3, 202, NULL, "", 'F', 3, 1.213600e+002, 0);
INSERT INTO `Split` VALUES(3, 223, NULL, "", 'F', 1, 3.910000e+001, 0);
INSERT INTO `Split` VALUES(3, 223, NULL, "", 'F', 2, 8.426000e+001, 0);
INSERT INTO `Split` VALUES(3, 223, NULL, "", 'F', 3, 1.304700e+002, 0);
INSERT INTO `Split` VALUES(3, 226, NULL, "", 'F', 1, 3.619000e+001, 0);
INSERT INTO `Split` VALUES(3, 226, NULL, "", 'F', 2, 7.743000e+001, 0);
INSERT INTO `Split` VALUES(3, 226, NULL, "", 'F', 3, 1.205100e+002, 0);
INSERT INTO `Split` VALUES(3, 249, NULL, "", 'F', 1, 3.356000e+001, 0);
INSERT INTO `Split` VALUES(3, 249, NULL, "", 'F', 2, 7.114000e+001, 0);
INSERT INTO `Split` VALUES(3, 249, NULL, "", 'F', 3, 1.095500e+002, 0);
INSERT INTO `Split` VALUES(3, 259, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 259, NULL, "", 'F', 2, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 259, NULL, "", 'F', 3, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 262, NULL, "", 'F', 1, 3.375000e+001, 0);
INSERT INTO `Split` VALUES(3, 262, NULL, "", 'F', 2, 7.001000e+001, 0);
INSERT INTO `Split` VALUES(3, 262, NULL, "", 'F', 3, 1.075500e+002, 0);
INSERT INTO `Split` VALUES(3, 263, NULL, "", 'F', 1, 3.324000e+001, 0);
INSERT INTO `Split` VALUES(3, 263, NULL, "", 'F', 2, 7.021000e+001, 0);
INSERT INTO `Split` VALUES(3, 263, NULL, "", 'F', 3, 1.096300e+002, 0);
INSERT INTO `Split` VALUES(3, 279, NULL, "", 'F', 1, 3.479000e+001, 0);
INSERT INTO `Split` VALUES(3, 279, NULL, "", 'F', 2, 7.199000e+001, 0);
INSERT INTO `Split` VALUES(3, 279, NULL, "", 'F', 3, 1.109400e+002, 0);
INSERT INTO `Split` VALUES(3, 368, NULL, "", 'F', 1, 2.963000e+001, 0);
INSERT INTO `Split` VALUES(3, 368, NULL, "", 'F', 2, 6.128000e+001, 0);
INSERT INTO `Split` VALUES(3, 368, NULL, "", 'F', 3, 9.343000e+001, 0);
INSERT INTO `Split` VALUES(3, 394, NULL, "", 'F', 1, 2.922000e+001, 0);
INSERT INTO `Split` VALUES(3, 394, NULL, "", 'F', 2, 6.113000e+001, 0);
INSERT INTO `Split` VALUES(3, 394, NULL, "", 'F', 3, 9.366000e+001, 0);
INSERT INTO `Split` VALUES(3, 406, NULL, "", 'F', 1, 3.163000e+001, 0);
INSERT INTO `Split` VALUES(3, 406, NULL, "", 'F', 2, 6.491000e+001, 0);
INSERT INTO `Split` VALUES(3, 406, NULL, "", 'F', 3, 9.877000e+001, 0);
INSERT INTO `Split` VALUES(3, 410, NULL, "", 'F', 1, 2.992000e+001, 0);
INSERT INTO `Split` VALUES(3, 410, NULL, "", 'F', 2, 6.272000e+001, 0);
INSERT INTO `Split` VALUES(3, 410, NULL, "", 'F', 3, 9.641000e+001, 0);
INSERT INTO `Split` VALUES(3, 421, NULL, "", 'F', 1, 2.988000e+001, 0);
INSERT INTO `Split` VALUES(3, 421, NULL, "", 'F', 2, 6.156000e+001, 0);
INSERT INTO `Split` VALUES(3, 421, NULL, "", 'F', 3, 9.351000e+001, 0);
INSERT INTO `Split` VALUES(3, 427, NULL, "", 'F', 1, 3.155000e+001, 0);
INSERT INTO `Split` VALUES(3, 427, NULL, "", 'F', 2, 6.509000e+001, 0);
INSERT INTO `Split` VALUES(3, 427, NULL, "", 'F', 3, 9.973000e+001, 0);
INSERT INTO `Split` VALUES(3, 453, NULL, "", 'F', 1, 3.668000e+001, 0);
INSERT INTO `Split` VALUES(3, 453, NULL, "", 'F', 2, 7.696000e+001, 0);
INSERT INTO `Split` VALUES(3, 453, NULL, "", 'F', 3, 1.189800e+002, 0);
INSERT INTO `Split` VALUES(3, 458, NULL, "", 'F', 1, 3.880000e+001, 0);
INSERT INTO `Split` VALUES(3, 458, NULL, "", 'F', 2, 8.454000e+001, 0);
INSERT INTO `Split` VALUES(3, 458, NULL, "", 'F', 3, 1.324900e+002, 0);
INSERT INTO `Split` VALUES(3, 475, NULL, "", 'F', 1, 3.805000e+001, 0);
INSERT INTO `Split` VALUES(3, 475, NULL, "", 'F', 2, 8.031000e+001, 0);
INSERT INTO `Split` VALUES(3, 475, NULL, "", 'F', 3, 1.257800e+002, 0);
INSERT INTO `Split` VALUES(3, 510, NULL, "", 'F', 1, 3.179000e+001, 0);
INSERT INTO `Split` VALUES(3, 510, NULL, "", 'F', 2, 6.519000e+001, 0);
INSERT INTO `Split` VALUES(3, 510, NULL, "", 'F', 3, 9.956000e+001, 0);
INSERT INTO `Split` VALUES(3, 526, NULL, "", 'F', 1, 3.378000e+001, 0);
INSERT INTO `Split` VALUES(3, 526, NULL, "", 'F', 2, 6.883000e+001, 0);
INSERT INTO `Split` VALUES(3, 526, NULL, "", 'F', 3, 1.053800e+002, 0);
INSERT INTO `Split` VALUES(3, 534, NULL, "", 'F', 1, 2.829000e+001, 0);
INSERT INTO `Split` VALUES(3, 534, NULL, "", 'F', 2, 5.891000e+001, 0);
INSERT INTO `Split` VALUES(3, 534, NULL, "", 'F', 3, 8.925000e+001, 0);
INSERT INTO `Split` VALUES(3, 553, NULL, "", 'F', 1, 3.022000e+001, 0);
INSERT INTO `Split` VALUES(3, 553, NULL, "", 'F', 2, 6.323000e+001, 0);
INSERT INTO `Split` VALUES(3, 553, NULL, "", 'F', 3, 9.621000e+001, 0);
INSERT INTO `Split` VALUES(3, 585, NULL, "", 'F', 1, 3.000000e+001, 0);
INSERT INTO `Split` VALUES(3, 585, NULL, "", 'F', 2, 6.330000e+001, 0);
INSERT INTO `Split` VALUES(3, 585, NULL, "", 'F', 3, 9.734000e+001, 0);
INSERT INTO `Split` VALUES(3, 619, NULL, "", 'F', 1, 2.886000e+001, 0);
INSERT INTO `Split` VALUES(3, 619, NULL, "", 'F', 2, 5.979000e+001, 0);
INSERT INTO `Split` VALUES(3, 619, NULL, "", 'F', 3, 9.058000e+001, 0);
INSERT INTO `Split` VALUES(3, 642, NULL, "", 'F', 1, 3.091000e+001, 0);
INSERT INTO `Split` VALUES(3, 642, NULL, "", 'F', 2, 6.448000e+001, 0);
INSERT INTO `Split` VALUES(3, 642, NULL, "", 'F', 3, 9.858000e+001, 0);
INSERT INTO `Split` VALUES(3, 664, NULL, "", 'F', 1, 3.201000e+001, 0);
INSERT INTO `Split` VALUES(3, 664, NULL, "", 'F', 2, 6.624000e+001, 0);
INSERT INTO `Split` VALUES(3, 664, NULL, "", 'F', 3, 1.013600e+002, 0);
INSERT INTO `Split` VALUES(3, 682, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 682, NULL, "", 'F', 2, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 682, NULL, "", 'F', 3, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(3, 709, NULL, "", 'F', 1, 3.052000e+001, 0);
INSERT INTO `Split` VALUES(3, 709, NULL, "", 'F', 2, 6.240000e+001, 0);
INSERT INTO `Split` VALUES(3, 709, NULL, "", 'F', 3, 9.534000e+001, 0);
INSERT INTO `Split` VALUES(3, 739, NULL, "", 'F', 1, 3.577000e+001, 0);
INSERT INTO `Split` VALUES(3, 739, NULL, "", 'F', 2, 7.702000e+001, 0);
INSERT INTO `Split` VALUES(3, 739, NULL, "", 'F', 3, 1.200600e+002, 0);
INSERT INTO `Split` VALUES(3, 746, NULL, "", 'F', 1, 3.031000e+001, 0);
INSERT INTO `Split` VALUES(3, 746, NULL, "", 'F', 2, 6.271000e+001, 0);
INSERT INTO `Split` VALUES(3, 746, NULL, "", 'F', 3, 9.644000e+001, 0);
INSERT INTO `Split` VALUES(3, 750, NULL, "", 'F', 1, 3.382000e+001, 0);
INSERT INTO `Split` VALUES(3, 750, NULL, "", 'F', 2, 7.291000e+001, 0);
INSERT INTO `Split` VALUES(3, 750, NULL, "", 'F', 3, 1.140800e+002, 0);
INSERT INTO `Split` VALUES(3, 773, NULL, "", 'F', 1, 3.717000e+001, 0);
INSERT INTO `Split` VALUES(3, 773, NULL, "", 'F', 2, 7.894000e+001, 0);
INSERT INTO `Split` VALUES(3, 773, NULL, "", 'F', 3, 1.198600e+002, 0);
INSERT INTO `Split` VALUES(3, 782, NULL, "", 'F', 1, 3.616000e+001, 0);
INSERT INTO `Split` VALUES(3, 782, NULL, "", 'F', 2, 7.778000e+001, 0);
INSERT INTO `Split` VALUES(3, 782, NULL, "", 'F', 3, 1.219900e+002, 0);
INSERT INTO `Split` VALUES(3, 789, NULL, "", 'F', 1, 3.535000e+001, 0);
INSERT INTO `Split` VALUES(3, 789, NULL, "", 'F', 2, 7.633000e+001, 0);
INSERT INTO `Split` VALUES(3, 789, NULL, "", 'F', 3, 1.182300e+002, 0);
INSERT INTO `Split` VALUES(3, 801, NULL, "", 'F', 1, 3.441000e+001, 0);
INSERT INTO `Split` VALUES(3, 801, NULL, "", 'F', 2, 7.094000e+001, 0);
INSERT INTO `Split` VALUES(3, 801, NULL, "", 'F', 3, 1.088000e+002, 0);
INSERT INTO `Split` VALUES(3, 806, NULL, "", 'F', 1, 3.156000e+001, 0);
INSERT INTO `Split` VALUES(3, 806, NULL, "", 'F', 2, 6.607000e+001, 0);
INSERT INTO `Split` VALUES(3, 806, NULL, "", 'F', 3, 1.025300e+002, 0);
INSERT INTO `Split` VALUES(3, 807, NULL, "", 'F', 1, 3.463000e+001, 0);
INSERT INTO `Split` VALUES(3, 807, NULL, "", 'F', 2, 7.413000e+001, 0);
INSERT INTO `Split` VALUES(3, 807, NULL, "", 'F', 3, 1.168900e+002, 0);
INSERT INTO `Split` VALUES(4, 90, NULL, "", 'F', 1, 3.328000e+001, 0);
INSERT INTO `Split` VALUES(4, 90, NULL, "", 'F', 2, 7.384000e+001, 0);
INSERT INTO `Split` VALUES(4, 90, NULL, "", 'F', 3, 1.239900e+002, 0);
INSERT INTO `Split` VALUES(4, 92, NULL, "", 'F', 1, 3.227000e+001, 0);
INSERT INTO `Split` VALUES(4, 92, NULL, "", 'F', 2, 7.082000e+001, 0);
INSERT INTO `Split` VALUES(4, 92, NULL, "", 'F', 3, 1.146900e+002, 0);
INSERT INTO `Split` VALUES(4, 102, NULL, "", 'F', 1, 3.280000e+001, 0);
INSERT INTO `Split` VALUES(4, 102, NULL, "", 'F', 2, 7.112000e+001, 0);
INSERT INTO `Split` VALUES(4, 102, NULL, "", 'F', 3, 1.104800e+002, 0);
INSERT INTO `Split` VALUES(4, 113, NULL, "", 'F', 1, 3.091000e+001, 0);
INSERT INTO `Split` VALUES(4, 113, NULL, "", 'F', 2, 6.506000e+001, 0);
INSERT INTO `Split` VALUES(4, 113, NULL, "", 'F', 3, 1.094500e+002, 0);
INSERT INTO `Split` VALUES(4, 133, NULL, "", 'F', 1, 3.222000e+001, 0);
INSERT INTO `Split` VALUES(4, 133, NULL, "", 'F', 2, 6.753000e+001, 0);
INSERT INTO `Split` VALUES(4, 133, NULL, "", 'F', 3, 1.090200e+002, 0);
INSERT INTO `Split` VALUES(4, 145, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(4, 145, NULL, "", 'F', 2, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(4, 145, NULL, "", 'F', 3, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(4, 160, NULL, "", 'F', 1, 4.402000e+001, 0);
INSERT INTO `Split` VALUES(4, 160, NULL, "", 'F', 2, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(4, 160, NULL, "", 'F', 3, 1.548800e+002, 0);
INSERT INTO `Split` VALUES(4, 164, NULL, "", 'F', 1, 3.455000e+001, 0);
INSERT INTO `Split` VALUES(4, 164, NULL, "", 'F', 2, 7.213000e+001, 0);
INSERT INTO `Split` VALUES(4, 164, NULL, "", 'F', 3, 1.188600e+002, 0);
INSERT INTO `Split` VALUES(4, 194, NULL, "", 'F', 1, 3.302000e+001, 0);
INSERT INTO `Split` VALUES(4, 194, NULL, "", 'F', 2, 7.103000e+001, 0);
INSERT INTO `Split` VALUES(4, 194, NULL, "", 'F', 3, 1.160900e+002, 0);
INSERT INTO `Split` VALUES(4, 200, NULL, "", 'F', 1, 3.169000e+001, 0);
INSERT INTO `Split` VALUES(4, 200, NULL, "", 'F', 2, 6.802000e+001, 0);
INSERT INTO `Split` VALUES(4, 200, NULL, "", 'F', 3, 1.112700e+002, 0);
INSERT INTO `Split` VALUES(4, 226, NULL, "", 'F', 1, 4.170000e+001, 0);
INSERT INTO `Split` VALUES(4, 226, NULL, "", 'F', 2, 8.838000e+001, 0);
INSERT INTO `Split` VALUES(4, 226, NULL, "", 'F', 3, 1.479600e+002, 0);
INSERT INTO `Split` VALUES(4, 231, NULL, "", 'F', 1, 3.302000e+001, 0);
INSERT INTO `Split` VALUES(4, 231, NULL, "", 'F', 2, 7.201000e+001, 0);
INSERT INTO `Split` VALUES(4, 231, NULL, "", 'F', 3, 1.210100e+002, 0);
INSERT INTO `Split` VALUES(4, 257, NULL, "", 'F', 1, 3.015000e+001, 0);
INSERT INTO `Split` VALUES(4, 257, NULL, "", 'F', 2, 6.518000e+001, 0);
INSERT INTO `Split` VALUES(4, 257, NULL, "", 'F', 3, 1.054400e+002, 0);
INSERT INTO `Split` VALUES(4, 260, NULL, "", 'F', 1, 3.027000e+001, 0);
INSERT INTO `Split` VALUES(4, 260, NULL, "", 'F', 2, 6.425000e+001, 0);
INSERT INTO `Split` VALUES(4, 260, NULL, "", 'F', 3, 1.047500e+002, 0);
INSERT INTO `Split` VALUES(4, 288, NULL, "", 'F', 1, 3.786000e+001, 0);
INSERT INTO `Split` VALUES(4, 288, NULL, "", 'F', 2, 8.183000e+001, 0);
INSERT INTO `Split` VALUES(4, 288, NULL, "", 'F', 3, 1.372700e+002, 0);
INSERT INTO `Split` VALUES(4, 289, NULL, "", 'F', 1, 3.703000e+001, 0);
INSERT INTO `Split` VALUES(4, 289, NULL, "", 'F', 2, 7.950000e+001, 0);
INSERT INTO `Split` VALUES(4, 289, NULL, "", 'F', 3, 1.296200e+002, 0);
INSERT INTO `Split` VALUES(4, 359, NULL, "", 'F', 1, 2.762000e+001, 0);
INSERT INTO `Split` VALUES(4, 359, NULL, "", 'F', 2, 6.020000e+001, 0);
INSERT INTO `Split` VALUES(4, 359, NULL, "", 'F', 3, 9.790000e+001, 0);
INSERT INTO `Split` VALUES(4, 378, NULL, "", 'F', 1, 3.141000e+001, 0);
INSERT INTO `Split` VALUES(4, 378, NULL, "", 'F', 2, 6.620000e+001, 0);
INSERT INTO `Split` VALUES(4, 378, NULL, "", 'F', 3, 1.085100e+002, 0);
INSERT INTO `Split` VALUES(4, 390, NULL, "", 'F', 1, 3.071000e+001, 0);
INSERT INTO `Split` VALUES(4, 390, NULL, "", 'F', 2, 6.846000e+001, 0);
INSERT INTO `Split` VALUES(4, 390, NULL, "", 'F', 3, 1.111300e+002, 0);
INSERT INTO `Split` VALUES(4, 399, NULL, "", 'F', 1, 3.253000e+001, 0);
INSERT INTO `Split` VALUES(4, 399, NULL, "", 'F', 2, 6.870000e+001, 0);
INSERT INTO `Split` VALUES(4, 399, NULL, "", 'F', 3, 1.152300e+002, 0);
INSERT INTO `Split` VALUES(4, 435, NULL, "", 'F', 1, 3.316000e+001, 0);
INSERT INTO `Split` VALUES(4, 435, NULL, "", 'F', 2, 7.250000e+001, 0);
INSERT INTO `Split` VALUES(4, 435, NULL, "", 'F', 3, 1.171100e+002, 0);
INSERT INTO `Split` VALUES(4, 437, NULL, "", 'F', 1, 3.771000e+001, 0);
INSERT INTO `Split` VALUES(4, 437, NULL, "", 'F', 2, 8.308000e+001, 0);
INSERT INTO `Split` VALUES(4, 437, NULL, "", 'F', 3, 1.409300e+002, 0);
INSERT INTO `Split` VALUES(4, 439, NULL, "", 'F', 1, 4.112000e+001, 0);
INSERT INTO `Split` VALUES(4, 439, NULL, "", 'F', 2, 9.078000e+001, 0);
INSERT INTO `Split` VALUES(4, 439, NULL, "", 'F', 3, 1.381800e+002, 0);
INSERT INTO `Split` VALUES(4, 447, NULL, "", 'F', 1, 3.649000e+001, 0);
INSERT INTO `Split` VALUES(4, 447, NULL, "", 'F', 2, 8.047000e+001, 0);
INSERT INTO `Split` VALUES(4, 447, NULL, "", 'F', 3, 1.326000e+002, 0);
INSERT INTO `Split` VALUES(4, 494, NULL, "", 'F', 1, 3.101000e+001, 0);
INSERT INTO `Split` VALUES(4, 494, NULL, "", 'F', 2, 6.815000e+001, 0);
INSERT INTO `Split` VALUES(4, 494, NULL, "", 'F', 3, 1.112300e+002, 0);
INSERT INTO `Split` VALUES(4, 496, NULL, "", 'F', 1, 3.121000e+001, 0);
INSERT INTO `Split` VALUES(4, 496, NULL, "", 'F', 2, 6.516000e+001, 0);
INSERT INTO `Split` VALUES(4, 496, NULL, "", 'F', 3, 1.088500e+002, 0);
INSERT INTO `Split` VALUES(4, 550, NULL, "", 'F', 1, 3.241000e+001, 0);
INSERT INTO `Split` VALUES(4, 550, NULL, "", 'F', 2, 7.101000e+001, 0);
INSERT INTO `Split` VALUES(4, 550, NULL, "", 'F', 3, 1.145000e+002, 0);
INSERT INTO `Split` VALUES(4, 572, NULL, "", 'F', 1, 3.458000e+001, 0);
INSERT INTO `Split` VALUES(4, 572, NULL, "", 'F', 2, 7.538000e+001, 0);
INSERT INTO `Split` VALUES(4, 572, NULL, "", 'F', 3, 1.227000e+002, 0);
INSERT INTO `Split` VALUES(4, 583, NULL, "", 'F', 1, 2.941000e+001, 0);
INSERT INTO `Split` VALUES(4, 583, NULL, "", 'F', 2, 6.715000e+001, 0);
INSERT INTO `Split` VALUES(4, 583, NULL, "", 'F', 3, 1.102000e+002, 0);
INSERT INTO `Split` VALUES(4, 661, NULL, "", 'F', 1, 3.393000e+001, 0);
INSERT INTO `Split` VALUES(4, 661, NULL, "", 'F', 2, 7.532000e+001, 0);
INSERT INTO `Split` VALUES(4, 661, NULL, "", 'F', 3, 1.249900e+002, 0);
INSERT INTO `Split` VALUES(4, 684, NULL, "", 'F', 1, 3.227000e+001, 0);
INSERT INTO `Split` VALUES(4, 684, NULL, "", 'F', 2, 6.954000e+001, 0);
INSERT INTO `Split` VALUES(4, 684, NULL, "", 'F', 3, 1.118900e+002, 0);
INSERT INTO `Split` VALUES(4, 686, NULL, "", 'F', 1, 3.626000e+001, 0);
INSERT INTO `Split` VALUES(4, 686, NULL, "", 'F', 2, 7.968000e+001, 0);
INSERT INTO `Split` VALUES(4, 686, NULL, "", 'F', 3, 1.259600e+002, 0);
INSERT INTO `Split` VALUES(4, 687, NULL, "", 'F', 1, 3.367000e+001, 0);
INSERT INTO `Split` VALUES(4, 687, NULL, "", 'F', 2, 7.090000e+001, 0);
INSERT INTO `Split` VALUES(4, 687, NULL, "", 'F', 3, 1.174900e+002, 0);
INSERT INTO `Split` VALUES(4, 700, NULL, "", 'F', 1, 3.252000e+001, 0);
INSERT INTO `Split` VALUES(4, 700, NULL, "", 'F', 2, 7.141000e+001, 0);
INSERT INTO `Split` VALUES(4, 700, NULL, "", 'F', 3, 1.169500e+002, 0);
INSERT INTO `Split` VALUES(4, 730, NULL, "", 'F', 1, 4.099000e+001, 0);
INSERT INTO `Split` VALUES(4, 730, NULL, "", 'F', 2, 8.654000e+001, 0);
INSERT INTO `Split` VALUES(4, 730, NULL, "", 'F', 3, 1.481600e+002, 0);
INSERT INTO `Split` VALUES(4, 748, NULL, "", 'F', 1, 3.967000e+001, 0);
INSERT INTO `Split` VALUES(4, 748, NULL, "", 'F', 2, 8.485000e+001, 0);
INSERT INTO `Split` VALUES(4, 748, NULL, "", 'F', 3, 1.350700e+002, 0);
INSERT INTO `Split` VALUES(4, 756, NULL, "", 'F', 1, 3.202000e+001, 0);
INSERT INTO `Split` VALUES(4, 756, NULL, "", 'F', 2, 7.274000e+001, 0);
INSERT INTO `Split` VALUES(4, 756, NULL, "", 'F', 3, 1.111600e+002, 0);
INSERT INTO `Split` VALUES(4, 758, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(4, 758, NULL, "", 'F', 2, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(4, 758, NULL, "", 'F', 3, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(4, 778, NULL, "", 'F', 1, 3.577000e+001, 0);
INSERT INTO `Split` VALUES(4, 778, NULL, "", 'F', 2, 7.995000e+001, 0);
INSERT INTO `Split` VALUES(4, 778, NULL, "", 'F', 3, 1.326300e+002, 0);
INSERT INTO `Split` VALUES(4, 781, NULL, "", 'F', 1, 3.322000e+001, 0);
INSERT INTO `Split` VALUES(4, 781, NULL, "", 'F', 2, 6.995000e+001, 0);
INSERT INTO `Split` VALUES(4, 781, NULL, "", 'F', 3, 1.093300e+002, 0);
INSERT INTO `Split` VALUES(4, 783, NULL, "", 'F', 1, 3.234000e+001, 0);
INSERT INTO `Split` VALUES(4, 783, NULL, "", 'F', 2, 6.770000e+001, 0);
INSERT INTO `Split` VALUES(4, 783, NULL, "", 'F', 3, 1.100000e+002, 0);
INSERT INTO `Split` VALUES(4, 787, NULL, "", 'F', 1, 3.690000e+001, 0);
INSERT INTO `Split` VALUES(4, 787, NULL, "", 'F', 2, 8.405000e+001, 0);
INSERT INTO `Split` VALUES(4, 787, NULL, "", 'F', 3, 1.444600e+002, 0);
INSERT INTO `Split` VALUES(6, 77, NULL, "", 'F', 1, 2.990000e+001, 0);
INSERT INTO `Split` VALUES(6, 85, NULL, "", 'F', 1, 3.165000e+001, 0);
INSERT INTO `Split` VALUES(6, 92, NULL, "", 'F', 1, 3.261000e+001, 0);
INSERT INTO `Split` VALUES(6, 126, NULL, "", 'F', 1, 2.933000e+001, 0);
INSERT INTO `Split` VALUES(6, 145, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(6, 155, NULL, "", 'F', 1, 3.980000e+001, 0);
INSERT INTO `Split` VALUES(6, 200, NULL, "", 'F', 1, 3.099000e+001, 0);
INSERT INTO `Split` VALUES(6, 211, NULL, "", 'F', 1, 3.354000e+001, 0);
INSERT INTO `Split` VALUES(6, 244, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(6, 249, NULL, "", 'F', 1, 3.762000e+001, 0);
INSERT INTO `Split` VALUES(6, 282, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(6, 295, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(6, 299, NULL, "", 'F', 1, 4.674000e+001, 0);
INSERT INTO `Split` VALUES(6, 381, NULL, "", 'F', 1, 3.061000e+001, 0);
INSERT INTO `Split` VALUES(6, 390, NULL, "", 'F', 1, 3.030000e+001, 0);
INSERT INTO `Split` VALUES(6, 401, NULL, "", 'F', 1, 3.253000e+001, 0);
INSERT INTO `Split` VALUES(6, 412, NULL, "", 'F', 1, 3.327000e+001, 0);
INSERT INTO `Split` VALUES(6, 427, NULL, "", 'F', 1, 3.151000e+001, 0);
INSERT INTO `Split` VALUES(6, 446, NULL, "", 'F', 1, 2.995000e+001, 0);
INSERT INTO `Split` VALUES(6, 504, NULL, "", 'F', 1, 3.149000e+001, 0);
INSERT INTO `Split` VALUES(6, 510, NULL, "", 'F', 1, 3.256000e+001, 0);
INSERT INTO `Split` VALUES(6, 531, NULL, "", 'F', 1, 3.368000e+001, 0);
INSERT INTO `Split` VALUES(6, 556, NULL, "", 'F', 1, 3.053000e+001, 0);
INSERT INTO `Split` VALUES(6, 573, NULL, "", 'F', 1, 3.936000e+001, 0);
INSERT INTO `Split` VALUES(6, 583, NULL, "", 'F', 1, 2.945000e+001, 0);
INSERT INTO `Split` VALUES(6, 599, NULL, "", 'F', 1, 3.454000e+001, 0);
INSERT INTO `Split` VALUES(6, 619, NULL, "", 'F', 1, 2.820000e+001, 0);
INSERT INTO `Split` VALUES(6, 664, NULL, "", 'F', 1, 3.359000e+001, 0);
INSERT INTO `Split` VALUES(6, 676, NULL, "", 'F', 1, 2.941000e+001, 0);
INSERT INTO `Split` VALUES(6, 680, NULL, "", 'F', 1, 3.542000e+001, 0);
INSERT INTO `Split` VALUES(6, 692, NULL, "", 'F', 1, 3.556000e+001, 0);
INSERT INTO `Split` VALUES(6, 700, NULL, "", 'F', 1, 3.344000e+001, 0);
INSERT INTO `Split` VALUES(6, 732, NULL, "", 'F', 1, 3.361000e+001, 0);
INSERT INTO `Split` VALUES(6, 741, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(6, 762, NULL, "", 'F', 1, 3.113000e+001, 0);
INSERT INTO `Split` VALUES(6, 769, NULL, "", 'F', 1, 3.970000e+001, 0);
INSERT INTO `Split` VALUES(6, 778, NULL, "", 'F', 1, 3.682000e+001, 0);
INSERT INTO `Split` VALUES(6, 797, NULL, "", 'F', 1, 3.067000e+001, 0);
INSERT INTO `Split` VALUES(6, 800, NULL, "", 'F', 1, 3.236000e+001, 0);
INSERT INTO `Split` VALUES(7, 14, NULL, "", 'F', 1, 2.867000e+001, 0);
INSERT INTO `Split` VALUES(7, 16, NULL, "", 'F', 1, 2.901000e+001, 0);
INSERT INTO `Split` VALUES(7, 46, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(7, 60, NULL, "", 'F', 1, 2.678000e+001, 0);
INSERT INTO `Split` VALUES(7, 68, NULL, "", 'F', 1, 2.799000e+001, 0);
INSERT INTO `Split` VALUES(7, 89, NULL, "", 'F', 1, 2.924000e+001, 0);
INSERT INTO `Split` VALUES(7, 103, NULL, "", 'F', 1, 2.953000e+001, 0);
INSERT INTO `Split` VALUES(7, 123, NULL, "", 'F', 1, 2.893000e+001, 0);
INSERT INTO `Split` VALUES(7, 138, NULL, "", 'F', 1, 3.011000e+001, 0);
INSERT INTO `Split` VALUES(7, 154, NULL, "", 'F', 1, 3.714000e+001, 0);
INSERT INTO `Split` VALUES(7, 158, NULL, "", 'F', 1, 3.060000e+001, 0);
INSERT INTO `Split` VALUES(7, 182, NULL, "", 'F', 1, 2.981000e+001, 0);
INSERT INTO `Split` VALUES(7, 198, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(7, 205, NULL, "", 'F', 1, 3.602000e+001, 0);
INSERT INTO `Split` VALUES(7, 206, NULL, "", 'F', 1, 3.324000e+001, 0);
INSERT INTO `Split` VALUES(7, 227, NULL, "", 'F', 1, 3.291000e+001, 0);
INSERT INTO `Split` VALUES(7, 230, NULL, "", 'F', 1, 3.410000e+001, 0);
INSERT INTO `Split` VALUES(7, 238, NULL, "", 'F', 1, 3.331000e+001, 0);
INSERT INTO `Split` VALUES(7, 243, NULL, "", 'F', 1, 3.899000e+001, 0);
INSERT INTO `Split` VALUES(7, 257, NULL, "", 'F', 1, 2.751000e+001, 0);
INSERT INTO `Split` VALUES(7, 285, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(7, 287, NULL, "", 'F', 1, 2.706000e+001, 0);
INSERT INTO `Split` VALUES(7, 359, NULL, "", 'F', 1, 2.635000e+001, 0);
INSERT INTO `Split` VALUES(7, 394, NULL, "", 'F', 1, 2.794000e+001, 0);
INSERT INTO `Split` VALUES(7, 402, NULL, "", 'F', 1, 2.997000e+001, 0);
INSERT INTO `Split` VALUES(7, 410, NULL, "", 'F', 1, 2.926000e+001, 0);
INSERT INTO `Split` VALUES(7, 421, NULL, "", 'F', 1, 2.818000e+001, 0);
INSERT INTO `Split` VALUES(7, 425, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(7, 430, NULL, "", 'F', 1, 3.814000e+001, 0);
INSERT INTO `Split` VALUES(7, 440, NULL, "", 'F', 1, 2.952000e+001, 0);
INSERT INTO `Split` VALUES(7, 458, NULL, "", 'F', 1, 3.821000e+001, 0);
INSERT INTO `Split` VALUES(7, 483, NULL, "", 'F', 1, 3.701000e+001, 0);
INSERT INTO `Split` VALUES(7, 488, NULL, "", 'F', 1, 3.955000e+001, 0);
INSERT INTO `Split` VALUES(7, 505, NULL, "", 'F', 1, 2.979000e+001, 0);
INSERT INTO `Split` VALUES(7, 533, NULL, "", 'F', 1, 2.629000e+001, 0);
INSERT INTO `Split` VALUES(7, 553, NULL, "", 'F', 1, 2.877000e+001, 0);
INSERT INTO `Split` VALUES(7, 561, NULL, "", 'F', 1, 2.872000e+001, 0);
INSERT INTO `Split` VALUES(7, 564, NULL, "", 'F', 1, 3.150000e+001, 0);
INSERT INTO `Split` VALUES(7, 584, NULL, "", 'F', 1, 3.238000e+001, 0);
INSERT INTO `Split` VALUES(7, 585, NULL, "", 'F', 1, 2.938000e+001, 0);
INSERT INTO `Split` VALUES(7, 587, NULL, "", 'F', 1, 3.565000e+001, 0);
INSERT INTO `Split` VALUES(7, 642, NULL, "", 'F', 1, 2.931000e+001, 0);
INSERT INTO `Split` VALUES(7, 658, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(7, 661, NULL, "", 'F', 1, 3.063000e+001, 0);
INSERT INTO `Split` VALUES(7, 679, NULL, "", 'F', 1, 2.969000e+001, 0);
INSERT INTO `Split` VALUES(7, 694, NULL, "", 'F', 1, 2.854000e+001, 0);
INSERT INTO `Split` VALUES(7, 695, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(7, 697, NULL, "", 'F', 1, 3.065000e+001, 0);
INSERT INTO `Split` VALUES(7, 710, NULL, "", 'F', 1, 2.808000e+001, 0);
INSERT INTO `Split` VALUES(7, 750, NULL, "", 'F', 1, 3.250000e+001, 0);
INSERT INTO `Split` VALUES(7, 754, NULL, "", 'F', 1, 3.195000e+001, 0);
INSERT INTO `Split` VALUES(7, 771, NULL, "", 'F', 1, 3.234000e+001, 0);
INSERT INTO `Split` VALUES(7, 782, NULL, "", 'F', 1, 3.399000e+001, 0);
INSERT INTO `Split` VALUES(7, 789, NULL, "", 'F', 1, 3.193000e+001, 0);
INSERT INTO `Split` VALUES(7, 790, NULL, "", 'F', 1, 3.199000e+001, 0);
INSERT INTO `Split` VALUES(7, 802, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(7, 806, NULL, "", 'F', 1, 3.042000e+001, 0);
INSERT INTO `Split` VALUES(7, 807, NULL, "", 'F', 1, 3.280000e+001, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 1, 3.263000e+001, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 2, 6.751000e+001, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 3, 1.038900e+002, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 4, 1.398900e+002, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 5, 1.767500e+002, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 6, 2.124200e+002, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 7, 2.492400e+002, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 8, 2.856900e+002, 0);
INSERT INTO `Split` VALUES(8, 84, NULL, "", 'F', 9, 3.222700e+002, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 1, 3.255000e+001, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 2, 6.866000e+001, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 3, 1.042300e+002, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 4, 1.407500e+002, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 5, 1.774400e+002, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 6, 2.135500e+002, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 7, 2.509100e+002, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 8, 2.880400e+002, 0);
INSERT INTO `Split` VALUES(8, 90, NULL, "", 'F', 9, 3.251500e+002, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 1, 3.080000e+001, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 2, 6.445000e+001, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 3, 9.887000e+001, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 4, 1.339000e+002, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 5, 1.690100e+002, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 6, 2.041300e+002, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 7, 2.394800e+002, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 8, 2.748300e+002, 0);
INSERT INTO `Split` VALUES(8, 96, NULL, "", 'F', 9, 3.102700e+002, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 1, 3.238000e+001, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 2, 6.751000e+001, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 3, 1.035200e+002, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 4, 1.401700e+002, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 5, 1.770500e+002, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 6, 2.133800e+002, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 7, 2.503500e+002, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 8, 2.872500e+002, 0);
INSERT INTO `Split` VALUES(8, 111, NULL, "", 'F', 9, 3.239600e+002, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 1, 3.166000e+001, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 2, 6.534000e+001, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 3, 9.947000e+001, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 4, 1.340600e+002, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 5, 1.694000e+002, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 6, 2.047400e+002, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 7, 2.396900e+002, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 8, 2.745200e+002, 0);
INSERT INTO `Split` VALUES(8, 147, NULL, "", 'F', 9, 3.090000e+002, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 1, 3.081000e+001, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 2, 6.420000e+001, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 3, 9.840000e+001, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 4, 1.327800e+002, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 5, 1.675300e+002, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 6, 2.025700e+002, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 7, 2.375100e+002, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 8, 2.722700e+002, 0);
INSERT INTO `Split` VALUES(8, 178, NULL, "", 'F', 9, 3.067800e+002, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 1, 3.101000e+001, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 2, 6.551000e+001, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 3, 1.005700e+002, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 4, 1.360500e+002, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 5, 1.726300e+002, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 6, 2.093200e+002, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 7, 2.459200e+002, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 8, 2.829300e+002, 0);
INSERT INTO `Split` VALUES(8, 195, NULL, "", 'F', 9, 3.196900e+002, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 1, 3.255000e+001, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 2, 6.760000e+001, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 3, 1.034000e+002, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 4, 1.390600e+002, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 5, 1.755000e+002, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 6, 2.118500e+002, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 7, 2.486800e+002, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 8, 2.852900e+002, 0);
INSERT INTO `Split` VALUES(8, 231, NULL, "", 'F', 9, 3.214300e+002, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 1, 3.421000e+001, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 2, 7.109000e+001, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 3, 1.085600e+002, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 4, 1.463800e+002, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 5, 1.844600e+002, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 6, 2.220800e+002, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 7, 2.600100e+002, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 8, 2.976500e+002, 0);
INSERT INTO `Split` VALUES(8, 266, NULL, "", 'F', 9, 3.353300e+002, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 1, 3.204000e+001, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 2, 6.743000e+001, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 3, 1.036900e+002, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 4, 1.396000e+002, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 5, 1.768300e+002, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 6, 2.132700e+002, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 7, 2.501800e+002, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 8, 2.875700e+002, 0);
INSERT INTO `Split` VALUES(8, 274, NULL, "", 'F', 9, 3.245200e+002, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 1, 2.901000e+001, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 2, 6.139000e+001, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 3, 9.407000e+001, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 4, 1.266300e+002, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 5, 1.596200e+002, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 6, 1.928200e+002, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 7, 2.261200e+002, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 8, 2.596900e+002, 0);
INSERT INTO `Split` VALUES(8, 368, NULL, "", 'F', 9, 2.932500e+002, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 1, 3.299000e+001, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 2, 6.954000e+001, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 3, 1.069700e+002, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 4, 1.446300e+002, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 5, 1.828600e+002, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 6, 2.211700e+002, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 7, 2.590400e+002, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 8, 2.974200e+002, 0);
INSERT INTO `Split` VALUES(8, 372, NULL, "", 'F', 9, 3.348900e+002, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 1, 3.272000e+001, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 2, 6.769000e+001, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 3, 1.029800e+002, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 4, 1.381100e+002, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 5, 1.742500e+002, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 6, 2.103900e+002, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 7, 2.469100e+002, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 8, 2.843800e+002, 0);
INSERT INTO `Split` VALUES(8, 406, NULL, "", 'F', 9, 3.214900e+002, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 1, 3.167000e+001, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 2, 6.608000e+001, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 3, 1.014400e+002, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 4, 1.373600e+002, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 5, 1.732500e+002, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 6, 2.095200e+002, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 7, 2.455200e+002, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 8, 2.819000e+002, 0);
INSERT INTO `Split` VALUES(8, 417, NULL, "", 'F', 9, 3.184200e+002, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 1, 3.145000e+001, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 2, 6.571000e+001, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 3, 1.007700e+002, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 4, 1.364700e+002, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 5, 1.721200e+002, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 6, 2.077900e+002, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 7, 2.438800e+002, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 8, 2.799800e+002, 0);
INSERT INTO `Split` VALUES(8, 504, NULL, "", 'F', 9, 3.160100e+002, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 1, 3.427000e+001, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 2, 7.153000e+001, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 3, 1.097000e+002, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 4, 1.486000e+002, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 5, 1.865500e+002, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 6, 2.248700e+002, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 7, 2.632100e+002, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 8, 3.014300e+002, 0);
INSERT INTO `Split` VALUES(8, 509, NULL, "", 'F', 9, 3.384800e+002, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 1, 2.883000e+001, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 2, 6.080000e+001, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 3, 9.332000e+001, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 4, 1.261700e+002, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 5, 1.590800e+002, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 6, 1.922000e+002, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 7, 2.250000e+002, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 8, 2.586500e+002, 0);
INSERT INTO `Split` VALUES(8, 534, NULL, "", 'F', 9, 2.919800e+002, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 1, 3.323000e+001, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 2, 7.064000e+001, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 3, 1.087800e+002, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 4, 1.484100e+002, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 5, 1.881000e+002, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 6, 2.277700e+002, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 7, 2.676200e+002, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 8, 3.082200e+002, 0);
INSERT INTO `Split` VALUES(8, 572, NULL, "", 'F', 9, 3.477800e+002, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 1, 3.274000e+001, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 2, 6.811000e+001, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 3, 1.046700e+002, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 4, 1.411900e+002, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 5, 1.780300e+002, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 6, 2.148200e+002, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 7, 2.522400e+002, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 8, 2.899000e+002, 0);
INSERT INTO `Split` VALUES(8, 674, NULL, "", 'F', 9, 3.280300e+002, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 2, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 3, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 4, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 5, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 6, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 7, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 8, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 682, NULL, "", 'F', 9, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 1, 3.290000e+001, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 2, 6.918000e+001, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 3, 1.054900e+002, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 4, 1.420000e+002, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 5, 1.781300e+002, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 6, 2.137600e+002, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 7, 2.497800e+002, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 8, 2.862400e+002, 0);
INSERT INTO `Split` VALUES(8, 684, NULL, "", 'F', 9, 3.225900e+002, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 1, 3.231000e+001, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 2, 6.759000e+001, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 3, 1.039700e+002, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 4, 1.413600e+002, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 5, 1.794400e+002, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 6, 2.176800e+002, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 7, 2.559500e+002, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 8, 2.947200e+002, 0);
INSERT INTO `Split` VALUES(8, 727, NULL, "", 'F', 9, 3.319300e+002, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 2, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 3, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 4, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 5, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 6, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 7, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 8, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 746, NULL, "", 'F', 9, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 1, 3.135000e+001, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 2, 6.553000e+001, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 3, 1.004800e+002, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 4, 1.362800e+002, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 5, 1.723400e+002, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 6, 2.086500e+002, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 7, 2.448600e+002, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 8, 2.811300e+002, 0);
INSERT INTO `Split` VALUES(8, 796, NULL, "", 'F', 9, 3.171700e+002, 0);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.837000e+001, 55);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.210000e+001, 35);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.597000e+001, 38);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.107000e+001, 21);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.068000e+001, 13);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.223000e+001, 5);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.802000e+001, 16);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.141000e+001, 27);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 4.317000e+001, 74);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 0.000000e+000, 33);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.971000e+001, 24);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.841000e+001, 73);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.869000e+001, 30);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.004000e+001, 61);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.066000e+001, 50);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.988000e+001, 8);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.787000e+001, 43);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.903000e+001, 2);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.716000e+001, 66);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.662000e+001, 18);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.793000e+001, 81);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 3.040000e+001, 67);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.629000e+001, 42);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.618000e+001, 54);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.657000e+001, 47);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.691000e+001, 12);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.647000e+001, 79);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 1, 2.749000e+001, 60);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.628000e+001, 55);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 6.502000e+001, 35);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 7.077000e+001, 38);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.992000e+001, 21);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 6.157000e+001, 13);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 6.326000e+001, 5);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.699000e+001, 16);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 6.215000e+001, 27);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 7.819000e+001, 74);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 0.000000e+000, 33);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.878000e+001, 24);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.805000e+001, 73);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.939000e+001, 30);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.794000e+001, 61);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 6.189000e+001, 50);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.806000e+001, 8);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.569000e+001, 43);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.685000e+001, 2);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.461000e+001, 66);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.453000e+001, 18);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.817000e+001, 81);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.987000e+001, 67);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.294000e+001, 42);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.293000e+001, 54);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.347000e+001, 47);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.354000e+001, 12);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.268000e+001, 79);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 2, 5.449000e+001, 60);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.279000e+001, 55);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 9.511000e+001, 35);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 1.106900e+002, 38);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 9.397000e+001, 21);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 9.275000e+001, 13);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 9.629000e+001, 5);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.689000e+001, 16);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 9.210000e+001, 27);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 1.153000e+002, 74);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 0.000000e+000, 33);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.695000e+001, 24);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.587000e+001, 73);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 9.086000e+001, 30);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.904000e+001, 61);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 9.159000e+001, 50);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.926000e+001, 8);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.489000e+001, 43);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.595000e+001, 2);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.348000e+001, 66);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.123000e+001, 18);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.710000e+001, 81);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.674000e+001, 67);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 7.973000e+001, 42);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 7.906000e+001, 54);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.127000e+001, 47);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.065000e+001, 12);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 7.910000e+001, 79);
INSERT INTO `Split` VALUES(9, 0, NULL, "", 'F', 3, 8.214000e+001, 60);
INSERT INTO `Split` VALUES(10, 14, NULL, "", 'F', 1, 3.418000e+001, 0);
INSERT INTO `Split` VALUES(10, 23, NULL, "", 'F', 1, 3.987000e+001, 0);
INSERT INTO `Split` VALUES(10, 24, NULL, "", 'F', 1, 3.767000e+001, 0);
INSERT INTO `Split` VALUES(10, 85, NULL, "", 'F', 1, 3.358000e+001, 0);
INSERT INTO `Split` VALUES(10, 113, NULL, "", 'F', 1, 3.015000e+001, 0);
INSERT INTO `Split` VALUES(10, 115, NULL, "", 'F', 1, 3.083000e+001, 0);
INSERT INTO `Split` VALUES(10, 117, NULL, "", 'F', 1, 3.377000e+001, 0);
INSERT INTO `Split` VALUES(10, 133, NULL, "", 'F', 1, 3.248000e+001, 0);
INSERT INTO `Split` VALUES(10, 141, NULL, "", 'F', 1, 4.598000e+001, 0);
INSERT INTO `Split` VALUES(10, 160, NULL, "", 'F', 1, 4.077000e+001, 0);
INSERT INTO `Split` VALUES(10, 164, NULL, "", 'F', 1, 3.447000e+001, 0);
INSERT INTO `Split` VALUES(10, 188, NULL, "", 'F', 1, 4.291000e+001, 0);
INSERT INTO `Split` VALUES(10, 194, NULL, "", 'F', 1, 3.549000e+001, 0);
INSERT INTO `Split` VALUES(10, 211, NULL, "", 'F', 1, 3.489000e+001, 0);
INSERT INTO `Split` VALUES(10, 217, NULL, "", 'F', 1, 3.522000e+001, 0);
INSERT INTO `Split` VALUES(10, 229, NULL, "", 'F', 1, 3.554000e+001, 0);
INSERT INTO `Split` VALUES(10, 232, NULL, "", 'F', 1, 3.874000e+001, 0);
INSERT INTO `Split` VALUES(10, 288, NULL, "", 'F', 1, 4.136000e+001, 0);
INSERT INTO `Split` VALUES(10, 297, NULL, "", 'F', 1, 3.538000e+001, 0);
INSERT INTO `Split` VALUES(10, 298, NULL, "", 'F', 1, 4.095000e+001, 0);
INSERT INTO `Split` VALUES(10, 299, NULL, "", 'F', 1, 3.934000e+001, 0);
INSERT INTO `Split` VALUES(10, 378, NULL, "", 'F', 1, 3.230000e+001, 0);
INSERT INTO `Split` VALUES(10, 381, NULL, "", 'F', 1, 3.412000e+001, 0);
INSERT INTO `Split` VALUES(10, 399, NULL, "", 'F', 1, 3.308000e+001, 0);
INSERT INTO `Split` VALUES(10, 401, NULL, "", 'F', 1, 3.581000e+001, 0);
INSERT INTO `Split` VALUES(10, 427, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(10, 437, NULL, "", 'F', 1, 4.371000e+001, 0);
INSERT INTO `Split` VALUES(10, 446, NULL, "", 'F', 1, 3.314000e+001, 0);
INSERT INTO `Split` VALUES(10, 447, NULL, "", 'F', 1, 4.023000e+001, 0);
INSERT INTO `Split` VALUES(10, 453, NULL, "", 'F', 1, 8.618000e+001, 0);
INSERT INTO `Split` VALUES(10, 470, NULL, "", 'F', 1, 4.085000e+001, 0);
INSERT INTO `Split` VALUES(10, 484, NULL, "", 'F', 1, 3.955000e+001, 0);
INSERT INTO `Split` VALUES(10, 496, NULL, "", 'F', 1, 3.107000e+001, 0);
INSERT INTO `Split` VALUES(10, 509, NULL, "", 'F', 1, 3.674000e+001, 0);
INSERT INTO `Split` VALUES(10, 523, NULL, "", 'F', 1, 3.692000e+001, 0);
INSERT INTO `Split` VALUES(10, 569, NULL, "", 'F', 1, 3.949000e+001, 0);
INSERT INTO `Split` VALUES(10, 570, NULL, "", 'F', 1, 3.888000e+001, 0);
INSERT INTO `Split` VALUES(10, 623, NULL, "", 'F', 1, 3.496000e+001, 0);
INSERT INTO `Split` VALUES(10, 626, NULL, "", 'F', 1, 3.111000e+001, 0);
INSERT INTO `Split` VALUES(10, 679, NULL, "", 'F', 1, 3.503000e+001, 0);
INSERT INTO `Split` VALUES(10, 687, NULL, "", 'F', 1, 3.357000e+001, 0);
INSERT INTO `Split` VALUES(10, 697, NULL, "", 'F', 1, 3.467000e+001, 0);
INSERT INTO `Split` VALUES(10, 709, NULL, "", 'F', 1, 3.380000e+001, 0);
INSERT INTO `Split` VALUES(10, 715, NULL, "", 'F', 1, 3.879000e+001, 0);
INSERT INTO `Split` VALUES(10, 718, NULL, "", 'F', 1, 3.767000e+001, 0);
INSERT INTO `Split` VALUES(10, 727, NULL, "", 'F', 1, 3.383000e+001, 0);
INSERT INTO `Split` VALUES(10, 741, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(10, 759, NULL, "", 'F', 1, 3.808000e+001, 0);
INSERT INTO `Split` VALUES(10, 763, NULL, "", 'F', 1, 3.436000e+001, 0);
INSERT INTO `Split` VALUES(10, 783, NULL, "", 'F', 1, 3.341000e+001, 0);
INSERT INTO `Split` VALUES(10, 787, NULL, "", 'F', 1, 4.043000e+001, 0);
INSERT INTO `Split` VALUES(10, 797, NULL, "", 'F', 1, 3.419000e+001, 0);
INSERT INTO `Split` VALUES(10, 803, NULL, "", 'F', 1, 8.693000e+001, 0);
INSERT INTO `Split` VALUES(10, 805, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(11, 43, NULL, "", 'F', 1, 4.075000e+001, 0);
INSERT INTO `Split` VALUES(11, 44, NULL, "", 'F', 1, 3.421000e+001, 0);
INSERT INTO `Split` VALUES(11, 70, NULL, "", 'F', 1, 3.494000e+001, 0);
INSERT INTO `Split` VALUES(11, 83, NULL, "", 'F', 1, 3.852000e+001, 0);
INSERT INTO `Split` VALUES(11, 84, NULL, "", 'F', 1, 3.768000e+001, 0);
INSERT INTO `Split` VALUES(11, 102, NULL, "", 'F', 1, 3.455000e+001, 0);
INSERT INTO `Split` VALUES(11, 149, NULL, "", 'F', 1, 4.350000e+001, 0);
INSERT INTO `Split` VALUES(11, 155, NULL, "", 'F', 1, 4.873000e+001, 0);
INSERT INTO `Split` VALUES(11, 158, NULL, "", 'F', 1, 3.553000e+001, 0);
INSERT INTO `Split` VALUES(11, 166, NULL, "", 'F', 1, 4.946000e+001, 0);
INSERT INTO `Split` VALUES(11, 205, NULL, "", 'F', 1, 4.328000e+001, 0);
INSERT INTO `Split` VALUES(11, 206, NULL, "", 'F', 1, 4.264000e+001, 0);
INSERT INTO `Split` VALUES(11, 214, NULL, "", 'F', 1, 4.331000e+001, 0);
INSERT INTO `Split` VALUES(11, 220, NULL, "", 'F', 1, 4.313000e+001, 0);
INSERT INTO `Split` VALUES(11, 230, NULL, "", 'F', 1, 4.093000e+001, 0);
INSERT INTO `Split` VALUES(11, 260, NULL, "", 'F', 1, 3.578000e+001, 0);
INSERT INTO `Split` VALUES(11, 262, NULL, "", 'F', 1, 3.707000e+001, 0);
INSERT INTO `Split` VALUES(11, 266, NULL, "", 'F', 1, 4.178000e+001, 0);
INSERT INTO `Split` VALUES(11, 279, NULL, "", 'F', 1, 3.737000e+001, 0);
INSERT INTO `Split` VALUES(11, 379, NULL, "", 'F', 1, 3.478000e+001, 0);
INSERT INTO `Split` VALUES(11, 402, NULL, "", 'F', 1, 3.865000e+001, 0);
INSERT INTO `Split` VALUES(11, 407, NULL, "", 'F', 1, 3.903000e+001, 0);
INSERT INTO `Split` VALUES(11, 412, NULL, "", 'F', 1, 3.677000e+001, 0);
INSERT INTO `Split` VALUES(11, 430, NULL, "", 'F', 1, 4.231000e+001, 0);
INSERT INTO `Split` VALUES(11, 435, NULL, "", 'F', 1, 3.634000e+001, 0);
INSERT INTO `Split` VALUES(11, 439, NULL, "", 'F', 1, 3.969000e+001, 0);
INSERT INTO `Split` VALUES(11, 494, NULL, "", 'F', 1, 3.659000e+001, 0);
INSERT INTO `Split` VALUES(11, 528, NULL, "", 'F', 1, 3.617000e+001, 0);
INSERT INTO `Split` VALUES(11, 550, NULL, "", 'F', 1, 3.737000e+001, 0);
INSERT INTO `Split` VALUES(11, 556, NULL, "", 'F', 1, 3.550000e+001, 0);
INSERT INTO `Split` VALUES(11, 567, NULL, "", 'F', 1, 3.890000e+001, 0);
INSERT INTO `Split` VALUES(11, 586, NULL, "", 'F', 1, 3.977000e+001, 0);
INSERT INTO `Split` VALUES(11, 660, NULL, "", 'F', 1, 3.947000e+001, 0);
INSERT INTO `Split` VALUES(11, 673, NULL, "", 'F', 1, 3.463000e+001, 0);
INSERT INTO `Split` VALUES(11, 674, NULL, "", 'F', 1, 3.755000e+001, 0);
INSERT INTO `Split` VALUES(11, 676, NULL, "", 'F', 1, 3.302000e+001, 0);
INSERT INTO `Split` VALUES(11, 691, NULL, "", 'F', 1, 3.986000e+001, 0);
INSERT INTO `Split` VALUES(11, 703, NULL, "", 'F', 1, 4.171000e+001, 0);
INSERT INTO `Split` VALUES(11, 714, NULL, "", 'F', 1, 3.945000e+001, 0);
INSERT INTO `Split` VALUES(11, 748, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(11, 756, NULL, "", 'F', 1, 3.305000e+001, 0);
INSERT INTO `Split` VALUES(11, 758, NULL, "", 'F', 1, 0.000000e+000, 0);
INSERT INTO `Split` VALUES(11, 773, NULL, "", 'F', 1, 4.180000e+001, 0);
INSERT INTO `Split` VALUES(11, 781, NULL, "", 'F', 1, 3.456000e+001, 0);
INSERT INTO `Split` VALUES(11, 788, NULL, "", 'F', 1, 4.188000e+001, 0);
INSERT INTO `Split` VALUES(11, 795, NULL, "", 'F', 1, 4.126000e+001, 0);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 3.364000e+001, 22);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 0.000000e+000, 39);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 0.000000e+000, 70);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 0.000000e+000, 36);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 4.107000e+001, 6);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 3.367000e+001, 51);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 3.732000e+001, 28);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 4.033000e+001, 76);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 0.000000e+000, 3);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 4.107000e+001, 63);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 3.367000e+001, 9);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 3.732000e+001, 75);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 4.033000e+001, 69);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 0.000000e+000, 14);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.841000e+001, 25);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.965000e+001, 31);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 3.121000e+001, 68);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 3.186000e+001, 82);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 3.007000e+001, 57);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.970000e+001, 45);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.970000e+001, 62);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.750000e+001, 56);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.693000e+001, 48);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.753000e+001, 44);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.806000e+001, 80);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 1, 2.798000e+001, 19);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 7.065000e+001, 22);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 0.000000e+000, 39);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 0.000000e+000, 70);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 0.000000e+000, 36);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 8.774000e+001, 6);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 6.941000e+001, 51);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 7.688000e+001, 28);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 8.426000e+001, 76);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 0.000000e+000, 3);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 8.774000e+001, 63);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 6.941000e+001, 9);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 7.688000e+001, 75);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 8.426000e+001, 69);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 0.000000e+000, 14);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 5.955000e+001, 25);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 6.128000e+001, 31);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 6.505000e+001, 68);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 6.511000e+001, 82);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 6.216000e+001, 57);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 6.195000e+001, 45);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 6.151000e+001, 62);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 5.653000e+001, 56);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 5.613000e+001, 48);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 5.810000e+001, 44);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 5.802000e+001, 80);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 2, 5.803000e+001, 19);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.010300e+002, 22);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 0.000000e+000, 39);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 0.000000e+000, 70);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 0.000000e+000, 36);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.227700e+002, 6);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.015900e+002, 51);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.120500e+002, 28);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.231900e+002, 76);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 0.000000e+000, 3);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.227700e+002, 63);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.015900e+002, 9);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.120500e+002, 75);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 1.231900e+002, 69);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 0.000000e+000, 14);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 9.075000e+001, 25);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 9.001000e+001, 31);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 9.780000e+001, 68);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 9.566000e+001, 82);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 9.428000e+001, 57);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 9.111000e+001, 45);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 9.166000e+001, 62);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 8.429000e+001, 56);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 8.290000e+001, 48);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 8.487000e+001, 44);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 8.641000e+001, 80);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 3, 8.611000e+001, 19);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.363100e+002, 22);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 0.000000e+000, 39);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 0.000000e+000, 70);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 0.000000e+000, 36);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.611700e+002, 6);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.370300e+002, 51);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.505800e+002, 28);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.627600e+002, 76);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 0.000000e+000, 3);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.611700e+002, 63);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.370300e+002, 9);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.505800e+002, 75);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.627600e+002, 69);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 0.000000e+000, 14);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.258200e+002, 25);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.224200e+002, 31);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.350700e+002, 68);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.287000e+002, 82);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.288200e+002, 57);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.246600e+002, 45);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.255000e+002, 62);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.155100e+002, 56);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.119100e+002, 48);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.147300e+002, 44);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.173400e+002, 80);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 4, 1.163600e+002, 19);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.679200e+002, 22);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 0.000000e+000, 39);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 0.000000e+000, 70);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 0.000000e+000, 36);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.952500e+002, 6);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.700800e+002, 51);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.864000e+002, 28);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.960500e+002, 76);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 0.000000e+000, 3);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.952500e+002, 63);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.700800e+002, 9);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.864000e+002, 75);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.960500e+002, 69);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 0.000000e+000, 14);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.556300e+002, 25);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.514900e+002, 31);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.652600e+002, 68);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.585200e+002, 82);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.583100e+002, 57);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.540700e+002, 45);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.545700e+002, 62);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.428800e+002, 56);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.426500e+002, 48);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.419700e+002, 44);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.468100e+002, 80);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 5, 1.454600e+002, 19);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.034400e+002, 22);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 0.000000e+000, 39);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 0.000000e+000, 70);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 0.000000e+000, 36);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.330100e+002, 6);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.058000e+002, 51);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.245200e+002, 28);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.322100e+002, 76);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 0.000000e+000, 3);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.330100e+002, 63);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.058000e+002, 9);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.245200e+002, 75);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.322100e+002, 69);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 0.000000e+000, 14);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.895200e+002, 25);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.840400e+002, 31);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 2.000100e+002, 68);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.915500e+002, 82);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.896800e+002, 57);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.871600e+002, 45);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.872900e+002, 62);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.728200e+002, 56);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.775700e+002, 48);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.712400e+002, 44);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.783500e+002, 80);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 6, 1.767400e+002, 19);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.381300e+002, 22);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 0.000000e+000, 39);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 0.000000e+000, 70);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 0.000000e+000, 36);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.646200e+002, 6);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.356100e+002, 51);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.580500e+002, 28);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.647300e+002, 76);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 0.000000e+000, 3);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.646200e+002, 63);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.356100e+002, 9);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.580500e+002, 75);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.647300e+002, 69);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 0.000000e+000, 14);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.185200e+002, 25);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.118400e+002, 31);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.333800e+002, 68);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.205000e+002, 82);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.180700e+002, 57);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.167000e+002, 45);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.143900e+002, 62);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 1.991900e+002, 56);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.050000e+002, 48);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 1.967200e+002, 44);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.047000e+002, 80);
INSERT INTO `Split` VALUES(12, 0, NULL, "", 'F', 7, 2.044400e+002, 19);
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
INSERT INTO `StdLanes` VALUES(6, 3, 4, 2, 5, 1, 6, 0, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(7, 4, 5, 3, 6, 2, 7, 1, 0, 0, 0);
INSERT INTO `StdLanes` VALUES(8, 4, 5, 3, 6, 2, 7, 1, 8, 0, 0);
INSERT INTO `StdLanes` VALUES(9, 5, 6, 4, 7, 3, 8, 2, 9, 1, 0);
INSERT INTO `StdLanes` VALUES(10, 5, 6, 4, 7, 3, 8, 2, 9, 1, 10);
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
INSERT INTO `TagNames` VALUES(1, 'AUTO', 0, 0, -1);
INSERT INTO `TagNames` VALUES(2, 'SEC', 0, 0, -1);
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
  `Team_statenew` VARCHAR(3),
  `Team_zip` VARCHAR(10),
  `Team_cntry` VARCHAR(3),
  `Team_daytele` VARCHAR(20),
  `Team_evetele` VARCHAR(20),
  `Team_faxtele` VARCHAR(20),
  `Team_email` VARCHAR(36),
  `Team_c3` VARCHAR(30),
  `Team_c4` VARCHAR(30),
  `Team_c5` VARCHAR(30),
  `Team_c6` VARCHAR(30),
  `Team_c7` VARCHAR(30),
  `Team_c8` VARCHAR(30),
  `Team_c9` VARCHAR(30),
  `Team_c10` VARCHAR(30),
  `Team_altabbr` VARCHAR(5),
  INDEX `teamabbr` (`Team_abbr`),
  PRIMARY KEY (`Team_no`)
);

#
# Dumping data for table 'Team'
#

LOCK TABLES `Team` WRITE;
INSERT INTO `Team` VALUES(1, 'Unattached', 'Unattached', 'UNAT ', "", '  ', NULL, NULL, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(2, 'Millard South                 ', "", 'MILLS', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(3, 'Millard North                 ', "", 'MILLN', "", '  ', 0, 0, 'David Tyler                   ', 'Amber Ripa                    ', "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(4, 'Bellevue West                 ', 'Thunderbirds    ', 'BELLW', "", '  ', 0, 0, 'Peg Speer                     ', 'Brandon Crenshaw              ', 'Peg Speer, Swim Coach         ', '1501 Thurston Ave .           ', 'Bellevue                      ', "", 'NE ', '68123     ', 'USA', '402-293-4040        ', '402-850-3731        ', '402-293-4149        ', 'bwcrenshaw@hotmail.com              ', "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(5, 'Bellevue East                 ', "", 'BELLE', "", '  ', 0, 0, "", "", "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(6, 'Columbus                      ', "", 'COLUM', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(7, 'Lincoln Southeast             ', "", 'LSE  ', "", '  ', 0, 0, "", "", "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(8, 'Lincoln Southwest             ', "", 'LSW  ', "", '  ', 0, 0, "", "", "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(9, 'Millard West                  ', "", 'MILLW', "", '  ', 0, 0, "", "", "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(10, 'Norfolk                       ', "", 'NRFLK', "", '  ', 0, 0, "", "", "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(11, 'Omaha Bryan                   ', "", 'OMBRY', "", '  ', 0, 0, "", "", "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(12, 'Omaha Burke                   ', "", 'OMBRK', "", '  ', 0, 0, "", "", "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(13, 'Omaha Central                 ', "", 'OMCEN', "", '  ', 0, 0, "", "", "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(14, 'Lincoln East                  ', 'LE              ', 'LE   ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(15, 'Papillion-LaVista             ', "", 'PLV  ', "", '  ', 0, 0, 'Lynn Weaver                   ', 'Jamie Blinn                   ', "", "", "", "", 'NE ', "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(16, 'Ralston/Omaha Gross           ', 'RALSTON/GROSS   ', 'RALGR', "", '  ', 0, 0, 'DOCKER J. HARTFIELD           ', 'LARRY HILL                    ', 'RALSTON HIGH/GROSS            ', '8989 PARK DRIVE               ', 'OMAHA                         ', "", 'NE ', '68127     ', 'USA', '402-898-3567        ', '402-492-8956        ', "", "", "", "", "", "", "", "", "", "", "");
INSERT INTO `Team` VALUES(17, 'Omaha North                   ', "", 'OMNO ', "", '  ', 0, 0, "", "", "", "", "", "", "", "", 'USA', "", "", "", "", "", "", "", "", "", "", "", "", "");
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
INSERT INTO `TimeStd` VALUES(1, 'F', 'R', 200, 'E', 0, 109, 1.241200e+002, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'I', 200, 'A', 0, 109, 1.255500e+002, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'I', 200, 'E', 0, 109, 1.420400e+002, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'I', 50, 'A', 0, 109, 2.600000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'I', 100, 'D', 0, 109, 6.462000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'I', 100, 'A', 0, 109, 5.747000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'I', 500, 'A', 0, 109, 3.385300e+002, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'R', 200, 'A', 0, 109, 1.101800e+002, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'I', 100, 'B', 0, 109, 6.517000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'I', 100, 'C', 0, 109, 7.322000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(1, 'F', 'R', 400, 'A', 0, 109, 2.442800e+002, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'I', 200, 'E', 0, 109, 1.534000e+002, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'I', 50, 'A', 0, 109, 2.808000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'I', 100, 'D', 0, 109, 6.979000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'R', 200, 'E', 0, 109, 1.340500e+002, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'I', 200, 'A', 0, 109, 1.355900e+002, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'I', 100, 'A', 0, 109, 6.207000e+001, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'I', 500, 'A', 0, 109, 3.656200e+002, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'R', 200, 'A', 0, 109, 1.190000e+002, 'Y');
INSERT INTO `TimeStd` VALUES(2, 'F', 'I', 100, 'B', 0, 109, 7.038000e+001, 'Y');
