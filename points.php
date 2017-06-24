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

if (isset($_POST['user_id'])) {

    // receiving the post params
    $user_id = $_POST['user_id'];

    // get the user by email and password
    $userPoints = $db->getUserPoints($user_id);

    if ($userPoints != "" && !empty($userPoints)) {
        // use is found
        $response["error"] = FALSE;
        $response["uid"] = $userPoints["user_id"];
        $response["userPoints"]["daily_points"] = $userPoints["daily_points"];
        $response["userPoints"]["total_points"] = $userPoints["total_points"];
        $response["userPoints"]["created_at"] = $userPoints["created_at"];
        $response["userPoints"]["updated_at"] = $userPoints["updated_at"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "No Data Found For this user.!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Logged in fails.No User Found!";
    echo json_encode($response);
}
?>

