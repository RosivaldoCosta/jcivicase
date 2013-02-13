INSERT INTO `civicrm_component` (`name`, `namespace`) VALUES ('CiviFormAutosave', 'CRM_iFormAutosave');
CREATE TABLE `form_autosave_data` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`data` TEXT NULL,
	`url` TEXT NULL,
	`time` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT