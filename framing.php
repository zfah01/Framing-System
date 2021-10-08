<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name = "viewport" content="width-device-width, initial-scale=1.0">
    <title>Framing System</title>
</head>
<body>
<div>
    <h1>Frame Price Estimator</h1>
    <?php
    $width = $_POST["width"];
    $height = $_POST["height"];
    $wUnit  = $_POST["units"];
    $hUnit = $_POST["units"];



    if($width =="" || $width == 0) {
        echo "<p>Width is not valid: Please enter the width.</p>";
    }

    if($height=="" || $height==0){
            echo "<p>Height is not valid: Please enter the height.</p>";
        }

    switch($wUnit){
        case "mm":   $width /= 1000; break;
        case "cm":    $width /= 100;  break;
        case "inches": $width /= 39.37; break;

    }

    switch($hUnit){
        case "mm":   $height /= 1000; break;
        case "cm":    $height /= 100;  break;
        case "inches": $height /= 39.37; break;

    }
    $area = $width * $height;
    $price = round(($area * $area) + (100*$area) + 6,2);

    $postage = $_POST["postage"];
    $longestEdge = max($width,$height);
    $postageCost= 0;

    if($postage == "Economy"){
     $postageCost = round((2*$longestEdge) + 4,2);
    } elseif ($postage == "Rapid") {
        $postageCost = round((3*$longestEdge) + 8,2);
    } elseif ($postage == "Next Day") {
        $postageCost = round((5*$longestEdge) + 12,2);
    }
    $totalCost = $price + $postageCost;
    $totalCostWithVAT = round(($totalCost * 0.2) + $totalCost,2);
    echo "<p>Your frame will cost £$price plus $postage postage of £$postageCost giving a total price of £$totalCostWithVAT including VAT.</p>";
    ?>

</div>
</body>
</html>
