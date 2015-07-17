<?php
$prefix = "keyper_";

$schema = array (
	"user" => array (
		"entity" => "Model\Entity\User",
		"columns" => array (
			array (
				"first_name" 
			),
			array (
				"last_name" 
			),
			array (
				"username" 
			),
			array (
				"password" 
			),
			array (
				"mobile" 
			),
			array (
				"telephone" 
			),
			array (
				"dated" 
			),
			array (
				"status" 
			) 
		) 
	),
	"locker" => array (
		"entity" => "Model\Entity\Locker",
		"columns" => array (
			array (
				"name" 
			),
			array (
				"description" 
			),
			array (
				"dated" 
			),
			array (
				"status" 
			) 
		) 
	),
	"box" => array (
		"entity" => "Model\Entity\Box",
		"columns" => array (
			array (
				"name" 
			),
			array (
				"status" 
			),
			array (
				"dated" 
			) 
		) 
	),
	"data" => array (
		"entity" => "Model\Entity\Data",
		"columns" => array (
			array (
				"name" 
			),
			array (
				"value" 
			),
			array (
				"notes" 
			),
			array (
				"status" 
			),
			array (
				"dated" 
			) 
		) 
	),
	"file" => array (
		"entity" => "Model\Entity\File",
		"columns" => array (
			array (
				"client_name" 
			),
			array (
				"server_name" 
			),
			array (
				"mime_type" 
			),
			array (
				"web_path" 
			),
			array (
				"physical_path" 
			),
			array (
				"status" 
			),
			array (
				"dated" 
			) 
		) 
	),
	"user_locker" => array (
		"entity" => "Model\Entity\UserLocker",
		"associate" => array (
			"user" => "user_id",
			"locker" => "locker_id" 
		),
		"columns" => array (
			array (
				"user_id",
				"data_type" => array (
					"int",
					"11" 
				) 
			),
			array (
				"locker_id",
				"data_type" => array (
					"int",
					"11" 
				) 
			) 
		) 
	),
	"locker_box" => array (
		"entity" => "Model\Entity\LockerBox",
		"associate" => array (
			"locker" => "locker_id",
			"box" => "box_id" 
		),
		"columns" => array (
			array (
				"locker_id",
				"data_type" => array (
					"int",
					"11" 
				) 
			),
			array (
				"box_id",
				"data_type" => array (
					"int",
					"11" 
				) 
			) 
		) 
	),
	"box_data" => array (
		"entity" => "Model\Entity\BoxData",
		"associate" => array (
			"box" => "box_id",
			"data" => "data_id" 
		),
		"columns" => array (
			array (
				"box_id",
				"data_type" => array (
					"int",
					"11" 
				) 
			),
			array (
				"data_id",
				"data_type" => array (
					"int",
					"11" 
				) 
			) 
		) 
	),
	"box_file" => array (
		"entity" => "Model\Entity\BoxFile",
		"associate" => array (
			"box" => "box_id",
			"file" => "file_id" 
		),
		"columns" => array (
			array (
				"box_id",
				"data_type" => array (
					"int",
					"11" 
				) 
			),
			array (
				"file_id",
				"data_type" => array (
					"int",
					"11" 
				) 
			) 
		) 
	) 
);

