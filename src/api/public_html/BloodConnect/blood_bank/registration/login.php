<?php
/**
 * Created by PhpStorm.
 * User: Xonshiz
 * Date: 06-05-2018
 * Time: 09:23 AM
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once('../../../../private/initialize.php');

    /*

     * Open a DB connection and start checking the fields whether they are empty or not.

     * If they're empty, return appropriate error.

     * Also, if the field is NOT empty and we're using that data, we need to escape the special chars to avoid SQL Injection.

     */

    $db = db_connect();


    $bank_email = mysqli_real_escape_string($db, field_value_checker("Email ID", $_POST['bank_email'], false, true));

    $bank_password = md5(field_value_checker("Password", $_POST['bank_password']));


    //$query = "SELECT user_wallet.w_reward_points AS u_reward_points, u_name, u_id, u_email, u_identification_no, u_phone_number, u_status, u_country, u_state, u_monthly_count FROM user_table, user_wallet WHERE u_email = '$user_email' AND u_password = '$user_password' AND user_table.u_id = user_wallet.w_user";

    $query = "SELECT b_id, b_name, b_address, b_phone_number, b_email, b_state, b_country, b_account_status, b_internal_id,
              b_A_neg, b_A_pos, b_B_neg, b_B_pos, b_AB_neg, b_AB_pos, b_O_neg, b_O_pos
               from bank_records WHERE b_password = '$bank_password' AND b_email = '$bank_email'";

    //echo $query;

    $result = mysqli_fetch_assoc(mysqli_query($db, $query));


    if (count($result) > 0) {

        echo json_encode(array(

            'b_id' => $result["b_id"],

            'b_name' => $result["b_name"],

            'b_address' => $result["b_address"],

            'b_phone_number' => $result["b_phone_number"],

            'b_email' => $result["b_email"],

            'b_state' => $result["b_state"],

            'b_country' => $result["b_country"],

            'b_account_status' => $result["b_account_status"],

            'b_internal_id' => $result["b_internal_id"],

            'b_A_neg' => $result["b_A_neg"],

            'b_A_pos' => $result["b_A_pos"],

            'b_B_neg' => $result["b_B_neg"],

            'b_B_pos' => $result["b_B_pos"],

            'b_AB_neg' => $result["b_AB_neg"],

            'b_AB_pos' => $result["b_AB_pos"],

            'b_O_neg' => $result["b_O_neg"],

            'b_O_pos' => $result["b_O_pos"]

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