CREATE TABLE `withholding` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `default` DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;