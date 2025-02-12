<?php

namespace Cool_FormKit\Modules\Forms\Registrars;

use Cool_FormKit\Modules\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Basic form fields registration manager.
 */
class Form_Fields_Registrar extends Registrar {

	/**
	 * Form_Fields_Registrar constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->init();
	}

	/**
	 * Initialize the default fields.
	 *
	 * @return void
	 */
	public function init() {
		$this->register( new Fields\Tel() );
		$this->register( new Fields\Acceptance() );
	}
}
