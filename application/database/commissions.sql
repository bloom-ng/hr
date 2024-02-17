SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `commissions` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `client` text,
  `total` bigint(20) NOT NULL DEFAULT '0',
  `commission` bigint(20) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `comments` longtext,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;