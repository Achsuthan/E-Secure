<?php


$temp_files = scandir("./");
foreach($temp_files as $files)
{
    $len=count(explode(".",$files));
    if((explode(".",$files)[$len-1]=="csv"))
    {
        $output=exec("python python/cluster.py ".$files);
    }
}

echo $output;
echo "<br/>";

$trojancount=10;
$viruscount=13;


$zero=0;
$one=0;
$two=0;

$trojannumber=-1;
$virusnumber=-1;
$wormsnumber=-1;
$adwarenumber=-1;
$othersnumber=-1;


$totaltrojan=0;
$totalvirus=0;
$totaladware=0;
$totalworms=0;
$totalothers=0;

$output=substr($output,1,(strlen($output)-2));
$output=" ".$output;

for($i=0; $i<$trojancount*2; $i++)
{
    if($output[$i]=="0")
    {
        $zero++;
    }
    if($output[$i]=="1")
    {
        $one++;
    }
    if($output[$i]=="2")
    {
        $two++;
    }
}

if($zero>$one)
{
    if($zero>$two)
    {
        $trojannumber="0";
    }
    else
    {
        $trojannumber="2";
    }

}
else
{
    if($one>$two)
    {
        $trojannumber="1";
    }
    else
    {
        $trojannumber="2";
    }
}


for($i=0; $i<strlen($output); $i++)
{
	if($output[$i]==$trojannumber)
	{
		$totaltrojan++;
	}
}









for($i=$trojancount*2+1; $i<strlen($output); $i++)
{
    if($output[$i]=="0")
    {
        $zero++;
    }
    if($output[$i]=="1")
    {
        $one++;
    }
    if($output[$i]=="2")
    {
        $two++;
    }
}

if($zero>$one)
{
    if($zero>$two)
    {
        $virusnumber="0";
    }
    else
    {
        $virusnumber="2";
    }

}
else
{
    if($one>$two)
    {
        $virusnumber="1";
    }
    else
    {
        $virusnumber="2";
    }
}


for($i=0; $i<strlen($output); $i++)
{
	if($output[$i]==$virusnumber)
	{
		$totalvirus++;
	}
}







for($i=0; $i<strlen($output); $i++)
{
	if(($output[$i]!=$virusnumber && $output[$i]!=$trojannumber) && $output[$i] !=" ")
	{
		$totalothers++;
	}
}





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


$sql = "INSERT INTO training SET trojan=?,virus=?,worms=?,adware=?,others=?";
            $statement = $conn->prepare($sql);

            /*if (!$statement) {
                throw new Exception($statement->error);
            }*/
            $statement->bind_param("sssss", $totaltrojan, $totalvirus, $totalworms, $totaladware, $totalothers);
            $statement->execute();

$conn->close();






echo $totalothers;
echo "<br/>";
echo $totalvirus;
echo "<br/>";
echo $totaltrojan;

?>

