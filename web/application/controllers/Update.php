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
			],
			[
				'id' => 9,
				'query' => 'ALTER TABLE `payer` ADD `surcharge` INT NULL DEFAULT 0 AFTER `discount`;',
				'description' => 'Add surcharge field to payer table',
			],
			[
				'id' => 10,
				'query' => "UPDATE `payer` SET `discount` = '' , surcharge= 100 WHERE `payer`.`payer_id` = 36 AND `payer`.`tour_id` = 38;",
				'description' => "Set discount to 0 and surcharge to 100 for payer 36 on tour 38",
			],
			[
				'id' => 11,
				'query' => "UPDATE `payer` SET `discount` = '', `surcharge` = '60' WHERE `payer`.`payer_id` = 478 AND `payer`.`tour_id` = 29;",
				'description' => "Set discount to 0 and surcharge to 100 for payer 478 on tour 29",
			],
			[
				'id' => 12,
				'query' => "UPDATE `payer` SET `discount` = '', `surcharge` = '75' WHERE `payer`.`payer_id` = 624 AND `payer`.`tour_id` = 33;",
				'description' => "Set discount to 0 and surcharge to 100 for payer 624 on tour 33",
			],
			[
				'id' => 13,
				'query' => "UPDATE `payer` SET `discount` = '', `surcharge` = '120' WHERE `payer`.`payer_id` = 715 AND `payer`.`tour_id` = 38;",
				'description' => "Set discount to 0 and surcharge to 100 for payer 715 on tour 38",
			],
			[
				'id' => 14,
				'query' => "UPDATE payer set discount = NULL WHERE discount is not null and discount = '' ORDER BY `discount` DESC;",
				'description' => "Set discount to NULL for payers with empty discount",
			],
			[
				'id' => 15,
				'query' => "ALTER TABLE `payer` CHANGE `discount` `discount` INT NULL DEFAULT 0;",
				'description' => "Change discount field to INT NULL DEFAULT NULL",
			]
		];
		$this->update->run_updates($updates);
		redirect('person');
	}

}
