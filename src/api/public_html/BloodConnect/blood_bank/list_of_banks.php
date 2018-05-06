<?php

/**

 * Created by PhpStorm.

 * User: Xonshiz

 * Date: 05-05-2018

 * Time: 10:21 PM

 */



if ($_SERVER['REQUEST_METHOD'] === 'POST')

{

    require_once('../../../private/initialize.php');

    /*

     * Open a DB connection and start checking the fields whether they are empty or not.

     * If they're empty, return appropriate error.

     * Also, if the field is NOT empty and we're using that data, we need to escape the special chars to avoid SQL Injection.

     */

    $db = db_connect();



    $blood_type = mysqli_real_escape_string($db, $_POST['blood_type']);

    $donation_amount = mysqli_real_escape_string($db, $_POST['donation_amount']);

    $state = mysqli_real_escape_string($db, $_POST['state']);

    $country = mysqli_real_escape_string($db, $_POST['country']);



    $query = "SELECT * from bank_records WHERE b_state = '$state' AND b_country = '$country'";

    // echo $query;

    $result = mysqli_query($db, $query);

    $my_json = array();



    if ($result->num_rows > 0)

    {

//        echo "[";



        $count  = 0;

        /* fetch associative array */

        while ($row = mysqli_fetch_assoc($result)) {



            if ($blood_type == 'A-' && (int) $row["b_A_neg"] > (int) $donation_amount)

            {

                $my_json[$count] =  array(

                    'b_id' => $row["b_id"],

                    'b_name' => $row["b_name"],

                    'b_state' => $row["b_state"],

                    'b_country' => $row["b_country"],
                    
                    'b_address' => $row["b_address"],

                    'b_A_neg' => $row["b_A_neg"],

                    'b_A_pos' => $row["b_A_pos"],

                    'b_B_neg' => $row["b_B_neg"],

                    'b_B_pos' => $row["b_B_pos"],

                    'b_AB_neg' => $row["b_AB_neg"],

                    'b_AB_pos' => $row["b_AB_pos"],

                    'b_O_neg' => $row["b_O_neg"],

                    'b_O_pos' => $row["b_O_pos"],

                );

            }

            else if ($blood_type == 'A+' && (int) $row["b_A_pos"] >= (int) $donation_amount)

            {

                $my_json[$count] =  array(

                    'b_id' => $row["b_id"],

                    'b_name' => $row["b_name"],

                    'b_state' => $row["b_state"],

                    'b_country' => $row["b_country"],

'b_address' => $row["b_address"],

                    'b_A_neg' => $row["b_A_neg"],

                    'b_A_pos' => $row["b_A_pos"],

                    'b_B_neg' => $row["b_B_neg"],

                    'b_B_pos' => $row["b_B_pos"],

                    'b_AB_neg' => $row["b_AB_neg"],

                    'b_AB_pos' => $row["b_AB_pos"],

                    'b_O_neg' => $row["b_O_neg"],

                    'b_O_pos' => $row["b_O_pos"],

                );

            }

            else if ($blood_type == 'B-' && (int) $row["b_B_neg"] >= (int) $donation_amount)

            {

                $my_json[$count] =  array(

                    'b_id' => $row["b_id"],

                    'b_name' => $row["b_name"],

                    'b_state' => $row["b_state"],

                    'b_country' => $row["b_country"],

'b_address' => $row["b_address"],

                    'b_A_neg' => $row["b_A_neg"],

                    'b_A_pos' => $row["b_A_pos"],

                    'b_B_neg' => $row["b_B_neg"],

                    'b_B_pos' => $row["b_B_pos"],

                    'b_AB_neg' => $row["b_AB_neg"],

                    'b_AB_pos' => $row["b_AB_pos"],

                    'b_O_neg' => $row["b_O_neg"],

                    'b_O_pos' => $row["b_O_pos"],

                );

            }

            else if ($blood_type == 'B+' && (int) $row["b_B_pos"] >= (int) $donation_amount)

            {

                $my_json[$count] =  array(

                    'b_id' => $row["b_id"],

                    'b_name' => $row["b_name"],

                    'b_state' => $row["b_state"],

                    'b_country' => $row["b_country"],

'b_address' => $row["b_address"],

                    'b_A_neg' => $row["b_A_neg"],

                    'b_A_pos' => $row["b_A_pos"],

                    'b_B_neg' => $row["b_B_neg"],

                    'b_B_pos' => $row["b_B_pos"],

                    'b_AB_neg' => $row["b_AB_neg"],

                    'b_AB_pos' => $row["b_AB_pos"],

                    'b_O_neg' => $row["b_O_neg"],

                    'b_O_pos' => $row["b_O_pos"],

                );

            }

            else if ($blood_type == 'AB-' && (int) $row["b_AB_neg"] >= (int) $donation_amount)

            {

                $my_json[$count] =  array(

                    'b_id' => $row["b_id"],

                    'b_name' => $row["b_name"],

                    'b_state' => $row["b_state"],

                    'b_country' => $row["b_country"],

'b_address' => $row["b_address"],

                    'b_A_neg' => $row["b_A_neg"],

                    'b_A_pos' => $row["b_A_pos"],

                    'b_B_neg' => $row["b_B_neg"],

                    'b_B_pos' => $row["b_B_pos"],

                    'b_AB_neg' => $row["b_AB_neg"],

                    'b_AB_pos' => $row["b_AB_pos"],

                    'b_O_neg' => $row["b_O_neg"],

                    'b_O_pos' => $row["b_O_pos"],

                );

            }

            else if ($blood_type == 'AB+' && (int) $row["b_AB_pos"] >= (int) $donation_amount)

            {

                $my_json[$count] =  array(

                    'b_id' => $row["b_id"],

                    'b_name' => $row["b_name"],

                    'b_state' => $row["b_state"],

                    'b_country' => $row["b_country"],

'b_address' => $row["b_address"],

                    'b_A_neg' => $row["b_A_neg"],

                    'b_A_pos' => $row["b_A_pos"],

                    'b_B_neg' => $row["b_B_neg"],

                    'b_B_pos' => $row["b_B_pos"],

                    'b_AB_neg' => $row["b_AB_neg"],

                    'b_AB_pos' => $row["b_AB_pos"],

                    'b_O_neg' => $row["b_O_neg"],

                    'b_O_pos' => $row["b_O_pos"],

                );

            }

            else if ($blood_type == 'O-' && (int) $row["b_O_neg"] >= (int) $donation_amount)

            {

                $my_json[$count] =  array(

                    'b_id' => $row["b_id"],

                    'b_name' => $row["b_name"],

                    'b_state' => $row["b_state"],

                    'b_country' => $row["b_country"],

'b_address' => $row["b_address"],

                    'b_A_neg' => $row["b_A_neg"],

                    'b_A_pos' => $row["b_A_pos"],

                    'b_B_neg' => $row["b_B_neg"],

                    'b_B_pos' => $row["b_B_pos"],

                    'b_AB_neg' => $row["b_AB_neg"],

                    'b_AB_pos' => $row["b_AB_pos"],

                    'b_O_neg' => $row["b_O_neg"],

                    'b_O_pos' => $row["b_O_pos"],

                );

            }

            else if ($blood_type == 'O+' && (int) $row["b_O_pos"] >= (int) $donation_amount)

            {

                $my_json[$count] =  array(

                    'b_id' => $row["b_id"],

                    'b_name' => $row["b_name"],

                    'b_state' => $row["b_state"],

                    'b_country' => $row["b_country"],

'b_address' => $row["b_address"],

                    'b_A_neg' => $row["b_A_neg"],

                    'b_A_pos' => $row["b_A_pos"],

                    'b_B_neg' => $row["b_B_neg"],

                    'b_B_pos' => $row["b_B_pos"],

                    'b_AB_neg' => $row["b_AB_neg"],

                    'b_AB_pos' => $row["b_AB_pos"],

                    'b_O_neg' => $row["b_O_neg"],

                    'b_O_pos' => $row["b_O_pos"],

                );

            }



            $count += 1;

        }



        if (empty($my_json))

        {

            echo json_encode(array(

                'error_code' => '1',

                'message' => 'No Entries Found',

                'total_count' => '0',

                'records' => $my_json

            ));

        }

        else

        {

            echo json_encode(array(

                'error_code' => '0',

                'message' => 'Entries Found',

                'total_count' => count($my_json),

                'records' => $my_json

            ));

        }

        db_disconnect($db, $result);

        exit(0);

    }

    else

    {

        echo json_encode(array(

            'error_code' => '1',

            'message' => 'No Entry Found'

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