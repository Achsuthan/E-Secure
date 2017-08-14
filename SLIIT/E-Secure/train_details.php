<?php

$totaltrojan=0;
$totalvirus=0;
$totaladware=0;
$totalworms=0;
$totalothers=0;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E-Secure";

$conn = new mysqli($servername, $username, $password, $dbname);

/*
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} */

$conn = new mysqli($servername, $username, $password, $dbname);

$sql="select * from training order by id desc limit 1";
        $result=$conn->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
		$totaltrojan=$row["trojan"];
		$totaladware=$row["adware"];
		$totalvirus=$row["virus"];
		$totalworms=$row["worms"];
		$totalothers=$row["others"];
            }
        }
$conn->close();


    $retrunArray["status"]="200";
    $retrunArray["virus"]=$totalvirus;
    $retrunArray["worms"]=$totalworms;
    $retrunArray["trojan"]=$totaltrojan;
    $retrunArray["adware"]=$totaladware;
    $retrunArray["others"]=$totalothers;

    echo json_encode($retrunArray);



?>
