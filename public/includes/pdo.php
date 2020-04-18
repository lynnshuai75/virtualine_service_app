<?php

 class Database { 
// connetion properties
//private  $dsn = "mysql:host=localhost;dbname=virtualines_db";
    private $dbhost ="localhost";
    private $dbuser ="root";
    private $dbpass  ="";
    private $dbname ="virtualines_db";
    

// connection handler
private $dbh ;

//statment Handler
private $stmt;
// open our connection
public function __construct(){
    $dsn = "mysql:host=" .$this->dbhost . "; dbname=" . $this->dbname;
    $options = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
        $this->dbh = new PDO ($dsn, $this->dbuser, $this->dbpass, $options);
       // echo "Connection Successful";

    }
     catch(PDOException $errorobj){
         $this->error = $errorobj->getMessage();
         echo $this->error;
     }
}
//**  Method to handle query statement */
public function query($query){             //query("SELECT * FROM users WHRE email =:email");
    $this->stmt = $this->dbh->prepare($query);
}


//** Method to handle bind values */
public function bind($param, $value, $type){                         // bind(":email, $email, PDO::PARAM_STR");
    $this->stmt->bindValue($param, $value, $type);
}


//*** Method to execute or run our statement */
public function execute(){
    return $this->stmt->execute();
}

//** Method to handle to fetch single values */
public function fetchSingle(){
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
}


//** Method to handle , to fetch multiple values */
public function fetchMultiple(){
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);

}
//** Method to count number of rows */
public function fetchColumn(){
    $this->execute();
    return $this->stmt->fetchColumn();
}


 }


?>