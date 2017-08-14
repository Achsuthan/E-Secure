<?php


class log
{
    public $Handler;
    public function log_message($arMsg)
    {
        //define empty string
        $stEntry = "";
        //get the event occur date time,when it will happened
        $arLogData['event_datetime'] = '[' . date('D Y-m-d h:i:s A') . ']';
        //if message is array type
        if (is_array($arMsg)) {
            //concatenate msg with datetime
            foreach ($arMsg as $msg)
                $stEntry .= $arLogData['event_datetime'] . " " . $msg . "rn";
        } else {   //concatenate msg with datetime

            $stEntry .= $arLogData['event_datetime'] . " " . $arMsg . "\r\n\r\n";
        }
        //create file with current date name
        //$stCurLogFileName = 'log_' . date('Ymd') . '.txt';
        $stCurLogFileName = 'virus.txt';
        chmod($stCurLogFileName,0777);
        //open the file append mode,dats the log file will create day wise
        $this->fHandler = fopen("./" . $stCurLogFileName, 'a+');
        //write the info into the file
        fwrite($this->fHandler, $stEntry);
        //close handler

        $this->close();
    }

    public function close()
    {
        fclose($this->fHandler);
    }

}


?>
