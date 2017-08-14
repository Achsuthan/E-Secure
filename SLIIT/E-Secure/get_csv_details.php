<?php

$fileid=$_GET["id"];



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E-Secure";

$conn = new mysqli($servername, $username, $password, $dbname);


$sql= "Select * from user_file where user_file_id='".$fileid."'";
$result=$conn->query($sql);



$output = '';
$output .= '<table style="width:1250px; border: 1px solid black; text-align: center; vertical-align:middle;">';



if ($result !=null && (mysqli_num_rows($result)>=1))
{
    $row=$result->fetch_array(MYSQLI_ASSOC);
    if(!empty($row))
    {
        $file = fopen($row["csv_path"],"r");
        $count=0;
        while(! feof($file))
        {

            $array=array();
            $array=fgetcsv($file);
            if($count==0)
            {
                $output .= '  
                     <tr>  ';
                for($i=0; $i<count($array); $i++)
                {
                    $output .= ' 
                          <td style="border: 1px solid black;" >' . $array[$i]. '</td>   
                ';
                }
                $output .='</tr>';
            }
            else
            {
                $output .= '  
                     <tr>  ';
                for($i=0; $i<count($array); $i++)
                {
                    $output .= ' 
                          <td style="border: 1px solid black;">' . $array[$i]. '</td>   
                ';
                }
                $output .='</tr>';
                $output .= '</table>';
            }
            $count++;
        }

        fclose($file);
    }
}
$conn->close();
echo $output;

?>
