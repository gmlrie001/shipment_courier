<?php


/* 
     * This example will go through:
     * 1) Obtaining a token for submissions
     * 2) getting places for collection and delivery
     * 3) building the submitCollection array for submission
     */

// // Zend Framework Json module
// require_once('Zend/Json.php');

// // Included JSON class if zend not available
// require_once('JSON.php');

//example class for submitting json requests
class Example
{
    
    function __construct() {
        $this->serviceURL = 'http://adpdemo.pperfect.com/ecomService/v10/Json/';
        $this->username = 'tcg11@ecomm'; //enter your username
        $this->password = 'tcgecomm11'; //enter your password
        $this->token = '';
        $this->salt = '';
        // $this->json = new Services_JSON();
        $this->nl = "\n<br>";
    }
    
    //this function makes a call to the ecom webservivce using json encoded parameters and returns the result 
    //this particular example uses curl but a regular php "file_get_contents" call can also be used
    function makeCall($class, $method, $params, $token = null) {
        $jsonParams = json_encode($params);
        
        echo $this->nl.$this->nl."------------------------".$this->nl.$method.$this->nl.'------------------------'.$this->nl;
        $serviceCall = $this->serviceURL.'?params='.urlencode($jsonParams)."&method=$method&class=$class";
        if ($token != null) {
            $serviceCall.='&token_id='.$token;
        }
        
        echo $this->nl . "CALL: $serviceCall" . $this->nl;
        $session = curl_init($serviceCall);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($session);
        curl_close($session);
        
        return json_decode($response);
    }
    
    function getSalt(){
        $params = array();
        $saltParams['email'] = $this->username;
        $response = $this->makeCall('Auth','getSalt',$saltParams);
        
        //check for errors
        if ($response->errorcode == 0) { //no error
            $salt = $response->results[0]->salt;
            $this->salt = $salt;
        } else { //error, display notice and quit
            echo "Error: ".$response->errormessage;
            //die;
        }
    }

    function getSecureToken(){
        //get the token
        $toMd5 = $this->password.$this->salt;
        $md5pass = md5($toMd5);
        
        $tokenParams = array();
        $tokenParams['email'] = $this->username;
        $tokenParams['password'] = $md5pass;
        $response = $this->makeCall('Auth','getSecureToken',$tokenParams);

        //check for error
        if ($response->errorcode == 0) { //no error
            $this->token = $response->results[0]->token_id;
        } else { //error, display notice and quit
            echo "Error: ".$response->errormessage;
            //die;
        }
    }

    function getAuthToken(){
        echo $this->nl . "TESTING AUTHORIZATION" . $this->nl;
        $this->getSalt();
        if($this->salt == ''){
            echo $this->nl . "ERROR: GETTING SALT FAILED" . $this->nl;
        } else {
            echo $this->nl . "SUCCESS: SALT GENERATED" . $this->nl;
        }
        //echo $this->nl . "GETTING SECURE TOKEN:" . $this->nl;
        $this->getSecureToken();
        if($this->token == ''){
            echo $this->nl . "ERROR: GETTING AUTH TOKEN FAILED" . $this->nl;
        } else {
            echo $this->nl . "SUCCESS: TOKEN GENERATED" . $this->nl;
        }
    }
    
    function getPlaces() {
        
        if($this->token == ''){
            echo $this->nl . "AUTH REQUIRED BEFORE THIS TEST" . $this->nl;
        }
        
        $available_postal_codes = array();
        $postCodeLookupParams = array();
        $postCodeLookupParams['postcode'] = '7700';
        $postCodeLookupResponse = $this->makeCall('Quote','getPlacesByPostcode',$postCodeLookupParams, $this->token);

        if ($postCodeLookupResponse->errorcode == 0) { //no error
            echo $this->nl . "SUCCESS: POSTCODE LOOKUP RETURNED WITHOUT ERROR" . $this->nl;
            $this->destPlace = $postCodeLookupResponse->results[0]->place;
            $this->destPlacename = $postCodeLookupResponse->results[0]->town;
        } else { //error, display notice and quit
            echo $this->nl . "ERROR: ".$postCodeLookupResponse->errormessage . $this->nl;
        }
        
        //originating location details
        $nameLookupParams['name'] = 'Johan';
        $nameLookupResponse = $this->makeCall('Quote','getPlacesByName',$nameLookupParams, $this->token);

        if ($nameLookupResponse->errorcode == 0) { //no error
            echo $this->nl . "SUCCESS: PLACENAME LOOKUP RETURNED WITHOUT ERROR" . $this->nl;
            $this->origPlace = $nameLookupResponse->results[0]->place;
            $this->origPlacename = $nameLookupResponse->results[0]->town;
        } else { //error, display notice and quit
            echo $this->nl . "ERROR: ".$nameLookupResponse->errormessage  . $this->nl;
        }    
    }
    


