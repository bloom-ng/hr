<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Department performance summaries (quarterly + yearly) computed from final-approved appraisals.
 *
 * HRM/Super see every department. A head of department sees only the department(s)
 * they head, i.e. their own subordinates.
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

	/** @var bool TRUE for hrm/super (unscoped), FALSE for a HOD. */
	private $is_admin_viewer = FALSE;
	/** @var array<int,int>|NULL Department ids the viewer may see; NULL means all. */
	private $allowed_department_ids = NULL;

	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . 'login');
		}

		$this->load->model('Performance_model');
		$this->load->model('Department_model');
		$this->load->model('Staff_model');
		$this->load->helper('url');

		$role = $this->session->userdata('role');

		if (in_array($role, ['hrm', 'super'])) {
			$this->is_admin_viewer = TRUE;
			$this->allowed_department_ids = NULL; // no scoping
			return;
		}

		// Heads of department get a scoped view of their own department(s).
		$departments = $this->_headed_departments();
		if (!empty($departments)) {
			$this->allowed_department_ids = array_map(function ($d) {
				return (int) $d['id'];
			}, $departments);
			return;
		}

		$this->session->set_flashdata('error', 'Access denied.');
		redirect('/');
	}

	/**
	 * Staff record of the logged-in user (session only carries staff_id for role=staff).
	 */
	private function _current_staff() {
		$staffId = $this->session->userdata('staff_id');
		if (!empty($staffId)) {
			$staff = $this->Staff_model->getWhere(['id' => $staffId]);
			return empty($staff) ? NULL : $staff[0];
		}

		$staff = $this->Staff_model->getWhere(['user_id' => $this->session->userdata('userid')]);
		return empty($staff) ? NULL : $staff[0];
	}

	/**
	 * Departments the logged-in user heads (empty when they head none).
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private function _headed_departments() {
		$staff = $this->_current_staff();
		if (empty($staff)) return [];

		return $this->Department_model->select_departments_by_head((int) $staff['id']);
	}

	/**
	 * Block a HOD from reading a department they do not head.
	 */
	private function _guard_department($departmentId) {
		if ($this->allowed_department_ids === NULL) return;
		if (in_array((int) $departmentId, $this->allowed_department_ids, TRUE)) return;

		$this->session->set_flashdata('error', 'You can only view performance for your own department.');
		redirect('performance/departments');
	}

	/**
	 * /performance/manage/{year}/{quarter?}
	 */
	public function manage($year = NULL, $quarter = NULL) {
		$year = $year === NULL ? (int) date('Y') : (int) $year;
		$quarter = $quarter === NULL ? NULL : (int) $quarter;

		if ($year <= 0) $year = (int) date('Y');
		if ($quarter !== NULL && ($quarter < 1 || $quarter > 4)) $quarter = NULL;

		$quarterSummaries = $this->Performance_model->getDepartmentQuarterlyPerformance($year, $quarter, $this->allowed_department_ids);
		$yearSummaries = $this->Performance_model->getDepartmentYearlyPerformance($year, $this->allowed_department_ids);

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
			'is_admin_viewer' => $this->is_admin_viewer,
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

		$this->_guard_department($departmentId);

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

		$this->_guard_department($departmentId);

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

		$this->_guard_department($departmentId);

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

		$this->_guard_department($departmentId);

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
	 * A HOD sees only the department(s) they head.
	 */
	public function departments($year = NULL) {
		$year = $year === NULL ? (int) date('Y') : (int) $year;
		if ($year <= 0) $year = (int) date('Y');

		if ($this->allowed_department_ids === NULL) {
			$departments = $this->Department_model->select_departments();
		} else {
			$departments = $this->_headed_departments();
		}

		$data = [
			'year' => $year,
			'departments' => empty($departments) ? [] : $departments,
			'is_admin_viewer' => $this->is_admin_viewer,
		];

		$this->load->view('admin/header');
		$this->load->view('admin/performance_departments', $data);
		$this->load->view('admin/footer');
	}
}

