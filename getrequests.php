<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name = "viewport" content="width-device-width, initial-scale=1.0">
    <title>Frame Price Estimator</title>
</head>
<body>
<div>
<?php
require_once "/home/wsb19173/DEVWEB/2021/xtpqzywxrfxhkhg/password.php";
//connect to MySQL
$host = "devweb2021.cis.strath.ac.uk";
$username="wsb19173";
$pass = get_password();
$dbname="wsb19173";
$conn= new mysqli($host,$username,$pass,$dbname);

if($conn->connect_error) {
    die("Connection failed :" . $conn->connect_error); //FIXME remove once working
}

$password = strip_tags(isset($_POST["pwd"]) ? $_POST["pwd"] : "");

if ($password===""){
?>
    <form action="getrequests.php" method="post">
        <input type="password" name="pwd" value="<?php echo $password ?>"><input type="submit"/>
    </form>

    <?php
} elseif($password !="WannaTellMeHow"){
    ?>
    <form action="getrequests.php" method="post">
        <input type="password" name="pwd" value="<?php echo $password ?>"><input type="submit"/>
    </form>
    <?php
    echo"*Permission was not granted: Your password did not match";
}


if($password ==="WannaTellMeHow") {
    $sql = "SELECT * FROM `framing_system`";
    $result = $conn->query($sql);

    echo"<table><tr><th>Width</th><th>Height</th><th>Postage</th><th>E-mail</th><th>Price (ex vat)</th><th>Requested</th></tr>\n";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                 <td>" .$row["Width"] .
                "</td><td>" . $row["Height"] .
                "</td><td>" . $row["Postage"] .
                "</td><td>" . $row["E-mail"] .
                "</td><td>" . $row["Price (ex vat)"] .
                "</td><td>" . $row["Requested"] . "</td></tr>\n";

        }

    }

 else{
        die ("There was no match");
    }

    $conn->close();
    echo"</table>\n";
  }

?>

</div>
</body>
</html>
