<?php


$output = '';
$output .= '<table style="width:1250px; border: 1px solid black; text-align: center; vertical-align:middle;">';
$fp = fopen("/home/achsuthan/SLIIT/E-Sucure/Training/python/sample.csv","r");

$output .= '  
                     <th>  ';

// get the first (header) line
$header = fgetcsv($fp);

$count=0;
foreach ($header as $j)
{
 if($count>0)
	{
 		$output .= ' 
                          <td style="border: 1px solid black;" >' . $j. '</td>   
                ';
	}
   $count++;
}
$output .='</th>';

$header = fgetcsv($fp);
// get the rest of the rows
$data = array();
$output .= '  
                     <tr>  ';
while ($row = fgetcsv($fp)) {
  $arr = array();
  foreach ($header as $i => $col)
  {
     $output .= ' 
                          <td style="border: 1px solid black;" >' . $row[$i]. '</td>   
                ';
  }
   
  $output .='</tr>';
}

echo $output;

?>
