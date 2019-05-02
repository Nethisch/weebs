<?php

class Connect {

    public $pdo;

    public function __construct(){

        // parent::__construct();

        $dsn = 'mysql:host=localhost;dbname=testdb';//init host dan nama database
        $username = 'root';//init username
        $password = '';//init password
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',//ntah, gw ngopi yg ini
        ); 

        $conn = new PDO($dsn, $username, $password, $options);//init PDO

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //biar bisa nge catch error

        $this->pdo = $conn; //masukin PDO ke property global
    }

    public function getData($param = ''){

        $pdo = $this->pdo; //property global dari konstruktor dimasukin ke var pdo

        if(!empty($param) && isset($param)){
            
            $stmt = explode('-',$param);//parameter lemparan di explode, formatnya paramter itu namaKolom-dataKolom, misalkan gender-male

            $searcher = $stmt[0];//isinya kolom database, misal gender

            $searchData = $stmt[1];//isinya data yang mau dicari, misal male

            if(!empty($searcher) && isset($searcher)){

                $sql = "
                    SELECT * FROM sample_data WHERE $searcher = :$searcher
                ";

                $query = $pdo->prepare($sql);//di prepare

                $query->bindParam(":$searcher", $searchData);//nyatain :$seacher jadi $searchData, contoh bacanya :gender, male

            }else{

                $sql = "
                    SELECT COUNT(id) jumlah, gender FROM sample_data GROUP BY gender
                ";
    
                $query = $pdo->prepare($sql);
            }
            
           
        }else{
            
            $sql = "
                SELECT COUNT(DISTINCT gender) jumlah, 'gender' gender FROM sample_data
            ";

            $query = $pdo->prepare($sql);
        }

        $query->execute();//jalanin query

        $result = $query->fetchAll(PDO::FETCH_ASSOC);//ambil data jadi array assoc

        return $result; //return


    }

    public function gridData($method, $param = array()){
        
        $pdo = $this->pdo;//property global dari konstruktor dimasukin ke var pdo

        switch ($method) {//jika methodnya sesuatu, lakukan sesuatu
            case 'GET': 
                    if(!empty($param) && isset($param)){
                    
                        $query = "SELECT * FROM sample_data WHERE gender LIKE ? ORDER BY id DESC";
                        $statement = $pdo->prepare($query);//prepare sql querynya
                        $statement->execute(array($param['gender']));//masukin ? jadi male/female
                    
                    }
                    $result = $statement->fetchAll();//ambil data
                        
                    return $result;//return

                break;

            case 'POST':

                    $first_name = $param['first_name'];//data parameter
                    $last_name = $param['last_name'];//data parameter
                    $age = $param['age'];//data parameter
                    $gender = $param['gender'];//data parameter
                
                    $query = "INSERT INTO sample_data (first_name, last_name, age, gender) VALUES (?,?,?,?)";
                    $statement = $pdo->prepare($query);//prepare sql querynya
                    $statement->execute(array($first_name,$last_name,$age,$gender));//masukin ? jadi variable sesuai urutan/ berurutan, klo masih gak ngeti yaudahlah, gw males ngetiknya
                    
                break;

            case 'PUT'://sama kek yg post
                    $first_name = $param['first_name'];
                    $last_name = $param['last_name'];
                    $age = $param['age'];
                    $gender = $param['gender'];
                    $id = $param['id'];
                
                    $query = "
                        UPDATE sample_data 
                        SET first_name = ?,
                        last_name = ?, 
                        age = ?, 
                        gender = ? 
                        WHERE id = ?
                    ";
                    $statement = $pdo->prepare($query);
                    $statement->execute(array($first_name,$last_name,$age,$gender,$id));
                break;

            case 'DELETE'://sama kek yang post
                    $id = $param['id']; 
                    $query = "DELETE FROM sample_data WHERE id = ?";
                    $statement = $pdo->prepare($query);
                    $statement->execute(array($id));
                break;
            
            default:
                # code...
                break;
        }
    }
}