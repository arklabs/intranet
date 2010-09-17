<?php

$dirname	= dirname(__FILE__).'/../..';
include($dirname.'/../../test/bootstrap/unit.php');

// TODO find a cleaner way to include all the plugin instead of all the classes by hand
require_once($dirname.'/lib/exception/sfDateTimeException.class.php');
require_once($dirname.'/lib/sfDate.class.php');
require_once($dirname.'/lib/sfDateTimeToolkit.class.php');
require_once($dirname.'/lib/sfTime.class.php');

$t = new lime_test(26, new lime_output_color());

// strtolower()
$t->diag('sfDate()');

// test finalDayOfMonth
$t->is(sfDate::getInstance('2009-04-01')->finalDayOfMonth()->o2h()	, "2009-04-30", 'finalDayOfMonth'	);
$t->is(sfDate::getInstance('2009-04-14')->finalDayOfMonth()->o2h()	, "2009-04-30", 'finalDayOfMonth'	);
// test all boundary finalDayOfMonth

$t->is(sfDate::getInstance('2009-01-31')->finalDayOfMonth()->o2h()	, "2009-01-31", 'finalDayOfMonth january boundary'	);
$t->is(sfDate::getInstance('2009-02-28')->finalDayOfMonth()->o2h()	, "2009-02-28", 'finalDayOfMonth february boundary'	);
$t->is(sfDate::getInstance('2009-03-31')->finalDayOfMonth()->o2h()	, "2009-03-31", 'finalDayOfMonth march boundary'	);
$t->is(sfDate::getInstance('2009-04-30')->finalDayOfMonth()->o2h()	, "2009-04-30", 'finalDayOfMonth april boundary'	);
$t->is(sfDate::getInstance('2009-05-31')->finalDayOfMonth()->o2h()	, "2009-05-31", 'finalDayOfMonth may boundary'	);
$t->is(sfDate::getInstance('2009-06-30')->finalDayOfMonth()->o2h()	, "2009-06-30", 'finalDayOfMonth june  boundary'	);
$t->is(sfDate::getInstance('2009-07-31')->finalDayOfMonth()->o2h()	, "2009-07-31", 'finalDayOfMonth july boundary'	);
$t->is(sfDate::getInstance('2009-08-31')->finalDayOfMonth()->o2h()	, "2009-08-31", 'finalDayOfMonth August boundary'	);
$t->is(sfDate::getInstance('2009-09-30')->finalDayOfMonth()->o2h()	, "2009-09-30", 'finalDayOfMonth september boundary'	);
$t->is(sfDate::getInstance('2009-10-31')->finalDayOfMonth()->o2h()	, "2009-10-31", 'finalDayOfMonth october boundary'	);
$t->is(sfDate::getInstance('2009-11-30')->finalDayOfMonth()->o2h()	, "2009-11-30", 'finalDayOfMonth november boundary'	);
$t->is(sfDate::getInstance('2009-12-31')->finalDayOfMonth()->o2h()	, "2009-12-31", 'finalDayOfMonth december boundary'	);

//test with bisextil year
$t->is(sfDate::getInstance('2008-02-29')->finalDayOfMonth()->o2h()	, "2008-02-29", 'finalDayOfMonth bisextil boundary'	);



$t->is(sfDate::getInstance('2009-04-01')->dayOfWeek()	, sfTime::WEDNESDAY, 'test dayOfWeek()'	);

$t->is(sfDate::getInstance('1970-06-25')->getDay()	, 25	, 'test getDay()'	);
$t->is(sfDate::getInstance('1970-06-25')->getMonth()	, 06	, 'test getMonth()'	);
$t->is(sfDate::getInstance('1970-06-25')->getYear()	, 1970	, 'test getYear()'	);

$t->is(sfDate::getInstance('1982-01-01')->isHolidayFR()	, true	, "premier de lan"	);
$t->is(sfDate::getInstance('1982-01-02')->isHolidayFR()	, false	, "pas premier de lan"	);

$t->is(sfDate::getInstance('2020-05-21')->isHolidayFR()	, true	, "ascention 2020"	);
$t->is(sfDate::getInstance('2013-04-01')->isHolidayFR()	, true	, "lundi paques 2013"	);

$t->is(sfDate::getInstance('2008-04-01')->isBisextil()	, true	, "2008 is bisextil"	);
$t->is(sfDate::getInstance('2009-04-01')->isBisextil()	, false	, "2009 is not"	);
$t->is(sfDate::getInstance('2009-04-01')->isBisextil()	, false	, "1998 is not"	);
