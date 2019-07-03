<!doctype HTML>
<html lang="en" class="no-js">
<head>
<title>Realex Payments HPP - PHP Sample Code - Response Script</title>

<?/* Note:The below code is used to grab the fields Realex Payments POSTs back 
 to this script after a transaction has been processed. Realex Payments need
 to know the full URL of this script in order to POST the data back to this
 script. Please inform Realex Payments of this URL if they do not have it 
 already.

 Look at the Realex Documentation to view all hidden fields Realex POSTs back
 for a card transaction. */

/*************************************************************************
## 3) Response page: http://sitename.co.uk/response-script
**************************************************************************/

// General site info, client info and time/date
$sitename = $_SERVER['HTTP_HOST'];
$companyname = $_POST['COMPANY_NAME'];
$emailaddress = $_POST['EMAIL_ADDRESS'];
$paymentreference = $_POST['PAYMENT_REFERENCE'];
$the_time = date('g.i a', $_SERVER['REQUEST_TIME']);
$the_date = date('d/m/Y', $_SERVER['REQUEST_TIME']);

// Response info required for digital signature
$timestamp = $_POST['TIMESTAMP'];
$result = $_POST['RESULT'];
$orderid = $_POST['ORDER_ID'];
$message = $_POST['MESSAGE'];
$authcode = $_POST['AUTHCODE'];
$pasref = $_POST['PASREF'];
$realexsha1 = $_POST['SHA1HASH'];

// Do some math print to print in appropriate format
// Divide by 100 and print with 2 decimal places 
$amount = $_POST['AMOUNT'];
$posted_amount = $_POST['AMOUNT'];
$amount = sprintf('%.2f', $amount / 100);

// Replace these with the values you receive from Realex Payments.If you have not yet received these values please contact Realex Payments.
$merchantid = "nameofclient";
$secret = "secret";

// Below is the code for creating the digital signature using the SHA1 algorithm provided by PHP
// This digital siganture should correspond to the 
// one Realex Payments POSTs back to this script and can therefore be used to verify the message Realex sends back.
$tmp = "$timestamp.$merchantid.$orderid.$result.$message.$pasref.$authcode";
$sha1hash = sha1($tmp);
$tmp = "$sha1hash.$secret";
$sha1hash = sha1($tmp);

//Check to see if hashes match or not
if ($sha1hash != $realexsha1) {
  echo "Hashes don't match - response not authenticated!";
}

$date_time_of_transaction = date('H.i a d/m/Y', $_SERVER['REQUEST_TIME']);

/*
 You can send yourself an email or send the customer an email or update a database or whatever you want to do here.

 The next part is important to understand. The result field sent back to this
 response script will indicate whether the transaction was successful or not.
 The result 00 indicates it was successful while anything else indicates it failed. 
 Refer to the Realex Payments documentation to get a full list to response codes.

 IMPORTANT: Whatever this response script prints is grabbed by Realex Payments
 and placed in the template again. It is placed wherever the <hpp:body/> tag
 is in the template you provide. This is the case so that from a customer's perspective, they are not suddenly removed from 
 a secure site to an unsecure site. This means that although we call this response script the 
 customer is still on Realex Payment's site and therefore it is recommended that a HTML link is
 printed in order to redirect the customrer back to the merchants site.
*/
?>
</head>
<body bgcolor="#FFFFFF">
<font face=verdana,helvetica,arial size=2>

