<?php

namespace Starbattle;

require_once('starbattle.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starbattle</title>
    <style>
        table {
            border-collapse: collapse;
        }
        td {
            border: 1px solid black;
            width: 100px;
            height: 100px;
            text-align: center;
            font-size: 70px;
        }
    </style>
</head>
<body>
<?php

$grids = [
    '12233,14433,11433,51433,55555',
    '11122,31344,33344,55344,55555',
    '11223,11243,11443,11553,55555',
    '111111,233311,222333,244533,225533,555566',
    '11111112,11122222,22222223,22243333,55543333,44446337,48666667,48677777'
];

$randomKey = array_rand($grids);
$randomKey = 0;
$grid = $grids[$randomKey];

$starbattle = new Starbattle($grid);
$starbattle->solve(substr_count($grid, ','));

?>
</body>
</html>
