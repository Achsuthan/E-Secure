<?php



$username=htmlentities($_REQUEST["username"]);
$password=htmlentities($_REQUEST["password"]);


if (empty($username) || empty($password) )
{
    $returnArray["status"]=800;
    $returnArray["message"]="Missing Required information";
    echo json_encode($returnArray);
    return;
}

//$salt=openssl_random_pseudo_bytes(20);
//$securedpassword=sha1($password.$salt);

$file=parse_ini_file("Test.ini");

$host=trim($file["dbhost"]);
$user=trim($file["dbuser"]);
$pass=trim($file["dbpass"]);
$name=trim($file["dbname"]);

require ("Secure/access.php");

$access=new access($host,$user,$pass,$name);
$access->connect();


$result=$access->loginuser($username,$password);
if($result)
{

    $uservalue=$access->getuser($username);
    $returnArray["status"]="200";
    $returnArray["message"]="User found";
    $returnArray["name"]=$uservalue["name"];
}
else
{
    $returnArray["status"]="400";
    $returnArray["message"]="User not found";
}
$access->disconnect();
echo json_encode($returnArray);

?>
