#
# Table structure for table #__sv_apptpro2_config
#

DROP TABLE IF EXISTS `#__sv_apptpro2_config`;
CREATE TABLE `#__sv_apptpro2_config` (
  `id_config` int(11) NOT NULL DEFAULT '0',
  `mailTO` varchar(255) NOT NULL DEFAULT '',
  `mailFROM` varchar(255) NOT NULL DEFAULT '',
  `mailSubject` varchar(80) NOT NULL DEFAULT '',
  `requireLogin` char(3) DEFAULT 'Yes',
  `headerText` text,
  `footerText` text,
  `which_calendar` varchar(20) DEFAULT NULL,
  `calendar_title` varchar(255) DEFAULT 'resource.name',
  `calendar_body` varchar(255) DEFAULT 'request.name',
  `calendar_body2` varchar(255) DEFAULT '[resource]<br>[requester name]',
  `prevent_dupe_bookings` char(3) DEFAULT 'Yes',
  `requireEmail` char(10) DEFAULT 'Yes',
  `requirePhone` char(10) DEFAULT 'No',
  `multiDay` varchar(12) DEFAULT 'Singleday',
  `allowSunday` char(3) DEFAULT 'Yes',
  `allowMonday` char(3) DEFAULT 'Yes',
  `allowTuesday` char(3) DEFAULT 'Yes',
  `allowWednesday` char(3) DEFAULT 'Yes',
  `allowThursday` char(3) DEFAULT 'Yes',
  `allowFriday` char(3) DEFAULT 'Yes',
  `allowSaturday` char(3) DEFAULT 'Yes',
  `hoursLimit` varchar(10) DEFAULT '24Hour',
  `timeRangeStart` varchar(20) DEFAULT NULL,
  `timeRangeEnd` varchar(20) DEFAULT NULL,
  `timeIncrement` varchar(20) DEFAULT 'Hour',
  `bookingIncrement` varchar(20) DEFAULT 'Range',
  `timeFormat` char(2) DEFAULT '12',
  `accept_when_paid` varchar(3) DEFAULT 'Yes',
  `enable_paypal` varchar(3) DEFAULT 'No',
  `additional_fee` decimal(10,2) DEFAULT '5.00',
  `fee_rate` varchar(20) DEFAULT 'Fixed',
  `paypal_button_url` varchar(255) DEFAULT 'https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif',
  `paypal_logo_url` varchar(255) DEFAULT '',
  `paypal_currency_code` varchar(6) DEFAULT 'CAD',
  `paypal_account` varchar(255) DEFAULT 'enter your account here',
  `paypal_sandbox_url` varchar(255) DEFAULT 'https://www.sandbox.paypal.com/us/cgi-bin/webscr',
  `paypal_use_sandbox` varchar(3) DEFAULT 'No',
  `paypal_production_url` varchar(255) DEFAULT 'https://www.paypal.com/us/cgi-bin/webscr',
  `paypal_identity_token` varchar(255) DEFAULT NULL,
  `html_email` varchar(3) DEFAULT 'Yes',
  `timeSlotMode` varchar(4) DEFAULT 'Yes',
  `booking_succeeded` text,
  `booking_succeeded_sms` varchar(160) DEFAULT '',
  `booking_in_progress` text,
  `booking_in_progress_sms` varchar(160) DEFAULT '',
  `booking_cancel` text,
  `booking_cancel_sms` varchar(160) DEFAULT '',
  `booking_too_close_to_cancel` text,
  `booking_reminder` text,
  `booking_reminder_sms` varchar(160) DEFAULT '',
  `auto_accept` varchar(4) DEFAULT 'No',
  `hide_logo` varchar(4) DEFAULT 'No',
  `allow_cancellation` varchar(4) DEFAULT 'No',
  `hours_before_cancel` varchar(4) DEFAULT '24',
  `use_div_calendar` varchar(4) DEFAULT 'Yes',
  `def_gad_grid_start` varchar(10) DEFAULT '8:00',
  `def_gad_grid_end` varchar(10) DEFAULT '17:00',
  `gad_grid_start_day` varchar(25) DEFAULT 'Tomorrow',
  `gad_grid_width` varchar(10) DEFAULT '700',
  `gad_grid_num_of_days` varchar(2) DEFAULT '7',
  `gad_name_width` varchar(10) DEFAULT '100',
  `gad_booked_image` varchar(255) DEFAULT './administrator/images/publish_x.png',
  `gad_available_image` varchar(255) DEFAULT './administrator/images/tick.png',
  `enable_clickatell` varchar(6) DEFAULT 'No',
  `clickatell_user` varchar(30) DEFAULT NULL,
  `clickatell_password` varchar(30) DEFAULT NULL,
  `clickatell_api_id` varchar(30) DEFAULT NULL,
  `clickatell_dialing_code` varchar(10) DEFAULT '1',
  `clickatell_what_to_send` varchar(20) DEFAULT 'Reminders',
  `clickatell_show_code` varchar(6) DEFAULT 'No',
  `clickatell_enable_unicode` varchar(6) DEFAULT 'No',
  `gad_grid_start_day_days` int(11) DEFAULT '1',
  `google_user` varchar(255) DEFAULT NULL,
  `google_password` varchar(255) DEFAULT NULL,
  `google_default_calendar_name` varchar(255) DEFAULT NULL,
  `name_cb_mapping` varchar(255) DEFAULT '',
  `name_read_only` varchar(6) DEFAULT 'No',
  `phone_cb_mapping` varchar(255) DEFAULT '',
  `phone_read_only` varchar(6) DEFAULT 'No',
  `email_cb_mapping` varchar(255) DEFAULT '',
  `email_read_only` varchar(6) DEFAULT 'No',
  `daylight_savings_time` varchar(6) DEFAULT 'No',
  `popup_week_start_day` int(3) DEFAULT '0',
  `enable_coupons` char(3) DEFAULT 'No',
  `show_available_seats` varchar(6) DEFAULT 'No',
  `gad_grid_hide_startend` varchar(6) DEFAULT 'No',
  `limit_bookings` int(3) DEFAULT '0',
  `limit_bookings_days` int(11) DEFAULT '1',
  `activity_logging` varchar(6) DEFAULT 'No',
  `phone_js_mapping` varchar(255) DEFAULT '',
  `paypal_itemname` varchar(126) DEFAULT '[resource]: [startdate] [starttime]',
  `paypal_on0` varchar(64) DEFAULT NULL,
  `paypal_os0` varchar(200) DEFAULT NULL,
  `paypal_on1` varchar(64) DEFAULT NULL,
  `paypal_os1` varchar(200) DEFAULT NULL,
  `paypal_on2` varchar(64) DEFAULT NULL,
  `paypal_os2` varchar(200) DEFAULT NULL,
  `paypal_on3` varchar(64) DEFAULT NULL,
  `paypal_os3` varchar(200) DEFAULT NULL,
  `allow_user_credit_refunds` varchar(6) DEFAULT 'Yes',
  `use_gad2` varchar(6) DEFAULT 'No',
  `gad2_row_height` int(6) DEFAULT '40',
  `dst_start_date` date DEFAULT NULL,
  `dst_end_date` date DEFAULT NULL,
  `adv_admin_show_resources` varchar(6) NOT NULL DEFAULT 'Yes',
  `adv_admin_show_services` varchar(6) NOT NULL DEFAULT 'Yes',
  `adv_admin_show_timeslots` varchar(6) NOT NULL DEFAULT 'Yes',
  `adv_admin_show_bookoffs` varchar(6) NOT NULL DEFAULT 'Yes',
  `adv_admin_show_paypal` varchar(6) NOT NULL DEFAULT 'Yes',
  `adv_admin_show_coupons` varchar(6) NOT NULL DEFAULT 'Yes',
  `adv_admin_show_extras` varchar(6) NOT NULL DEFAULT 'Yes',
  `attach_ics_resource` varchar(6) DEFAULT 'No',
  `attach_ics_admin` varchar(6) DEFAULT 'No',
  `attach_ics_customer` varchar(6) DEFAULT 'No',
  `purge_stale_paypal` varchar(6) DEFAULT 'No',
  `minutes_to_stale` int(11) DEFAULT '10',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_config`)
) ENGINE=MyISAM;

#
# Dumping data for table #__sv_apptpro2_config
#
LOCK TABLES `#__sv_apptpro2_config` WRITE;

INSERT INTO `#__sv_apptpro2_config` 
VALUES (1,
'your.email@goes.here.com',
'your_from.email@goes.here.com',
'Appointment Booking',
'No',
'Enter header text here - clear for no header',
'Enter footer text here - clear for no footer',
'None',
'resource.name',
'resource.name',
'',
'Yes',
'No',
'No',
'Multiday',
'Yes',
'Yes',
'Yes',
'Yes',
'Yes',
'Yes',
'Yes',
'Range',
'8',
'17',
'HalfHour',
'Range',
'12',
'Yes',
'No',
0,
'Fixed',
'https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif',
'',
'CAD',
'your paypal account here',
'https://www.sandbox.paypal.com/us/cgi-bin/webscr',
'Yes',
'https://www.paypal.com/us/cgi-bin/webscr',
'',
'Yes',
'Yes',
'<p><strong><span style=\"font-family: arial,helvetica,sans-serif;\">Thank you for your order.</span></strong></p>\r\n<p><span style=\"font-family: arial,helvetica,sans-serif;\"><strong>[resource]</strong>,  has been booked for <em>[requester name]</em> for this<em> <em>date/time</em>:</em></span><span style=\"font-family: arial,helvetica,sans-serif;\"> [startdate] [starttime] to [endtime]</span></p>\r\n<p><span style=\"font-family: arial,helvetica,sans-serif;\"><br /></span></p>',
'New Booking: [requester name] for [resource], [startdate] [starttime] to [endtime]',
'<p>Thank you, [requester name].</p>\r\n<p>Your request will be reviewed shortly.</p>',
'New Booking Request: [requester name] for [resource], [startdate] [starttime]',
'<p><span style=\"font-family: arial,helvetica,sans-serif;\">Booking Cancellation: [requester name] for [resource], [startdate] [starttime]</span></p>',
'Booking Cancellation: [requester name] for [resource], [startdate] [starttime]',
'<p>Cancellation is only accepted up to <span style=\"color: #ff0000;\">24 hours</span> before your booking.</p>',
'<p><span style=\"font-family: arial,helvetica,sans-serif;\">Just a Reminder.<br /><strong>[resource]</strong>, has been booked for [requester name] for this <em>date/time</em>:<br /> [startdate] [starttime] to [endtime]</span></p>\r\n<p><span style=\"font-family: arial,helvetica,sans-serif;\">Your Cancellation ID is <span style=\"color: red;\">[cancellation_id]</span><br /> To cancel your booking enter this code into the <span style=\"background-color: #ffff99;\">booking screen</span>.</span></p>',
'[resource] has been booked for [requester name] for: [startdate] [starttime] to [endtime]',
'Yes',
'No',
'No',
'4',
'Yes',
'8:00',
'17:00',
'Tomorrow',
'700',
'7',
'100',
'./administrator/images/publish_x.png',
'./administrator/images/tick.png',
'No',
'',
'',
'',
'1',
'Reminders',
'No',
'No',
2,
'',
'',
'',
'',
'No',
'',
'No',
'',
'No',
'Yes',
0,
'No',
'No',
'No',
0,
1,
'Off',
'',
'[resource]: [startdate] [starttime]',
'',
'',
'',
'',
'',
'',
'',
'',
'Yes',
'No',
40,
'2010-04-25',
'2010-10-10',
'Yes',
'Yes',
'Yes',
'Yes',
'Yes',
'Yes',
'Yes',
'No',
'No',
'No',
'No',
10,
0,
'',
0,
1);

UNLOCK TABLES;


#
# Table structure for table #__sv_apptpro2_errorlog
#

DROP TABLE IF EXISTS `#__sv_apptpro2_errorlog`;
CREATE TABLE `#__sv_apptpro2_errorlog` (
  `id_errorlog` int(11) NOT NULL AUTO_INCREMENT,
  `stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `sql_data` varchar(255) DEFAULT NULL,
  `err_object` varchar(252) DEFAULT NULL,
  `err_severity` varchar(25) DEFAULT NULL,
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_errorlog`)
) ENGINE=MyISAM;


