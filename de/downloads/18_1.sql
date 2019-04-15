-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 27, 2017 at 09:07 AM
-- Server version: 5.6.30
-- PHP Version: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nsaascores`
--

-- --------------------------------------------------------

--
-- Table structure for table `hostapp_ubo`
--

CREATE TABLE IF NOT EXISTS `hostapp_ubo` (
  `id` int(11) NOT NULL,
  `school` varchar(200) NOT NULL DEFAULT '',
  `interested` varchar(5) NOT NULL DEFAULT '',
  `oct14` varchar(5) NOT NULL DEFAULT '',
  `director` varchar(200) NOT NULL DEFAULT '',
  `choice` varchar(200) NOT NULL DEFAULT '',
  `neutral` varchar(200) NOT NULL DEFAULT '',
  `comments` text NOT NULL,
  `date1` varchar(10) NOT NULL,
  `facility` varchar(300) NOT NULL,
  `city` varchar(200) NOT NULL,
  `lanes` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hostapp_ubo`
--

INSERT INTO `hostapp_ubo` (`id`, `school`, `interested`, `oct14`, `director`, `choice`, `neutral`, `comments`, `date1`, `facility`, `city`, `lanes`) VALUES
(1, 'Alma', '', '', 'Galen Kronhofman', '', '', 'We would host the meet at the Alma City Golf course.  We hosted 2010 District meet, our conference meet in the past and we host our own CC meet.  We felt the District Meet went very well and had a lot of compliments from coaches, athletes and fans.', '', '', '', ''),
(2, 'Bloomfield', 'y', '', 'Duane Wilken', '', '', 'If approved, the meet will be held at Rolling Hills Country Club, Wausa, NE  We have hosted the district contest a number of years and would like to host it again.  We also host the Lewis and Clark Conference meet and our own Invite the week before the district meet.  \r\n\r\nThanks,\r\nKirk Hamm', '', '', '', ''),
(3, 'North Platte', 'y', '', 'Marc Mroczek', '', '', '', '', '', '', ''),
(4, 'Fillmore Central', '', '', 'Steve Adkisson', '', '', '', '', '', '', ''),
(5, 'Holdrege', '', '', 'Scott Schoneman', '', '', 'Course is flat.', '', '', '', ''),
(6, 'Fremont', '', '', 'Bill Fitzgerald - CMAA', '', '', 'Course is at Valley View Golf Club, south of Fremont on south side of Platte River, just off of Hwy 77.', '', '', '', ''),
(7, 'Malcolm', 'y', '', 'Jack Tarr', '', '', 'We need to be in the district.', '', '', '', ''),
(8, 'Aquinas Catholic', 'y', '', 'John Svec', '', '', '', '', '', '', ''),
(9, 'Fairbury', 'y', '', 'Derek Anderson', '', '', 'Fairbury has hosted this event multiple times and have been complimented for having a course the prepares athletes for the state course.', '', '', '', ''),
(10, 'Seward', 'y', '', 'John Moody', '', '', 'We would host the event at Concordia University.', '', '', '', ''),
(11, 'Scottsbluff', '', '', 'Dave Pauli', '', '', '', '', '', '', ''),
(12, 'Stanton', '', '', '', '', '', '', '', '', '', ''),
(13, 'Kearney', '', '', 'Mitchell Stine', '', '', 'Will be run at Meadowlark Hills.', '', '', '', ''),
(14, 'Crete', '', '', 'Jim Moore', '', '', '', '', '', '', ''),
(15, 'Arapahoe', 'y', '', 'John Paulsen', '', '', 'We have a great course.  Hosted in 09, 11, 13, 14, 15 and 16.  I would be willing to host again in 2017.', '', '', '', ''),
(16, 'Deshler', '', '', '', '', '', '', '', '', '', ''),
(17, 'Ogallala', 'y', '', 'Scott Rezac', '', '', '', '', '', '', ''),
(18, 'Lincoln Pius X', '', '', 'George O''Boyle', '', '', 'Run at Pioneers Park in Lincoln', '', '', '', ''),
(19, 'Anselmo-Merna', '', '', '', '', '', '', '', '', '', ''),
(20, 'Perkins County', '', '', 'Carlie Wells/Paula Wurst', '', '', 'Host our own invite/conference tournament each year and have ample workforce to host the event.  Our golf facility setup allows us to charge admission easily.', '', '', '', ''),
(21, 'Pierce', '', '', 'Gary Timm', '', '', '', '', '', '', ''),
(22, 'Norfolk Catholic', '', '', 'Shane Anderson', '', '', '', '', '', '', ''),
(23, 'Crofton', '', '', 'Jayne Arens/Ann Kramer', '', '', '', '', '', '', ''),
(24, 'Thayer Central', 'y', '', 'Mark Leonard', '', '', 'Have hosted Class D in 2010, Have an excellent course and a great staff to help run the meet. The course is very fan friendly as they can view the majority of the race. The course is moderately challenging to the runners. The course is 5000 meters done on gps.', '', '', '', ''),
(25, 'Gering', 'y', '', 'Glen Koski', '', '', '', '', '', '', ''),
(26, 'Norris', '', '', '', '', '', '', '', '', '', ''),
(27, 'Omaha Skutt Catholic', '', '', 'Jeremy Moore', '', '', '', '', '', '', ''),
(28, 'Lincoln Southwest', '', '', 'Mark Armstrong', '', '', '', '', '', '', ''),
(29, 'Norfolk', 'y', '', 'Ben Ries', '', '', 'Skyview Lake is  the course Norfolk runs on in Norfolk.', '', '', '', ''),
(30, 'Grand Island', '', '', 'Joe Kutlas/Jessica McDowell', '', '', '', '', '', '', ''),
(31, 'Niobrara', '', '', '', '', '', '', '', '', '', ''),
(32, 'Johnson County Central', '', '', '', '', '', '', '', '', '', ''),
(33, 'Millard North', '', '', '', '', '', '', '', '', '', ''),
(34, 'Boone Central', 'y', '', 'Marcus Donner', '', '', '', '', '', '', ''),
(35, 'O''Neill', 'y', '', 'Nick Hostert', '', '', '', '', '', '', ''),
(36, 'Ainsworth', 'y', '', 'Jared Hansmeyer', '', '', 'We have hosted before and feel we do an outstanding job.   The 2013, 2015, and 2016 meets were great examples.   We would love to be considered again.', '', 'Good', 'NE', '5'),
(37, 'Lincoln East', '', '', 'Wendy Henrichs', '', '', '', '', '', '', ''),
(38, 'Columbus', '', '', '', '', '', 'Might be interested in a couple of years. Hosting this year would give us 3 straight weeks of hosting an XC meet because we host the conference meet. \r\nWe would also need to get our course set up for an "official" 5,000 meters designation, which currently, it is not.', '', '', '', ''),
(39, 'Papillion-La Vista South', 'y', '', 'Jeremy VanAckeren/Brent Gehring', '', '', 'Site would be Walnut Creek', '', '', '', ''),
(40, 'Chase County', '', '', '', '', '', '', '', '', '', ''),
(41, 'Blair', '', '', 'Marty Rogers', '', '', '', '', '', '', ''),
(42, 'Fort Calhoun', 'y', '', 'Nick Wemhoff', '', '', 'This would be run at Fort Atkinson State Park.  People would be required to have a state parking pass to park in the facility.  ', '', '', '', ''),
(43, 'Superior', '', '', '', '', '', '', '', '', '', ''),
(44, 'St. Mary''s', 'n', '', '', '', '', '', '', '', '', ''),
(45, 'Beatrice', '', '', '', '', '', '', '', '', '', ''),
(46, 'Gothenburg', '', '', 'Seth Ryker', '', '', '', '', '', '', ''),
(47, 'Papillion-La Vista', '', '', 'Jason Ryan', '', '', 'We would be hosting at Walnut Creek near Papillion-La Vista South High School. ', '', '', '', ''),
(48, 'Louisville', 'n', '', '', '', '', '', '', '', '', ''),
(49, 'McCool Junction', 'y', '', 'Guy Stark', '', '', 'We would host this meet at Camp Kateri just south of McCool Junction.  We do host our own meet here every year  and hosted our district cross country meet last year and have had good feedback on the course.', '', '', '', ''),
(50, 'Maxwell', '', '', '', '', '', '', '', '', '', ''),
(51, 'Lincoln North Star', 'y', '', 'Kevin Simmerman', '', '', '', '', '', '', ''),
(52, 'Lincoln Southeast', '', '', 'Mike Rasmussen, Kathi Wieskamp, Dave Nebel', '', '', 'Pioneers Park would be the location for the event.', '', '', '', ''),
(53, 'Elwood', '', '', '', '', '', '', '', '', '', ''),
(54, 'Columbus Scotus', '', '', 'Gary Puetz/Merlin Lahm', '', '', '', '', '', '', ''),
(55, 'South Sioux City', '', '', '', '', '', '', '', '', '', ''),
(56, 'Lexington', '', '', 'Alan Frank', '', '', '', '', '', '', ''),
(57, 'West Holt', 'n', '', '', '', '', '', '', '', '', ''),
(58, 'Cambridge', '', '', 'Doug Nibbe', '', '', 'We have a beautiful 18 hole golf course to run on.  Doug would rely on Janice Howell to help direct.  Janice is a veteran cross country coach and has organized many cross country races.  We do host a large meet at the beginning of each year so feel experienced enough to handle a district meet without any concerns.', '', '', '', ''),
(59, 'Boys Town', '', '', 'Paul Blomenkamp', '', '', 'Boys Town would like to be considered for hosting its district cross country meet in 2016.', '', '', '', ''),
(60, 'Lindsay Holy Family', '', '', '', '', '', '', '', '', '', ''),
(61, 'Schuyler', '', '', '', '', '', 'We do not have an area that would represent a true cross country course. ', '', '', '', ''),
(62, 'Gretna', '', '', '', '', '', '', '', '', '', ''),
(63, 'Cozad', 'y', '', 'Jordan Cudney ', '', '', 'Cozad has done a good job of building their Cross Country programs (boys and girls).  We would like an opportunity to host a District Cross Country meet.  I believe we would be able to host two districts similar to what Ogallala has done in the past.  Thank you for the opportunity.', '', '', '', ''),
(64, 'Parkview Christian', '', '', '', '', '', 'We do not have a cross country team at Parkview Christian School.', '', '', '', ''),
(65, 'Mount Michael Benedictine', 'y', '', 'Derrik Spooner', '', '', '', '', '', '', ''),
(66, 'Oakland-Craig', '', '', 'Merritt Nelson', '', '', 'We would love to host the district cross country meet. We have an experienced staff of tournament directors.  The site would be at our local golf course.  ', '', '', '', ''),
(67, 'West Point-Beemer', '', '', 'Mitch Hoffer', '', '', 'Event will be held at Indian Trails Country Club in Beemer if selected. ', '', '', '', ''),
(68, 'Wallace', '', '', '', '', '', '', '', '', '', ''),
(69, 'Wayne', '', '', 'Rocky Ruhl', '', '', 'We would host at the Wayne Country Club.', '', '', '', ''),
(70, 'Hemingford', 'n', '', '', '', '', '', '', '', '', ''),
(71, 'Bennington', '', '', 'yes', '', '', '', '', '', '', ''),
(72, 'Eustis-Farnam', '', '', '', '', '', '', '', '', '', ''),
(73, 'Battle Creek', '', '', '', '', '', '', '', '', '', ''),
(74, 'Northwest', '', '', '', '', '', '', '', '', '', ''),
(75, 'Logan View', '', '', 'Nate Larsen', '', '', '', '', '', '', ''),
(76, 'South Platte', '', '', '', '', '', 'No local facility to run event.', '', '', '', ''),
(77, 'Raymond Central', 'y', '', 'Greg Wilmes', '', '', '', '', '', '', ''),
(78, 'Thedford', '', '', 'Jim York -- Athletic Director', '', '', 'This would be hosted at Thedford Golf Course.', '', '', '', ''),
(79, 'Southwest', '', '', '', '', '', '', '', '', '', ''),
(80, 'Bayard', 'y', '', 'Tammy Tilman', '', '', 'Our course has been mapped using GPS and we have many experienced workers with cross country.  We have a beautiful golf course to run on that is extremely helpful with the meets. District cross country was a positive experience for us two years ago and last year..', '', '', '', ''),
(81, 'Test''s School', '', '', 'Ann Gaffigan', '', '', 'Comments - just a test.', '', '', '', ''),
(82, '', '', '', '', '', '', '', '', '', '', ''),
(83, 'Alliance', '', '', 'Troy Unzicker', '', '', 'run at Skyview Golf course  nice course with variance in terrain', '', '', '', ''),
(84, 'Adams Central', '', '', 'Alan Frank', '', '', 'We hosted this in the Fall of 2015 and have a nice flat course to run on.', '', '', '', ''),
(85, 'Aurora', 'y', '', 'Jay Staehr', '', '', '', '', '', '', ''),
(86, 'Lincoln High', '', '', 'Pat Gatzemeyer', '', '', '', '', '', '', ''),
(87, 'Dorchester', '', '', '', '', '', '', '', '', '', ''),
(88, 'Nebraska Lutheran', '', '', '', '', '', '', '', '', '', ''),
(89, 'Hastings St. Cecilia', '', '', 'Spencer Zysset and Randy Ahrens', '', '', 'The site will be Brickyard Park in Hastings. ', '', '', '', ''),
(90, 'Elba', '', '', '', '', '', 'Unavailable ', '', '', '', ''),
(91, 'Burwell', '', '', '', '', '', '', '', '', '', ''),
(92, 'Tri County', '', '', 'Matthew Uher', '', '', '', '', '', '', ''),
(93, 'Madison', '', '', '', '', '', '', '', '', '', ''),
(94, 'Waverly', '', '', 'Brad McMillan', '', '', 'District will take place at Pioneer Park. Waverly hosted this event in 2015. ', '', '', '', ''),
(95, 'Hartington-Newcastle', '', '', 'Mandy Hochstein', '', '', '', '', '', '', ''),
(96, 'Minden', 'y', '', 'yes', '', '', 'Minden would like to host the Cross Country District our school would be participating in this fall.\r\n', '', '', '', ''),
(97, 'Harvard', '', '', 'Blake R. Thompson', '', '', 'We currently have a girls cross country team and are willing to hold districts for boys/girls in our area.  We have contacted Crooked Creek Golf Course in Clay Center, NE and they are willing to allow us to use their facility.  Their facility would be a central location for schools in our district and would be an applicable course.  Our district has experience in hosting other district events and works hard to go above and beyond to ensure every school and coach has received top notch care.  We would be honored to host this event!', '', '', '', ''),
(98, 'Omaha Bryan', '', '', '', '', '', '', '', '', '', ''),
(99, 'Ansley', 'y', '', 'Dave Mroczek', '', '', 'We would Host in Broken Bow or Loup City', '', '', '', ''),
(100, 'Pender', 'y', '', 'Andy Welsh', '', '', '', '', '', '', ''),
(101, 'Grand Island Central Catholic', '', '', '', '', '', '', '', '', '', ''),
(102, 'Elkhorn', 'n', '', '', '', '', '', '', '', '', ''),
(103, 'Wahoo', 'y', '', 'Marc Kaminski', '', '', '', '', '', '', ''),
(104, 'Twin River', '', '', '', '', '', '', '', '', '', ''),
(105, 'Verdigre', '', '', '', '', '', '', '', '', '', ''),
(106, 'Lourdes Central Catholic', '', '', '', '', '', '', '', '', '', ''),
(107, 'Yutan', 'y', '', 'Doug Veik', '', '', 'Meet would be held at Walnut Grove Park in Omaha, NE', '', '', '', ''),
(108, 'Lyons-Decatur Northeast', '', '', '', '', '', '', '', '', '', ''),
(109, 'Osmond', '', '', '', '', '', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hostapp_ubo`
--
ALTER TABLE `hostapp_ubo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school` (`school`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hostapp_ubo`
--
ALTER TABLE `hostapp_ubo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=110;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
