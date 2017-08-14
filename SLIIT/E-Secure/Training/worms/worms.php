
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
$directory="worms_samples";
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
else
{

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
    $training_array=array();
    $training_array[0][0]="Samples";

    /**Get the Success API calls name and store in the first rows**/
    for($i=0; $i<count($success); $i++)
    {
        $training_array[0][$i+1]=$success[$i];
    }

    /**Get the Failure API calls name and store in the first rows**/
    for($j=0; $j<count($fail);$j++)
    {
        $training_array[0][$j+1+count($success)]=$fail[$j];
    }

    /**Get the Return code name and store in the first rows**/
    for($k=0; $k<count($return_value);$k++)
    {
        $training_array[0][$k+1+count($success)+count($fail)]=$return_value[$k];
    }


    $log->log_message("SUCCESS : Check JSON Files again in the ".$directory." directory to store in the matrix");
    /**get the JSON file from the  directory**/

    /**sample variable is used to calculate the number of rows (no of samples**/
    $sample=1;
    foreach($temp_files as $files )
    {
        /**get the JSON files only **/
        $len=count(explode(".",$files));
        if((explode(".",$files)[$len-1]=="json"))
        {
            $training_array[$sample][0]=explode(".",$files)[0];
            $sample++;
        }
    }
    $log->log_message("SUCCESS : ".$sample." No of samples identified from the ".$directory." Directory ");

    /**Fill the training array with null value**/
    for($i=1; $i<$sample; $i++)
    {
        for($j=1; $j<count($success)+count($fail)+count($return_value)+1; $j++)
        {
            $training_array[$i][$j]=null;
        }
    }

    /**This sample is used to increment the rows in the training array**/
    $sample=1;
    foreach($temp_files as $files )
    {
        $log->log_message("SUCCESS :  counting the API calls and return calculation start from the ".$files." JSON file");
        /**get the JSON files only **/
        $len=count(explode(".",$files));
        if((explode(".",$files)[$len-1]=="json"))
        {
            /**Get file contents**/
            $str = file_get_contents($directory."/".$files);
            /**Decode the file json content to array**/
            $json = json_decode($str, true);

            /**Get the Calls array length**/
            $length=count($json["behavior"]["processes"][0]["calls"]);
            for($i=0; $i<$length; $i++)
            {
                /**Get the API call names**/
                $api = $json["behavior"]["processes"][0]["calls"][$i]["api"];
                /**GET THE return code **/
                $return_code=$json["behavior"]["processes"][0]["calls"][$i]["return"];

                /**Get the success API calls**/
                if ($json["behavior"]["processes"][0]["calls"][$i]["status"] == 1)
                {
                    $success_search=array_search($api,$success);
                    if(is_numeric($success_search))
                    {
                        $training_array[$sample][1+$success_search]=$training_array[$sample][1+$success_search]+1;
                    }

                }
                if ($json["behavior"]["processes"][0]["calls"][$i]["status"] == 0)
                {
                    $fail_search=array_search($api,$fail);
                    if(is_numeric($fail_search))
                    {
                        $training_array[$sample][1+count($success)+$fail_search] = $training_array[$sample][1+count($success)+$fail_search]+1;
                    }
                }

                $return_search=array_search($return_code,$return_value);
                if(is_numeric($return_search))
                {
                    $training_array[$sample][1+count($success)+count($fail)+$return_search]=$training_array[$sample][1+count($success)+count($fail)+$return_search]+1;
                }

            }
            $log->log_message("SUCCESS :  counting the API calls and return calculation finished from the ".$files." JSON file");
            $sample++;
        }

    }

    $log->log_message("SUCCESS : Check JSON Files again finished in the ".$directory." directory to store in the matrix");


    /**
     *
     *
     * For testing purpose
     *
     */

    /**printing all training array in table format**/
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
    /**open the file "worms.csv" for writing**/
    $file = fopen('worms.csv', 'w');
    chmod("worms.csv",0777);


    /**save the column headers **/
    $column=array();

    for ($i=0; $i<1; $i++)
    {
        array_push($column,$training_array[$i]);
    }

    fputcsv($file, $column[0]);

    /**save each row of the data **/
    for ($j=1; $j<count($training_array);$j++)
    {
        fputcsv($file, $training_array[$j]);
    }


    /**Close the file**/
    fclose($file);

    $log->log_message("SUCCESS  :  Finished CSV file creation");



    /***
     *
     *
     *
     * Finished create CSV file
     *
     *
     *
     */


}

?>
