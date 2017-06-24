<?php
/**
 * @author Md.Monriuzzaman
 * @Desciption  University of Eastern Finland,Joensuu, Finland
 * @email monircse403@gmail.com
 */
class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($reg_date, $nome,$cognome, $username, $email, $password, $cellulare,
								$Luogodinascita, $Datadinascita, $Targa, $Marca, $Modello,$tagline, $verification_code) {

        $stmt = $this->conn->prepare("INSERT INTO users(reg_date, nome, cognome, username, email,password,cellulare,
								Luogodinascita, Datadinascita, Targa, Marca ,Modello, tagline, verification_code) 
									VALUES(?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ? )");
        $stmt->bind_param("sssss", $reg_date, $nome,$cognome, $username, $email, $password, $cellulare,
								$Luogodinascita, $Datadinascita, $Targa, $Marca, $Modello,$tagline, $verification_code);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            //$user = $stmt->get_result()->fetch_assoc();
            $user = $stmt->fetch();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getUserByUsernameAndPassword($username, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");

        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            //$user = $stmt->get_result()->fetch_assoc();
            //$user = $stmt->fetch();
             $stmt->store_result();
            //$user = $this->fetchAssocStatement($stmt);
            while($assoc_array = $this->fetchAssocStatement($stmt))
    		{
				$user = $assoc_array;
    		}
            $stmt->close();

            // verifying user password
            #$salt = $user['salt'];
            $db_password = $user['password'];
            #$hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($db_password == $password) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
    
    
    
    /**
     * Get user by email and password
     */
    public function getUserPoints($user_id) {

        $stmt = $this->conn->prepare("SELECT * FROM user_earnings WHERE user_id = ?");

        $stmt->bind_param("s", $user_id);

        if ($stmt->execute()) {
            while($assoc_array = $this->fetchAssocStatement($stmt))
            {
                $usersPoints = $assoc_array;
            }
            $stmt->close();
            return $usersPoints;
        } else {
            return NULL;
        }
    }
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }
    
    public function fetchAssocStatement($stmt)
    {
        if($stmt->num_rows>0)
        {
            $result = array();
            $md = $stmt->result_metadata();
            $params = array();
            while($field = $md->fetch_field()) {
                $params[] = &$result[$field->name];
            }
            call_user_func_array(array($stmt, 'bind_result'), $params);
            if($stmt->fetch())
                return $result;
        }

        return null;
    }

}

?>
