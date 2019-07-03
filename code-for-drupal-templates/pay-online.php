<!-- 	Step one: Add the below comment above the closing head tag eg, </head> -->

  <? /*************************************************************************
    ## 1) Pay online page: posts to "Payment details" page 
    **************************************************************************/ ?>

<!-- Step two: Add this form inside the main content area. For example, to add to the Landscape Boilerplate theme, you would add it inside the div with the ID of main -->

    <!-- Payment Online form: begin -->
    <form action="https://www.sitename.co.uk/payment-details" method="post" class="form-horizontal">
      <div class="form-group">
          <label for="inputEmail" class="control-label col-xs-12 col-sm-2">Your company / name </label>
          <div class="col-xs-12 col-sm-10">
              <input type="text" name="companyname" class="form-control" id="inputEmail" placeholder="Company name or name">
          </div>
      </div>
      <div class="form-group">
          <label for="inputPassword" class="control-label col-xs-12 col-sm-2">Your Email address</label>
          <div class="col-xs-12 col-sm-10">
              <input type="text" name="emailaddress" class="form-control" id="inputPassword" placeholder="john.doe@companyname.com">
          </div>
      </div>
      <div class="form-group">
          <label for="inputPassword" class="control-label col-xs-12 col-sm-2">Our client reference</label>
          <div class="col-xs-12 col-sm-8">
              <input type="text" name="paymentreference" class="form-control" id="inputPassword" placeholder="pay-001">
          </div>
          <div class="col-xs-12 col-sm-2">
            <p><a href="/sites/www.sitename.co.uk/files/images/whats-this.jpg" class="btn btn-link">Whats this?</a></p>
          </div>              
      </div>   
     <div class="form-group">
          <label for="inputPassword" class="control-label col-xs-12 col-sm-2">Amount to pay</label>
          <div class="col-xs-12 col-sm-10">
            <div class="input-group">
              <span class="input-group-addon" id="basic-addon3">£</span>
              <input type="text" name="amountpaid" class="form-control" id="payment-amount" placeholder="0.00">
            </div>
              
          </div>
      </div>

      <div class="form-group">
          <div class="col-xs-12">
              <button type="submit" class="btn btn-primary pull-right">Submit</button>
          </div>
      </div>
    </form>
    <!-- Payment Online form: end -->

<!-- Step 3: Add the below script before the closing </body> tag -->    

    <!-- Price formatter: Adds periodsd and commas to format the amount field -->
    <script type="text/javascript">    
      $('#payment-amount').priceFormat({
          prefix: '',
          centsSeparator: '.',
          thousandsSeparator: ','
      });
    </script>      