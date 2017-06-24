<?php

/**
 * @author Md.Monriuzzaman
 * @Desciption  University of Eastern Finland,Joensuu, Finland
 * @email monircse403@gmail.com
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array


if (isset($_POST['username']) && isset($_POST['password'])) {

    // receiving the post params
    $username = $_POST['username'];
    $password = $_POST['password'];

    // get the user by username and password
    $user = $db->getUserByUsernameAndPassword($username, $password);

    if ($user != false) {
        // use is found
        echo "success";
    } else {
        // user is not found with the credentials
		echo "Invalid Username or Password";
    }
} else {
    // required post params is missing
    echo "Required parameters username or password is missing!";
}
?>

