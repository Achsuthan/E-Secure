
<?php

ini_set('max_execution_time', 30000);


/***This is a php file
/***It is going to get the Malware samples Behaviour information from the JSON file
/***The JSON files are located in the samples folder
/***This project is done by E-Secure Team
/***SLIIT(Sri Lanka Institute of Information Technology)
/***Authors :
###### Mahendran Achsuthan ######
###### Krishnamohan Thebeyanthan ######
###### Selvakumar Ashok  ######
###### Paramananthasivam Vaikunthan ######
/***Project done in the 2017
/***More Details :
######  Achsuthan       achsuthan@icloud.com          ######
######  Thebeyanthan    Thebeyanthan9410@gmail.com    ######
######  Ashok           aaashok1225@gmail.com         ######
######  Vaikunthan      pvaikunthan28@gmail.com       ######
 ***/


/**Define the Json file directory**/
$directory="samples";
$temp_files = scandir("./".$directory);

/**Success API calls array**/
$success=array();

/**Fail API calls array**/
$fail=array();

/**Return code array**/
$return_value=array();

/**Get the return code**/
$return_code="";

/**Get the API calls**/
$api="";

/**To check whether the file is available in the Directory**/
$file_available=false;

/***Call the log.php file to create logs **/
require("./log.php");
/**Create log class object which is inside the log.php file**/
$log=new log();

$virus_total=array();
$temp=array();