<? // The transaction has been successful
if ($result == "00") { ?>
<!-- Display successful transaction message -->
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Payment successful</h3>
  </div>
  <div class="panel-body">    
    <p>Payment made by: <strong><?php print $companyname; ?></strong></p>
    <p>Transaction timed at: <strong><?php print $the_time; ?></strong> on <strong><?php print $the_date; ?></strong></p>
    <p>Email address: <strong><?php print $emailaddress; ?></strong></p>
    <p>Client reference: <strong><?php print $paymentreference; ?></strong></p>
    <p>The amount paid: <strong>£<?php print $amount; ?></strong></p>
    <p>Authorisation number: <strong><?php print $authcode; ?></strong></p>
    <p>A confirmation email has been sent to <?php print $sitename; ?></p>
    <p><a href="<?php print 'http://'.$sitename; ?>"><b><u>Continue browsing</u></b></a></p>
  </div>
</div>

<?php

/* HTML email */
$to = "designatedperson@sitename.co.uk";
$subject = "Payment made on sitename.co.uk";
$message = "
<html>
  <head>
    <title>Payment successful</title>
  </head>
  <body>    
    <h2>A payment has been made on $sitename</h2>
    <p>Payment made by: <strong>$companyname</strong></p>
    <p>Email address: <strong>$emailaddress</strong></p>
    <p>Client reference: <strong>$paymentreference</strong></p>
    <p>Amount paid: <strong>£$amount</strong></p>
  </body>
</html>";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <sitename.co.uk/>' . "\r\n";
mail($to,$subject,$message,$headers); ?>

<br/><br/>

<? // The transaction was not successful. You can ask the customer to amend their details or try a different payment method
} elseif ($result == "101") { ?>
<!-- Display unsuccessful transaction message -->
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Payment un-successful</h3>
  </div>
  <div class="panel-body">   
    <p>Your transaction has not been successful</p>
    <p>To continue with your transaction please try another payment method on the <a href="<?php print 'http://' . $sitename . '/pay-online'; ?>"><b><u>payment page</u></b></a></p>
    <p><a href="<?php print 'http://' . $sitename; ?>"><b><u>Continue browsing</u></b></a></p>
  </div>
</div>

<? } elseif ($result == "103") { ?>
<!-- Display card lost/stolen transaction message -->
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Card has been reported lost or stolen</h3>
  </div>
  <div class="panel-body">   
    <p>This card has been reported lost or stolen, please contact your bank</p>
    <p><a href="<?php print 'http://' . $sitename; ?>"><b><u>Continue browsing</u></b></a></p>
  </div>
</div>

<? } elseif ($result == "205") { ?>
<!-- Display communications error message -->
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Communications error</h3>
  </div>
  <div class="panel-body">   
    <p>There has been a communications error, please try again later</p>
    <br/><br/>
    <p><a href="<?php print 'http://' . $sitename; ?>"><b><u>Continue browsing</u></b></a></p>
    <br/><br/>
  </div>
</div>

<? } else { ?>
<!-- You can replace this text with whatever you wish to display to your customers following an unsuccessful transaction-->
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Error processing transaction</h3>
  </div>
  <div class="panel-body">  
    <p>There was an error processing your transaction</p>
    <p>To continue with your transaction please <a href="<?php print 'http://' . $sitename . '/pay-online'; ?>"><b><u>re-enter your payment details</u></b></a></p>
    <p>If your query is urgent, please contact us at <a href="mailto:mail@sitename.co.uk"><b><u>mail@sitename.co.uk</u></b></a> or if you  prefer call us on 01792 410 100</p>
  </div>
</div>
<? } ?>

</font>
</body>
</html>

<? /* Pay and Shop Limited (Realex Payments) - Licence Agreement.
© Copyright and zero Warranty Notice.

Merchants and their internet, call centre, and wireless application
developers (either in-house or externally appointed partners and
commercial organisations) may access Realex Payments technical
references, application programming interfaces (APIs) and other sample
code and software ("Programs") either free of charge from
www.realexpayments.com or by emailing info@realexpayments.com. 

Realex Payments provides the programs "as is" without any warranty of
any kind, either expressed or implied, including, but not limited to,
the implied warranties of merchantability and fitness for a particular
purpose. The entire risk as to the quality and performance of the
programs is with the merchant and/or the application development
company involved. Should the programs prove defective, the merchant
and/or the application development company assumes the cost of all
necessary servicing, repair or correction.

Copyright remains with Realex Payments, and as such any copyright
notices in the code are not to be removed. The software is provided as
sample code to assist internet, wireless and call center application
development companies integrate with the Realex Payments service.

Any Programs licensed by Realex Payments to merchants or developers are
licensed on a non-exclusive basis solely for the purpose of availing
of the Realex Payments service in accordance with the
written instructions of an authorised representative of Realex Payments.
Any other use is strictly prohibited. */ ?>

3