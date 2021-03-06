<?php
namespace nw3\app\api;

use nw3\app\model\Detail;
/**
 * All temperature stats n stuff
 */
class Temperature extends Datadetail {

	function __construct() {
		parent::__construct([
			'tmin',
			'tmax',
			'tmean',
		]);
	}

	public function recent_values() {
		\nw3\app\model\Climate::g()->load();
		return $this->get_recent_values(
			array_merge($this->default_vars, ['afhrs'])
		);
	}

	public function ranks_daily_curr_month() {
		return $this->get_ranks([
			self::ALL => [
				[Detail::MONTHLY, Detail::RECORD_M, MINMAX],
			]
		]);
	}

	public function past_yr_monthly_aggs() {
		return $this->get_past_yr_month_aggs([
			'tmin' => [MEAN, DAYS],
			'tmax' => [MEAN, DAYS],
			'tmean' => [MEAN],
		]);
	}
}
?>
