<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Performance_model extends CI_Model {
	/*
	 * MVP KPI handling (based on the provided PDF + current data model):
	 * - Department-specific KPI differences are expected to come from the KPA rows that
	 *   are entered during each department's monthly appraisal (stored in appraisal_kpas).
	 * - For scoring, we currently include the average of all KPA ratings in the
	 *   "Job Performance (Core KPIs) 45%" component.
	 *
	 * Follow-up (not implemented in MVP):
	 * - If departments require different KPA selection rules or different KPI weights,
	 *   we should add a config table (e.g., department_kpi_template / kpi_weights)
	 *   and use it when computing scoreFinalAppraisalRow().
	 */
	// Document: Score bands
	const BAND_OUTSTANDING = 'Outstanding';
	const BAND_EXCELLENT = 'Excellent';
	const BAND_GOOD = 'Good';
	const BAND_FAIR = 'Fair';
	const BAND_POOR = 'Poor';

	/**
	 * Convert month-under-review (YYYY-MM) into calendar year + quarter.
	 *
	 * @return array{year:int,quarter:int,quarter_label:string}|null
	 */
	public function deriveYearAndQuarterFromMonthUnderReview($monthUnderReview) {
		if (empty($monthUnderReview) || !is_string($monthUnderReview)) return NULL;

		$dt = DateTime::createFromFormat('Y-m', $monthUnderReview);
		if (!$dt) return NULL;

		$month = (int) $dt->format('n'); // 1..12
		$year = (int) $dt->format('Y');
		$quarter = (int) floor(($month - 1) / 3) + 1; // 1..4
		$quarterLabel = 'Q' . $quarter;

		return [
			'year' => $year,
			'quarter' => $quarter,
			'quarter_label' => $quarterLabel,
		];
	}

	/**
	 * Map accuracy rate bucket to a percent for Learning & Growth proxy.
	 */
	public function mapAccuracyRateToPercent($accuracyRate) {
		if (empty($accuracyRate) || !is_string($accuracyRate)) {
			// Treat missing as "Good" (matches create view dropdown options).
			$accuracyRate = 'Good';
		}

		$key = strtolower(trim($accuracyRate));
		switch ($key) {
			case 'excellent':
				return 85.0;
			case 'good':
				return 75.0;
			case 'fair':
				return 65.0;
			case 'needs improvement':
				return 55.0;
			default:
				return 75.0;
		}
	}

	private function clampFloat($value, $min, $max) {
		$value = is_numeric($value) ? (float) $value : NULL;
		if ($value === NULL) return NULL;
		if ($value < $min) return (float) $min;
		if ($value > $max) return (float) $max;
		return $value;
	}

	private function rating1to10ToPercent($rating1to10) {
		$r = $this->clampFloat($rating1to10, 0.0, 10.0);
		if ($r === NULL) return NULL;
		return ($r / 10.0) * 100.0;
	}

	private function scoreFinalAppraisalComponents($appraisalRow) {
		$communicationPercent = $this->rating1to10ToPercent($appraisalRow['rating_communication'] ?? NULL);
		$qualityPercent = $this->rating1to10ToPercent($appraisalRow['rating_quality'] ?? NULL);
		$timelinessPercent = $this->rating1to10ToPercent($appraisalRow['rating_timeliness'] ?? NULL);
		$kpaAvgRating = $appraisalRow['avg_kpa_rating'] ?? NULL;
		$kpaAvgPercent = ($kpaAvgRating === NULL || $kpaAvgRating === '') ? NULL : $this->rating1to10ToPercent($kpaAvgRating);

		$jobComponents = [];
		if ($communicationPercent !== NULL) $jobComponents[] = $communicationPercent;
		if ($qualityPercent !== NULL) $jobComponents[] = $qualityPercent;
		if ($timelinessPercent !== NULL) $jobComponents[] = $timelinessPercent;
		if ($kpaAvgPercent !== NULL) $jobComponents[] = $kpaAvgPercent;
		$jobPerformancePercent = count($jobComponents) > 0 ? (array_sum($jobComponents) / count($jobComponents)) : 0.0;

		$teamContributionPercent = $this->rating1to10ToPercent($appraisalRow['rating_teamwork'] ?? NULL) ?? 0.0;
		$innovationPercent = $this->rating1to10ToPercent($appraisalRow['rating_innovation'] ?? NULL) ?? 0.0;
		$professionalismPercent = $this->rating1to10ToPercent($appraisalRow['rating_professionalism'] ?? NULL) ?? 0.0;

		$completionRatePercent = $this->clampFloat($appraisalRow['completion_rate'] ?? NULL, 0.0, 100.0);
		$accuracyPercent = $this->mapAccuracyRateToPercent($appraisalRow['accuracy_rate'] ?? NULL);

		$learningComponents = [];
		if ($completionRatePercent !== NULL) $learningComponents[] = $completionRatePercent;
		if ($accuracyPercent !== NULL) $learningComponents[] = (float) $accuracyPercent;
		$learningGrowthPercent = count($learningComponents) > 0 ? (array_sum($learningComponents) / count($learningComponents)) : 0.0;

		$totalScore =
			(0.45 * $jobPerformancePercent) +
			(0.15 * $teamContributionPercent) +
			(0.20 * $innovationPercent) +
			(0.10 * $professionalismPercent) +
			(0.10 * $learningGrowthPercent);

		return [
			'communication_percent' => $communicationPercent,
			'quality_percent' => $qualityPercent,
			'timeliness_percent' => $timelinessPercent,
			'kpa_avg_percent' => $kpaAvgPercent,
			'job_performance_percent' => $jobPerformancePercent,
			'team_contribution_percent' => $teamContributionPercent,
			'innovation_percent' => $innovationPercent,
			'professionalism_percent' => $professionalismPercent,
			'completion_rate_percent' => $completionRatePercent,
			'accuracy_percent' => $accuracyPercent,
			'learning_growth_percent' => $learningGrowthPercent,
			'total_score' => round($totalScore, 2),
		];
	}

	private function ratingBandFromScore($score) {
		$score = is_numeric($score) ? (float) $score : 0.0;
		if ($score >= 90.0) return self::BAND_OUTSTANDING;
		if ($score >= 80.0) return self::BAND_EXCELLENT;
		if ($score >= 70.0) return self::BAND_GOOD;
		if ($score >= 60.0) return self::BAND_FAIR;
		return self::BAND_POOR;
	}

	/**
	 * Score one final monthly appraisal into a 0..100 number + rating band.
	 *
	 * MVP mapping (from provided PDF):
	 * - Job Performance (Core KPIs): 45%
	 *   - Avg of communication, quality, timeliness, and avg KPA rating (if any).
	 * - Team Contribution: 15% (rating_teamwork)
	 * - Innovation & Initiative: 20% (rating_innovation)
	 * - Professionalism & Culture Fit: 10% (rating_professionalism)
	 * - Learning & Growth: 10% (avg(completion_rate, accuracy_rate percent))
	 *
	 * @param array $appraisalRow
	 * @return array{score:float,rating_band:string}
	 */
	public function scoreFinalAppraisalRow($appraisalRow) {
		$components = $this->scoreFinalAppraisalComponents($appraisalRow);

		return [
			'score' => $components['total_score'],
			'rating_band' => $this->ratingBandFromScore($components['total_score']),
		];
	}

	/**
	 * Like scoreFinalAppraisalRow(), but returns a breakdown for UI.
	 *
	 * @return array{score:float,rating_band:string,breakdown:array<string,mixed>}
	 */
	public function scoreFinalAppraisalRowWithBreakdown($appraisalRow) {
		$components = $this->scoreFinalAppraisalComponents($appraisalRow);

		return [
			'score' => $components['total_score'],
			'rating_band' => $this->ratingBandFromScore($components['total_score']),
			'breakdown' => [
				'communication_percent' => $components['communication_percent'],
				'quality_percent' => $components['quality_percent'],
				'timeliness_percent' => $components['timeliness_percent'],
				'kpa_avg_percent' => $components['kpa_avg_percent'],
				'job_performance_percent' => $components['job_performance_percent'],
				'team_contribution_percent' => $components['team_contribution_percent'],
				'innovation_percent' => $components['innovation_percent'],
				'professionalism_percent' => $components['professionalism_percent'],
				'completion_rate_percent' => $components['completion_rate_percent'],
				'accuracy_percent' => $components['accuracy_percent'],
				'learning_growth_percent' => $components['learning_growth_percent'],
			],
		];
	}

	/**
	 * Fetch final monthly appraisals for a given year with avg KPA rating (and KPA count).
	 *
	 * @return array<int,array>
	 */
	public function getFinalMonthlyAppraisalsForYear($year) {
		return $this->getFinalMonthlyAppraisalsForYearFiltered($year, NULL, NULL);
	}

	private function getFinalMonthlyAppraisalsForYearFiltered($year, $departmentId = NULL, $staffId = NULL) {
		$year = (int) $year;
		if ($year <= 0) return [];

		// month_under_review stored as YYYY-MM (VARCHAR).
		$this->db->select('
			a.*,
			d.department_name,
			s.staff_name,
			AVG(k.rating) as avg_kpa_rating,
			COUNT(k.rating) as kpa_rating_count
		', FALSE);
		$this->db->from('appraisals_new a');
		$this->db->join('department_tbl d', 'd.id = a.department_id', 'left');
		$this->db->join('staff_tbl s', 's.id = a.staff_id', 'left');
		$this->db->join('appraisal_kpas k', 'k.appraisal_id = a.id', 'left');
		$this->db->where('a.status', 'final');
		$this->db->like('a.month_under_review', (string) $year . '-', 'after');
		if ($departmentId !== NULL) $this->db->where('a.department_id', (int)$departmentId);
		if ($staffId !== NULL) $this->db->where('a.staff_id', (int)$staffId);

		// Group by a.id to collapse multiple kpa rows.
		$this->db->group_by('a.id, d.department_name, s.staff_name');
		$this->db->order_by('a.department_id', 'ASC');
		$this->db->order_by('a.staff_id', 'ASC');
		$this->db->order_by('a.month_under_review', 'ASC');

		return $this->db->get()->result_array();
	}

	/**
	 * Aggregate final monthly appraisals into department-quarter performance.
	 *
	 * For each staff+quarter, compute avg(score) across that staff's months.
	 * For each department+quarter, compute avg(staff-quarter avg) across that department's staff.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public function getDepartmentQuarterlyPerformance($year, $quarter = NULL) {
		$year = (int) $year;
		$quarter = $quarter === NULL ? NULL : (int) $quarter;
		if ($year <= 0) return [];

		$rows = $this->getFinalMonthlyAppraisalsForYear($year);
		if (empty($rows)) return [];

		$staffQuarterScores = []; // [deptId][staffId][quarter] => ['scores'=>[], 'department_name'=>string]

		foreach ($rows as $row) {
			$meta = $this->deriveYearAndQuarterFromMonthUnderReview($row['month_under_review'] ?? NULL);
			if ($meta === NULL) continue;
			if ($meta['year'] !== $year) continue;
			if ($quarter !== NULL && $meta['quarter'] !== $quarter) continue;

			$staffId = (int) ($row['staff_id'] ?? 0);
			$departmentId = (int) ($row['department_id'] ?? 0);
			if ($staffId <= 0 || $departmentId <= 0) continue;

			$scoreResult = $this->scoreFinalAppraisalRow($row);
			$staffQuarterScores[$departmentId][$staffId][$meta['quarter']] = $staffQuarterScores[$departmentId][$staffId][$meta['quarter']] ?? [
				'scores' => [],
				'department_name' => $row['department_name'] ?? ('Dept ' . $departmentId),
			];
			$staffQuarterScores[$departmentId][$staffId][$meta['quarter']]['scores'][] = $scoreResult['score'];
		}

		if (empty($staffQuarterScores)) return [];

		// deptQuarter => list of staff-quarter avg scores + band distribution
		$departmentQuarter = []; // [deptId][quarter] => ['scores'=>[], 'distribution'=>[band=>count], 'department_name'=>string]
		foreach ($staffQuarterScores as $departmentId => $staffMap) {
			foreach ($staffMap as $staffId => $quarterMap) {
				foreach ($quarterMap as $q => $payload) {
					if (empty($payload['scores'])) continue;
					$avg = array_sum($payload['scores']) / count($payload['scores']);
					$band = $this->ratingBandFromScore($avg);

					$departmentQuarter[$departmentId][$q] = $departmentQuarter[$departmentId][$q] ?? [
						'scores' => [],
						'distribution' => [
							self::BAND_OUTSTANDING => 0,
							self::BAND_EXCELLENT => 0,
							self::BAND_GOOD => 0,
							self::BAND_FAIR => 0,
							self::BAND_POOR => 0,
						],
						'department_name' => $payload['department_name'] ?? ('Dept ' . $departmentId),
					];
					$departmentQuarter[$departmentId][$q]['scores'][] = $avg;
					$departmentQuarter[$departmentId][$q]['distribution'][$band] += 1;
				}
			}
		}

		$out = [];
		foreach ($departmentQuarter as $departmentId => $quarterMap) {
			foreach ($quarterMap as $q => $payload) {
				$avg = empty($payload['scores']) ? 0.0 : (array_sum($payload['scores']) / count($payload['scores']));
				$out[] = [
					'year' => $year,
					'quarter' => (int) $q,
					'quarter_label' => 'Q' . (int) $q,
					'department_id' => (int) $departmentId,
					'department_name' => $payload['department_name'],
					'avg_score' => round($avg, 2),
					'rating_band' => $this->ratingBandFromScore($avg),
					'staff_count' => count($payload['scores']),
					'rating_distribution' => $payload['distribution'],
				];
			}
		}

		// Sort for stable rendering.
		usort($out, function ($a, $b) {
			if ($a['quarter'] === $b['quarter']) return strcmp($a['department_name'], $b['department_name']);
			return $a['quarter'] < $b['quarter'] ? -1 : 1;
		});

		return $out;
	}

	/**
	 * Aggregate final monthly appraisals into department-year performance.
	 *
	 * For each staff, compute avg(score) across that staff's months in the year.
	 * For each department, compute avg(staff-year avg) across that department's staff.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public function getDepartmentYearlyPerformance($year) {
		$year = (int) $year;
		if ($year <= 0) return [];

		$rows = $this->getFinalMonthlyAppraisalsForYear($year);
		if (empty($rows)) return [];

		$staffYearScores = []; // [deptId][staffId] => ['scores'=>[], 'department_name'=>string]

		foreach ($rows as $row) {
			$meta = $this->deriveYearAndQuarterFromMonthUnderReview($row['month_under_review'] ?? NULL);
			if ($meta === NULL) continue;
			if ($meta['year'] !== $year) continue;

			$staffId = (int) ($row['staff_id'] ?? 0);
			$departmentId = (int) ($row['department_id'] ?? 0);
			if ($staffId <= 0 || $departmentId <= 0) continue;

			$scoreResult = $this->scoreFinalAppraisalRow($row);
			$staffYearScores[$departmentId][$staffId] = $staffYearScores[$departmentId][$staffId] ?? [
				'scores' => [],
				'department_name' => $row['department_name'] ?? ('Dept ' . $departmentId),
			];
			$staffYearScores[$departmentId][$staffId]['scores'][] = $scoreResult['score'];
		}

		if (empty($staffYearScores)) return [];

		$departmentYear = []; // [deptId] => ['scores'=>[], 'distribution'=>[band=>count], 'department_name'=>string]
		foreach ($staffYearScores as $departmentId => $staffMap) {
			foreach ($staffMap as $staffId => $payload) {
				if (empty($payload['scores'])) continue;
				$avg = array_sum($payload['scores']) / count($payload['scores']);
				$band = $this->ratingBandFromScore($avg);

				$departmentYear[$departmentId] = $departmentYear[$departmentId] ?? [
					'scores' => [],
					'distribution' => [
						self::BAND_OUTSTANDING => 0,
						self::BAND_EXCELLENT => 0,
						self::BAND_GOOD => 0,
						self::BAND_FAIR => 0,
						self::BAND_POOR => 0,
					],
					'department_name' => $payload['department_name'] ?? ('Dept ' . $departmentId),
				];

				$departmentYear[$departmentId]['scores'][] = $avg;
				$departmentYear[$departmentId]['distribution'][$band] += 1;
			}
		}

		$out = [];
		foreach ($departmentYear as $departmentId => $payload) {
			$avg = empty($payload['scores']) ? 0.0 : (array_sum($payload['scores']) / count($payload['scores']));
			$out[] = [
				'year' => $year,
				'department_id' => (int) $departmentId,
				'department_name' => $payload['department_name'],
				'avg_score' => round($avg, 2),
				'rating_band' => $this->ratingBandFromScore($avg),
				'staff_count' => count($payload['scores']),
				'rating_distribution' => $payload['distribution'],
			];
		}

		usort($out, function ($a, $b) {
			return strcmp($a['department_name'], $b['department_name']);
		});

		return $out;
	}

	/**
	 * Get per-staff quarterly performance within a department.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public function getDepartmentStaffQuarterlyPerformance($year, $quarter, $departmentId) {
		$rows = $this->getFinalMonthlyAppraisalsForYearFiltered($year, $departmentId, NULL);
		if (empty($rows)) return [];

		$staffScores = []; // [staffId] => ['staff_name'=>..., 'scores'=>[], 'department_name'=>...]
		$quarter = (int) $quarter;

		foreach ($rows as $row) {
			$meta = $this->deriveYearAndQuarterFromMonthUnderReview($row['month_under_review'] ?? NULL);
			if ($meta === NULL) continue;
			if ($meta['quarter'] !== $quarter) continue;

			$staffId = (int) ($row['staff_id'] ?? 0);
			if ($staffId <= 0) continue;

			$scoreResult = $this->scoreFinalAppraisalRow($row);

			$staffScores[$staffId] = $staffScores[$staffId] ?? [
				'scores' => [],
				'staff_name' => $row['staff_name'] ?? ('Staff ' . $staffId),
				'department_name' => $row['department_name'] ?? ('Dept ' . (int)$departmentId),
			];
			$staffScores[$staffId]['scores'][] = $scoreResult['score'];
		}

		$out = [];
		foreach ($staffScores as $staffId => $payload) {
			if (empty($payload['scores'])) continue;
			$avg = array_sum($payload['scores']) / count($payload['scores']);
			$out[] = [
				'staff_id' => (int) $staffId,
				'staff_name' => $payload['staff_name'],
				'avg_score' => round($avg, 2),
				'rating_band' => $this->ratingBandFromScore($avg),
				'month_count' => count($payload['scores']),
				'department_name' => $payload['department_name'],
			];
		}

		usort($out, function ($a, $b) {
			return $b['avg_score'] <=> $a['avg_score'];
		});

		return $out;
	}

	/**
	 * Get per-staff yearly performance within a department.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public function getDepartmentStaffYearlyPerformance($year, $departmentId) {
		$rows = $this->getFinalMonthlyAppraisalsForYearFiltered($year, $departmentId, NULL);
		if (empty($rows)) return [];

		$staffScores = []; // [staffId] => ['staff_name'=>..., 'scores'=>[], 'department_name'=>...]

		foreach ($rows as $row) {
			$staffId = (int) ($row['staff_id'] ?? 0);
			if ($staffId <= 0) continue;

			$scoreResult = $this->scoreFinalAppraisalRow($row);

			$staffScores[$staffId] = $staffScores[$staffId] ?? [
				'scores' => [],
				'staff_name' => $row['staff_name'] ?? ('Staff ' . $staffId),
				'department_name' => $row['department_name'] ?? ('Dept ' . (int)$departmentId),
			];
			$staffScores[$staffId]['scores'][] = $scoreResult['score'];
		}

		$out = [];
		foreach ($staffScores as $staffId => $payload) {
			if (empty($payload['scores'])) continue;
			$avg = array_sum($payload['scores']) / count($payload['scores']);
			$out[] = [
				'staff_id' => (int) $staffId,
				'staff_name' => $payload['staff_name'],
				'avg_score' => round($avg, 2),
				'rating_band' => $this->ratingBandFromScore($avg),
				'month_count' => count($payload['scores']),
				'department_name' => $payload['department_name'],
			];
		}

		usort($out, function ($a, $b) {
			return $b['avg_score'] <=> $a['avg_score'];
		});

		return $out;
	}

	/**
	 * Detailed performance view for one staff member.
	 *
	 * @return array<string,mixed>
	 */
	public function getStaffPerformanceDetail($year, $departmentId, $staffId, $quarter = NULL) {
		$rows = $this->getFinalMonthlyAppraisalsForYearFiltered($year, $departmentId, $staffId);
		if (empty($rows)) return [];

		$quarter = $quarter === NULL ? NULL : (int) $quarter;
		$monthly = [];

		$departmentName = $rows[0]['department_name'] ?? ('Dept ' . (int)$departmentId);
		$staffName = $rows[0]['staff_name'] ?? ('Staff ' . (int)$staffId);

		foreach ($rows as $row) {
			if ($quarter !== NULL) {
				$meta = $this->deriveYearAndQuarterFromMonthUnderReview($row['month_under_review'] ?? NULL);
				if ($meta === NULL || $meta['quarter'] !== $quarter) continue;
			}

			$scoreBreakdown = $this->scoreFinalAppraisalRowWithBreakdown($row);

			$monthly[] = [
				'month_under_review' => $row['month_under_review'],
				'completion_rate' => $row['completion_rate'],
				'accuracy_rate' => $row['accuracy_rate'],
				'avg_kpa_rating' => $row['avg_kpa_rating'],
				'kpa_rating_count' => $row['kpa_rating_count'],
				'ratings' => [
					'teamwork' => $row['rating_teamwork'],
					'communication' => $row['rating_communication'],
					'quality' => $row['rating_quality'],
					'timeliness' => $row['rating_timeliness'],
					'innovation' => $row['rating_innovation'],
					'professionalism' => $row['rating_professionalism'],
				],
				'computed' => [
					'score' => $scoreBreakdown['score'],
					'rating_band' => $scoreBreakdown['rating_band'],
					'breakdown' => $scoreBreakdown['breakdown'],
				],
			];
		}

		if (empty($monthly)) return [];

		$sum = 0.0;
		$count = 0;
		foreach ($monthly as $m) {
			$sum += (float) $m['computed']['score'];
			$count += 1;
		}
		$avg = $count > 0 ? ($sum / $count) : 0.0;

		return [
			'year' => (int) $year,
			'quarter' => $quarter,
			'department_id' => (int) $departmentId,
			'department_name' => $departmentName,
			'staff_id' => (int) $staffId,
			'staff_name' => $staffName,
			'overall_score' => round($avg, 2),
			'overall_rating_band' => $this->ratingBandFromScore($avg),
			'monthly' => $monthly,
		];
	}
}