#
# Table structure for table #__sv_apptpro2_paypal_transactions
#

DROP TABLE IF EXISTS `#__sv_apptpro2_paypal_transactions`;
CREATE TABLE `#__sv_apptpro2_paypal_transactions` (
  `id_paypal_transactions` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `buyer_email` varchar(127) NOT NULL DEFAULT '',
  `street` varchar(200) NOT NULL DEFAULT '',
  `city` varchar(40) NOT NULL DEFAULT '',
  `state` char(40) NOT NULL DEFAULT '',
  `zipcode` varchar(20) NOT NULL DEFAULT '',
  `memo` varchar(255) DEFAULT NULL,
  `itemname` varchar(255) DEFAULT NULL,
  `itemnumber` varchar(127) DEFAULT NULL,
  `os0` varchar(20) DEFAULT NULL,
  `on0` varchar(50) DEFAULT NULL,
  `os1` varchar(20) DEFAULT NULL,
  `on1` varchar(50) DEFAULT NULL,
  `quantity` char(3) DEFAULT NULL,
  `paymentdate` varchar(50) NOT NULL DEFAULT '',
  `paymenttype` varchar(10) NOT NULL DEFAULT '',
  `txnid` varchar(30) NOT NULL DEFAULT '',
  `mc_gross` varchar(6) NOT NULL DEFAULT '',
  `mc_fee` varchar(5) NOT NULL DEFAULT '',
  `paymentstatus` varchar(15) NOT NULL DEFAULT '',
  `pendingreason` varchar(10) DEFAULT NULL,
  `txntype` varchar(10) NOT NULL DEFAULT '',
  `tax` varchar(10) DEFAULT NULL,
  `mc_currency` varchar(5) NOT NULL DEFAULT '',
  `reasoncode` varchar(20) NOT NULL DEFAULT '',
  `custom` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `datecreation` date NOT NULL DEFAULT '0000-00-00',
  `stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_paypal_transactions`)
) ENGINE=MyISAM;


