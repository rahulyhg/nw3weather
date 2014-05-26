<?php
namespace nw3\app\controller;

use nw3\app\core;
use nw3\app\model\Current;

/**
 * Data Detail (rain, temp, hum etc.)
 *
 * @author Ben LR
 */
class Datadetail extends core\Controller {

	public function __construct($path) {
		parent::__construct(__CLASS__, $path);
	}

	public function index() {
		$this->rain();
	}

	public function rain() {
		$this->build('Rain Detail');
		$this->render();
	}

}

?>
