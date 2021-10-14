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
    $width = isset($_POST["width"]) ? $_POST["width"] : "";
    $height = isset($_POST["height"]) ? $_POST["height"] : "";
    $units  = isset($_POST["units"]) ? $_POST["units"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";

    if($width =="" || $width == 0) {

        echo "<p>Width is not valid: Please enter the width.</p>";
    }
    if($height=="" || $height==0) {
        echo "<p>Height is not valid: Please enter the height.</p>";
    }



    switch($units){
        case "mm":  if($width < 200 || $width> 2000){
                    echo"Please enter an appropriate size.";
                    echo"<p>Minimum size: 200mm  Maximum size: 2000mm</p>";
                     }
                     if(is_numeric($width)) {
                         if (is_numeric($height)) {
                             $width /= 1000;
                             $height /= 1000;
                         }
                     }else{
                         echo"<p>Please enter a numeric value</p>";
                     }
                     break;

        case "cm": if($width < 20 || $width> 200){
                    echo"Please enter an appropriate size.";
                     echo"<p>Minimum size: 20cm  Maximum size: 200cm</p>";
                     }
                    if(is_numeric($width)) {
                    if (is_numeric($height)) {
                        $width /= 100;
                        $height /= 100;
                        }else{
                        echo"<p>Please enter a numeric value</p>";
                    }
                    }
        break;


        case "inches": if($width < 7.87 || $width> 78.74){
                        echo"Please enter an appropriate size.";
                        echo"<p>Minimum size: 7.87 inches  Maximum size: 78.74 inches</p>";
                        }
                        if(is_numeric($width)) {
                            if (is_numeric($height)) {
                                $width /= 39.37;
                                $height /= 39.37;

                            } else{
                                echo"<p>Please enter a numeric value</p>";
                            }
                        }
                         break;
    }

    $area = (int)$width * (int)$height;
    $price = round(($area * $area) + (100*$area) + 6,2);
    $postage = isset($_POST["postage"]) ? $_POST["postage"] : "";
    $longestEdge = max($width,$height);
    $postageCost= 0.0;

    if($postage == "economy"){
     $postageCost = round((2*$longestEdge) + 4,2);
    }
    if ($postage == "rapid") {
        $postageCost = round((3*$longestEdge) + 8,2);
    }
    if ($postage == "next day") {
        $postageCost = round((5*$longestEdge) + 12,2);
    }

    $totalCost = $price + $postageCost;
    $totalCostWithVAT = round(($totalCost * 0.2) + $totalCost,2);

    function checkEmail($email) {
        $find1 = strpos($email, '@');
        $find2 = strpos($email, '.');
        return ($find1 !== false && $find2 !== false && $find2 > $find1);
    }


    if(($height >= 0.2 && $width >= 0.2 )&&($height <= 2 && $width <=2 ) && (!empty($email)&& checkEmail($email))){

        echo $email . " looks like a valid email address.";
        echo "<p>Your frame will cost £$price plus $postage postage of £$postageCost giving a total price of £$totalCostWithVAT including VAT.</p>";
        }
    else {
        echo "<p>An error occurred whilst processing the order</p>";
        echo "<p>Email address is required: Please enter a valid email address  e.g. example@company.com</p>";

        ?>
        <form action="framing.php" method="post">
                    <p>Please enter your photo sizes to get a framing cost estimate.</p>
                    <p>Photo Width: <label>
                            <input type="text" name="width" value="<?php echo $width; ?>"/>
                        </label>
                        <label>
                            <select name="units">
                                <option selected value="mm">mm </option>
                                <option value="cm">cm</option>
                                <option value="inches">inches</option>
                                <?php echo $units;?>
                            </select>
                        </label></p>
                    <p>Photo Height: <label>
                            <input type="text" name="height" value="<?php echo $height;?>"/>
                        </label></p>
                    <p>Postage:
                        <label>
                            <input type="radio" name="postage" value="economy checked<?php echo $postage;?>" />
                        </label> Economy
                        <label>
                            <input type="radio" name="postage" value="rapid<?php echo $postage;?>"/>
                        </label> Rapid
                        <label>
                            <input type="radio" name="postage" value="next day<?php echo $postage;?>"/>
                        </label> Next Day
                    </p>
                    <p><label>
                            <input type="checkbox" name="vat" value="yes" checked/>
                        </label> Include VAT in price</p>
                    <p>Email: <label>
                            <input type="text" name="email" value="<?php echo $email;?>"/>
                        </label></p>
                    <p><input type="submit"/> </p>
        </form>
    <?php
    }

    $msg = " Thanks for placing your order
     'Your frame will cost £$price plus $postage postage of £$postageCost giving a total price of £$totalCostWithVAT including VAT.'
     
     Here is the link to our website: https://devweb2021.cis.strath.ac.uk/~wsb19173/xtpqzywxrfxhkhg/index.html";

    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);

    // send email
    mail($email,"Thanks for your order",$msg);

    ?>

</div>
</body>
</html>
