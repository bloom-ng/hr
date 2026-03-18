<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Department performance summaries (quarterly + yearly) computed from final-approved appraisals.
 *
 * @property $session
 * @property $Performance_model
 */
class Performance extends CI_Controller {
	/** @var mixed */
	public $session;
	/** @var Performance_model */
	public $Performance_model;
	/** @var Department_model */
	public $Department_model;
	/** @var Staff_model */
	public $Staff_model;

	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . 'login');
		}

		// MVP is positioned as an admin/HR report.
		$role = $this->session->userdata('role');
		if (!in_array($role, ['hrm', 'super'])) {
			$this->session->set_flashdata('error', 'Access denied.');
			redirect('/');
		}

		$this->load->model('Performance_model');
		$this->load->helper('url');
	}

	/**
	 * /performance/manage/{year}/{quarter?}
	 */
	public function manage($year = NULL, $quarter = NULL) {
		$year = $year === NULL ? (int) date('Y') : (int) $year;
		$quarter = $quarter === NULL ? NULL : (int) $quarter;

		if ($year <= 0) $year = (int) date('Y');
		if ($quarter !== NULL && ($quarter < 1 || $quarter > 4)) $quarter = NULL;

		$quarterSummaries = $this->Performance_model->getDepartmentQuarterlyPerformance($year, $quarter);
		$yearSummaries = $this->Performance_model->getDepartmentYearlyPerformance($year);

		$quartersMap = [];
		foreach ($quarterSummaries as $row) {
			$q = (int) $row['quarter'];
			$quartersMap[$q] = $quartersMap[$q] ?? [];
			$quartersMap[$q][] = $row;
		}

		$data = [
			'year' => $year,
			'quarter_filter' => $quarter,
			'quarters_map' => $quartersMap,
			'quarter_summaries' => $quarterSummaries,
			'year_summaries' => $yearSummaries,
		];

		$this->load->view('admin/header');
		$this->load->view('admin/performance_summary', $data);
		$this->load->view('admin/footer');
	}

	/**
	 * /performance/department/{departmentId}/{year}
	 */
	public function department_year($departmentId = NULL, $year = NULL) {
		$departmentId = (int) $departmentId;
		$year = $year === NULL ? (int) date('Y') : (int) $year;
		if ($departmentId <= 0) {
			$this->session->set_flashdata('error', 'Invalid department.');
			redirect('performance');
		}

		$this->load->model('Department_model');
		$this->load->model('Staff_model');

		$departmentName = $this->Department_model->get_department_name($departmentId);
		$staffSummaries = $this->Performance_model->getDepartmentStaffYearlyPerformance($year, $departmentId);

		$data = [
			'year' => $year,
			'quarter' => NULL,
			'department_id' => $departmentId,
			'department_name' => $departmentName,
			'staff_summaries' => $staffSummaries,
		];

		$this->load->view('admin/header');
		$this->load->view('admin/performance_department', $data);
		$this->load->view('admin/footer');
	}

	/**
	 * /performance/department/{departmentId}/{year}/{quarter}
	 */
	public function department_quarter($departmentId = NULL, $year = NULL, $quarter = NULL) {
		$departmentId = (int) $departmentId;
		$year = $year === NULL ? (int) date('Y') : (int) $year;
		$quarter = $quarter === NULL ? NULL : (int) $quarter;
		if ($departmentId <= 0 || $quarter === NULL || $quarter < 1 || $quarter > 4) {
			$this->session->set_flashdata('error', 'Invalid department/quarter.');
			redirect('performance');
		}

		$this->load->model('Department_model');
		$this->load->model('Staff_model');

		$departmentName = $this->Department_model->get_department_name($departmentId);
		$staffSummaries = $this->Performance_model->getDepartmentStaffQuarterlyPerformance($year, $quarter, $departmentId);

		$data = [
			'year' => $year,
			'quarter' => (int) $quarter,
			'department_id' => $departmentId,
			'department_name' => $departmentName,
			'staff_summaries' => $staffSummaries,
		];

		$this->load->view('admin/header');
		$this->load->view('admin/performance_department', $data);
		$this->load->view('admin/footer');
	}

	/**
	 * /performance/department/{departmentId}/{year}/staff/{staffId}
	 */
	public function staff_detail_year($departmentId = NULL, $year = NULL, $staffId = NULL) {
		$departmentId = (int) $departmentId;
		$year = $year === NULL ? (int) date('Y') : (int) $year;
		$staffId = (int) $staffId;

		if ($departmentId <= 0 || $staffId <= 0) {
			$this->session->set_flashdata('error', 'Invalid staff/department.');
			redirect('performance');
		}

		$this->load->model('Department_model');
		$this->load->model('Staff_model');

		$detail = $this->Performance_model->getStaffPerformanceDetail($year, $departmentId, $staffId, NULL);
		if (empty($detail)) {
			$this->session->set_flashdata('error', 'No final appraisals found for this staff/year.');
			redirect('performance/department_year/' . $departmentId . '/' . $year);
		}

		$this->load->view('admin/header');
		$this->load->view('admin/performance_staff_detail', $detail);
		$this->load->view('admin/footer');
	}

	/**
	 * /performance/department/{departmentId}/{year}/{quarter}/staff/{staffId}
	 */
	public function staff_detail_quarter($departmentId = NULL, $year = NULL, $quarter = NULL, $staffId = NULL) {
		$departmentId = (int) $departmentId;
		$year = $year === NULL ? (int) date('Y') : (int) $year;
		$quarter = $quarter === NULL ? NULL : (int) $quarter;
		$staffId = (int) $staffId;

		if ($departmentId <= 0 || $staffId <= 0 || $quarter === NULL || $quarter < 1 || $quarter > 4) {
			$this->session->set_flashdata('error', 'Invalid staff/department/quarter.');
			redirect('performance');
		}

		$this->load->model('Department_model');
		$this->load->model('Staff_model');

		$detail = $this->Performance_model->getStaffPerformanceDetail($year, $departmentId, $staffId, $quarter);
		if (empty($detail)) {
			$this->session->set_flashdata('error', 'No final appraisals found for this staff/quarter.');
			redirect('performance/department_quarter/' . $departmentId . '/' . $year . '/' . $quarter);
		}

		$this->load->view('admin/header');
		$this->load->view('admin/performance_staff_detail', $detail);
		$this->load->view('admin/footer');
	}

	/**
	 * /performance/departments/{year?}
	 *
	 * Simple department index so HRM/Super can drill down to staff performance.
	 */
	public function departments($year = NULL) {
		$year = $year === NULL ? (int) date('Y') : (int) $year;
		if ($year <= 0) $year = (int) date('Y');

		$this->load->model('Department_model');

		$data = [
			'year' => $year,
			'departments' => $this->Department_model->select_departments(),
		];

		$this->load->view('admin/header');
		$this->load->view('admin/performance_departments', $data);
		$this->load->view('admin/footer');
	}
}

