<?php

// get the values form ajex

$username=$_REQUEST["username"];

//testing purpose in the browser

/*$username=htmlentities($_REQUEST["username"]);
$password=htmlentities($_REQUEST["password"]);
*/

//check whether the username and password values are empty or not
if (empty($username) )
{

    //error message if those values contain null or not assign

    $returnArray["status"]=800;
    $returnArray["message"]="Missing Required information";
    echo json_encode($returnArray);
    return;
}

//$salt=openssl_random_pseudo_bytes(20);
//$securedpassword=sha1($password.$salt);

//access the Test.ini file
$file=parse_ini_file("Test.ini"); //get the database name,username ,password values

//get the values form Test.ini and assign those values to the variable
$host=trim($file["dbhost"]);
$user=trim($file["dbuser"]);
$pass=trim($file["dbpass"]);
$name=trim($file["dbname"]);


//require the access.php file to call the function for the future purpose
require ("Secure/access.php");

//call the class and assign the values get from the Test.ini
$access=new access($host,$user,$pass,$name);

//call the connect function to connect with the database
$access->connect();


//call the loginuser function to check whether the given username and password is available in the database
$result=$access->usernamecheck($username);
if($result)
{
    //found result
    $returnArray["status"]="200";
    $returnArray["message"]="Username found";
}
else
{
    //error result
    $returnArray["status"]="400";
    $returnArray["message"]="Username Not found";

}

//disconnect the database connection
$access->disconnect();
echo json_encode($returnArray);
?>
