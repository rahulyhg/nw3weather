<?php
namespace nw3\app\vari;

use nw3\app\core\Db;
use nw3\app\util\Date;
use nw3\app\model\Store;
use nw3\app\model\Variable;

/**
 * All rain stats n stuff
 */
class Rain extends Live {

	const AGG = 'total';

	const MAX_DRY_QUANTITY = 0.1; // Allow up to (inclusive) this much rain to still count as a dry day

	private $cached_spells;

	public $no_daily_anom = true;

	function __construct() {
		$this->days_filter = '> '.self::MAX_DRY_QUANTITY;
		parent::__construct('rain');
	}

	public function live() {
		$currspell = $this->curr_spell();
		$currspell_type = ($this->now->rain > 0) ? 'Wet' : 'Dry';
		return [$this->main_live(), [
				'descrip' => 'Rain Duration',
				'val' => $this->now->hr24->rnduration,
				'type' => Variable::Hours
			], [
				'descrip' => 'Past 24hrs Duration',
				'val' => $this->now->hr24->wethrs,
				'type' => Variable::Hours
			], [
				'descrip' => 'Most Recent Rain',
				'val' => $this->now->hr24->rnlast,
				'type' => Variable::Laststamp
			], [
				'descrip' => "Consecutive $currspell_type Days",
				'val' => $currspell,
				'type' => Variable::Days
			],
		];
	}

	public function spells($mmm) {
		$all_spells = $this->all_spells();
		$data = [];
		foreach (self::get_periods('has_spell') as $period) {
			$data[$period] = $this->get_longest_spell_for_period($all_spells[$mmm],
				$this->get_spell_filter($period));
		}
		return $data;
	}

	/**
	 * If rained today, the number of days it has rained consecutively,
	 * else the number of complete days since it last rained
	 */
	public function curr_spell() {
		$rained_today = $this->now->rain > 0;
		$cond = $rained_today ? '<=' : '>';
		$dt = date(Db::DATE_FORMAT, D_now);
		$dc = self::MAX_DRY_QUANTITY;
		return $this->db->query("DATEDIFF('$dt', d)")
			->filter("rain $cond $dc")
			->extreme(MAX, 'd')
			->scalar()
		;
	}

	public function means($periods=null) {
		$data = [];
		if(is_null($periods)) $periods = self::get_periods('multi');
		foreach ($periods as $period) {
			$data[$period] = $this->period_agg($period);
		}
		$data[self::NOWMON]['anom_f'] = $this->get_period_end_anom($data, self::NOWMON);
		$data[self::NOWYR]['anom_f'] = $this->get_period_end_anom($data, self::NOWYR);
		$data[self::NOWSEAS]['anom_f'] = $this->get_period_end_anom($data, self::NOWSEAS);
		return $data;
	}

	public function extreme_days_monthly() {
		$data = ['max' => [], 'min' => []];
		foreach (self::get_periods('month_recs') as $period) {
			$cnt_max = $this->period_count_month_extreme($period, Db::MAX, $this->days_filter);
			$cnt_min = $this->period_count_month_extreme($period, Db::MIN, $this->days_filter);
			$data['max'][$period] = ['val' => $cnt_max['val'], 'dt' => $cnt_max['d']];
			$data['min'][$period] = ['val' => $cnt_min['val'], 'dt' => $cnt_min['d']];
		}
		return $data;
	}
	public function extreme_days_yearly() {
		$data = ['max' => [], 'min' => []];
		$period = self::RECORD;
		$cnt_max = $this->period_count_year_extreme(Db::MAX, $this->days_filter);
		$cnt_min = $this->period_count_year_extreme(Db::MIN, $this->days_filter);
		$data['max'][$period] = ['val' => $cnt_max['val'], 'dt' => $cnt_max['y']];
		$data['min'][$period] = ['val' => $cnt_min['val'], 'dt' => $cnt_min['y']];
		return $data;
	}

	/**
	 * Wettest and driest spells (totals over fixed-length periods)
	 * TODO - refactor so spells work
	 * @param type $rainall
	 */
//	public function extremes_ndays() {
//		$data = ['max' => [], 'min' => [], 'max_days' => [], 'min_days' => []];
//		$rainall = $this->db->query('d', $this->colname)->order(Db::ASC)->all();
//		foreach(self::$periodsn as $spell_len) {
//			$spells = $this->nday_agg_extremes($spell_len, $rainall);
//			$data['min'][$spell_len] = $spells['min'];
//			$data['max'][$spell_len] = $spells['max'];
//			$data['min_days'][$spell_len] = $spells['min_days'];
//			$data['max_days'][$spell_len] = $spells['max_days'];
//		}
//		return $data;
//	}

