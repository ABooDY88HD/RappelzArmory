CREATE TABLE IF NOT EXISTS `logging` (
`ID` int(11) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  `source` varchar(90) NOT NULL,
  `level` int(1) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `logging`
 ADD PRIMARY KEY (`ID`);

ALTER TABLE `logging`
MODIFY `ID` int(11) unsigned NOT NULL AUTO_INCREMENT;

