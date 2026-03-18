<?php

define('BASEPATH', __DIR__ . '/../application/');

require_once __DIR__ . '/../system/core/Model.php';
require_once __DIR__ . '/../application/models/Performance_model.php';

use PHPUnit\Framework\TestCase;

final class Performance_model_test extends TestCase {
	private function newModel() {
		return new Performance_model();
	}

	public function testDeriveYearAndQuarter() {
		$model = $this->newModel();

		$meta = $model->deriveYearAndQuarterFromMonthUnderReview('2026-01');
		$this->assertNotNull($meta);
		$this->assertSame(2026, $meta['year']);
		$this->assertSame(1, $meta['quarter']);

		$meta = $model->deriveYearAndQuarterFromMonthUnderReview('2026-04');
		$this->assertNotNull($meta);
		$this->assertSame(2, $meta['quarter']);

		$meta = $model->deriveYearAndQuarterFromMonthUnderReview('2026-10');
		$this->assertNotNull($meta);
		$this->assertSame(4, $meta['quarter']);

		$this->assertNull($model->deriveYearAndQuarterFromMonthUnderReview('invalid'));
	}

	public function testRatingBandOutstanding() {
		$model = $this->newModel();

		$result = $model->scoreFinalAppraisalRow([
			'rating_teamwork' => 10,
			'rating_communication' => 10,
			'rating_quality' => 10,
			'rating_timeliness' => 10,
			'rating_innovation' => 10,
			'rating_professionalism' => 10,
			'completion_rate' => 100,
			'accuracy_rate' => 'Excellent',
			'avg_kpa_rating' => 10,
		]);

		$this->assertSame('Outstanding', $result['rating_band']);
		$this->assertGreaterThanOrEqual(90, $result['score']);
	}

	public function testRatingBandPoor() {
		$model = $this->newModel();

		$result = $model->scoreFinalAppraisalRow([
			'rating_teamwork' => 1,
			'rating_communication' => 1,
			'rating_quality' => 1,
			'rating_timeliness' => 1,
			'rating_innovation' => 1,
			'rating_professionalism' => 1,
			'completion_rate' => 0,
			'accuracy_rate' => 'Needs Improvement',
			'avg_kpa_rating' => 1,
		]);

		$this->assertSame('Poor', $result['rating_band']);
		$this->assertLessThan(60, $result['score']);
	}

	public function testRatingBandExcellent() {
		$model = $this->newModel();

		// Intentionally crafted to be in the 80..89 band.
		$result = $model->scoreFinalAppraisalRow([
			'rating_teamwork' => 8,
			'rating_communication' => 9,
			'rating_quality' => 9,
			'rating_timeliness' => 9,
			'rating_innovation' => 8,
			'rating_professionalism' => 8,
			'completion_rate' => 100,
			'accuracy_rate' => 'Good',
			'avg_kpa_rating' => 8,
		]);

		$this->assertSame('Excellent', $result['rating_band']);
		$this->assertGreaterThanOrEqual(80, $result['score']);
		$this->assertLessThan(90, $result['score']);
	}

	public function testMissingCompletionAndUsesAccuracyOnly() {
		$model = $this->newModel();

		$result = $model->scoreFinalAppraisalRow([
			'rating_teamwork' => 8,
			'rating_communication' => 8,
			'rating_quality' => 8,
			'rating_timeliness' => 8,
			'rating_innovation' => 8,
			'rating_professionalism' => 8,
			// completion_rate is missing/invalid; Learning & Growth falls back to accuracy only.
			'completion_rate' => '',
			'accuracy_rate' => 'Good',
			// Missing KPA rating: job performance uses only communication/quality/timeliness.
		]);

		$this->assertSame('Good', $result['rating_band']);
	}
}

