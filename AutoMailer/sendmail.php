<?php
#checks if a request is a post request the process it accordingly.

error_reporting(E_ALL);

header("Content-type: application/json;charset=\"utf-8\"");
header('Accept: application/json');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$requestObject = json_decode(trim(file_get_contents('php://input')),true);
// print_r($requestObject);
// exit;

//the psot request must be there
if ($requestObject===null || empty($requestObject)===true) {

    $response =  json_encode(
            array(
                'STATUS' => 500,
                'DESCRIPTION' => 'EMAIL REQUEST CANNOT BE EMPTY'
            )
    );
}
else{

  $response = json_encode(
            array(
                'STATUS' => 200,
                'DESCRIPTION' => 'EMAIL REQUEST RECEIVED SUCCESSFULLY'
            )
    );

//process request

	// $request = '';
	// {
	// 	"subject":"Empire AutoReport Service",
	// 	"messageBody":"Dear Eclectics PG Group,Please find attached yesterday Transactions for Empire MFI. Kind Regards",
	// 	"data":"DATE|REF NO|NARRATION|DR(Ksh)|CR(Ksh)|CLEARED BAL(Ksh)~Sep  1 2017  4:24AM|W15WBACADH|CHARGES FOR BILL PAYMENT TO NWSC FOR ACC: 15361512 |10.00| - |97,389.50|~",
	// 	"receipients":"ojwang.jacob@ekenya.co.ke",
	// 	"from":"sales@empire.co.ke",
 //  		"nameFrom":"Empire MFI"

	// }

 

        // print_r($requestObject);exit;
        $data = isset($requestObject['data']) ? $requestObject['data'] : '';

        //egenerate pdf
        require_once 'GeneratePDF.php';
        $generate = new GeneratePDF();
        $filename = $generate->generateTransactions($data);
  
        $file_to_attach = __DIR__.'/AutoReport/'.$filename;


        $subject = isset($requestObject['subject']) ? $requestObject['subject'] : 'Auto-Report Generator';
        $message  = isset($requestObject['messageBody']) ? $requestObject['messageBody'] : 'Auto-Report Generator';
        $email_from =isset($requestObject['from']) ? $requestObject['from'] : '';
        $name_from = isset($requestObject['nameFrom']) ? $requestObject['nameFrom'] : 'Auto-Report Service';
        $email_to 	= isset($requestObject['receipients']) ? $requestObject['receipients'] : 'AutoReport Service';

		require_once 'Mailer.php';
        $mailer = new Mailer();
        $sent_email =  $mailer->sendEmailAttachment($email_from,$name_from,$email_to, $subject, $message,$file_to_attach,$filename);

    //return correct response to requesting Service
                if (isset($sent_email) && $sent_email === true) {

                  $response = json_encode(
			            array(
			                'STATUS' => 200,
			                'DESCRIPTION' => 'EMAIL REQUEST RECEIVED SUCCESSFULLY'
			            )
			    );
                     $response =  json_encode(
                            array(
                                'STATUS' => 200,
                                'DESCRIPTION' => 'EMAIL DESPATCH WAS SUCCESSFUL'
                            )
                    );
                } 
                else {
                  
                    $response = json_encode(
                            array(
                                'STATUS' => 301,
                                'DESCRIPTION' => 'EMAIL DESPATCH FAILED'
                            )
                    );
                }


}

print_r($response);
