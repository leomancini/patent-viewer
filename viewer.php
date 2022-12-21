<?php
	header('Content-type: application/pdf');

	function renderLocalPDF($patentNumber) {
		$file = "cache/$patentNumber.pdf";

		header('Content-Disposition: inline; filename="'.$file.'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize($file));
		header('Accept-Ranges: bytes');

		@readfile($file);
	}

	function renderRemotePDF($patentNumber) {
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, "https://image-ppubs.uspto.gov/dirsearch-public/print/downloadPdf/$patentNumber");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10); 
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout );

		$result = curl_exec($curl);
		curl_close($curl);
		echo $result;
	}

	$patentNumber = preg_replace('/[^-D0-9_]/', '', $_GET['patentNumber']);

	if (file_exists("cache/$patentNumber.pdf")) {
		renderLocalPDF($patentNumber);
	} else {
		renderRemotePDF($patentNumber);
	}
?>