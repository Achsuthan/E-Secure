<?php


$file=parse_ini_file("Test.ini"); //get the database name,username ,password values

//get the values form Test.ini and assign those values to the variable
$host=trim($file["dbhost"]);
$user=trim($file["dbuser"]);
$pass=trim($file["dbpass"]);
$name=trim($file["dbname"]);

/*
//require the access.php file to call the function for the future purpose
require ("Secure/access.php");

//call the class and assign the values get from the Test.ini
$access=new access($host,$user,$pass,$name);

//call the connect function to connect with the database
$access->connect();


//call the loginuser function to check whether the given username and password is available in the database
$result=$access->loginuser($username,$password);
if($result)
{
    //found result
    $result["status"]="200";
    $result["message"]="User found";
    $result["person"]="User";
    echo json_encode($result);
}
else
{
    //error result
    $returnArray["status"]="400";
    $returnArray["message"]="User not found";
    echo json_encode($returnArray);
}

//disconnect the database connection
$access->disconnect();
*/

$target_path = "/home/achsuthan/SLIIT/E-Sucure/uploads/";

$target_path = $target_path . basename( $_FILES['file']['name']);
$id=$_POST["id"];
$person=$_POST["person"];
if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path))
{
    $output=exec("python ../../cuckoo-1.0/utils/submit.py ".$target_path);
    //$output=exec("python ../../cuckoo-1.0/utils/submit.py ../..//Downloads/pdf-sample.pdf");
    $output=explode(" ", $output);
    $returnArray["status"]="200";
    $returnArray["message"]="File uploaded";
    $returnArray["output"]=$output[8];
    $returnArray["person"]=$_POST["person"];
    $returnArray["attachmentname"]=$_FILES['file']['name'];
    $returnArray["path"]=$target_path;
    $returnArray["id"]=$id;
}
else
{
    $returnArray["status"]="400";
    $returnArray["message"]="File not uploaded";
}
echo json_encode($returnArray);
