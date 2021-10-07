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

    $width = isset($_POST["width"]) ? $_POST["width"] : 0;
    $height = isset($_POST["height"]) ? $_POST["height"] : 0;
    $area = ($width/1000) * ($height/1000); // divide by 1000 to convert to metres
    $price = ($area * $area) + (100*$area) + 6;
    $priceTo2dec = round($price,2);

    if(empty($width) || empty($height)) {//conditions for erroneous submission
        //Need to output the form
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            echo "<p>Please provide suitable frame dimensions.</p>"; //Error message
        }
        ?>
        <form action="framing.php" method="post">
            <p>Please enter your photo sizes to get a framing cost estimate.</p>
            <p>Photo Width: <input type="number" name="width" min = "1"/> </p>
            <p>Photo Height: <input type="number" name="height" min = "1"/> </p>
            <p><input type="submit"/> </p>
        </form>
        <?php
    } else

        echo "Your frame will cost £$priceTo2dec";
    ?>

</div>
</body>
</html>
