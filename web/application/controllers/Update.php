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
			[
				'id' => 3,
				'query' => 'DELETE FROM `tour` WHERE `id` = 48;',
				'description' => 'Delete tour 48',
			],
			[
				'id' => 4,
				'query' => 'DELETE FROM `payer` WHERE `tour_id` = 48;',
				'description' => 'Delete payers for tour 48',
			],
			[
				'id' => 5,
				'query' => 'DELETE FROM `payment` WHERE `tour_id` = 48;',
				'description' => 'Delete payments for tour 48',
			],
			[
				'id' => 6,
				'query' => 'DELETE FROM `tourist` WHERE `tour_id` = 48;',
				'description' => 'Delete tourists for tour 48',
			],
			[
				'id' => 7,
				'query' => 'DELETE FROM `letter` WHERE `tour_id` = 48;',
				'description' => 'Delete letters for tour 48',
			],
			[
				'id' => 8,
				'query' => 'DELETE FROM `hotel` WHERE `tour_id` = 48;',
				'description' => 'Delete hotels for tour 48',
			]

		];
		$this->update->run_updates($updates);
		redirect('person');
	}

}
