CREATE TABLE `memo` (
  `id` int(11) NOT NULL,
  `title` text,
  `body` text,
  `date` date NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



ALTER TABLE `memo`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `memo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;