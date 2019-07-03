# Templates for Realex payment integration
If you have a client that wants to use Realex Hosted Payment Page (HPP) for online payments, this guide will help you understand how to implement the necessary templates into a Landscape/Drupal site.

## Site templates

There are 3 templates you'll need to create and edit in your Landscape/Drupal site, they are:

**page node template 1 (Pay Online)**

This is the Pay Online page. It will hold your form fields for capturing payment details

**page node template 2 (Payment Details)**

This is the Payment Details page. It will receive the posted form variables from the Pay Online page.

**page node template 3 (Response Script)**

This is the Response script. It prints appropriate messages that come back from the processed Realex payment.


## How to create the templates

Create a standard Drupal page (content type of page). Note down the node ID

Copy the themes page.tpl.php (or if one dosnt exist, copy Drupals core page.tpl.php) and paste into your themes templates folder

Rename the file: use the following pattern to rename:

page-node-123.tpl.php, replace 123 with the node ID you noted down earlier

You will want to do this for all 3 payment templates.

### Adding the Realex code

First, refer to the folder 'code-for-drupal-templates'. It contains the code fragments you need to add to your theme templates (which you created in the previous step).

Each template is commented - the comments instruct the user on where to place the code fragments.

For example, to add to the Landscape Boilerplate theme, you would add each form element inside the div with the ID of 'main'.

With the Response script, you will want to use the template provided and mostly change the html in each if/else statment 


## Parts of each template you will want to change

### Overview of the changes needed to get the templates working

The checklist below briefly outlines the changes you need to make. These changes are covered in more detail in the 'Detailed instructions' section further below in this guide.

1) Change the HTML form elements' action attribute to the the url of the Payment Details page, eg:

```
<form action="https://www.sitename.co.uk/payment-details" method="post" class="form-horizontal">
```

2) For the Test account change the form elements' action attribute to:

```
<form action="https://hpp.sandbox.realexpayments.com/pay" method="post">  
```
and for the Live account use

```
<form action="https://hpp.realexpayments.com/pay" method="post">  

```

3) Change the value attribute in the HTML input element which contains the name attribute "MERCHANT_RESPONSE_URL':

```
<input type=hidden name="MERCHANT_RESPONSE_URL" value="http://credentials@sitename.co.uk/response-script">
```

4) Change the following variables in the templates:
* `$secret` 
* `$merchantid` 
* `$to`
* `$payee`
* `$headers`
* `$message_one`
* `$message_two`

Remember to add the authentication credentials as sites are built on External Build. Remove authentication credentials for live sites.


Detailed instructions
---

### Page node template 1 (Pay Online)

Change the form elements action attribute to the node you created for the Payment Details page. For example, if you created a Page called 'Payment details' its url would be sitename.co.uk/payment-details

```
https://www.sitename.co.uk/payment-details
```