    function submitCollection(){    
        
        //build quote request:
        $collectParams = array();
        $collectParams = array();
        $collectParams['details'] = array();
        
        $collectParams['details']['specinstruction'] = "This is a test";
        $collectParams['details']['reference'] = "This is a test";
        $collectParams['details']['service'] = "ONX";
        $collectParams['details']['accnum'] = "PPO";
        //originating location
        $collectParams['details']['origperadd1'] = 'Address line 1';
        $collectParams['details']['origperadd2'] = 'Address line 2';
        $collectParams['details']['origperadd3'] = 'Address line 3';
        $collectParams['details']['origperadd4'] = 'Address line 4';
        $collectParams['details']['origperphone'] = '012345678';
        $collectParams['details']['origpercell'] = '012345678';
        
        $collectParams['details']['origplace'] = $this->origPlace;
        $collectParams['details']['origtown'] = $this->origPlacename;
        $collectParams['details']['origpers'] = 'TESTCUSTOMER';
        $collectParams['details']['origpercontact'] = 'origcontactsname';
        $collectParams['details']['origperpcode'] = '6730'; //postal code
                
        //destination location details
        $collectParams['details']['destperadd1'] = 'Address line 1';
        $collectParams['details']['destperadd2'] = 'Address line 2';
        $collectParams['details']['destperadd3'] = 'Address line 3';
        $collectParams['details']['destperadd4'] = 'Address line 4';
        $collectParams['details']['destperphone'] = '012345678';
        $collectParams['details']['destpercell'] = '012345678';
        
        //i chose the 1st result, but this will be up to the user as above
        $collectParams['details']['destplace'] = $this->destPlace;
        $collectParams['details']['desttown'] = $this->destPlacename;
        $collectParams['details']['destpers'] = 'TESTCUSTOMER';
        $collectParams['details']['destpercontact'] = 'destcontactsname';
        $collectParams['details']['destperpcode'] = '3340'; //postal code
        $collectParams['details']['starttime'] = '08:00'; 
        $collectParams['details']['endtime'] = '16:30'; 
        $collectParams['details']['notes'] = 'collection note'; 

        /* Add the Contents:
         * There needs to be at least 1 contest item with an "actmass" > 0 otherwise a rate will not calculate.
         * "Contents" needs to be an array object, even if there is only one contents item. */
         
        //Create the waybill's contents array object
        $collectParams['contents'] = array();
        
        //Create first contents item (index 0 in the contents array)
        $collectParams['contents'][0] = array();
        
        //Add contents details
        $collectParams['contents'][0]['item'] = 1;
        $collectParams['contents'][0]['description'] = 'this is a test';
        $collectParams['contents'][0]['pieces'] = 1;
        $collectParams['contents'][0]['dim1'] = 1;
        $collectParams['contents'][0]['dim2'] = 1;
        $collectParams['contents'][0]['dim3'] = 1;
        $collectParams['contents'][0]['actmass'] = 1;
        $collectParams['contents'][0]['defitem'] = 1;
        
        //Create second contents item (index 1 in the contents array)
        $collectParams['contents'][1] = array();
        
        //Add contents details
        $collectParams['contents'][1]['item'] = 2;
        $collectParams['contents'][1]['description'] = 'this is another test';
        $collectParams['contents'][1]['pieces'] = 1;
        $collectParams['contents'][1]['dim1'] = 1;
        $collectParams['contents'][1]['dim2'] = 1;
        $collectParams['contents'][1]['dim3'] = 1;
        $collectParams['contents'][1]['actmass'] = 1;
        $collectParams['contents'][1]['defitem'] = 1;
        
        
        // Uncomment this if you want to see if the paramaters are being build correctly
        //echo $this->nl . " ---- request params ---- " . $this->nl;
        //print_r($collectParams);
        
        //echo $this->nl . " ---- calling submitCompoundCollection ---- " . $this->nl;
        
        $response = $this->makeCall('Collection','submitCollection',$collectParams, $this->token);
        
        // Uncomment these if you want to see the raw submission data
        //echo $this->nl . " ---- response ---- " . $this->nl;
        //print_r($response);
        
        if ($response->errorcode !== 0) {
            echo $this->nl . "ERROR: ".$response->errormessage . $this->nl;
        }else{
            echo $this->nl . "SUCCESS: COLLECTION SUBMITTED" . $this->nl;
            print_r( $response );
        }

    }

}

echo "\n-------------------------------";
echo "\nSCRIPT STARTED";

$ex = new Example();

$ex->getAuthToken();        

$ex->getPlaces();            

// this is where all the magic happens
$ex->submitCollection();

echo "\nSCRIPT ENDED";
echo "\n-------------------------------\n";
