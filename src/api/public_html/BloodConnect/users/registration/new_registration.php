<?php

/**

 * Created by PhpStorm.

 * User: Xonshiz

 * Date: 05-05-2018

 * Time: 02:44 PM

 */



if ($_SERVER['REQUEST_METHOD'] === 'POST')

{

    require_once('../../../../private/initialize.php');



    /*

     * Open a DB connection and start checking the fields whether they are empty or not.

     * If they're empty, return appropriate error.

     * Also, if the field is NOT empty and we're using that data, we need to escape the special chars to avoid SQL Injection.

     */

    $db = db_connect();



    $user_name = mysqli_real_escape_string($db, field_value_checker("Name", $_POST['user_name']));

    $user_email = mysqli_real_escape_string($db, field_value_checker("Email ID", $_POST['user_email'], true, true));

    $user_password = md5(field_value_checker("Password", $_POST['user_password']));

    $user_id_no = mysqli_real_escape_string($db, field_value_checker("Identification Number", $_POST['user_id_no']));

    $user_phone_no = mysqli_real_escape_string($db, field_value_checker("Phone Number", $_POST['user_phone']));

    $user_info_json = user_location_lookup($_SERVER['REMOTE_ADDR']);

    // $user_info_json = user_location_lookup("103.212.145.150");

    $user_country = mysqli_real_escape_string($db, field_value_checker("Detected  User Country", $user_info_json[1], false));

    $user_state = mysqli_real_escape_string($db, field_value_checker("Detected  User City", $user_info_json[0], false));



    $user_dob = mysqli_real_escape_string($db, field_value_checker("DOB", $_POST['user_dob']));

    $user_age = date_diff(date_create($user_dob), date_create('today'))->y;



    $user_blood_type = mysqli_real_escape_string($db, field_value_checker("Blood Type", $_POST['user_blood_type']));

    $user_medial_issues_flag = mysqli_real_escape_string($db, field_value_checker("Flag", $_POST['user_medial_issues_flag']));

    $user_medial_issues = mysqli_real_escape_string($db, field_value_checker("Issues", $_POST['user_medial_issues']));

    $user_address = mysqli_real_escape_string($db, field_value_checker("Address", $_POST['user_address']));





    $verification_code = substr(sha1(mt_rand()), 17, 15);

    $user_qr_link = qr_generation($verification_code);



    $query = "INSERT INTO user_table";

    $query .= "(u_name, u_email, u_password, u_dob, u_age, u_blood_type, u_medical_issues_flag, u_medical_issues, u_UIDAI,

     u_address, u_state, u_country, u_phone_number, u_account_status, u_qr_link, u_verification_code, u_internal_hash) ";

    $query .= "VALUES ";

    $query .= "('$user_name', '$user_email', '$user_password', '$user_dob', '$user_age', '$user_blood_type', '$user_medial_issues_flag',

    '$user_medial_issues', '$user_id_no', '$user_address', '$user_state', '$user_country', '$user_phone_no', '1', '$user_qr_link', '$verification_code', '$verification_code')";



    // echo "$query";

    $result = mysqli_query($db, $query);



    if ($result) {

        // Email Verification Function return 2 values. Boolean Status, followed by a string about the response.

        $email_status = email_verification_send("https://xonshiz.heliohost.org/BloodConnect/users/registration/account_verification.php", $user_email, $verification_code);



        if ($email_status[0]) {

            echo json_encode(array(

                'error_code' => '0',

                'message' => 'Verification Email Sent!'

            ));

            db_disconnect($db, $result);

            exit(0);

        } else {

            echo json_encode(array(

                'error_code' => '1',

                'message' => $email_status[1]

            ));

            db_disconnect($db, $result);

            exit(1);

        }

    } else {



        if (mysqli_errno($db) == 1062) // Duplicate Entry

        {

            echo json_encode(array(

                'error_code' => '1',

                'message' => 'That Record Already Exists.'

            ));

            db_disconnect($db, $result);

            exit(1);

        }

        else

        {

            echo json_encode(array(

                'error_code' => '1',

                'message' => 'Error Number  : ' . mysqli_errno($db)

            ));

            db_disconnect($db, $result);

            exit(1);

        }

    }

}

else

{

    echo "<html><body><h1>Method Not Allowed</h1></body></html>";

}



?>