#
# Table structure for table #__sv_apptpro2_pp_currency
#

DROP TABLE IF EXISTS `#__sv_apptpro2_pp_currency`;
CREATE TABLE `#__sv_apptpro2_pp_currency` (
  `Id` int(11) NOT NULL auto_increment,
  `code` varchar(3) default NULL,
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM;

#
# Dumping data for table #__sv_apptpro2_pp_currency
#
LOCK TABLES `#__sv_apptpro2_pp_currency` WRITE;
/*!40000 ALTER TABLE `#__sv_apptpro2_pp_currency` DISABLE KEYS */;

INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (1,'AUD','Australian Dollar');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (2,'CAD','Canadian Dollar');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (3,'CHF','Swiss Franc');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (4,'CZK','Czech Koruna');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (5,'DKK','Danish Krone');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (6,'EUR','Euro');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (7,'GBP','Pound Sterling');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (8,'HKD','Hong Kong Dollar');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (9,'HUF','Hungarian Forint');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (10,'JPY','Japanese Yen');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (11,'NOK','Norwegian Krone');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (12,'NZD','New Zealand Dollar');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (13,'PLN','Polish Zloty');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (14,'SEK','Swedish Krona');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (15,'SGD','Singapore Dollar');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (16,'USD','U.S. Dollar');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (17,'ILS','Israeli New Sheqel');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (18,'MXN','Mexican Peso');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (19,'BRL','Brazilian Real');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (20,'MYR','Malaysian Ringgit');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (21,'PHP','Philippine Peso');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (22,'TWD','Taiwan New Dollar');
INSERT INTO `#__sv_apptpro2_pp_currency` VALUES (23,'THB','Thai Baht');
/*!40000 ALTER TABLE `#__sv_apptpro2_pp_currency` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table #__sv_apptpro2_requests
#

DROP TABLE IF EXISTS `#__sv_apptpro2_requests`;
CREATE TABLE `#__sv_apptpro2_requests` (
  `id_requests` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `resource` varchar(50) NOT NULL DEFAULT '',
  `startdate` date DEFAULT NULL,
  `starttime` time DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `endtime` time DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `admin_comment` varchar(255) DEFAULT NULL,
  `request_status` varchar(20) DEFAULT 'new',
  `payment_status` varchar(20) DEFAULT 'pending',
  `show_on_calendar` char(3) NOT NULL DEFAULT '1',
  `calendar_comment` varchar(200) NOT NULL DEFAULT '',
  `calendar_category` int(11) DEFAULT NULL,
  `calendar_calendar` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cancellation_id` varchar(255) DEFAULT NULL,
  `service` varchar(50) DEFAULT NULL,
  `txnid` varchar(255) DEFAULT NULL,
  `sms_reminders` varchar(10) DEFAULT 'No',
  `sms_phone` varchar(50) DEFAULT NULL,
  `sms_dial_code` varchar(4) DEFAULT '1',
  `google_event_id` varchar(255) DEFAULT '',
  `google_calendar_id` varchar(255) DEFAULT '',
  `booking_total` decimal(10,2) DEFAULT '0.00',
  `booking_due` decimal(10,2) DEFAULT '0.00',
  `coupon_code` varchar(255) DEFAULT NULL,
  `booked_seats` int(11) DEFAULT '1',
  `booking_language` varchar(25) DEFAULT 'en-gb',
  `credit_used` float(10,2) DEFAULT '0.00',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_requests`),
  KEY `startdate` (`startdate`)
) ENGINE=MyISAM;

#
# Table structure for table #__sv_apptpro2_resources
#

DROP TABLE IF EXISTS `#__sv_apptpro2_resources`;
CREATE TABLE `#__sv_apptpro2_resources` (
  `id_resources` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(80) NOT NULL DEFAULT '',
  `cost` varchar(40) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `resource_email` varchar(200) DEFAULT NULL,
  `prevent_dupe_bookings` varchar(10) DEFAULT 'Global',
  `max_dupes` int(11) DEFAULT '0',
  `resource_admins` varchar(255) DEFAULT '',
  `rate` decimal(10,2) DEFAULT '1.00',
  `rate_unit` varchar(25) DEFAULT 'Flat',
  `default_calendar_category` varchar(50) DEFAULT 'General',
  `default_calendar` varchar(50) DEFAULT 'Default',
  `allowSunday` char(3) DEFAULT 'Yes',
  `allowMonday` char(3) DEFAULT 'Yes',
  `allowTuesday` char(3) DEFAULT 'Yes',
  `allowWednesday` char(3) DEFAULT 'Yes',
  `allowThursday` char(3) DEFAULT 'Yes',
  `allowFriday` char(3) DEFAULT 'Yes',
  `allowSaturday` char(3) DEFAULT 'Yes',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `published` tinyint(3) unsigned DEFAULT '0',
  `timeslots` varchar(10) DEFAULT 'Global',
  `disable_dates_before` varchar(30) DEFAULT 'Tomorrow',
  `disable_dates_before_days` int(11) DEFAULT '0',
  `min_lead_time` smallint(5) DEFAULT '1',
  `disable_dates_after` varchar(30) DEFAULT 'Not Set',
  `disable_dates_after_days` int(11) DEFAULT '0',
  `sms_phone` varchar(30) DEFAULT '',
  `google_user` varchar(255) DEFAULT NULL,
  `google_password` varchar(255) DEFAULT NULL,
  `google_default_calendar_name` varchar(255) DEFAULT NULL,
  `access` varchar(25) DEFAULT 'everyone',
  `enable_coupons` char(3) DEFAULT 'No',
  `max_seats` int(11) DEFAULT '1',
  `non_work_day_message` varchar(255) DEFAULT '',
  `non_work_day_hide` varchar(6) DEFAULT 'Yes',
  `paypal_account` varchar(50) DEFAULT '',
  PRIMARY KEY (`id_resources`)
) ENGINE=MyISAM;


#
# Table structure for table #__sv_apptpro2_timeslots
#

DROP TABLE IF EXISTS `#__sv_apptpro2_timeslots`;
CREATE TABLE `#__sv_apptpro2_timeslots` (
  `id_timeslots` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `day_number` int(11) DEFAULT NULL,
  `timeslot_starttime` time DEFAULT NULL,
  `timeslot_endtime` time DEFAULT NULL,
  `timeslot_description` varchar(50) DEFAULT '',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `published` tinyint(3) unsigned DEFAULT '0',
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  PRIMARY KEY (`id_timeslots`),
  KEY `start_publish` (`start_publishing`),
  KEY `end_publish` (`end_publishing`)
) ENGINE=MyISAM;

#
# Dumping data for table #__sv_apptpro2_timeslots
#
LOCK TABLES `#__sv_apptpro2_timeslots` WRITE;
/*!40000 ALTER TABLE `#__sv_apptpro2_timeslots` DISABLE KEYS */;

INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (1,0,1,'08:00:00','09:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (2,0,1,'09:00:00','10:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (3,0,1,'10:00:00','11:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (4,0,1,'11:00:00','12:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (5,0,1,'13:00:00','14:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (6,0,1,'14:00:00','15:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (7,0,1,'15:00:00','16:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (9,0,2,'08:00:00','09:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (10,0,2,'09:00:00','10:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (11,0,2,'10:00:00','11:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (12,0,2,'11:00:00','12:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (13,0,2,'13:00:00','14:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (14,0,2,'14:00:00','15:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (15,0,2,'15:00:00','16:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (16,0,3,'08:00:00','09:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (17,0,3,'09:00:00','10:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (18,0,3,'10:00:00','11:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (19,0,3,'11:00:00','12:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (20,0,3,'13:00:00','14:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (21,0,3,'14:00:00','15:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (22,0,3,'15:00:00','16:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (23,0,4,'08:00:00','09:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (24,0,4,'09:00:00','10:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (25,0,4,'10:00:00','11:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (26,0,4,'11:00:00','12:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (27,0,4,'13:00:00','14:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (28,0,4,'14:00:00','15:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (29,0,4,'15:00:00','16:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (30,0,5,'08:00:00','09:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (31,0,5,'09:00:00','10:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (32,0,5,'10:00:00','11:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (33,0,5,'11:00:00','12:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (34,0,5,'13:00:00','14:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (35,0,5,'14:00:00','15:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (36,0,5,'15:00:00','16:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (37,0,6,'08:00:00','09:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (38,0,6,'09:00:00','10:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (39,0,6,'10:00:00','11:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (40,0,6,'11:00:00','12:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (41,0,6,'13:00:00','14:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (42,0,6,'14:00:00','15:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (43,0,6,'15:00:00','16:00:00','',0,NULL,1,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (44,0,0,'08:00:00','09:00:00','',0,NULL,0,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (45,0,0,'09:00:00','10:00:00','',0,NULL,0,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (46,0,0,'10:00:00','11:00:00','',0,NULL,0,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (47,0,0,'11:00:00','12:00:00','',0,NULL,0,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (48,0,0,'13:00:00','14:00:00','',0,NULL,0,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (49,0,0,'14:00:00','15:00:00','',0,NULL,0,NULL,NULL,0);
INSERT INTO `#__sv_apptpro2_timeslots` (`id_timeslots`,`resource_id`,`day_number`,`timeslot_starttime`,`timeslot_endtime`,`timeslot_description`,`checked_out`,`checked_out_time`,`published`,`start_publishing`,`end_publishing`,`ordering`) VALUES (50,0,0,'15:00:00','16:00:00','',0,NULL,0,NULL,NULL,0);

/*!40000 ALTER TABLE `#__sv_apptpro2_timeslots` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table #__sv_apptpro2_tokens
#

DROP TABLE IF EXISTS `#__sv_apptpro2_tokens`;
CREATE TABLE `#__sv_apptpro2_tokens` (
  `Id` int(11) NOT NULL auto_increment,
  `token_text` varchar(50) default NULL,
  `db_field` varchar(100) default NULL,
  `db_table` varchar(50) default NULL,
  `description` varchar(100) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM;

#
# Dumping data for table #__sv_apptpro2_tokens
#
LOCK TABLES `#__sv_apptpro2_tokens` WRITE;
/*!40000 ALTER TABLE `#__sv_apptpro2_tokens` DISABLE KEYS */;

INSERT INTO `#__sv_apptpro2_tokens` VALUES (1,'[resource]','description','resources','name of the resource being booked');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (2,'[requester name]','name','requests',' the name of the requester');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (3,'[startdate]','startdate','requests','start date of the booking');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (4,'[starttime]','starttime','requests','start time of the booking');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (5,'[enddate]','enddate','requests','end date of the booking');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (6,'[endtime]','endtime','requests','end time of the booking');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (7,'[phone]','phone','requests','the phone number of the requester');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (8,'[email]','email','requests','the email address of the requester');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (9,'[user_field1_label]','user_field1_label','config','the label set for user field 1');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (10,'[user_field1_value]','user_field1_value','requests','the value entered for user field 1');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (11,'[user_field2_label]','user_field2_label','config','the label set for user field 2');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (12,'[user_field2_value]','user_field2_value','requests','the value entered for user field 2');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (13,'[user_field3_label]','user_field3_label','config','the label set for user field 3');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (14,'[user_field3_value]','user_field3_value','requests','the value entered for user field 3');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (15,'[user_field4_label]','user_field4_label','config','the label set for user field 4');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (16,'[user_field4_value]','user_field4_value','requests','the value entered for user field 4');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (17,'[cancellation_id]','cancellation_id','requests','the system generated cancellation_id');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (18,'[resource_category]','name','categories','the resource category');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (19,'[resource_service]','name','services','the resource service');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (20,'[sms_phone]','sms_phone','requests','customers sms phone');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (21,'[booking_total]','booking_total','requests','booking total cost');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (22,'[booking_due]','booking_due','requests','booking amount due');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (23,'[coupon]','coupon_code','requests','coupon used for booking');
INSERT INTO `#__sv_apptpro2_tokens` VALUES (24,'[booking_id]','id','requests','booking request id');

/*!40000 ALTER TABLE `#__sv_apptpro2_tokens` ENABLE KEYS */;
UNLOCK TABLES;


DROP TABLE IF EXISTS `#__sv_apptpro2_bookoffs`;
CREATE TABLE `jos_sv_apptpro2_bookoffs` (
  `id_bookoffs` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `description` varchar(100) DEFAULT 'Day Off',
  `off_date` date DEFAULT NULL,
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  `full_day` varchar(6) DEFAULT 'Yes',
  `bookoff_starttime` time DEFAULT NULL,
  `bookoff_endtime` time DEFAULT NULL,
  PRIMARY KEY (`id_bookoffs`),
  KEY `off_date` (`off_date`)
  ) ENGINE=MyISAM;


DROP TABLE IF EXISTS `#__sv_apptpro2_categories`;
CREATE TABLE `jos_sv_apptpro2_categories` (
  `id_categories` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parent_category` int(11) DEFAULT NULL,
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_categories`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `#__sv_apptpro2_services`;
CREATE TABLE `jos_sv_apptpro2_services` (
  `id_services` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `service_duration` int(11) DEFAULT '0',
  `service_duration_unit` varchar(25) DEFAULT 'Minute',
  `service_rate` decimal(10,2) DEFAULT '0.00',
  `service_rate_unit` varchar(25) DEFAULT 'Hour',
  `service_fee` decimal(10,2) DEFAULT '0.00',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_services`)
) ENGINE=MyISAM;


#
# Table structure for table #__sv_apptpro2_udfs
#

DROP TABLE IF EXISTS `#__sv_apptpro2_udfs`;
CREATE TABLE `#__sv_apptpro2_udfs` (
  `id_udfs` int(11) NOT NULL AUTO_INCREMENT,
  `udf_label` varchar(40) DEFAULT NULL,
  `udf_type` varchar(10) NOT NULL DEFAULT 'Textbox',
  `udf_size` varchar(3) DEFAULT '40',
  `udf_rows` varchar(3) DEFAULT '2',
  `udf_cols` varchar(3) DEFAULT '40',
  `udf_radio_options` varchar(255) DEFAULT NULL,
  `udf_required` varchar(4) DEFAULT 'No',
  `udf_tooltip` varchar(255) DEFAULT NULL,
  `udf_help` varchar(255) DEFAULT NULL,
  `cb_mapping` varchar(255) DEFAULT '',
  `read_only` varchar(4) DEFAULT 'No',
  `js_mapping` varchar(255) DEFAULT '',
  `js_read_only` varchar(6) DEFAULT 'No',
  `scope` varchar(255) DEFAULT '',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_udfs`)
) ENGINE=MyISAM;

#
# Dumping data for table #__sv_apptpro2_udfs
#
LOCK TABLES `#__sv_apptpro2_udfs` WRITE;
INSERT INTO `#__sv_apptpro2_udfs` VALUES (1,'Comments','Textarea','40','2','40','','No','Max 255 characters','','','No','','No','',0,'0000-00-00 00:00:00',10,1);
/*!40000 ALTER TABLE `#__sv_apptpro2_udfs` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table #__sv_apptpro2_udfvalues
#

DROP TABLE IF EXISTS `#__sv_apptpro2_udfvalues`;
CREATE TABLE `#__sv_apptpro2_udfvalues` (
  `id` int(11) NOT NULL auto_increment,
  `udf_id` int(11) default NULL,
  `request_id` int(11) default NULL,
  `udf_value` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `#__sv_apptpro2_dialing_codes`; 
CREATE TABLE `#__sv_apptpro2_dialing_codes` (
  `id` int(11) NOT NULL auto_increment,
  `country` varchar(255) default NULL,
  `dial_code` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

#
# Dumping data for table #__sv_apptpro2_dialing_codes
#

INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (1,'Afganistan','93');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (2,'Albania','355');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (3,'Algeria','213');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (4,'Andorra','376');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (5,'Angola','244');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (6,'Antilles Netherland','599');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (7,'Antiqua','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (8,'Argentina','54');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (9,'Armenia','374');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (10,'Aruba','297');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (11,'Australia','61');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (12,'Austria','43');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (13,'Azerbaijan','994');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (14,'Bahamas','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (15,'Bahrain','973');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (16,'Bangladesh','880');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (17,'Barbados','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (18,'Belarus','375');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (19,'Belgium','32');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (20,'Belize','501');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (21,'Benin','229');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (22,'Bermuda','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (23,'Bhutan','975');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (24,'Bolivia','591');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (25,'Bosnia Herzegovina','387');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (26,'Botswana','267');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (27,'Brazil','55');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (28,'British Virgin','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (29,'Brunei Darussalam','673');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (30,'Bulgaria','359');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (31,'Burkina Faso','226');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (32,'Cameroon','237');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (33,'Canada','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (34,'Canary Islands','0');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (35,'Cayman Islands','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (36,'Central African','236');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (37,'Chad','235');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (38,'Chile','56');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (39,'China','86');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (40,'Chinese Taipei','0');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (41,'Colombia','57');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (42,'Congo Republic','242');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (43,'Cook Islands','682');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (44,'Costa Rica','506');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (45,'Croatia','385');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (46,'Cuba','53');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (47,'Cyprus','357');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (48,'Czech Republic','420');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (49,'Denmark','45');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (50,'Djibouti','253');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (51,'Dominican Republic','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (52,'Ecuador','593');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (53,'Egypt','20');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (54,'El Salvador','503');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (55,'Equatorial Guinea','240');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (56,'Estonia','372');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (57,'Ethiopia','251');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (58,'Faeroe Islands','298');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (59,'Fiji','679');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (60,'Finland','358');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (61,'France','33');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (62,'French Guiana','594');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (63,'Gabon Republic','241');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (64,'Georgia','995');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (65,'Germany','49');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (66,'Ghana','233');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (67,'Gibraltar','350');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (68,'Greece','30');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (69,'Greenland','299');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (70,'Grenada','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (71,'Guadeloupe','590');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (72,'Guatemala','502');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (73,'Guinea (PRP)','224');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (74,'Guinea - Bissau','245');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (75,'Guyana','592');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (76,'Haiti','509');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (77,'Honduras','504');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (78,'Hong Kong','852');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (79,'Hungary','36');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (80,'Iceland','354');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (81,'India','91');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (82,'Indonesia','62');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (83,'Iran','98');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (84,'Iraq','964');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (85,'Ireland','353');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (86,'Israel','972');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (87,'Italy','39');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (88,'Ivory Coast','225');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (89,'Jamaica','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (90,'Japan','81');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (91,'Jordan','962');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (92,'Kazakhstan','7');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (93,'Kenya','254');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (94,'Kuwait','965');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (95,'Kyrghyzstan','996');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (96,'Laos','856');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (97,'Latvia','371');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (98,'Lebanon','961');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (99,'Lesotho','266');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (100,'Liberia','231');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (101,'Libya','218');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (102,'Liechtenstein','423');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (103,'Lithuania','370');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (104,'Luxembourg','352');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (105,'Macau','853');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (106,'Macedonia','389');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (107,'Madagascar','261');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (108,'Malawi','265');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (109,'Malaysia','60');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (110,'Maldives','960');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (111,'Mali','223');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (112,'Malta','356');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (113,'Martinique','596');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (114,'Mauritania','222');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (115,'Mauritius','230');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (116,'Mexico','52');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (117,'Moldova','373');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (118,'Monaco','377');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (119,'Mongolia','976');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (120,'Montenegro','381');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (121,'Morocco','212');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (122,'Mozambique','258');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (123,'Namibia','264');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (124,'Nauru','674');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (125,'Nepal','977');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (126,'Netherlands','31');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (127,'New Caledonia','687');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (128,'New Zealand','64');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (129,'Nicaragua','505');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (130,'Niger','227');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (131,'Nigeria','234');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (132,'North Korea','850');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (133,'Norway','47');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (134,'Oman','968');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (135,'Pakistan','92');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (136,'Panama','507');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (137,'Paraguay','595');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (138,'Peru','51');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (139,'Philippines','63');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (140,'Poland','48');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (141,'Portugal','351');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (142,'Qatar','974');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (143,'Reunion Is.','262');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (144,'Romania','40');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (145,'Russia','7');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (146,'Rwanda','250');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (147,'Samoa','685');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (148,'San Marino','378');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (149,'Saudi Arabia','966');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (150,'Senegal','221');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (151,'Serbia','381');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (152,'Seychelles','248');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (153,'Sierra Leone','232');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (154,'Singapore','65');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (155,'Slovakia','421');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (156,'Slovenia','386');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (157,'Solomon Islands','677');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (158,'Somalia','252');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (159,'South Africa','27');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (160,'South Africa','27');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (161,'South Korea','82');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (162,'Spain','34');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (163,'Sri Lanka','94');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (164,'St. Kitts','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (165,'St. Lucia','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (166,'St. Pierre','508');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (167,'St. Vincent','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (168,'Sudan','249');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (169,'Surinam','597');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (170,'Swaziland','268');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (171,'Sweden','46');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (172,'Switzerland','41');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (173,'Syria','963');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (174,'Tadjikistan','992');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (175,'Tahiti','689');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (176,'Taiwan ROC','886');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (177,'Tanzania','255');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (178,'Thailand','66');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (179,'Togo','228');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (180,'Tonga','676');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (181,'Trinidad','868');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (182,'Tunisia','216');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (183,'Turkey','90');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (184,'Uganda','256');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (185,'Ukraine','380');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (186,'United Arab Emirates','971');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (187,'United Kingdom','44');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (188,'Uruguay','598');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (189,'USA','1');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (190,'Vanuatu','678');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (191,'Vatican City','39');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (192,'Venezuela','58');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (193,'Vietnam','84');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (194,'Yemen','967');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (195,'Yugoslavia','11');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (196,'Zaire','243');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (197,'Zambia','260');
INSERT INTO `#__sv_apptpro2_dialing_codes` VALUES (198,'Zimbabwe','263');

DROP TABLE IF EXISTS `#__sv_apptpro2_reminderlog`; 
CREATE TABLE `#__sv_apptpro2_reminderlog` (
  `id_reminderlog` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `local_time` varchar(50) DEFAULT NULL,
  `stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_reminderlog`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `#__sv_apptpro2_coupons`; 
CREATE TABLE `#__sv_apptpro2_coupons` (
  `id_coupons` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(80) DEFAULT 'Discount Coupon',
  `coupon_code` varchar(255) DEFAULT NULL,
  `discount` float DEFAULT '10',
  `discount_unit` varchar(25) DEFAULT 'percent',
  `max_total_use` int(11) DEFAULT '0',
  `max_user_use` int(11) DEFAULT '0',
  `expiry_date` datetime DEFAULT NULL,
  `scope` varchar(255) DEFAULT '',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_coupons`)
) ENGINE=MyISAM;

INSERT INTO `#__sv_apptpro2_coupons` (`id_coupons`,`description`,`coupon_code`,`discount`,`discount_unit`,`max_total_use`,`max_user_use`,`expiry_date`,`scope`,`checked_out`,`checked_out_time`,`ordering`,`published`) VALUES (1,'10% off','1234',10,'percent',0,0,'2009-09-30 00:00:00','',0,'0000-00-00 00:00:00',1,0);
INSERT INTO `#__sv_apptpro2_coupons` (`id_coupons`,`description`,`coupon_code`,`discount`,`discount_unit`,`max_total_use`,`max_user_use`,`expiry_date`,`scope`,`checked_out`,`checked_out_time`,`ordering`,`published`) VALUES (2,'$5.00 OFF','1122',5,'fixed',0,0,'2010-07-10 00:00:00','',0,'0000-00-00 00:00:00',1,0);


DROP TABLE IF EXISTS `#__sv_apptpro2_seat_types`; 
CREATE TABLE `#__sv_apptpro2_seat_types` (
  `id_seat_types` int(11) NOT NULL AUTO_INCREMENT,
  `seat_type_label` varchar(40) DEFAULT NULL,
  `seat_type_tooltip` varchar(255) DEFAULT '',
  `seat_type_cost` decimal(10,2) DEFAULT '0.00',
  `default_quantity` tinyint(3) DEFAULT '1',
  `seat_type_help` varchar(255) DEFAULT NULL,
  `seat_group` varchar(6) DEFAULT 'No',
  `seat_group_max` int(11) DEFAULT '5',
  `scope` varchar(255) DEFAULT '',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_seat_types`)
) ENGINE=MyISAM;

INSERT INTO `#__sv_apptpro2_seat_types` (`id_seat_types`,`seat_type_label`,`seat_type_tooltip`,`seat_type_cost`,`default_quantity`,`seat_type_help`,`seat_group`,`seat_group_max`,`scope`,`checked_out`,`checked_out_time`,`ordering`,`published`) VALUES (1,'Adult','Select the number of Adult seats you wish to order.',10,0,'$10.00 (whatever text you want here ;-)','No',10,'',0,'0000-00-00 00:00:00',1,0);
INSERT INTO `#__sv_apptpro2_seat_types` (`id_seat_types`,`seat_type_label`,`seat_type_tooltip`,`seat_type_cost`,`default_quantity`,`seat_type_help`,`seat_group`,`seat_group_max`,`scope`,`checked_out`,`checked_out_time`,`ordering`,`published`) VALUES (2,'Youth','Select the number of Youth seats you wish to order.',5,0,'$ 5.00 (12-18 yrs)','No',10,'',0,NULL,2,0);
INSERT INTO `#__sv_apptpro2_seat_types` (`id_seat_types`,`seat_type_label`,`seat_type_tooltip`,`seat_type_cost`,`default_quantity`,`seat_type_help`,`seat_group`,`seat_group_max`,`scope`,`checked_out`,`checked_out_time`,`ordering`,`published`) VALUES (3,'Family','Select total seats required to a maximum of 5',25,0,'&nbsp;&nbsp;$25.00 (Select total seats required)','Yes',5,'',0,NULL,4,0);
INSERT INTO `#__sv_apptpro2_seat_types` (`id_seat_types`,`seat_type_label`,`seat_type_tooltip`,`seat_type_cost`,`default_quantity`,`seat_type_help`,`seat_group`,`seat_group_max`,`scope`,`checked_out`,`checked_out_time`,`ordering`,`published`) VALUES (4,'Large Group','Select total seats required to a maximum of 20',75,0,'$75.00 (Select total seats required)','Yes',20,'',0,'0000-00-00 00:00:00',5,0);
INSERT INTO `#__sv_apptpro2_seat_types` (`id_seat_types`,`seat_type_label`,`seat_type_tooltip`,`seat_type_cost`,`default_quantity`,`seat_type_help`,`seat_group`,`seat_group_max`,`scope`,`checked_out`,`checked_out_time`,`ordering`,`published`) VALUES (5,'Child','Select the number of Child seats you wish to order.',0,0,'Free (under 12 yrs - must be accompanied by an Adult)','No',10,'',0,NULL,3,0);


DROP TABLE IF EXISTS `#__sv_apptpro2_seat_counts`; 
CREATE TABLE `#__sv_apptpro2_seat_counts` (
  `id` int(11) NOT NULL auto_increment,
  `seat_type_id` int(11) default NULL,
  `request_id` int(11) default NULL,
  `seat_type_qty` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `#__sv_apptpro2_extras`; 
CREATE TABLE `#__sv_apptpro2_extras` (
  `id_extras` int(11) NOT NULL AUTO_INCREMENT,
  `extras_label` varchar(40) DEFAULT '',
  `extras_tooltip` varchar(255) DEFAULT '',
  `extras_cost` decimal(10,2) DEFAULT '0.00',
  `cost_unit` varchar(25) DEFAULT 'Hour',
  `default_quantity` tinyint(3) DEFAULT '1',
  `max_quantity` tinyint(3) DEFAULT '1',
  `extras_help` varchar(255) DEFAULT '',
  `resource_scope` varchar(255) DEFAULT '',
  `service_scope` varchar(255) DEFAULT '',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_extras`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `#__sv_apptpro2_extras_data`; 
CREATE TABLE `#__sv_apptpro2_extras_data` (
  `id` int(11) NOT NULL auto_increment,
  `extras_id` int(11) default NULL,
  `request_id` int(11) default NULL,
  `extras_qty` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `#__sv_apptpro2_activitylog`; 
CREATE TABLE `#__sv_apptpro2_activitylog` (
  `id_activitylog` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `local_time` varchar(50) DEFAULT NULL,
  `stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_activitylog`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `#__sv_apptpro2_user_credit`;
CREATE TABLE `#__sv_apptpro2_user_credit` (
  `id_user_credit` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `balance` float(10,2) DEFAULT '0.00',
  `checked_out` smallint(5) DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `ordering` smallint(5) DEFAULT '0',
  `published` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_user_credit`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `#__sv_apptpro2_user_credit_activity`;
CREATE TABLE `#__sv_apptpro2_user_credit_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `increase` float(10,2) DEFAULT NULL,
  `decrease` float(10,2) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `balance` float(10,2) DEFAULT '0.00',
  `operator_id` int(11) DEFAULT NULL,
  `stamp` timestamp NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `#__sv_apptpro2_status`;
CREATE TABLE `#__sv_apptpro2_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) DEFAULT NULL,
  `internal_value` varchar(30) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

#
# Dumping data for table #__sv_apptpro2_status
#

INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (1,'RS1_ADMIN_SCRN_REQUEST_STATUS_NEW','new',1);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (2,'RS1_ADMIN_SCRN_REQUEST_STATUS_PENDING','pending',2);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (3,'RS1_ADMIN_SCRN_REQUEST_STATUS_ACCEPTED','accepted',3);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (4,'RS1_ADMIN_SCRN_REQUEST_STATUS_CANCELED','canceled',4);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (5,'RS1_ADMIN_SCRN_REQUEST_STATUS_DELETED','deleted',5);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (6,'RS1_ADMIN_SCRN_REQUEST_STATUS_COMPLETED','completed',6);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (7,'RS1_ADMIN_SCRN_REQUEST_STATUS_DECLINED','declined',7);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (8,'RS1_ADMIN_SCRN_REQUEST_STATUS_NO_SHOW','no_show',8);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (9,'RS1_ADMIN_SCRN_REQUEST_STATUS_ATTENDED','attended',9);
INSERT INTO `#__sv_apptpro2_status` (`id`,`status`,`internal_value`,`ordering`) VALUES (10,'RS1_ADMIN_SCRN_REQUEST_STATUS_PAYPAL_TIMEOUT','timeout',10);


