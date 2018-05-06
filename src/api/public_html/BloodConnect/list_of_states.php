<?php

/**

 * Created by PhpStorm.

 * User: Xonshiz

 * Date: 05-05-2018

 * Time: 08:44 PM

 */



if ($_SERVER['REQUEST_METHOD'] === 'GET')

{

    require_once('../../private/initialize.php');

    header('Content-Type: text');

    /*

     * Open a DB connection and start checking the fields whether they are empty or not.

     * If they're empty, return appropriate error.

     * Also, if the field is NOT empty and we're using that data, we need to escape the special chars to avoid SQL Injection.

     */



    $db = db_connect();



    $user_country = mysqli_real_escape_string($db, field_value_checker("Verification Code", $_GET['user_country']));



    $json = array();



    $query = "SELECT DISTINCT b_state FROM bank_records WHERE b_country = '$user_country'";



    $result = mysqli_query($db, $query);



    if ($result->num_rows > 0) {



        //echo "[";



        /* fetch associative array */

        while ($row = mysqli_fetch_assoc($result)) {



            echo field_value_checker("", $row["b_state"], false);

            echo ",";

        }

        echo '0';



        /* free result set */

        mysqli_free_result($result);

        db_disconnect($db, $result);

        exit(0);

    }

    else

    {

        if (mysqli_errno($db) == 0)

        {

            echo json_encode(array(

                'error_code' => '1',

                'message' => 'No Entry Found'

            ));

        }

        else

        {

            echo json_encode(array(

                'error_code' => '1',

                'message' => 'Error Number  : ' . mysqli_errno($db)

            ));

        }

        db_disconnect($db, $result);

        exit(1);

    }



}

else

{

    echo "<html><body><h1>Method Not Allowed</h1></body></html>";

}



?>