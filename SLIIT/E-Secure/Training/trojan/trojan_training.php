<?php


ini_set('max_execution_time', 30000);

require("./log.php");
$log=new log();


$files = glob('trojan_samples/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}


$log->log_message("SUCCESS  :  Trojan training session start");

$directory="trojan";
$filename=array();
$output_directory_name=array();
$name="";
$files = array_slice(scandir($directory."/"), 2);

for($j=0; $j<count($files);$j++)
{
    $target_path="/home/achsuthan/SLIIT/E-Sucure/Training/trojan/".$directory."/".$files[$j];
    $output=exec("python ../../../../cuckoo-1.0/utils/submit.py ".$target_path);
    $output=explode(" ", $output);
    array_push($output_directory_name,$output[8]);
    $filepath="../../../../cuckoo-1.0/storage/analyses/".$output[8]."/reports/report.json";


     array_push($filename,$files[$j]);

}

for($i=0; $i<count($output_directory_name); $i++)
{
    $filepath="../../../../cuckoo-1.0/storage/analyses/".$output_directory_name[$i]."/reports/report.json";

    file_search:

    if(file_exists($filepath))
    {
        sleep(5);
        copy($filepath, "trojan_samples/".$filename[$i].".json");
	    chmod("trojan_samples/".$filename[$i].".json",0777);
    }

    else
    {
        goto file_search;
    }
}

$log->log_message("SUCCESS  :  Trojan training session finished");


header("location:trojan.php");

?>