	public function record_24hr() {
		$data = ['max' => []];
		$now = Store::g()->change('rain', '24h');
		$data['max'] = ($now <= 54.2) ? [
			'val' => 54.2,
			'dt' => 1272805680
		] : [
			'val' => $now,
			'dt' => D_now
		];
		return $data;
	}

	/** Get all wet and dry spells */
	function all_spells() {
		if($this->cached_spells) {
			return $this->cached_spells;
		}

		$drylen = $wetlen = 0;
		$dryspells = [];
		$wetspells = [];

		$rainall = $this->db->query($this->colname, Db::timestamp('d'))->all();

		foreach ($rainall as &$db_rain) {
			$rain = $db_rain['rain'];
			$dt = (int)$db_rain['dt'];

			if ($rain <= self::MAX_DRY_QUANTITY) {
				$drylen++;
				# End of wet spell
				if($wetlen > 0) {
					$wetspells[] = ['val' => $wetlen, 'dt' => $dt];
					$wetlen = 0;
				}
			} else {
				$wetlen++;
				# End of dry spell
				if($drylen > 0) {
					$dryspells[] = ['val' => $drylen, 'dt' => $dt];
					$drylen = 0;
				}
			}
		}
		# Handle last day (ongoing spell)
		if($drylen > 0) {
			$dryspells[] = ['val' => $drylen, 'dt' => $dt];
		} else {
			$wetspells[] = ['val' => $wetlen, 'dt' => $dt];
		}

		$this->cached_spells = [
			SPELL_INV => $dryspells,
			SPELL => $wetspells
		];
		return $this->cached_spells;
	}

	/**
	 * Filter function for longest wet and dry spells for given period
	 * A spell counts if its midpoint falls within the period
	 */
	private function get_spell_filter($period) {
		if(in_array($period, self::$periodsn)) {
			return function($spell) use (&$period) {
				return ($spell['dt'] - $spell['val'] * Date::secs_DAY / 2) > Date::mkday(D_day - $period);
			};
		}
		# Named period
		switch($period) {
			case self::RECORD:
				return function($spell) {
					return true;
				};
			case self::NOWMON:
				return function($spell) {
					return $spell['dt'] - $spell['val'] * Date::secs_DAY / 2 > Date::mkday(1);
				};
			case self::NOWYR:
				return function($spell) {
					return $spell['dt'] - $spell['val'] * Date::secs_DAY / 2 > Date::mkdate(1, 1);
				};
			case self::NOWSEAS:
				return function($spell) {
					return $spell['dt'] - $spell['val'] * Date::secs_DAY / 2 > Date::mkdate(Date::get_current_season_start_month(), 1);
				};
			case self::RECORD_M:
				return function($spell) {
					return date('Yn', $spell['dt'] - $spell['val'] * Date::secs_DAY / 2) == (D_year .''. D_month);
				};
		}
		throw new \Exception("Invalid period '$period' specified");
	}

	private function nday_agg_extremes($n, &$rainall) {
		# Totals
		$driest = INT_MAX;
		$wettest = 0;
		$cumrn = 0.0001;
		# Days
		$days_fewest = INT_MAX;
		$days_most = 0;
		$cumdays = 0;

		foreach ($rainall as $i => $rainpt) {
			$dt = $rainpt['d'];
			$cumrn += $rainpt['rain'];
			$cumdays += ((float)$rainpt['rain'] > $this->days_filter);
			if ($i >= $n) {
				$cumrn -= $rainall[$i - $n]['rain'];
				$cumdays -= ((float)$rainall[$i - $n]['rain'] > $this->days_filter);
				if ($cumrn < $driest) {
					$driest = $cumrn;
					$driest_end = $dt;
				}
				if ($cumdays < $days_fewest) {
					$days_fewest = $cumdays;
					$least_end = $dt;
				}
			}
			# Wet spells don't require accumulation of [n] days
			if ($cumrn > $wettest) {
				$wettest = $cumrn;
				$wettest_end = $dt;
			}
			if ($cumdays > $days_most) {
				$days_most = $cumdays;
				$most_end = $dt;
			}
		}
		return [
			'min' => ['val' => $driest, 'dt' => $driest_end],
			'max' => ['val' => $wettest, 'dt' => $wettest_end],
			'min_days' => ['val' => $days_fewest, 'dt' => $least_end],
			'max_days' => ['val' => $days_most, 'dt' => $most_end]
		];
	}

	private function get_longest_spell_for_period(&$all_spells, $fn_is_in_period) {
		$max = 0;
		foreach ($all_spells as $spell) {
			if ($spell['val'] > $max && $fn_is_in_period($spell)) {
				$max = $spell['val'];
				$longest = $spell;
			}
		}
		return $longest;
	}
}

?>