The amount field is mandatory (see Request Field Definitions at https://developer.realexpayments.com/#!/hpp/transaction-processing).  Add/remove text fields accoring to your design/brief, baring in mind these input values will post to the Payment Page

### Page node template 2 (Payment Details)

The $merchantid and $secret variables should be given by Realex. If you dont have them contact the client and/or Realex asking for test account credentials

You may want to edit the variables block titled "Get general site info" for your own site specific variables

For testing change the form elements action attribute to

```
https://hpp.sandbox.realexpayments.com/pay
```

**Passing authentication for the Merchant response URL**

For testing, the the merchant response URL must pass authenticaion

You need to use test credentials in the input value:

```
<input type=hidden name="MERCHANT_RESPONSE_URL" value="http://credentials@sitename.co.uk/response-script">
```

For the live site, remove:
```
credentials
```


**Amount must be greater than 0**

If the $amount variable is 0 then the user is prompted to re-enter the payment details. Only payments above 1 pence are valid.

### page node template 3 (Response Script)
This template displays information based on the response code it receives. 

For testing, uncomment the test $secret and $merchantid

You may want to change the variables in the variables block titled "General site info, client info and time/date", as these are site specific.

The variables in the "Response info required for digital signature" block are mandatory

The variables in the "Do some math" are used for presenting a human readable version of the amount, ie, £15.99 as opposed to the raw 1599 that the system requires

The variables in the "Replace these with the values you receive from Realex Payments" section are the details provided for the client account

Variables in the "Below is the code for creating the digital signature" section are mandatory


**Change variables**

Change the $to and $payee variables; this is the receipt for the transaction

Change the $headers to suit

Change the $message_one and $message_two values: these MUST be a valid email address, ie, MUST have an @ symbol. You can detect issues by switching on Databse logging module and going to /admin/reports/dblog. Failed email sends will be displayed.

Every if/else statement handles a different response code. Each code represents success, failure, card lost/stolen or communications error. You may want to alter the HTML and text for each if/else statement to suit the design/clients wishes 


## Secure pages

Sites should be setup with secure pages (https://www.sitename.co.uk/admin/build/securepages)

The following pages were added to the Pages text field

* pay-online
* payment-details
* response-script

Note: do not add a carraige return (enter key) at the end of response-script - there should be now space after it

## Payment form templates
These templates are used on the realex server. Both templates display the payment form, for desktop and mobile displays respectively. The css validation does not matter. However the templates must contain:

* Valid html
* A `<hpp: />` tag

Just remember to comment out/remove the `<hpp: />` tag before validating

The templates you need to edit are
- desktop.html
- mobile.html

These are in the 'realex-server-resources' folder 

### Changes need for both desktop.html and mobile.html
- Add site specific links to main menu
- Add site specific links to footer menu
- Add site specific logo
- Add site specific telephone number to header
- Add site specific email address
- Add site specific primary branding colour to main menu background
- Add site specific primary branding colour to footer menu background

## Going live

Things to check off before going live:

**All templates**

1. Add 'https://www' to the pay-online and payment-details template urls
2. Change template page-node ids for live site

**Payment details template**

1. Change sandbox URL to live URL
2. Remove ex build authentication details in the 'MERCHANT_RESPONSE_URL'

**Response script template**

1. Add client email address to the '$to' variable in the response script (this is for sending the payment receipt)
2. Change response script 'from' address in the $headers variable
3. Change response script 'from' in message_* arrays

**template.php**

1. Change node id in template.php for loading of the price-format.js

**Live site**

1. Create the following pages on the live site:
  * pay-online
  * payment-details
  * response-script

2. Add those pages to the 'pages' field in /admin/build/securepages

**Notify Realex**

1. Provide Realex with the referring urls (posts to Global iris' payment page). This will be /payment-details as this is the template that posts to the Realex payment page
2. Ask Realex to 'registrate' the live account (this is something they do there end) 
3. Email Realex and client letting them know a live transaction will be made

## Troubleshooting

### Changing test and live values
There are specific $secret and $merchantid values for both the test and live accounts (these were given by Realex support team)

Be aware of these values (they are commented out in the live site templates)


### HTTPS and WWW

When testing there was no need to add www to the start of form action values, so everthing worked fine

However, adding them for the live site was essential for posting values to the payment-details page:

```
form action="https://www.sitename.co.uk/payment-details" method="post" class="form-horizontal"
```

You also need to add this for the live sites' merchant response url:

```
<input type=hidden name="MERCHANT_RESPONSE_URL" value="https://www.sitename.co.uk/response-script">
```

### Emails dont get sent

You MUST ensure the 'from' value is a valid email address in the $message_one and $message_two arrays. Use something like, clientname@clientname.co.uk.




## Resources
* [Updated developer doc](https://developer.realexpayments.com/#!/hpp/transaction-processing)
* [Older developer doc](https://resourcecentre.globaliris.com/documents/pdf.html?id=135)

## Test cards
* [Updated test cards](https://developer.realexpayments.com/#!/technical-resources/test-card)
* [Older test cards](https://resourcecentre.globaliris.com/downloads.html?id=203)
