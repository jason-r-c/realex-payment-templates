<!-- Step one: Add the below above the closing head tag, eg, </head> -->

<?
  /********************************************************************************
  ## 2) Payment Details page, aka Post Request: Receives posted form variables from Pay Online page, 
  and posts to Global Iris for payment capture and processing
  ********************************************************************************/

  /**
    * Test account details 
    */
  //## test account secret
  $secret = "secret";

  //## test account ID
  $merchantid = "nameofclient";

  /**
   * Posted variables 
   */
  //## live id
  // $merchantid = "nameofclient";
  
  //## live secret 
  // $secret = "kB11JsWgHw";

  // The code below is used to create the timestamp format required by Realex Payments
  $timestamp = strftime("%Y%m%d%H%M%S");
  mt_srand((double)microtime()*1000000);

  // orderid: Replace this with the order id you want to use. The order id must be unique.
  // In the example below a combination of the timestamp and a random number is used.
  $orderid = $timestamp."-".mt_rand(1, 999);

  // In this example these values are hardcoded. In reality you may pass 
  // these values from another script or take it from a database. 
  $curr = "GBP";
  
  /** 
    * Get general site info
    */
  $companyname = $_POST["companyname"];
  $emailaddress = $_POST["emailaddress"];
  $paymentreference = $_POST["paymentreference"];

  // Get the amount paid and do some math to get into appropriate format
  $amount = $_POST["amountpaid"];
  $amount = str_replace(',', '', $amount);
  $amount = $amount * 100;
  $amount = $amount;
  
  // get the raw value which includes £ sign
  $posted_amount = $_POST["amountpaid"];

  // Below is the code for creating the digital signature using the SHA1 algorithm provided by PHP
  $tmp = "$timestamp.$merchantid.$orderid.$amount.$curr";
  $sha1hash = sha1($tmp);
  $tmp = "$sha1hash.$secret";
  $sha1hash = sha1($tmp);
?>

<!-- Step two: Add this form inside the main content area. For example, to add to the Landscape Boilerplate theme, you would add it inside the div with the ID of main -->

<!-- Payment Details form: begin -->
<?php
  $is_email_valid = filter_var($emailaddress, FILTER_VALIDATE_EMAIL);
  //print var_dump($is_email_valid);
  //print var_dump($amount);
?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Please review your payment information</h3>
  </div>
  <div class="panel-body">
    <div class="text-center">

      <?php if($amount == '0' && $is_email_valid == FALSE): ?>
        <p class="alert alert-danger">You must enter an amount and a valid email address in order to proceed</p>

      <?php elseif($amount == "0"): ?>
        <p class="alert alert-danger">You must enter an amount in order to proceed</p>

      <?php elseif($is_email_valid == FALSE): ?>            
        <p class="alert alert-danger">You must enter a valid email address in order to proceed</p>
      <?php endif; ?>

      <p><strong>Payment reference</strong></p>
      <?php if($paymentreference): ?>
        <p class="text-center"><?php print $paymentreference; ?></p>   
      <?php else: ?>
        <p class="text-center">No reference entered</p>   
      <?php endif; ?>

      <p class="text-center"><strong>Amount to pay</strong></p>
      <?php if($amount != "0"): ?>
        <p class="text-center">£<?php print $posted_amount; ?></p>
      <?php else: ?>
        <p style="color: #a94442; ">£<?php print $posted_amount; ?></p>
      <?php endif; ?>

      <!-- URL for live transactions -->
        <!-- https://hpp.realexpayments.com/pay -->

      <!-- URL for test transactions -->
        <!-- https://hpp.sandbox.realexpayments.com/pay -->

      <?php 
      //@debug
      //print "the amount is: " . $amount;
      //print "<br>"; 
      //print "the email is: " .$emailaddress; 
      ?>

      <form action="https://hpp.sandbox.realexpayments.com/pay " method="post">                    
        <input type=hidden name="MERCHANT_ID" value="<?=$merchantid?>">
        <input type=hidden name="ORDER_ID" value="<?=$orderid?>">
        <input type=hidden name="ACCOUNT" value="internet">
        <input type=hidden name="CURRENCY" value="<?=$curr?>">
        <input type=hidden name="AMOUNT" value="<?=$amount?>">
        <input type=hidden name="TIMESTAMP" value="<?=$timestamp?>">
        <input type=hidden name="SHA1HASH" value="<?=$sha1hash?>">
        <input type=hidden name="AUTO_SETTLE_FLAG" value="1">
        <input type=hidden name="MERCHANT_RESPONSE_URL" value="https://credentials@sitename.co.uk/response-script">
        <input type=hidden name="COMMENT1" value="<?=$comment1?>">
        <input type=hidden name="COMPANY_NAME" value="<?=$companyname?>">
        <input type=hidden name="EMAIL_ADDRESS" value="<?=$emailaddress?>">            
        <input type=hidden name="PAYMENT_REFERENCE" value="<?=$paymentreference?>">    


        <?php //if($amount == '0' || !$is_email_valid ): ?>     

          <?php if($amount == '0' && $is_email_valid == FALSE): ?>
            <a href="/pay-online"><button type="button" class="btn btn-default">Re-enter an amount and valid email address</button></a> 
          
          <?php elseif($amount == '0'):?>
            <a href="/pay-online"><button type="button" class="btn btn-default">Re-enter an amount</button></a>                         
          
          <?php elseif($is_email_valid == FALSE): ?>  
            <a href="/pay-online"><button type="button" class="btn btn-default">Re-enter a valid email address</button></a>          
          <?php endif; ?>

        <?php //endif; ?>

        <?php if($amount != '0' && $is_email_valid == TRUE): ?>
          <input type=submit value="Proceed to payment gateway" style="background-color: lightgray; border-radius: 5px; border: 0px; padding: 15px;">
        <?php endif; ?>

      </form>
    </div>
  </div>
</div>  
<!-- Payment Details form: end -->   