$log->log_message("SUCCESS : Check JSON Files in the ".$directory." directory");
/**get the JSON file from the samples directory**/
foreach($temp_files as $files )
{
    /**get the JSON files only **/
    $len=count(explode(".",$files));
    if((explode(".",$files)[$len-1]=="json"))
    {


        $file_available=true;
        $log->log_message("SUCCESS : ".$files. " file found in the ".$directory." directory");

        /**Get file contents**/
        $str = file_get_contents($directory."/".$files);
        /**Decode the file json content to array**/
        $json = json_decode($str, true);

        $log->log_message($files." file Decode successfully ");
         


        array_push($temp,"File Name   : ".$json["target"]["file"]["name"]);
        array_push($temp,"Total       : ".$json["virustotal"]["total"]);
        array_push($temp,"Positives   : ".$json["virustotal"]["positives"]);
        array_push($temp,"Percentage  : ".$json["virustotal"]["positives"]/$json["virustotal"]["total"]*100);
        array_push($temp,"");


        /**Get the Calls array length**/
        $length=count($json["behavior"]["processes"][0]["calls"]);
        $log->log_message("SUCCESS : Start to get the API calls and return code form the JSON file  ".$files);
        for($i=0; $i<$length; $i++)
        {
            /**Get the API call names**/
            $api = $json["behavior"]["processes"][0]["calls"][$i]["api"];
            /**GET THE return code **/
            $return_code=$json["behavior"]["processes"][0]["calls"][$i]["return"];
            /**Get the success API calls**/
            if ($json["behavior"]["processes"][0]["calls"][$i]["status"] == 1)
            {
                /**Insert only new Success API calls**/
                $success_search=array_search($api,$success);
                if(!is_numeric($success_search))
                {
                    array_push($success,$api);
                    $log->log_message("SUCCESS : Success API found in the JSON file ".$files);
                }

            }

            /**Get the Failure API calls**/
            if ($json["behavior"]["processes"][0]["calls"][$i]["status"] == 0)
            {
                /**Insert only new Failure API**/
                $fail_search=array_search($api,$fail);
                if(!is_numeric($fail_search))
                {
                    array_push($fail,$api);
                    $log->log_message("SUCCESS : Failure API found in the JSON file ".$files);
                }
            }

            /**Insert only new retrun code to return_value array**/
            $return_search=array_search($return_code,$return_value);
            if(!is_numeric($return_search))
            {
                array_push($return_value,$return_code);
                $log->log_message("SUCCESS : Retrun Code found in the JSON file ".$files);
            }

        }
        $log->log_message("SUCCESS : Finished getting the API calls and return code form the JSON file  ".$files);
    }
}
if(!$file_available)
{
    $log->log_message("ERROR :  No file available in the samples Directory");
}
else {

    array_push($virus_total,$temp);
    //print_r(json_encode($virus_total));

    /****
     *
     *
     *
     *
     * This part must removed from here to
     *
     *
     *
     *
     */


    /**Store the APIs calls and retrun code in the training_array**/
    /**Create a array for store traing data_set**/
    /**Define the array with in the rows Samples name in the column success API calls Failure API calls and Return code**/
    $training_array = array();
    $training_array[0][0] = "Samples";

    /**Get the Success API calls name and store in the first rows**/
    for ($i = 0; $i < count($success); $i++) {
        $training_array[0][$i + 1] = $success[$i];
    }

    /**Get the Failure API calls name and store in the first rows**/
    for ($j = 0; $j < count($fail); $j++) {
        $training_array[0][$j + 1 + count($success)] = $fail[$j];
    }

    /**Get the Return code name and store in the first rows**/
    for ($k = 0; $k < count($return_value); $k++) {
        $training_array[0][$k + 1 + count($success) + count($fail)] = $return_value[$k];
    }


    $log->log_message("SUCCESS : Check JSON Files again in the " . $directory . " directory to store in the matrix");
    /**get the JSON file from the  directory**/

    /**sample variable is used to calculate the number of rows (no of samples**/
    $sample = 1;
    foreach ($temp_files as $files) {
        /**get the JSON files only **/
        $len = count(explode(".", $files));
        if ((explode(".", $files)[$len - 1] == "json")) {
            $training_array[$sample][0] = explode(".", $files)[0];
            $sample++;
        }
    }
    $log->log_message("SUCCESS : " . $sample . " No of samples identified from the " . $directory . " Directory ");

    /**Fill the training array with null value**/
    for ($i = 1; $i < $sample; $i++) {
        for ($j = 1; $j < count($success) + count($fail) + count($return_value) + 1; $j++) {
            $training_array[$i][$j] = 0;
        }
    }

    /**This sample is used to increment the rows in the training array**/
    $sample = 1;
    foreach ($temp_files as $files) {
        $log->log_message("SUCCESS :  counting the API calls and return calculation start from the " . $files . " JSON file");
        /**get the JSON files only **/
        $len = count(explode(".", $files));
        if ((explode(".", $files)[$len - 1] == "json")) {
            /**Get file contents**/
            $str = file_get_contents($directory . "/" . $files);
            /**Decode the file json content to array**/
            $json = json_decode($str, true);

            /**Get the Calls array length**/
            $length = count($json["behavior"]["processes"][0]["calls"]);
            for ($i = 0; $i < $length; $i++) {
                /**Get the API call names**/
                $api = $json["behavior"]["processes"][0]["calls"][$i]["api"];
                /**GET THE return code **/
                $return_code = $json["behavior"]["processes"][0]["calls"][$i]["return"];

                /**Get the success API calls**/
                if ($json["behavior"]["processes"][0]["calls"][$i]["status"] == 1) {
                    $success_search = array_search($api, $success);
                    if (is_numeric($success_search)) {
                        $training_array[$sample][1 + $success_search] = $training_array[$sample][1 + $success_search] + 1;
                    }

                }
                if ($json["behavior"]["processes"][0]["calls"][$i]["status"] == 0) {
                    $fail_search = array_search($api, $fail);
                    if (is_numeric($fail_search)) {
                        $training_array[$sample][1 + count($success) + $fail_search] = $training_array[$sample][1 + count($success) + $fail_search] + 1;
                    }
                }

                $return_search = array_search($return_code, $return_value);
                if (is_numeric($return_search)) {
                    $training_array[$sample][1 + count($success) + count($fail) + $return_search] = $training_array[$sample][1 + count($success) + count($fail) + $return_search] + 1;
                }

            }
            $log->log_message("SUCCESS :  counting the API calls and return calculation finished from the " . $files . " JSON file");
            $sample++;
        }

    }


    /**
     *
     *
     * For testing purpose
     *
     */

    /**printing all training array in table format**/

    /*
        $out  = "";
        $out .= "<table style='border-collapse: collapse;border: 1px solid black; text-align: center'>";

        for ($i=0; $i<1; $i++)
        {
            $out .= "<tr>";
            for($j=0; $j<count($training_array[$i]);$j++)
            {
                $value=$training_array[$i][$j];
                $out .= "<td style='border: 1px solid black; text-align: center'>$value</td>";
            }
            $out .= "</tr>";
        }

        for ($i=1; $i<count($training_array); $i++)
        {
            $out .= "<tr>";
            for($j=0; $j<count($training_array[$i]);$j++)
            {
                $value=$training_array[$i][$j];
                $out .= "<td style='border: 1px solid black; text-align: center'>$value</td>";
            }
            $out .= "</tr>";
        }


        $out .= "</table>";

        echo $out;

    */

    /**Printing all traing array vlaues finished**/


    /**
     *
     *
     * For testing purpose
     *
     */


    /****
     *
     *
     *
     *
     * This part must removed here
     *
     *
     *
     *
     */


    /****
     *
     *
     *
     *
     *
     * Create CSV file
     *
     *
     *
     */

    $log->log_message("SUCCESS : Start Create CSV file");
    /**open the file "sample.csv" for writing**/
    $file = fopen('sample.csv', 'w');
    chmod("sample.csv", 0777);


    /**save the column headers **/
    $column = array();

    for ($i = 0; $i < 1; $i++) {
        array_push($column, $training_array[$i]);
    }

    fputcsv($file, $column[0]);

    /**save each row of the data **/
    for ($j = 1; $j < count($training_array); $j++) {
        fputcsv($file, $training_array[$j]);
    }


    /**Close the file**/
    fclose($file);


    $file = fopen('VirusTotal.csv', 'w');
    chmod("VirusTotal.csv", 0777);


    /**save the column headers **/
    /*$column = array();

    for ($i = 0; $i < 1; $i++) {
        array_push($column, $training_array[$i]);
    }

    fputcsv($file, $column[0]);

    /**save each row of the data **/

    foreach ($virus_total as $output) {
        fputcsv($file, $output);
    }


    fclose($file);

    $log->log_message("SUCCESS  :   Finished CSV file creation");


    /***
     *
     *
     *
     * Finished create CSV file
     *
     *
     *
     */


    /**Create a array to pass the values to .js file **/
    $retrunArray = array();

    /**Get the malware samples name form training_array**/
    $viruscount = 0;
    $trojancount = 0;
    $wormscount = 0;
    $adwarecount = 0;


    for ($i = 0; $i < count($training_array); $i++) {
        if ($training_array[$i][0] == "virus" || $training_array[$i][0] == "Virus")
            $viruscount++;

        if ($training_array[$i][0] == "worms" || $training_array[$i][0] == "Worms")
            $wormscount++;

        if ($training_array[$i][0] == "trojan" || $training_array[$i][0] == "Trojan")
            $trojancount++;

        if ($training_array[$i][0] == "adware" || $training_array[$i][0] == "Adware")
            $adwarecount++;
    }

    /**Get the Malware samples name form training_array**/


    $log->log_message("SUCCESS  :   Clustering started");
    /**Run the python clustering cording from php **/
    $temp_files = scandir("./");
    foreach ($temp_files as $files) {
        $len = count(explode(".", $files));
        if ((explode(".", $files)[$len - 1] == "csv")) {
            $output = exec("python python/cluster.py " . $files);
        }
    }
    /**Run the python clustering cording from php **/
    $log->log_message("SUCCESS  :   Clustering completed");


    /**Testing purposes**/
    $trojancount = 10;
    $viruscount = 13;
    /**Testing purposes**/



    /**Count the clusters numbers for the moment we have two malware samples**/
    $zero = 0;
    $one = 0;
    $two = 0;
    /**Count the clusters numbers for the moment we have two malware saples**/

    /**Variable to get the cluster number each mulware and other category**/
    $trojannumber = -1;
    $virusnumber = -1;
    $wormsnumber = -1;
    $adwarenumber = -1;
    $othersnumber = -1;
    /**Variables to get the cluseter number each mulware and other category**/


    /**To get the each malware total number**/
    $totaltrojan = 0;
    $totalvirus = 0;
    $totaladware = 0;
    $totalworms = 0;
    $totalothers = 0;
    /**To get the each malware total number**/


    /**Get the total number of Trojan in the cluster**/
    $output = substr($output, 1, (strlen($output) - 2));
    $output = " " . $output;

    for ($i = 0; $i < $trojancount * 2; $i++) {
        if ($output[$i] == "0") {
            $zero++;
        }
        if ($output[$i] == "1") {
            $one++;
        }
        if ($output[$i] == "2") {
            $two++;
        }
    }

    if ($zero > $one) {
        if ($zero > $two) {
            $trojannumber = "0";
        } else {
            $trojannumber = "2";
        }

    } else {
        if ($one > $two) {
            $trojannumber = "1";
        } else {
            $trojannumber = "2";
        }
    }


    for ($i = 0; $i < strlen($output); $i++) {
        if ($output[$i] == $trojannumber) {
            $totaltrojan++;
        }
    }
    /**Get the total number of Trojan in the cluster**/



    $log->log_message("SUCCESS  :   No of Trojan clusters Identified");





    /**Get the total number of viurs in the cluster**/
    for ($i = $trojancount * 2 + 1; $i < strlen($output); $i++) {
        if ($output[$i] == "0") {
            $zero++;
        }
        if ($output[$i] == "1") {
            $one++;
        }
        if ($output[$i] == "2") {
            $two++;
        }
    }

    if ($zero > $one) {
        if ($zero > $two) {
            $virusnumber = "0";
        } else {
            $virusnumber = "2";
        }

    } else {
        if ($one > $two) {
            $virusnumber = "1";
        } else {
            $virusnumber = "2";
        }
    }


    for ($i = 0; $i < strlen($output); $i++) {
        if ($output[$i] == $virusnumber) {
            $totalvirus++;
        }
    }
    /**Get the total number of virus in the cluster**/



    $log->log_message("SUCCESS  :   No of Virus cluster identified");




    /**Get the total number of others in the clusters**/
    for ($i = 0; $i < strlen($output); $i++) {
        if (($output[$i] != $virusnumber && $output[$i] != $trojannumber) && $output[$i] != " ") {
            $totalothers++;
        }
    }
    /**Get the total number of others in the clusters**/


    $log->log_message("SUCCESS  :   No of other clusters identified");




    /**Store the information in the database**/
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

    /*if (!$statement)
    {
        throw new Exception($statement->error);
    }*/
    $statement->bind_param("sssss", $totaltrojan, $totalvirus, $totalworms, $totaladware, $totalothers);
    $statement->execute();
    $conn->close();
    /**Store the information in the database**/


    $log->log_message("SUCCESS  :   Cluster details stored successfully in the database");


    /**Return the values to the .js file**/
    $retrunArray["status"] = "200";
    $retrunArray["virus"] = $totalvirus;
    $retrunArray["worms"] = $totalworms;
    $retrunArray["trojan"] = $totaltrojan;
    $retrunArray["adware"] = $totaladware;
    $retrunArray["others"] = $totalothers;
    echo json_encode($retrunArray);
    /**Retrun the values to the .js file**/
}

?>

