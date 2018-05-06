<?php

/**
 * Created by PhpStorm.
 * User: Xonshiz
 * Date: 05-05-2018
 * Time: 03:53 PM
 */


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once('../../../../private/initialize.php');

    /*

     * Open a DB connection and start checking the fields whether they are empty or not.

     * If they're empty, return appropriate error.

     * Also, if the field is NOT empty and we're using that data, we need to escape the special chars to avoid SQL Injection.

     */

    $db = db_connect();


    $user_email = mysqli_real_escape_string($db, field_value_checker("Email ID", $_POST['user_email'], false, true));

    $user_password = md5(field_value_checker("Password", $_POST['user_password']));


    //$query = "SELECT user_wallet.w_reward_points AS u_reward_points, u_name, u_id, u_email, u_identification_no, u_phone_number, u_status, u_country, u_state, u_monthly_count FROM user_table, user_wallet WHERE u_email = '$user_email' AND u_password = '$user_password' AND user_table.u_id = user_wallet.w_user";

    $query = "SELECT u_id, u_name, u_email, u_dob, u_age, u_blood_type, u_medical_issues_flag, u_medical_issues, u_UIDAI,

 u_address, u_state, u_country, u_phone_number, u_account_status, u_qr_link, u_internal_hash from user_table WHERE u_password = '$user_password' AND u_email = '$user_email'";

//echo $query;

    $result = mysqli_fetch_assoc(mysqli_query($db, $query));


    if (count($result) > 0) {

        echo json_encode(array(

            'u_id' => $result["u_id"],

            'u_name' => $result["u_name"],

            'u_email' => $result["u_email"],

            'u_dob' => $result["u_dob"],

            'u_age' => $result["u_age"],

            'u_blood_type' => $result["u_blood_type"],

            'u_medical_issues_flag' => $result["u_medical_issues_flag"],

            'u_medical_issues' => $result["u_medical_issues"],

            'u_UIDAI' => $result["u_UIDAI"],

            'u_address' => $result["u_address"],

            'u_state' => $result["u_state"],

            'u_country' => $result["u_country"],

            'u_phone_number' => $result["u_phone_number"],

            'u_account_status' => $result["u_account_status"],

            'u_qr_link' => $result["u_qr_link"],

            'u_internal_hash' => $result["u_internal_hash"]

        ));

        db_disconnect($db, $result);

        exit(0);

    } else {

        echo json_encode(array(

            'error_code' => '1',

            'message' => 'Error Number  : ' . mysqli_errno($db)

        ));

        db_disconnect($db, $result);

        exit(1);

    }

} else {

    echo "<html><body><h1>Method Not Allowed</h1></body></html>";

}


?>