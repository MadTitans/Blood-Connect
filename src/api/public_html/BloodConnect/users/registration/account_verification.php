<?php

/**

 * Created by PhpStorm.

 * User: Xonshiz

 * Date: 05-05-2018

 * Time: 02:57 PM

 */



if ($_SERVER['REQUEST_METHOD'] === 'GET')

{

    require_once('../../../../private/initialize.php');

    /*

     * Open a DB connection and start checking the fields whether they are empty or not.

     * If they're empty, return appropriate error.

     * Also, if the field is NOT empty and we're using that data, we need to escape the special chars to avoid SQL Injection.

     */

    $db = db_connect();



    $verification_code = mysqli_real_escape_string($db, field_value_checker("Verification Code", $_GET['verify']));



    $query = "UPDATE user_table SET u_account_status = 0, u_verification_code  = 'NONE' WHERE u_verification_code = '$verification_code'";

//                echo "wallet_id : " . $wallet_id . " ";



    $result = mysqli_query($db, $query);



    if($result)

    {

        echo "Your Account Has Been Activated. Please Log In Now.";

        db_disconnect($db, $result);

        exit(0);

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