<?php

namespace Starbattle;

define('DELAY_IN_SECONDS', 0.01);

require_once('starbattle.php');

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

set_time_limit(0);

ob_implicit_flush(true);
ob_end_flush();


$grids = [
    '12233,14433,11433,51433,55555',
    '11122,31344,33344,55344,55555',
    '11223,11243,11443,11553,55555',
    '111111,233311,222333,244533,225533,555566',
    '11111112,11122222,22222223,22243333,55543333,44446337,48666667,48677777'
];

$randomKey = array_rand($grids);
$randomKey = 1;
$grid = $grids[$randomKey];

$starbattle = new Starbattle($grid);

$starbattle->solve();
