<?php

/**

 * Created by PhpStorm.

 * User: Xonshiz

 * Date: 05-05-2018

 * Time: 04:13 PM

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



    $r_blood_type = mysqli_real_escape_string($db, field_value_checker("Blood Type", $_POST['r_blood_type']));

    $r_age = mysqli_real_escape_string($db, field_value_checker("Age", $_POST['r_age']));

    $r_state = mysqli_real_escape_string($db, field_value_checker("State", $_POST['r_state']));

    $r_country = mysqli_real_escape_string($db, field_value_checker("Country", $_POST['r_country']));

    $r_weight = mysqli_real_escape_string($db, field_value_checker("Weight", $_POST['r_weight']));

    $r_phone = mysqli_real_escape_string($db, field_value_checker("Phone", $_POST['r_phone']));

    $r_requestor_id = mysqli_real_escape_string($db, field_value_checker("Requestor ID", $_POST['r_requestor_id']));



    $query = "INSERT INTO requests";

    $query .= "(r_blood_type, r_age, r_weight, r_country, r_state, r_phone, r_requestor_id) ";

    $query .= "VALUES ";

    $query .= "('$r_blood_type', '$r_age', '$r_weight', '$r_country', '$r_state', '$r_phone', '$r_requestor_id')";



    // echo "$query";

    $result = mysqli_query($db, $query);



    if ($result) {

        echo json_encode(array(

            'error_code' => '0',

            'message' => 'Request Added!'

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