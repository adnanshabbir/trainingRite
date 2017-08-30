<?php
/**
 * Created by PhpStorm.
 * User: AdnanShabbir
 * Date: 30/08/2017
 * Time: 18:06
 */


/**
 * Get uploaded file details
 *
 * @param $file
 * @return array
 */
function getFileDetails ( $file )
{
    $file_details = [];
    //Display File Name
    $file_details['file_name'] = $file->getClientOriginalName();

    //Display File Extension
    $file_details['file_ext'] = $file->getClientOriginalExtension();

    //Display File Real Path
    $file_details['file_real_path'] = $file->getRealPath();

    return $file_details;
}

/**
 * Apply country code logic as per user selection and return number
 *
 * @param $phone
 * @return string
 */
function cleanNumber ( $phone )
{
    $phone = str_replace(" ", "", $phone);
    $phone = str_replace("-", "", $phone);
    $phone = str_replace(")", "", $phone);
    $phone = str_replace("(", "", $phone);

    if ( strlen($phone) == 10 ) {
        // add the user choice
        return $phoneNumber = '+1' . $phone;
    }

    if ( strlen($phone) == 11 ) {
        // add the user choice
        return $phoneNumber = '+' . $phone;
    }

    // do nothing just return the number
    return $phone;
}