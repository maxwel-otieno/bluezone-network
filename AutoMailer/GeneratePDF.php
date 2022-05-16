<?php
error_reporting(E_ALL);

class GeneratePDF {
	
    //Function to generate statement
    public function generateTransactions($transData) {
        // create new PDF document
        //echo('create new PDF document');
  		require_once 'tcpdf/tcpdf.php';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Empire');
        $pdf->SetAuthor('Empire');
        $pdf->SetTitle('Empire - Daily Transactions');
        $pdf->SetSubject('Empire - Daily Transactions');
        $pdf->SetKeywords('Empire, PDF, Daily, Transactions');

        // set default header data
		// if(defined('CUSTOM_PDF_HEADER_STRING')){
		
		// }
		// else{
		// 	define('CUSTOM_PDF_HEADER_STRING', "Daily Transactions: \n" . "Today ".date('Y-m-d'));

		// }


        // $pdf->SetHeaderData(CUSTOM_PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(15, PDF_MARGIN_TOP, 15);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set font
        $pdf->SetFont('dejavusans', '', 7);

        // add a page
        $pdf->AddPage();

        //set the data
        $html = '
            <html>
                <body>
                    <table border="0" cellpadding="2" cellspacing="1">
            ';

			//DATE|REF NO|NARRATION|DR(Ksh)|CR(kKsh)|CLEARED BAL(Ksh)~
			//2016-12-05|161202000666|EXCISE DUTY-MM-161202000666-254724017787|0.50|-|99082.19
			
			
        //split the data and feed it to the pdf itself
        $dataArr = explode("~", $transData);

        //check if the array has nay data
        if (count($dataArr) > 0):

            //the first array contains the columns split it and  loop for the header
            $headerColumns = explode("|", $dataArr[0]);
            $tbHeader = '<thead >
                                            <tr style="background-color: #46ae42; color:#fff;" >';
            if (count($headerColumns) > 0):
                foreach ($headerColumns as $column):
                    $tbHeader .= '<td style="text-align: center; border: solid 1px #000000; font-weight: 500; font-size: 13px; padding: 10px">' . str_replace("_", " ", $column) . '</td>';
                endforeach;
            endif;
            $tbHeader .= '  </tr>
                                          </thead>';
            //shift the data Array
            array_shift($dataArr);

            //loop through the data
            $tbBody = '<tbody>';
			$i = 0;
            foreach ($dataArr as $data):
				$i++;
                //split the data to get the actual values
                $columnData = explode("|", $data);
                
                if (count($columnData) > 3):
					$tbBody .= "<tr>";
				
					//$tbBody .= '<td width="4%" style="text-align: center;  border: solid 1px #000000">' . $i . '</td>';
                    foreach ($columnData as $column):
                        $tbBody .= '<td style="text-align: center;  border: solid 1px #000000">' . $column . '</td>';
                    endforeach;
					
					$tbBody .= "</tr>";
                endif;
                
            endforeach;
            $tbBody .= '</tbody>';
        endif;

        //append all data
        $html .= $tbHeader . $tbBody;

        $html .= '    </table>
                </body>
            </html>
        ';
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // reset pointer to the last page
        $pdf->lastPage();
        ob_clean();
        // ---------------------------------------------------------
        //Close and output PDF document
        $filename = strtolower(str_replace(" ", "_", 'AutoReport')) ."_".date('dhsi'). '.pdf';      
        $path = __DIR__.'/AutoReport/'.$filename;
        $pdf->Output($path, 'F');

        return $filename;
    }

}