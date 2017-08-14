<?php


$details=$_REQUEST["filename"];

$output=json_decode($details,true);
$path=$output["path"];
$userid=$output["id"];
$filename=$output["output"];
$attachment=$output["attachmentname"];

$filepath="../../cuckoo-1.0/storage/analyses/".$filename."/reports/report.json";


if (file_exists($filepath))
{
    $random=rand(0,1000000000000);
    $report = file_get_contents($filepath);
    $json = json_decode($report, true);

    $retunArray["id"]=$random;
    $retunArray["started"] = $json["info"]["started"];
    $retunArray["ended"]=$json["info"]["ended"];
    $retunArray["duration"]=$json["info"]["duration"];
    $retunArray["sha1"]=$json["target"]["file"]["sha1"];
    $retunArray["sha256"]=$json["target"]["file"]["sha256"];
    $retunArray["md5"]=$json["target"]["file"]["md5"];
    $retunArray["name"]=$json["target"]["file"]["name"];
    $retunArray["type"]=$json["target"]["file"]["type"];
    $retunArray["size"]=$json["target"]["file"]["size"];





    $j_file=$json["target"]["file"]["name"];

    if (!file_exists("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid))
    {

        mkdir("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid, 0777, true);
        mkdir("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/file", 0777, true);
        mkdir("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/json", 0777, true);
        mkdir("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/csv", 0777, true);
    }
    //chmod("/Applications/XAMPP/xamppfiles/htdocs/SLIIT/E-Sucure/uploads/".$username,777);

    $json_file=$userid."-".$random.".json";
    $user_file=$userid."-".$random.$attachment;
    copy($filepath,"/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/json/".$json_file);
    copy($path,"/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/file/".$user_file);




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

    if(file("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/json/".$json_file))
    {
        $file_available=true;
        $str= file_get_contents("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/json/".$json_file);
        $json = json_decode($str, true);
        //print_r($json);

        $length=count($json["behavior"]["processes"][0]["calls"]);

        for($i=0; $i<$length; $i++)
        {
            $api = $json["behavior"]["processes"][0]["calls"][$i]["api"];
            $return_code = $json["behavior"]["processes"][0]["calls"][$i]["return"];
            if ($json["behavior"]["processes"][0]["calls"][$i]["status"] == 1) {
                $success_search = array_search($api, $success);
                if (!is_numeric($success_search)) {
                    array_push($success, $api);
                }

            }

            if ($json["behavior"]["processes"][0]["calls"][$i]["status"] == 0) {
                $fail_search = array_search($api, $fail);
                if (!is_numeric($fail_search)) {
                    array_push($fail, $api);
                }
            }

            $return_search = array_search($return_code, $return_value);
            if (!is_numeric($return_search)) {
                array_push($return_value, $return_code);
            }
        }
        if(!$file_available)
        {

        }
        else
        {
            $training_array = array();
            $training_array[0][0] = "Filename";

            for ($i = 0; $i < count($success); $i++) {
                $training_array[0][$i + 1] = $success[$i];
            }

            for ($j = 0; $j < count($fail); $j++) {
                $training_array[0][$j + 1 + count($success)] = $fail[$j];
            }

            for ($k = 0; $k < count($return_value); $k++) {
                $training_array[0][$k + 1 + count($success) + count($fail)] = $return_value[$k];
            }

            $sample = 1;

            $training_array[$sample][0]=$j_file;


            for ($j = 1; $j < count($success) + count($fail) + count($return_value) + 1; $j++)
            {
                $training_array[1][$j] = 0;
            }

            $sample = 1;


            $length = count($json["behavior"]["processes"][0]["calls"]);
            for ($i = 0; $i < $length; $i++)
            {
                $api = $json["behavior"]["processes"][0]["calls"][$i]["api"];
                $return_code = $json["behavior"]["processes"][0]["calls"][$i]["return"];

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
            $sample++;


            if(file_exists("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/csv/".$random."csv.csv"))
            {
                $file = fopen("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/csv/".$random."csv.csv", 'w');
                $column = array();

                for ($i = 0; $i < 1; $i++) {
                    array_push($column, $training_array[$i]);
                }

                fputcsv($file, $column[0]);

                for ($j = 1; $j < count($training_array); $j++) {
                    fputcsv($file, $training_array[$j]);
                }


            }
            else
            {
                $file = fopen("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/csv/".$random."csv.csv", 'w');

                chmod("/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/csv/".$random."csv.csv",777);

                $column = array();
                for ($i = 0; $i < 1; $i++) {
                    array_push($column, $training_array[$i]);
                }

                fputcsv($file, $column[0]);
                for ($j = 1; $j < count($training_array); $j++) {
                    fputcsv($file, $training_array[$j]);
                }
            }

            fclose($file);

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


    $fileid="";
    $sql= "Select * from user_file ORDER BY id DESC LIMIT 1; ";

    $result=$conn->query($sql);

    if ($result !=null && (mysqli_num_rows($result)>=1))
    {
        $row=$result->fetch_array(MYSQLI_ASSOC);
        if(!empty($row))
        {
            //echo substr('abcdef', 1, 3);  // bcd

            $id=substr($row["user_file_id"], 4, 6);

            $id=$id+1;
            $fileid="FILE".$id;
        }
        else
        {
            $fileid="FILE"."111111";
        }
    }
    else
    {
        $fileid="FILE"."111111";
    }





    $id=$retunArray["id"];
    $person=$output["person"];
    $type=$json["target"]["file"]["type"];
    $filename=$retunArray["name"];
    $date=date("Y-m-d");
    date_default_timezone_set("Asia/Colombo");
    $time=date("h:i:sa");
    $csv="/home/achsuthan/SLIIT/E-Sucure/uploads/".$userid."/csv/".$random."csv.csv";
    $malware="{malware}";
    $critical="{critical}";
    $path="http://192.168.172.140/SLIIT/E-Sucure/uploads/".$userid."/file/".$user_file;

    $sql = "INSERT INTO user_file SET user_file_id=?,cuckoo_id=?,date=?,time=?,filename=?,filetype=?,malware=?,critical=?,attachment_path=?,csv_path=?,person=?,user_id=?";
    $statement = $conn->prepare($sql);

    //if (!$statement)
    //{
      //  throw new Exception($statement->error);
    //}

    $statement->bind_param("ssssssssssss", $fileid, $id, $date, $time, $filename,$type,$malware,$critical,$path,$csv,$person,$userid);
    $statement->execute();

    $conn->close();

    echo json_encode($retunArray);

}

?>
