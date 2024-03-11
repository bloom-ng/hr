CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `period` varchar(255) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `date` date NOT NULL,
  `comments` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;