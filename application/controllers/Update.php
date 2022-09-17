<?php

class Update extends My_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('Update_model', 'update');
	}

	function index() {
		$updates = [
			[
				'id' => 1,
				'query' => 'ALTER TABLE `user_sessions` CHANGE `id` `id` varchar(128) NOT NULL;',
				'description' => 'CI upgrade from 3.1.1 to 3.1.2',
			],
			[
				'id' => 2,
				'query' => 'ALTER TABLE `tour` ADD `status` BOOLEAN NOT NULL DEFAULT TRUE AFTER `id`;',
				'description' => 'Add status field to tour table',
			],
		];
		$this->update->run_updates($updates);
		redirect('person');
	}

}
