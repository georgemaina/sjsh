# add column at pricetable
ALTER TABLE `care_tz_drugsandservices` ADD `is_labtest` TINYINT NOT NULL DEFAULT '0' AFTER `is_consumable` ;
#
# copy all values to pricetable
INSERT INTO care_tz_drugsandservices (
  `item_number` ,
  `is_pediatric` ,
  `is_adult` ,
  `is_other` ,
  `is_consumable` ,
  `is_labtest` ,
  `item_description` ,
  `item_full_description` ,
  `unit_price` ,
  `unit_price_1` ,
  `unit_price_2` ,
  `unit_price_3` ,
  `purchasing_class` )
SELECT
  	CONCAT('LAB',tests.id) AS item_number,
  	0 AS `is_pediatric`,
	0 AS `is_adult`,
	0 AS `is_other`,
	1 AS `is_consumable`,
	1 AS `is_labtest`,
	tests.name AS item_description,
  	tests.name AS item_fullcare_menu_main_description,
  	param.price AS `unit_price`,
  	0 AS `unit_price1`,
	0 AS `unit_price2`,
	0 AS `unit_price3`,
  	'labtest' AS purchasing_class
FROM
	care_tz_laboratory_tests tests,
	care_tz_laboratory_param param
WHERE tests.id = param.id AND parent<>-1