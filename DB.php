<?php 
class Database
{
    private static $objAccesDB;
    public $objPDO;

    public static $url="localhost";
    public static $dbname="adoclic";
    public static $user="root";
    public static $pass="";

    private function __construct()
    {
        try { 
            $this->objPDO = new PDO('mysql:host='.self::$url.';dbname='.self::$dbname.';charset=utf8', self::$user, self::$pass, array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objPDO->exec("SET CHARACTER SET utf8");
            } 
        catch (PDOException $e) { 
            print "Error!: " . $e->getMessage(); 
            die();
        }
    }
 
    public function getQuery($sql)
    { 
        return $this->objPDO->prepare($sql); 
    }
    
    public function getLastId()
    { 
        return $this->objPDO->lastInsertId(); 
    }

    public static function objDB()
    { 
        if (!isset(self::$objAccesDB)) {          
            self::$objAccesDB = new Database(); 
        } 
        return self::$objAccesDB;        
    }
 
 
     // Evita que el objeto se pueda clonar
    public function __clone()
    { 
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
    }
}
