<?php

class access
{

    var $host=null;
    var $user=null;
    var $pass=null;
    var $dbname=null;
    var $con=null;
    var $result=null;
    var $payment;
    var $hand;
    var $submitted;


    function __construct($dbhost,$dbuser,$dbpass,$dbname)
    {
        $this->host=$dbhost;
        $this->user=$dbuser;
        $this->pass=$dbpass;
        $this->dbname=$dbname;
    }

    public function connect()
    {
        $this->con=new mysqli($this->host,$this->user,$this->pass,$this->dbname);
        if(mysqli_connect_error())
        {
            echo "Could no connect databe";
        }
        $this->con->set_charset("utf8");
    }

    public function disconnect()
    {
        if($this->con!=null) {
            $this->con->close();
        }
    }

    public function logindoctor($username,$password)
    {
        $sql="select * from doctor where name='".$username."' and password='".$password."'";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
            return $returnArray;
        }
    }

    public function loginuser($username,$password)
    {
        $sql="select * from user where name='".$username."' and password='".$password."'";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
            return $returnArray;
        }
    }

    public function registerdoctor($name,$dob,$gender,$nic,$address,$city,$state,$zip,$email,$spe,$time,$password)
    {
        if($this->checkuser($name,"doctor"))
        {
            return 0;
        }
        else {
            $sql = "INSERT INTO doctor SET name=?,dob=?,gender=?,nic=?,address=?,city=?,state=?,zip=?,email=?,spe=?,time=?,password=?";
            $statement = $this->con->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ssssssssssss", $name, $dob, $gender, $nic, $address, $city, $state, $zip, $email, $spe, $time, $password);

            $returnvalue = $statement->execute();
            return 1;
        }
    }

    public function registeruser($name,$dob,$gender,$nic,$address,$city,$state,$zip,$email,$doctors,$password)
    {
        if($this->getuser($name))
        {
            return 0;
        }
        else {
            $sql = "INSERT INTO user SET name=?,dob=?,gender=?,nic=?,address=?,city=?,state=?,zip=?,email=?,password=?,doctors=?";
            $statement = $this->con->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("sssssssssss", $name, $dob, $gender, $nic, $address, $city, $state, $zip, $email, $password,$doctors);

            $returnvalue = $statement->execute();
            return 1;
        }
    }

    public function checkuser($name,$db)
    {
        $sql="select * from ".$db." where name='".$name."'";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
            return $returnArray;
        }
    }

    public function getuser($name)
    {
        $sql="select * from user where name='".$name."'";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
            return $returnArray;
        }
    }

    public function getdoctor($name)
    {
        $sql="select * from doctor where name='".$name."'";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
            return $returnArray;
        }
    }

    public function getdoctoruser($name)
    {
        $query="select doctors from user where name='".$name."'";
        $result=mysqli_query($this->con,$query);
        $no_of_rows=mysqli_num_rows($result);

        $returnArray=array();

        if ($no_of_rows > 0)
        {
            while($row=mysqli_fetch_assoc($result))
            {
                $returnArray[]=$row;
            }
        }
        return $returnArray;
    }

    public function getdoctors()
    {
        $query= "Select name from doctor; ";

        $result=mysqli_query($this->con,$query);
        $no_of_rows=mysqli_num_rows($result);

        $temp_array=array();

        if ($no_of_rows > 0)
        {
            while($row=mysqli_fetch_assoc($result))
            {
                $returnArray[]=$row;
            }
        }

        return $returnArray;
    }
    public function getusers()
    {
        $sql="select name from doctor ";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
            return $returnArray;
        }
    }


    public function senddoctor($doctor,$weight,$presure,$file,$morning,$lunch,$night,$more,$user)
    {



        $sql="select * from message where userid='".$user."' and doctorid='".$doctor."'";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
        }

        if($returnArray)
        {
            return 0;
        }
        else {

            $sql = "INSERT INTO message SET weight=?,presure=?,file=?,morning=?,lunch=?,dinner=?,more=?,userid=?,doctorid=?";
            $statement = $this->con->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("sssssssss", $weight, $presure, $file, $morning, $lunch, $night, $more, $user, $doctor);

            $statement->execute();

            return 1;
        }

    }


    public function getmessages($username)
    {
        $query= "Select userid from message where doctorid='".$username."'; ";

        $result=mysqli_query($this->con,$query);
        $no_of_rows=mysqli_num_rows($result);

        $temp_array=array();

        if ($no_of_rows > 0)
        {
            while($row=mysqli_fetch_assoc($result))
            {
                $returnArray[]=$row;
            }
        }

        return $returnArray;

        return $returnArray;
    }

    public function getmessage($uid,$did)
    {
        $sql= "Select * from message where doctorid='".$did."' and userid='".$uid."'; ";

        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
            return $returnArray;
        }
    }


    public function reply($uid,$did,$reply)
    {

        if($this->checkreply($uid,$did))
        {
            return 0;

        }
        else
        {
            $available="1";
            $sql = "UPDATE message SET  reply=?, available=? WHERE userid='".$uid."' and doctorid='".$did."'";
            $statement = $this->con->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }

            $statement->bind_param("ss", $reply,$available);

            $statement->execute();

            return 1;
        }
    }


    public function checkreply($uid,$did)
    {
        $sql= "Select * from message where doctorid='".$did."' and userid='".$uid."'and available=1; ";

        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row))
            {
                $returnArray=$row;
            }
            return $returnArray;
        }
    }


   
    public function updatepasswordtemp($id,$password)
    {
        $newtemppassword="";

        $sql="UPDATE user SET  temppassword=?,password=? WHERE id='".$id."'";
        $statement=$this->con->prepare($sql);

        if(!$statement)
        {
            throw new Exception($statement->error);
        }

        $statement->bind_param("ss",$newtemppassword,$password);

        $statement->execute();

    }

}
?>