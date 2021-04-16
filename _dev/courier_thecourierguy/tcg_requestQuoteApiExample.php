<?php 

// Zend Framework Json module
// require_once('Zend/Json.php');
// require_once('JSON.php');

//exmaple class for submitting json requests
class Example {

    function __construct() {
        $this->serviceURL = 'http://adpdemo.pperfect.com/ecomService/Json/';
        $this->username = 'tcg11@ecomm'; //enter your username
        $this->password = 'tcgecomm11'; //enter your password
        $this->token = '';
        // $this->json = new Services_JSON();
    }
    
    function runJsonExample() {
    
        //get the salt
        $params = array();
        $saltParams['email'] = $this->username;
        $response = $this->makeCall('Auth','getSalt',$saltParams);
        var_dump($response);
        //echo "\n<BR>";
        
        //check for error
        if ($response->errorcode == 0) { //no error
            $salt = $response->results[0]->salt;
            echo "Salt: ".$salt."<BR>";
        } else { //error, display notice and quit
            echo "Error: ".$response->errormessage;
            die;
        }
    
        //get the token
        $md5pass = md5($this->password.$salt);
        //echo "md5pass $md5pass <BR>\n";
        $tokenParams = array();
        $tokenParams['email'] = $this->username;
        $tokenParams['password'] = $md5pass;
        $response = $this->makeCall('Auth','getSecureToken',$tokenParams);
        
        //check for error
        if ($response->errorcode == 0) { //no error
            $this->token = $response->results[0]->token_id;
            echo "Token: ".$this->token."<BR><BR>";
        } else { //error, display notice and quit
            echo "Error: ".$response->errormessage;
            die;
        }

        $available_postal_codes=array();
        $postCodeLookupParams = array();
        //$postCodeLookupParams['details'] = array();
        $postCodeLookupParams['postcode'] = '7925';
        $postCodeLookupResponse = $this->makeCall('Quote','getPlacesByPostcode',$postCodeLookupParams, $this->token);
  
        $postCodeLookupResponse->results[7]->pcode = $postCodeLookupParams['postcode'];
  
        echo "<BR>";
        print_r($postCodeLookupResponse);
        echo "<BR>";
        // exit();
 
        foreach($postCodeLookupResponse->results as $key=>$item) {
          foreach($item as $key2=>$item2 ) {
            $available_postal_codes[$key2]=$item2;
          }
       }

        //originating location details
        $nameLookupParams['name'] = 'Walmer';
        $nameLookupResponse = $this->makeCall('Quote','getPlacesByName',$nameLookupParams, $this->token);
        $nameLookupResponse->results[3]->pcode = '6070';

        echo "<BR>";
        print_r($nameLookupResponse);
        echo "<BR>";
        // exit();

        foreach($nameLookupResponse->results as $key=>$item) {
          foreach($item as $key2=>$item2 ) {
            echo "<br>$key2 - $item2";
          }
        }
        
        //build quote request:
        $quoteParams = array();
        $quoteParams['details'] = array();
        
        //i added these just to make sure these tests are not processed as actual waybills
        $quoteParams['details']['specinstruction'] = "This is a test";
        $quoteParams['details']['reference'] = "This is a test";
        
        //originating location
        $quoteParams['details']['origperadd1'] = 'Address line 1';
        $quoteParams['details']['origperadd2'] = 'Address line 2';
        $quoteParams['details']['origperadd3'] = 'Address line 3';
        $quoteParams['details']['origperadd4'] = 'Address line 4';
        $quoteParams['details']['origperphone'] = '012345678';
        $quoteParams['details']['origpercell'] = '012345678';
        
        //i used the 1st result from the list returned when looking up postcode = 3340 as there was only 1, but normally the user would choose
        $quoteParams['details']['origplace'] = $postCodeLookupResponse->results[7]->place;
        $quoteParams['details']['origtown'] = $postCodeLookupResponse->results[7]->town;
        $quoteParams['details']['origpers'] = 'TESTCUSTOMER';
        $quoteParams['details']['origpercontact'] = 'origcontactsname';
        $quoteParams['details']['origperpcode'] = $postCodeLookupResponse->results[7]->pcode; // '7925'; //postal code

        //destination location details
        $quoteParams['details']['destperadd1'] = 'Address line 1';
        $quoteParams['details']['destperadd2'] = 'Address line 2';
        $quoteParams['details']['destperadd3'] = 'Address line 3';
        $quoteParams['details']['destperadd4'] = 'Address line 4';
        $quoteParams['details']['destperphone'] = '012345678';
        $quoteParams['details']['destpercell'] = '012345678';
        
        //i chose the 1st result, but this will be up to the user as above
        $quoteParams['details']['destplace'] = $nameLookupResponse->results[3]->place;
        $quoteParams['details']['desttown'] = $nameLookupResponse->results[3]->town;
        $quoteParams['details']['destpers'] = 'TESTCUSTOMER';
        $quoteParams['details']['destpercontact'] = 'destcontactsname';
        $quoteParams['details']['destperpcode'] = $nameLookupResponse->results[3]->pcode; // '6070'; //postal code
        
        //add the contents. There needs to be at least 1 contents item with an "actmass" > 0 otherwise a rate will not calculate. 
        $quoteParams['contents'] = array();
        $quoteParams['contents'][0] = array();
        $quoteParams['contents'][0]['item'] = 1;
        $quoteParams['contents'][0]['desc'] = 'this is a test';
        $quoteParams['contents'][0]['pieces'] = 1;
        $quoteParams['contents'][0]['dim1'] = 1;
        $quoteParams['contents'][0]['dim2'] = 1;
        $quoteParams['contents'][0]['dim3'] = 1;
        $quoteParams['contents'][0]['actmass'] = 1;
        
        $quoteParams['contents'][1] = array();
        $quoteParams['contents'][1]['item'] = 2;
        $quoteParams['contents'][1]['desc'] = 'ths is a test';
        $quoteParams['contents'][1]['pieces'] = 1;
        $quoteParams['contents'][1]['dim1'] = 1;
        $quoteParams['contents'][1]['dim2'] = 1;
        $quoteParams['contents'][1]['dim3'] = 1;
        $quoteParams['contents'][1]['actmass'] = 1;
        
        echo "<BR><BR> ---- request params ---- <BR>";
        // var_dump($quoteParams);
        sleep( 1 );
        
        echo "<BR><BR> ---- making request ---- <BR>";
        $quoteResponse = $this->makeCall('Quote','requestQuote',$quoteParams, $this->token);
        sleep( 1 );
    
        echo "<BR><BR> ---- response ---- <BR><BR>";  
        print_r($quoteResponse);
        sleep( 1 );

        // exit();
        
        /*
         * then the user needs to choose the service most desirable to them and then use 
         * the "updateService" method to  set the desired service,
         * then use "quoteToWaybill" convert the quote to a legitimate waybill
         * */
        
        $updateServiceParams = [];
        $updateServiceParams['quoteno'] = $quoteResponse->results[0]->quoteno;
        $updateServiceParams['service'] = $quoteResponse->results[0]->rates[3]->service; //i used the first 1 returned
        $updateResponse = $this->makeCall('Quote','updateService',$updateServiceParams, $this->token);
        
        echo "<BR>--------<BR>Final Rates:<BR><BR>";
        sleep( 1 );

        var_dump($updateResponse);

        echo "<BR><BR>-----------------------------------Converting-----------------------------------<BR><BR>";        
        //The following code converts the quote to a waybill, uncomment to test
        /*
        //Convert quote to waybill
        //Calling this method will create a waybill with the same details as the submitted quote
        echo "<BR>\n<BR>\n ---- converting to waybill---- <BR>\n";
        
        $convertQuoteToWaybillParams = array();
        $convertQuoteToWaybillParams['quoteno'] = $quoteResponse->results[0]->quoteno; //this parameter is MANDATORY
        $convertQuoteToWaybillParams['specins'] = "special instructions"; //this parameter is OPTIONAL
        $convertQuoteToWaybillParams['printWaybill'] = 1;
        $convertQuoteToWaybillParams['printLabels'] = 0;
        
        echo "<BR>\n<BR>\n ---- convert params ---- <BR>\n";
        print_r($convertQuoteToWaybillParams);
        
        $convertResponse = $this->makeCall('Quote','quoteToWaybill',$convertQuoteToWaybillParams, $this->token);
        */
        
        //The following code converts the quote to a collection, uncomment to test
        //Convert quote to Collection
        //Calling this method will create a Collection with the same details as the submitted quote
        echo "<BR>\n<BR>\n ---- converting to collection---- <BR>\n";
        
        $convertQuoteToCollectionParams = [];
        $convertQuoteToCollectionParams['quoteno'] = $quoteResponse->results[0]->quoteno; //this parameter is MANDATORY
        $convertQuoteToCollectionParams['specinstruction'] = "special instructions"; //this parameter is OPTIONAL
        $convertQuoteToCollectionParams['starttime'] = "08:30";
        $convertQuoteToCollectionParams['endtime'] = "17:00";
        $convertQuoteToCollectionParams['quoteCollectionDate'] = "12/04/2021";
        $convertQuoteToCollectionParams['notes'] = "some notes here";
        // $convertQuoteToCollectionParams['printWaybill'] = 1; //OPTIONAL param to return a base64 encoded pdf of the waybill
        // $convertQuoteToCollectionParams['printLabels'] = 0; //OPTIONAL param to return a base64 encoded pdf of the labels
        
        echo "<BR>\n<BR>\n ---- convert params ---- <BR>\n";
        print_r($convertQuoteToCollectionParams);
        
        $convertResponse = $this->makeCall('Collection','quoteToCollection',$convertQuoteToCollectionParams, $this->token);
        sleep( 1 );        
        echo "<BR>\n<BR>\n ---- response ---- <BR>\n<BR>\n";
        print_r($convertResponse);
        exit();
        
        echo "<BR><BR>-----------------------------------Saving PDFs-----------------------------------<BR><BR>";
        
        //save printable pdfs to disk
        //waybill
        if (isset($convertResponse->results[0]->waybillBase64)) {
            echo "<BR><BR>saving waybill.pdf<BR>";
            if ($this->saveBase64ToFile($convertResponse->results[0]->waybillBase64, "waybill.pdf")) {
                echo "waybill.pdf saved<BR><BR>";
            } else {
                echo "error saving waybill.pdf<BR><BR>";
            }
        } else {
            echo "<BR>waybill.pdf data not returned<BR><BR>";
        }
  }
    
    function makeCall($class, $method, $params, $token = null) {
        //$jsonParams = Zend_Json::encode($params);
        $jsonParams = json_encode($params);
        
        $serviceCall = $this->serviceURL.'?params='.urlencode($jsonParams)."&method=$method&class=$class";
        if ($token != null) {
            $serviceCall.='&token_id='.$token;
        }
        
        echo "<BR>CALL: $serviceCall <BR><BR>";
        $session = curl_init($serviceCall);
        curl_setopt($session, CURLOPT_HEADER, false);
          curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($session);
        curl_close($session);
        
        /*echo "<BR>RAW RESPONSE<BR><BR>";
        var_dump($response);
        echo "<BR><BR>";*/
        
        //return Zend_Json::decode($response);
        return json_decode($response);
    }
    
}

// ---------------------------------------------------------------------------------------------
echo "\n-------------------------------";
echo "\nSCRIPT STARTED";

$ex = new Example();
$ex->runJsonExample();

echo "\nSCRIPT ENDED";
echo "\n-------------------------------\n";
// ---------------------------------------------------------------------------------------------
