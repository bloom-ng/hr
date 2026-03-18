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
}

