<?php

//get the values form ajex
$username=$_REQUEST["username"];
$password=$_REQUEST["password"];
$email=$_REQUEST["email"];
$firstname=$_REQUEST["firstname"];
$lastname=$_REQUEST["lastname"];


//this is for checking purposes in browser

/*
$username=htmlentities($_REQUEST["username"]);
$password=htmlentities($_REQUEST["password"]);
$email=htmlentities($_REQUEST["email"]);
$firstname=htmlentities($_REQUEST["firstname"]);
$lastname=htmlentities($_REQUEST["lastname"]);
*/

//check the get method values are null or not
if (empty($username) || empty($password) || empty($username) || empty($password)  || empty($username)  )
{
    $returnArray["status"]=800;
    $returnArray["message"]="Missing Required information";
    echo json_encode($returnArray);
    return;
}

//access the Test.ini file which contain the dbname,username for the database, password, db name
$file=parse_ini_file("Test.ini");

//get the values form Test.ini and assign those values to the variable
$host=trim($file["dbhost"]);
$user=trim($file["dbuser"]);
$pass=trim($file["dbpass"]);
$name=trim($file["dbname"]);


//require the access.php file
require ("Secure/access.php");


//call the access class constructor to assign the values which get from the Test.ini file
$access=new access($host,$user,$pass,$name);

//call the connect function to connect with the database
$access->connect();


//call the resiter function in the access.php

$result=$access->register($username,$password,$email,$firstname,$lastname);
if($result==1)
{
    //successfully registered
    $returnArray["status"]="200";
    $returnArray["message"]="Registered Successfully";
}
else
{
    //Not registred
    $returnArray["status"]="400";
    $returnArray["message"]="Registration Error";

}
//close the db connection
$access->disconnect();

//encode the array to json and send it to caller
echo json_encode($returnArray);

?>
