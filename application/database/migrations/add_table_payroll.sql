CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `period` varchar(255) DEFAULT NULL,
  `salary` DECIMAL(10,2) DEFAULT 0,
  `housing` DECIMAL(10,2) DEFAULT 0,
  `transport` DECIMAL(10,2) DEFAULT 0,
  `utility` DECIMAL(10,2) DEFAULT 0,
  `wardrobe` DECIMAL(10,2) DEFAULT 0,
  `medical` DECIMAL(10,2) DEFAULT 0,
  `meal_subsidy` DECIMAL(10,2) DEFAULT 0,
  `addition_advance_salary` DECIMAL(10,2) DEFAULT 0,
  `addition_loans` DECIMAL(10,2) DEFAULT 0,
  `addition_commission` DECIMAL(10,2) DEFAULT 0,
  `addition_others` DECIMAL(10,2) DEFAULT 0,
  `deduction_advance_salary` DECIMAL(10,2) DEFAULT 0,
  `deduction_loans` DECIMAL(10,2) DEFAULT 0,
  `deduction_commission` DECIMAL(10,2) DEFAULT 0,
  `deduction_others` DECIMAL(10,2) DEFAULT 0,
  `date` date NOT NULL,
  `remark` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;