--
-- Table structure for table `leave_tbl`
--

CREATE TABLE `leave_tbl` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `leave_reason` varchar(90) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `leave_from` date NOT NULL,
  `leave_to` date NOT NULL,
  `updated_on` date NOT NULL,
  `applied_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leave_tbl`
--

INSERT INTO `leave_tbl` (`id`, `staff_id`, `leave_reason`, `description`, `status`, `leave_from`, `leave_to`, `updated_on`, `applied_on`) VALUES
(2, 5, 'Casual Leave', 'been working for full hours since last month, got to free my mind for few days', 1, '2021-05-28', '2021-05-29', '0000-00-00', '2021-05-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leave_tbl`
--
ALTER TABLE `leave_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leave_tbl`
--
ALTER TABLE `leave_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;