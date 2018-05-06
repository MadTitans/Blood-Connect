<?php

/**

 * Created by PhpStorm.

 * User: Xonshiz

 * Date: 05-05-2018

 * Time: 02:44 PM

 */



require ('src/Exception.php');

require ('src/PHPMailer.php');

require ('src/SMTP.php');

require ('src/OAuth.php');

require ('src/POP3.php');



function db_connect()

{

    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);



    if ($connection->connect_error) {

        echo json_encode(array(

            'error_code' => "1",

            'message' => "$connection->connect_error"

        ));

        die("Connection failed: " . $connection->connect_error);

    }

    return $connection;

}



function db_disconnect($connection, $result_set)

{

    if (isset($connection))

    {

//        mysqli_free_result($connection);

        mysqli_close($connection);

    }

}



function field_value_checker($field_name, $field_value, $important=true, $is_email=false, $is_number=false)

{

    /*

     * This check is done in the beginning, when the connection has not been established to the DB, hence,

     * db_disconnect() won't be used in this method.

     */

    if (!empty($field_value)) //The field is not empty

    {

        if ($is_email)

        {

            if (filter_var($field_value, FILTER_VALIDATE_EMAIL))

            {

                return $field_value;

            }

            else

            {

                echo json_encode(array(

                    'error_code' => "1",

                    'message' => "That Email Doesn't Look Right."

                ));

                exit(1);

            }

        }

        else

        {

            return $field_value;

        }

    }

    else // When the field is empty.

    {

        if ($important)

        {

            if ($field_value == '0')

            {

                return $field_value;

            }

            else

            {

                echo json_encode(array(

                    'error_code' => "1",

                    'message' => "$field_name"  . " Cannot be empty."

                ));

                exit(1);

            }

        }

        else

        {

            if ($is_number) // If the field is Numerical, then we cannot put NONE string. We need an Integer, i.e., 0.

            {

                return 0;

            }

            else

            {

                // If the field is empty, but we don't want to throw an error, let's return a string value of "NONE".

                return "NONE";

            }

        }

    }

}



function qr_generation($unique_id)

{

    $qr_link = "http://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" . $unique_id . "&choe=UTF-8";



    file_put_contents($unique_id . '.jpg', file_get_contents($qr_link));



    return "https://xonshiz.heliohost.org/BloodConnect/users/registration/" . $unique_id . '.jpg';



}

function user_location_lookup($user_ip)

{

    // https://stackoverflow.com/questions/409999/getting-the-location-from-an-ip-address

    $details = json_decode(file_get_contents("https://api.ipdata.co/$user_ip"));

    $detected_user_city = $details->city;

    $detected_user_country = $details->country_name;

    $detected_user_latitude = $details->latitude;

    $detected_user_longitude = $details->longitude;

    return array($detected_user_city, $detected_user_country,  $detected_user_latitude, $detected_user_longitude);

}



function email_verification_send($verification_url, $email_id_to_send, $verification_code)

{



    $mail = new \PHPMailer\PHPMailer\PHPMailer(true); // Passing `true` enables exceptions

    try {

        //Server settings

        $mail->SMTPDebug = 0;                                 // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP

        $mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers

        $mail->SMTPAuth = true;                               // Enable SMTP authentication

        $mail->Username = 'apikey';                 // SMTP username

        $mail->Password = 'MyKey';                           // SMTP password

        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted

        $mail->Port = 465;                                    // TCP port to connect to



        //Recipients

        $mail->setFrom('no-reply@bloodconnect.com', 'Admin');

        $mail->addAddress($email_id_to_send);     // Add a recipient



        //Content

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Blood Connect | Account Verification';

        $mail->Body    = 'Please Click on this link to verify your account : <br>' .

            '<a href="' . $verification_url .'?verify=' . $verification_code . '" target="_blank">Click Here To Verify</a>';

        $mail->AltBody = 'Please Click on this link to verify your account : <br>' . $verification_url .'?verify=' . $verification_code;;



        $mail->send();



        return array(true, 'successfully sent');



    } catch (Exception $e) {

        // Create a log with the error details.

        $mail_error = $mail->ErrorInfo;

        return array(false, $mail_error);

//        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;

    }

}