<?php

/**
 * @author Md.Monriuzzaman
 * @Desciption  University of Eastern Finland,Joensuu, Finland
 * @email monircse403@gmail.com
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['nome']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

    // receiving the post params
	
    $reg_date = $_POST['reg_date'];
	$nome = $_POST['nome'];
	$cognome = $_POST['cognome'];
    $username = $_POST['username'];
	$email = $_POST['email'];
    $password = $_POST['password'];
	$cellulare = $_POST['cellulare'];
    $Luogodinascita = $_POST['Luogodinascita'];
	$Datadinascita = $_POST['Datadinascita'];
    $Targa = $_POST['Targa'];
	$Marca = $_POST['Marca'];
	$Modello = $_POST['Modello'];
    $tagline = $_POST['tagline'];
	$verification_code = "";
	
    // check if user is already existed with the same email
    if ($db->isUserExisted($email)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $email;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeUser($reg_date, $nome,$cognome, $username, $email, $password, $cellulare,
								$Luogodinascita, $Datadinascita, $Targa, $Marca, $Modello,$tagline, $verification_code  );
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>

