<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2024-01-29 00:10:42 --> Severity: Warning --> Attempt to read property "payment_type" on null /var/www/html/web/application/models/Payer_model.php 88
ERROR - 2024-01-29 00:10:42 --> Severity: Warning --> Attempt to read property "room_size" on null /var/www/html/web/application/models/Payer_model.php 88
ERROR - 2024-01-29 00:10:42 --> Severity: Warning --> Attempt to read property "payment_type" on null /var/www/html/web/application/models/Payer_model.php 95
ERROR - 2024-01-29 00:10:42 --> Severity: Warning --> Undefined property: stdClass::$ /var/www/html/web/application/models/Payer_model.php 95
ERROR - 2024-01-29 00:10:42 --> Severity: Warning --> Attempt to read property "room_size" on null /var/www/html/web/application/models/Payer_model.php 95
ERROR - 2024-01-29 00:10:42 --> Severity: Warning --> Undefined property: stdClass::$ /var/www/html/web/application/models/Payer_model.php 95
ERROR - 2024-01-29 00:12:08 --> Severity: Warning --> Attempt to read property "payment_type" on null /var/www/html/web/application/models/Payer_model.php 89
ERROR - 2024-01-29 00:12:08 --> Severity: Warning --> Attempt to read property "room_size" on null /var/www/html/web/application/models/Payer_model.php 89
ERROR - 2024-01-29 00:21:22 --> Severity: Warning --> session_start(): Failed to read session data: user (path: /var/lib/php/sessions) /var/www/html/web/system/libraries/Session/Session.php 137
ERROR - 2024-01-29 00:30:22 --> Severity: Warning --> Undefined property: stdClass::$is_comp /var/www/html/web/application/models/Payer_model.php 78
ERROR - 2024-01-29 00:30:22 --> Severity: Warning --> Undefined property: stdClass::$is_cancelled /var/www/html/web/application/models/Payer_model.php 78
ERROR - 2024-01-29 00:30:36 --> Severity: Warning --> Undefined property: stdClass::$is_comp /var/www/html/web/application/models/Payer_model.php 78
ERROR - 2024-01-29 00:30:36 --> Severity: Warning --> Undefined property: stdClass::$is_cancelled /var/www/html/web/application/models/Payer_model.php 78
ERROR - 2024-01-29 00:30:43 --> Severity: Warning --> Undefined property: stdClass::$is_comp /var/www/html/web/application/models/Payer_model.php 78
ERROR - 2024-01-29 00:30:43 --> Severity: Warning --> Undefined property: stdClass::$is_cancelled /var/www/html/web/application/models/Payer_model.php 78
ERROR - 2024-01-29 00:57:41 --> Severity: error --> Exception: syntax error, unexpected token ":", expecting ";" /var/www/html/web/application/views/person/filter.php 5
ERROR - 2024-01-29 01:03:50 --> Query error: Illegal mix of collations (latin1_swedish_ci,IMPLICIT) and (utf8mb4_general_ci,COERCIBLE) for operation 'like' - Invalid query: SELECT *
FROM `person`
WHERE `status` = 1
AND  `first_name` LIKE '%🚫%' ESCAPE '!'
OR  `last_name` LIKE '%🚫%' ESCAPE '!'
AND `address_id` IS NOT NULL
ORDER BY `last_name` ASC, `first_name` ASC
ERROR - 2024-01-29 01:23:04 --> Severity: Warning --> Undefined array key "veterans_only" /var/www/html/web/application/models/Person_model.php 84
ERROR - 2024-01-29 01:23:09 --> Severity: Warning --> Undefined array key "veterans_only" /var/www/html/web/application/models/Person_model.php 84
ERROR - 2024-01-29 01:27:02 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ')
ORDER BY `address`.`zip`' at line 7 - Invalid query: SELECT `address`.*
FROM `address`
WHERE address.address IS NOT NULL
AND address.city IS NOT NULL
AND address.state IS NOT NULL
AND address.zip IS NOT NULL
AND `address`.`id` IN()
ORDER BY `address`.`zip`
ERROR - 2024-01-29 01:38:57 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::where_null() /var/www/html/web/application/models/Person_model.php 85
ERROR - 2024-01-29 01:39:10 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::where_null() /var/www/html/web/application/models/Person_model.php 85
ERROR - 2024-01-29 01:39:12 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::where_null() /var/www/html/web/application/models/Person_model.php 85
ERROR - 2024-01-29 01:39:18 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::where_null() /var/www/html/web/application/models/Person_model.php 85
ERROR - 2024-01-29 01:40:31 --> Severity: Warning --> Undefined array key "veterans" /var/www/html/web/application/views/person/list.php 32
ERROR - 2024-01-29 01:44:59 --> Severity: Warning --> include(/var/www/html/web/application/third_party/fpdf185/fpdf.php): Failed to open stream: No such file or directory /var/www/html/web/application/views/tourist/export.php 2
ERROR - 2024-01-29 01:44:59 --> Severity: Warning --> include(): Failed opening '/var/www/html/web/application/third_party/fpdf185/fpdf.php' for inclusion (include_path='.:/usr/share/php') /var/www/html/web/application/views/tourist/export.php 2
ERROR - 2024-01-29 01:58:03 --> Query error: Unknown column 'double_rate' in 'field list' - Invalid query: SELECT `double_rate`
FROM `tour`
WHERE `id` = 48
ERROR - 2024-01-29 02:00:52 --> Severity: Warning --> Undefined property: stdClass::$ /var/www/html/web/application/models/Payer_model.php 84
ERROR - 2024-01-29 02:01:23 --> Severity: Warning --> Undefined property: stdClass::$ /var/www/html/web/application/models/Payer_model.php 84
ERROR - 2024-01-29 02:06:57 --> Severity: error --> Exception: Attempt to assign property "tourists" on null /var/www/html/web/application/models/Tour_model.php 88
ERROR - 2024-01-29 02:06:59 --> Severity: error --> Exception: Attempt to assign property "phones" on null /var/www/html/web/application/models/Person_model.php 46
ERROR - 2024-01-29 02:07:01 --> Severity: error --> Exception: Attempt to assign property "tourists" on null /var/www/html/web/application/models/Tour_model.php 88
ERROR - 2024-01-29 02:07:02 --> Severity: error --> Exception: Attempt to assign property "phones" on null /var/www/html/web/application/models/Person_model.php 46
ERROR - 2024-01-29 02:15:05 --> Severity: error --> Exception: get_first_missing_number(): Argument #2 ($field) must be of type object, string given, called in /var/www/html/web/application/models/Room_model.php on line 35 /var/www/html/web/application/helpers/general_helper.php 260
