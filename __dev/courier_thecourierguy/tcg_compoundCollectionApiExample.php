<?php

// namespace ///;

// Zend Framework Json module
// require_once('Zend/Json.php');

// Included JSON class if zend not available
// require_once('JSON.php');

class Example
{    
    /**
     * This example will go through:
     * 
     *   1) Obtaining a token for submissions;
     *   2) getting places for collection and delivery; and
     *   3) building the submitCompoundCollection array for submission.
     * 
     * 
     * This is an example class for submitting json requests.
     * 
     */

    function __construct()
    {
        $this->serviceURL = 'http://adpdemo.pperfect.com/ecomService/v10/Json/';
    
        $this->username = 'tcg11@ecomm'; // Enter your username
        $this->password = 'tcgecomm11'; // Enter your password
    
        $this->token = '';
        $this->salt = '';
    
        // $this->json = new Services_JSON();
        $this->nl = "\n<br>";
    }
    
    // This function makes a call to the ecom webservivce using json encoded parameters and returns the result 
    // This particular example uses curl but a regular php "file_get_contents" call can also be used
    function makeCall($class, $method, $params, $token = null)
    {
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
    
    function getSalt()
    {
        $params = [];
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

    function getSecureToken()
    {
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

    function getAuthToken()
    {
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
    
    function getPlaces()
    {    
        if($this->token == ''){
            echo $this->nl . "AUTH REQUIRED BEFORE THIS TEST" . $this->nl;
        }
        
        $available_postal_codes = array();
        $postCodeLookupParams = array();
        $postCodeLookupParams['postcode'] = '7925';
        $postCodeLookupResponse = $this->makeCall('Quote','getPlacesByPostcode',$postCodeLookupParams, $this->token);

        if ($postCodeLookupResponse->errorcode == 0) { //no error
            echo $this->nl . "SUCCESS: POSTCODE LOOKUP RETURNED WITHOUT ERROR" . $this->nl;
            $this->destPlace = $postCodeLookupResponse->results[7]->place;
            $this->destPlacename = $postCodeLookupResponse->results[7]->town;
            $this->destPostalCode = $nameLookupResponse->results[7]->pcode;
            // var_dump( $this );
            // exit();
        } else { //error, display notice and quit
            echo $this->nl . "ERROR: ".$postCodeLookupResponse->errormessage . $this->nl;
        }
        
        //originating location details
        $nameLookupParams['name'] = 'Walmer';
        $nameLookupResponse = $this->makeCall('Quote','getPlacesByName',$nameLookupParams, $this->token);

        if ($nameLookupResponse->errorcode == 0) { //no error
            echo $this->nl . "SUCCESS: PLACENAME LOOKUP RETURNED WITHOUT ERROR" . $this->nl;
            $this->origPlace = $nameLookupResponse->results[2]->place;
            $this->origPlacename = $nameLookupResponse->results[2]->town;
            $this->origPostalCode = $nameLookupResponse->results[2]->pcode;
            // var_dump( __LINE__, $this );
            // exit();
        } else { //error, display notice and quit
            echo $this->nl . "ERROR: ". $nameLookupResponse->errormessage . $this->nl;
        }    
    }

    function submitCompoundCollection($collectNo, $firstWaybill, $secondWaybill)
    {    
        $accnum = 'PPOT';        
        
        $firstWayFirstTrack = $firstWaybill . '0001';
        $firstWaySecondTrack = $firstWaybill . '0002';
        
        $secondWayFirstTrack = $secondWaybill . '0001';
        $secondWaySecondTrack = $secondWaybill . '0002';
        
        $compColParams = array();
        $compColParams['details'] = array();
        
        $compColParams['details']['collectno'] = $collectNo;
        $compColParams['details']['accnum'] = "PPOT";
        $compColParams['details']['specinstruction'] = "This is a test";
        $compColParams['details']['reference'] = "test reference";
        $compColParams['details']['collectiondate'] = "01.06.2021";
        
        //originating location
        $compColParams['details']['origperadd1'] = 'Address line 1';
        $compColParams['details']['origperadd2'] = 'Address line 2';
        $compColParams['details']['origperadd3'] = 'Address line 3';
        $compColParams['details']['origperadd4'] = 'Address line 4';
        $compColParams['details']['origperphone'] = '012345678';
        $compColParams['details']['origpercell'] = '012345678';
        
        $compColParams['details']['origplace'] = $this->origPlace;
        $compColParams['details']['origtown'] = $this->origPlacename;
        $compColParams['details']['origpers'] = 'orig customername';
        $compColParams['details']['origpercontact'] = 'contact name';
        $compColParams['details']['origperpcode'] = $this->origPostalCode; //postal code
        
        // $compColParams['details']['origlongitude'] = 34.123456;
        // $compColParams['details']['origlatitude'] = 32.123456;
        $compColParams['details']['starttime'] = "08:00";
        $compColParams['details']['endtime'] = "16:30";
        $compColParams['details']['duedate'] = "01.06.2021";

        //Create the waybills array object
        $compColParams['waybills'] = array();
        
        //Create first waybills item (index 0 in the wayrefs array)
        $compColParams['waybills'][0] = array();
        
        // destination and waybill details
        $compColParams['waybills'][0]['details'] = array();
        
        //destination location details
        $compColParams['waybills'][0]['details']['waybill'] = $firstWaybill;
        $compColParams['waybills'][0]['details']['waydate'] = "11.01.2021";
        $compColParams['waybills'][0]['details']['service'] = "ONX";
        $compColParams['waybills'][0]['details']['duedate'] = "20.01.2021";

        //destination location details
        $compColParams['waybills'][0]['details']['destperadd1'] = 'Address line 1';
        $compColParams['waybills'][0]['details']['destperadd2'] = 'Address line 2';
        $compColParams['waybills'][0]['details']['destperadd3'] = 'Address line 3';
        $compColParams['waybills'][0]['details']['destperadd4'] = 'Address line 4';
        $compColParams['waybills'][0]['details']['destperphone'] = '012345678';
        $compColParams['waybills'][0]['details']['destpercell'] = '012345678';
                
        // destplace, desttown, destpers, destpercontact, destperpcode inputs are mandatory
        $compColParams['waybills'][0]['details']['destplace'] = $this->destPlace;
        $compColParams['waybills'][0]['details']['desttown'] = $this->destPlacename;
        $compColParams['waybills'][0]['details']['destpers'] = 'dest customername';
        $compColParams['waybills'][0]['details']['destpercontact'] = 'contact name';
        $compColParams['waybills'][0]['details']['destperpcode'] = $this->destPostalCode; //postal code    
        
        $compColParams['waybills'][0]['details']['destperemail'] = "test@parcelperfect.com";
        $compColParams['waybills'][0]['details']['notifydestpers'] = 0;
        $compColParams['waybills'][0]['details']['specinstruction'] = "instruction";
        $compColParams['waybills'][0]['details']['reference'] = "waybill reference";
        $compColParams['waybills'][0]['details']['insuranceflag'] = 1;
        $compColParams['waybills'][0]['details']['instype'] = 1;
        $compColParams['waybills'][0]['details']['declaredvalue'] = 58.75;
        $compColParams['waybills'][0]['details']['nondoxflag'] = 1;
        $compColParams['waybills'][0]['details']['currency'] = "ZAR";
        $compColParams['waybills'][0]['details']['customsvalue'] = 58.75;
        
        // surcharges if applicable
        $compColParams['waybills'][0]['details']['surchargeflag1'] = 0;
        $compColParams['waybills'][0]['details']['surchargeflag2'] = 0;
        $compColParams['waybills'][0]['details']['surchargeflag3'] = 0;
        $compColParams['waybills'][0]['details']['surchargeflag4'] = 0;
        $compColParams['waybills'][0]['details']['surchargeflag5'] = 0;
        $compColParams['waybills'][0]['details']['surchargeflag6'] = 0;
        $compColParams['waybills'][0]['details']['surchargeflag7'] = 0;
        $compColParams['waybills'][0]['details']['surchargeflag8'] = 0;
        $compColParams['waybills'][0]['details']['surchargeflag9'] = 0;

        
        //Create the waybill's contents array object
        $compColParams['waybills'][0]['contents'] = array();
        
        //Create first contents item (index 0 in the contents array)
        $compColParams['waybills'][0]['contents'][0] = array();
        
        //Add contents details
        $compColParams['waybills'][0]['contents'][0]['item'] = 1;
        $compColParams['waybills'][0]['contents'][0]['description'] = 'this is a test';
        $compColParams['waybills'][0]['contents'][0]['pieces'] = 1;
        $compColParams['waybills'][0]['contents'][0]['dim1'] = 10;
        $compColParams['waybills'][0]['contents'][0]['dim2'] = 15;
        $compColParams['waybills'][0]['contents'][0]['dim3'] = 15;
        $compColParams['waybills'][0]['contents'][0]['actmass'] = 1.5;
        
        //Create second contents item (index 1 in the contents array)
        $compColParams['waybills'][0]['contents'][1] = array();
        
        //Add the second content's details
        $compColParams['waybills'][0]['contents'][1]['item'] = 2;
        $compColParams['waybills'][0]['contents'][1]['description'] = 'this is another test';
        $compColParams['waybills'][0]['contents'][1]['pieces'] = 1;
        $compColParams['waybills'][0]['contents'][1]['dim1'] = 10;
        $compColParams['waybills'][0]['contents'][1]['dim2'] = 10;
        $compColParams['waybills'][0]['contents'][1]['dim3'] = 10;
        $compColParams['waybills'][0]['contents'][1]['actmass'] = 2.5;
        
        
        //Create the wayrefs array object
        $compColParams['waybills'][0]['wayrefs'] = array();
        
        //Create first wayrefs item 
        $compColParams['waybills'][0]['wayrefs'][0] = array();
        
        //Add wayrefs details
        $compColParams['waybills'][0]['wayrefs'][0]['reference'] = 'REFERENCENO1';
        $compColParams['waybills'][0]['wayrefs'][0]['pageno'] = 1;
        
        //Create second wayrefs item 
        $compColParams['waybills'][0]['wayrefs'][1] = array();
        
        //Add the second wayrefs details
        $compColParams['waybills'][0]['wayrefs'][1]['reference'] = 'REFERENCENO2';
        $compColParams['waybills'][0]['wayrefs'][1]['pageno'] = 1;
        
        
        //Create the track's contents array object
        $compColParams['waybills'][0]['tracks'] = array();
        
        //Create first tracks item 
        $compColParams['waybills'][0]['tracks'][0] = array();
        
        //Add tracks details
        $compColParams['waybills'][0]['tracks'][0]['trackno'] = $firstWayFirstTrack;
        $compColParams['waybills'][0]['tracks'][0]['parcelno'] = 1;
        $compColParams['waybills'][0]['tracks'][0]['item'] = 1;
        
        //Create second tracks item 
        $compColParams['waybills'][0]['tracks'][1] = array();
        
        //Add second track's details
        $compColParams['waybills'][0]['tracks'][1]['trackno'] = $firstWaySecondTrack;
        $compColParams['waybills'][0]['tracks'][1]['parcelno'] = 2;
        $compColParams['waybills'][0]['tracks'][1]['item'] = 2;
        
        
        
        //Create second waybills item 
        $compColParams['waybills'][1] = array();        
        
        $compColParams['waybills'][1]['details']['waybill'] = $secondWaybill;
        $compColParams['waybills'][1]['details']['waydate'] = "11.01.2018";
        $compColParams['waybills'][1]['details']['service'] = "ONX";
        $compColParams['waybills'][1]['details']['duedate'] = "20.01.2018";        
        $compColParams['waybills'][1]['details']['destperadd1'] = 'Address line 1';
        $compColParams['waybills'][1]['details']['destperadd2'] = 'Address line 2';
        $compColParams['waybills'][1]['details']['destperadd3'] = 'Address line 3';
        $compColParams['waybills'][1]['details']['destperadd4'] = 'Address line 4';
        $compColParams['waybills'][1]['details']['destperphone'] = '012345678';
        $compColParams['waybills'][1]['details']['destpercell'] = '012345678';
        $compColParams['waybills'][1]['details']['destplace'] = $this->destPlace;
        $compColParams['waybills'][1]['details']['desttown'] = $this->destPlacename;
        $compColParams['waybills'][1]['details']['destpers'] = 'destperson';
        $compColParams['waybills'][1]['details']['destpercontact'] = 'contact name';
        $compColParams['waybills'][1]['details']['destperpcode'] = $this->destPostalCode; //postal code        
        $compColParams['waybills'][1]['details']['destperemail'] = "test@parcelperfect.com";
        $compColParams['waybills'][1]['details']['notifydestpers'] = 0;
        $compColParams['waybills'][1]['details']['specinstruction'] = "instruction";
        $compColParams['waybills'][1]['details']['reference'] = "waybill reference";
        $compColParams['waybills'][1]['details']['insuranceflag'] = 1;
        $compColParams['waybills'][1]['details']['instype'] = 1;
        $compColParams['waybills'][1]['details']['declaredvalue'] = 58.75;
        $compColParams['waybills'][1]['details']['nondoxflag'] = 1;
        $compColParams['waybills'][1]['details']['currency'] = "ZAR";
        $compColParams['waybills'][1]['details']['customsvalue'] = 58.75;
        $compColParams['waybills'][1]['details']['surchargeflag1'] = 0;
        $compColParams['waybills'][1]['details']['surchargeflag2'] = 0;
        $compColParams['waybills'][1]['details']['surchargeflag3'] = 0;
        $compColParams['waybills'][1]['details']['surchargeflag4'] = 0;
        $compColParams['waybills'][1]['details']['surchargeflag5'] = 0;
        $compColParams['waybills'][1]['details']['surchargeflag6'] = 0;
        $compColParams['waybills'][1]['details']['surchargeflag7'] = 0;
        $compColParams['waybills'][1]['details']['surchargeflag8'] = 0;
        $compColParams['waybills'][1]['details']['surchargeflag9'] = 0;
        
        
        $compColParams['waybills'][1]['contents'] = array();        
        $compColParams['waybills'][1]['contents'][0] = array();
        
        $compColParams['waybills'][1]['contents'][0]['item'] = 1;
        $compColParams['waybills'][1]['contents'][0]['description'] = 'this is a test';
        $compColParams['waybills'][1]['contents'][0]['pieces'] = 1;
        $compColParams['waybills'][1]['contents'][0]['dim1'] = 10;
        $compColParams['waybills'][1]['contents'][0]['dim2'] = 15;
        $compColParams['waybills'][1]['contents'][0]['dim3'] = 15;
        $compColParams['waybills'][1]['contents'][0]['actmass'] = 1.5;
        
        $compColParams['waybills'][1]['contents'][1] = array();        
        $compColParams['waybills'][1]['contents'][1]['item'] = 2;
        $compColParams['waybills'][1]['contents'][1]['description'] = 'this is another test';
        $compColParams['waybills'][1]['contents'][1]['pieces'] = 1;
        $compColParams['waybills'][1]['contents'][1]['dim1'] = 10;
        $compColParams['waybills'][1]['contents'][1]['dim2'] = 10;
        $compColParams['waybills'][1]['contents'][1]['dim3'] = 10;
        $compColParams['waybills'][1]['contents'][1]['actmass'] = 2.5;
        
        $compColParams['waybills'][1]['wayrefs'] = array();        
        $compColParams['waybills'][1]['wayrefs'][0] = array();
        $compColParams['waybills'][1]['wayrefs'][0]['reference'] = 'REFERENCENO1';
        $compColParams['waybills'][1]['wayrefs'][0]['pageno'] = 1;
        
        $compColParams['waybills'][1]['wayrefs'][1] = array();        
        $compColParams['waybills'][1]['wayrefs'][1]['reference'] = 'REFERENCENO2';
        $compColParams['waybills'][1]['wayrefs'][1]['pageno'] = 1;
        
        $compColParams['waybills'][1]['tracks'] = array();
        $compColParams['waybills'][1]['tracks'][0] = array();
        $compColParams['waybills'][1]['tracks'][0]['trackno'] = $secondWayFirstTrack;
        $compColParams['waybills'][1]['tracks'][0]['parcelno'] = 1;
        $compColParams['waybills'][1]['tracks'][0]['item'] = 1;
        
        $compColParams['waybills'][1]['tracks'][1] = array();
        $compColParams['waybills'][1]['tracks'][1]['trackno'] = $secondWaySecondTrack;
        $compColParams['waybills'][1]['tracks'][1]['parcelno'] = 2;
        $compColParams['waybills'][1]['tracks'][1]['item'] = 2;
        
        
        // Uncomment this if you want to see if the paramaters are being build correctly
        //echo $this->nl . " ---- request params ---- " . $this->nl;
        //print_r($compColParams);
        
        //echo $this->nl . " ---- calling submitCompoundCollection ---- " . $this->nl;
        
        $compColResponse = $this->makeCall('Collection','submitCompoundCollection',$compColParams, $this->token);
        
        // Uncomment these if you want to see the raw submission data
        //echo $this->nl . " ---- response ---- " . $this->nl;
        //print_r($compColResponse);
        
        if ($compColResponse->errorcode !== 0) {
            echo $this->nl . "ERROR: ".$compColResponse->errormessage . $this->nl;
        }else{
            echo $this->nl . "SUCCESS: COMPOUND COLLECTION SUBMITTED" . $this->nl;
        }        
        
    }

}

echo "\n-------------------------------";
echo "\nSCRIPT STARTED";

$ex = new Example();
$ex->getAuthToken();        
$ex->getPlaces();            

// below we specify our collection number, along with two waybills for submission
$compoundCollectNo = 7030;
$compoundFirstWaybill = 'TESTWAYBILL12391';
$compoundSecondWaybill = 'TESTWAYBILL12392';

// this is where all the magic happens
$ex->submitCompoundCollection($compoundCollectNo, $compoundFirstWaybill, $compoundSecondWaybill);

echo "\nSCRIPT ENDED";
echo "\n-------------------------------\n";
