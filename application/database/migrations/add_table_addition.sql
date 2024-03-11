CREATE TABLE `addition` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `default` DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `addition`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `addition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;