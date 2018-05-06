<?php

/**

 * Created by PhpStorm.

 * User: Xonshiz

 * Date: 05-05-2018

 * Time: 05:11 PM

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



    $r_request_id = mysqli_real_escape_string($db, field_value_checker("Blood Type", $_POST['r_request_id']));

    $r_requestor_id = mysqli_real_escape_string($db, field_value_checker("Requestor ID", $_POST['r_requestor_id']));

    $r_donor_id = mysqli_real_escape_string($db, field_value_checker("Donor ID", $_POST['r_donor_id']));

    $r_donation_camp_name = mysqli_real_escape_string($db, field_value_checker("Camp Name", $_POST['r_donation_camp_name']));



    $query = "UPDATE requests set r_donor_id = '$r_donor_id', r_donation_status = '2', r_donation_date = NOW(), r_donation_camp_name = '$r_donation_camp_name' WHERE $r_request_id = '$r_request_id' AND $r_requestor_id = '$r_requestor_id'";



    // echo "$query";

    $result = mysqli_query($db, $query);



    if ($result) {

        echo json_encode(array(

            'error_code' => '0',

            'message' => 'Request Updated!'

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

}

else

{

    echo "<html><body><h1>Method Not Allowed</h1></body></html>";

}



?>