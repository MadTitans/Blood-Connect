<?php

/**

 * Created by PhpStorm.

 * User: Xonshiz

 * Date: 05-05-2018

 * Time: 11:04 PM

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



    $u_internal_id = mysqli_real_escape_string($db, field_value_checker("User ID", $_POST['u_internal_id']));

    $blood_type = mysqli_real_escape_string($db, field_value_checker("Blood Type", $_POST['blood_type']));

    $blood_amount = mysqli_real_escape_string($db, field_value_checker("Blood Amount", $_POST['blood_amount']));

    $bank_id = mysqli_real_escape_string($db, field_value_checker("Bank ID", $_POST['bank_id']));

    $t_type = mysqli_real_escape_string($db, field_value_checker("Transaction Type", $_POST['t_type']));



    $query = "";



    if ( $t_type == '0') // Donated --> Bank gave blood to a user.

    {

        $query = "INSERT INTO blood_transactions (t_internal, t_donation_date, t_receiving_date, t_amount, t_type, t_bank_id, t_blood_type) 

VALUES ('$u_internal_id', NOW(), '', '$blood_amount', '0', '$bank_id', '$blood_type')";

    }

    else // Received --> User gave blood to a blood bank

    {

        $query = "INSERT INTO blood_transactions (t_internal, t_donation_date, t_receiving_date, t_amount, t_type, t_bank_id, t_blood_type) 

VALUES ('$u_internal_id', '', NOW(), '$blood_amount', '1', '$bank_id', '$blood_type')";

    }

//                echo "wallet_id : " . $wallet_id . " ";



    $result = mysqli_query($db, $query);



    if($result)

    {

        $query_bank_count_update = "";



        if ($blood_type == 'A+')

        {

            if ( $t_type == '0') // Donated --> Bank gave blood to a user.

            {

                $query_bank_count_update = "UPDATE bank_records SET b_A_pos = b_A_pos - $blood_amount WHERE b_id = '$bank_id'";

            }

            else // Received --> User gave blood to a blood bank

            {

                $query_bank_count_update = "UPDATE bank_records SET b_A_pos = b_A_pos + $blood_amount WHERE b_id = '$bank_id'";

            }

        }



        else if ($blood_type == 'A-')

        {

            if ( $t_type == '0') // Donated --> Bank gave blood to a user.

            {

                $query_bank_count_update = "UPDATE bank_records SET b_A_neg = b_A_neg - $blood_amount WHERE b_id = '$bank_id'";

            }

            else // Received --> User gave blood to a blood bank

            {

                $query_bank_count_update = "UPDATE bank_records SET b_A_neg = b_A_neg + $blood_amount WHERE b_id = '$bank_id'";

            }

        }



        else if ($blood_type == 'B+')

        {

            if ( $t_type == '0') // Donated --> Bank gave blood to a user.

            {

                $query_bank_count_update = "UPDATE bank_records SET b_B_pos = b_B_pos - $blood_amount WHERE b_id = '$bank_id'";

            }

            else // Received --> User gave blood to a blood bank

            {

                $query_bank_count_update = "UPDATE bank_records SET b_B_pos = b_B_pos + $blood_amount WHERE b_id = '$bank_id'";

            }

        }



        else if ($blood_type == 'B-')

        {

            if ( $t_type == '0') // Donated --> Bank gave blood to a user.

            {

                $query_bank_count_update = "UPDATE bank_records SET b_B_neg = b_B_neg - $blood_amount WHERE b_id = '$bank_id'";

            }

            else // Received --> User gave blood to a blood bank

            {

                $query_bank_count_update = "UPDATE bank_records SET b_B_neg = b_B_neg + $blood_amount WHERE b_id = '$bank_id'";

            }

        }



        else if ($blood_type == 'AB+')

        {

            if ( $t_type == '0') // Donated --> Bank gave blood to a user.

            {

                $query_bank_count_update = "UPDATE bank_records SET b_AB_pos = b_AB_pos - $blood_amount WHERE b_id = '$bank_id'";

            }

            else // Received --> User gave blood to a blood bank

            {

                $query_bank_count_update = "UPDATE bank_records SET b_AB_pos = b_AB_pos + $blood_amount WHERE b_id = '$bank_id'";

            }

        }



        else if ($blood_type == 'AB-')

        {

            if ( $t_type == '0') // Donated --> Bank gave blood to a user.

            {

                $query_bank_count_update = "UPDATE bank_records SET b_AB_neg = b_AB_neg - $blood_amount WHERE b_id = '$bank_id'";

            }

            else // Received --> User gave blood to a blood bank

            {

                $query_bank_count_update = "UPDATE bank_records SET b_AB_neg = b_AB_neg + $blood_amount WHERE b_id = '$bank_id'";

            }

        }



        else if ($blood_type == 'O+')

        {

            if ( $t_type == '0') // Donated --> Bank gave blood to a user.

            {

                $query_bank_count_update = "UPDATE bank_records SET b_O_pos = b_O_pos - $blood_amount WHERE b_id = '$bank_id'";

            }

            else // Received --> User gave blood to a blood bank

            {

                $query_bank_count_update = "UPDATE bank_records SET b_O_pos = b_O_pos + $blood_amount WHERE b_id = '$bank_id'";

            }

        }



        else if ($blood_type == 'O-')

        {

            if ( $t_type == '0') // Donated --> Bank gave blood to a user.

            {

                $query_bank_count_update = "UPDATE bank_records SET b_O_neg = b_O_neg - $blood_amount WHERE b_id = '$bank_id'";

            }

            else // Received --> User gave blood to a blood bank

            {

                $query_bank_count_update = "UPDATE bank_records SET b_O_neg = b_O_neg + $blood_amount WHERE b_id = '$bank_id'";

            }

        }



        $result_update = mysqli_query($db, $query_bank_count_update);



        if ($result_update)

        {

            echo json_encode(array(

                'error_code' => '0',

                'message' => 'Successfully Added'

            ));

            db_disconnect($db, $result);

            exit(0);

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

    else

    {

        echo "Couldn't Verify Your Account. Additionally, this error number popped up : " . mysqli_errno($db);

        db_disconnect($db, $result);

        exit(1);

    }



}

else

{

    echo "<html><body><h1>Method Not Allowed</h1></body></html>";

}



?>