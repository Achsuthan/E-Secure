<?php

//this class is going to interact with the database
class access
{

    //create needed variable for this class

    var $host=null;
    var $user=null;
    var $pass=null;
    var $dbname=null;
    var $con=null;
    var $result=null;
    var $payment;
    var $hand;
    var $submitted;
    var $uid;


    function __construct($dbhost,$dbuser,$dbpass,$dbname)   //get the values from the caller
    {
        $this->host=$dbhost;   //assgin the hostname
        $this->user=$dbuser;   //assign the username
        $this->pass=$dbpass;   //assign the password
        $this->dbname=$dbname;  //assgin the db name
    }

    public function connect()   //DB connection
    {
        $this->con=new mysqli($this->host,$this->user,$this->pass,$this->dbname);  //get the host,user,password and the dbname from the caller
        if(mysqli_connect_error())  //check whether the db connection contain any error
        {
            echo "Could no connect databe"; //prompt the error message
        }
        $this->con->set_charset("utf8");
    }

    //disconnect the db connection
    public function disconnect()
    {
        if($this->con!=null)  //check whether the con variable contain any value
        {
            $this->con->close();  //close the db connection
        }
    }



    public function loginuser($username,$password)  //getting the values
    {
        $sql="select * from user where username='".$username."' and password='".$password."'"; //sql query for get the user's details with username and password
        $result=$this->con->query($sql);  //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the row values from database
            if(!empty($row))      //check whether the row value is null or not
            {
                $returnArray=$row;   // assign the row values to the retrunarray
            }
            return $returnArray;   //return the returnarry to caller
        }
    }

    //Register user
    public function register($username,$password,$email,$firstname,$lastname)
    {
        $this->createid();  //crate auto increment id for the user

        $sql = "INSERT INTO user SET username=?,password=?,email=?,firstname=?,lastname=?,uid=?";  //sql query for insert values to the database
        $statement = $this->con->prepare($sql);   //get the statement executing the sql query

        if (!$statement)    //check whether the statement contain any results
        {
            throw new Exception($statement->error);   //error message
        }
        $statement->bind_param("ssssss", $username,$password, $email, $firstname,$lastname,$this->uid);  //pass the values

        $returnvalue = $statement->execute();  //executing the sql query
        return 1;   //return the caller to notify that the user is inserted successfully
    }


    //creating userid with specific string and number
    public function createid()
    {

        $sql= "Select * from user ORDER BY id DESC LIMIT 1; ";  //get the last value form the database

        $result=$this->con->query($sql); //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                //echo substr('abcdef', 1, 3);  // bcd

                $id=substr($row["uid"], 3, 6);  //get the integer potion part for  fro example if the database contain a uid USR111111, get the last 6 digit

                $id=$id+1;  //increase the last 6 digit value by one
                $this->uid="USR".$id;  //asign back to id as a USR111112
            }
        }
    }

    public function loginadmin($username,$password)  //getting the values
    {
        $sql="select * from admin where username='".$username."' and password='".$password."'"; //sql query for get the user's details with username and password
        $result=$this->con->query($sql);  //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the row values from database
            if(!empty($row))      //check whether the row value is null or not
            {
                $returnArray=$row;   // assign the row values to the retrunarray
            }
            return $returnArray;   //return the returnarry to caller
        }
    }

    public function usernamecheck($username)
    {
        $sql="select * from user where username='".$username."'"; //sql query for get the user's details with username and password
        $result=$this->con->query($sql);  //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the row values from database
            if(!empty($row))      //check whether the row value is null or not
            {
                $returnArray=$row;   // assign the row values to the retrunarray
            }
            return $returnArray;   //return the returnarry to caller
        }
    }

}
?>