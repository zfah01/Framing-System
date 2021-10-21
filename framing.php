<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name = "viewport" content="width-device-width, initial-scale=1.0">
    <title>Frame Price Estimator</title>
</head>
<body>
<div>
    <h1>Frame Price Estimator</h1>
    <?php
        require_once "/home/wsb19173/DEVWEB/2021/xtpqzywxrfxhkhg/password.php";
        //connect to MySQL
        $host = "devweb2021.cis.strath.ac.uk";
        $username="wsb19173";
        $pass = get_password();
        $dbname="wsb19173";
        $conn= new mysqli($host,$username,$pass,$dbname);

        if($conn->connect_error){
            die("Connection failed :".$conn->connect_error); //FIXME remove once working
        }

        $width = strip_tags(isset($_POST["width"]) ? $_POST["width"] : "");
        $height = strip_tags(isset($_POST["height"]) ? $_POST["height"] : "");
        $units  = isset($_POST["units"]) ? $_POST["units"] : "";
        $email = strip_tags(isset($_POST["email"]) ? $_POST["email"] : "");
        $postage = isset($_POST["postage"]) ? $_POST["postage"] : "";
        $error="";
        $optIn = isset($_POST["optIn"]) ? $_POST["optIn"] : "";
    ?>
    <form action="framing.php" method="post">
        <p>Please enter your photo sizes to get a framing cost estimate.</p>
        <p>Photo Width: <label>
                <input type="text" name="width" value="<?php echo $width; ?>"/>
            </label>
            <label>
                <select name="units">
                    <option value="" selected disabled>Select</option>
                    <option <?php if($units ==="mm"){?>selected <?php } ?> selected value="mm">mm </option>
                    <option <?php if($units ==="cm"){?>selected <?php }?>value="cm">cm</option>
                    <option <?php if($units ==="inches"){?>selected <?php }?>value="inches">inches</option>
                </select>
            </label></p>
        <p>Photo Height: <label>
                <input type="text" name="height" value="<?php echo $height;?>"/>
            </label></p>
        <p>Postage:
            <label>
                <input type="radio" id="economy" name="postage" value="Economy" checked <?php if($postage==="Economy"){?> checked="checked"<?php };?>>
            </label> Economy
            <label>
                <input type="radio" id="rapid" name="postage" value="Rapid"<?php if($postage==="Rapid"){?> checked="checked"<?php };?>>
            </label> Rapid
            <label>
                <input type="radio" id="next day" name="postage" value="Next Day"<?php if($postage==="Next Day"){?> checked="checked"<?php };?>>
            </label> Next Day
        </p>
        <p><label>
                <input type="checkbox" name="vat" value="yes" checked/>
            </label> Include VAT in price</p>
        <p>Please enter your email to receive a quote.</p>
        <p>Email: <label>
                <input type="text" name="email" value="<?php echo $email;?>">
            </label></p>
        <p>Receive mail and future information about my framing calculation <label>
                <input type = "checkbox" name="optIn" id="optIn" value="optIn"<?php if($optIn==="optIn"){?> checked="checked"<?php };?>></label></p>
        <p><input type="submit"/> </p>
    </form>
    <?php

    if($width !="" && $height !="") {
        $error = checkMeasurements($error, $units, $width, "Width");
        $error = checkMeasurements($error, $units, $height, "Height");

        //check if email is valid
        if ($email != "") {
            $error = checkEmail($error, $email);
        } elseif($optIn != ""){
            $error = checkIfEmpty($error,$email,"Email");
        }

        //if no error perform calculations
        if ($error === "") {
            $widthInput = $width;
            $heightInput = $height;
            $width = metresConversion($units, $width);
            $height = metresConversion($units, $height);
            $longestEdge = max($width, $height);
            $area = $width * $height;
            $price = number_format((float)(($area * $area) + (100 * $area) + 6), 2, '.', ',');
            $postageCost = number_format((float)calculatePostage($longestEdge, $postage), 2, '.', ',');

            $totalCost = $price + $postageCost;

            $totalCostWithVAT = number_format((float)(($totalCost * 0.2) + $totalCost), 2, '.', ',');

            if ($optIn === "optIn" && $email != "") {

                $msg = " Thanks for placing your order
        ' Your frame will cost £$price plus $postage postage of £$postageCost giving a total price of £$totalCostWithVAT including VAT.'
     
     Here is the link to our website: https://devweb2021.cis.strath.ac.uk/~wsb19173/xtpqzywxrfxhkhg/index.html";

                // use wordwrap() if lines are longer than 70 characters
                $msg = wordwrap($msg, 70);

                // send email
                mail($email, "Thanks for your order", $msg);
                $timestamp = date("Y-m-d H:i:s");
                $sql = "INSERT INTO `wsb19173`.`framing_system`(`Width`, `Height`, `Postage`, `E-mail`, `Price (ex vat)`, `Requested`) VALUES "."('$widthInput','$heightInput','$postage','$email','$totalCost','$timestamp');";

                if($conn->query($sql)=== TRUE){
                    echo "New order inserted "."<br>";
                } else{
                    die("Error: ".$sql."<br>".$conn->error);
                }

                $conn->close();
            }
            echo "<p>Your frame will cost £ $price plus $postage postage of £$postageCost giving a total price of £$totalCostWithVAT including VAT.</p>";
        }
             else {
                echo $error;
            }
        }

         elseif($width != "" || $height != "" || $optIn != ""){

            $error = checkIfEmpty($error, $width, "Width");
            $error = checkIfEmpty($error, $height, "Height");
            $error = checkIfEmpty($error, $email, "Email");
            echo $error;
    }

        function checkIfEmpty($error, $input, $field)
        {
            if ($input === "") {
                $error = $error . "*" . $field . " is required<br>";
            }
            return $error;
        }

        function checkEmail($error, $email)
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = $error . "*Invalid email format";
            }
            return $error;
        }

        function metresConversion($units, $input)
        {
            switch ($units) {
                case "mm":
                    return $input / 1000;
                case "cm":
                    return $input / 100;
                case "inches":
                    return $input / 39.37;
            }
        }


        function checkMeasurements($error, $units, $input, $field)
        {
            if (!is_numeric($input)) {
                $error = $error . "*" . $field . " must be a numeric value<br>";
            } else {
                $measurement = metresConversion($units, $input);
                if ($measurement === 0) {
                    $measurement = 0.1;
                }
                switch ($measurement) {
                    case $measurement < 0.2:
                        switch ($units) {
                            case "mm":
                                $error = $error . "*" . $field . " has to be at least 200mm<br>";
                                break;

                            case "cm":
                                $error = $error . "*" . $field . " has to be at least 20cm<br>";
                                break;

                            case "inches":
                                $error = $error . "*" . $field . " has to be at least 7.87 inches<br>";

                        }
                        break;
                    case $measurement > 2.0:
                        switch ($units) {

                            case "mm":
                                $error = $error . "*" . $field . " has to be less than 2000mm<br>";
                                break;

                            case"cm":
                                $error = $error . "*" . $field . " has to be less than 200cm<br>";
                                break;

                            case"inches":
                                $error = $error . "*" . $field . " has to be less than 78.74 inches<br>";
                        }
                        break;

                }
            }
            return $error;
        }

        function calculatePostage($longestEdge, $postage)
        {
            if ($postage == "Economy") {
                return round((2 * $longestEdge) + 4, 2);
            } elseif ($postage == "Rapid") {
                return round((3 * $longestEdge) + 8, 2);
            } elseif ($postage == "Next Day") {
                return round((5 * $longestEdge) + 12, 2);
            }
        }




    ?>

</div>
</body>
</html>
