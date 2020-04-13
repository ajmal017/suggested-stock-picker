<?php
/**
* File: nyse.php
* Description: main execution script
* Author: Patrick Ingle for PressPage Entertainment Inc DBA PINGLEARE
* Author URL: https://pingleware.work
* License: COMMON DEVELOPMENT AND DISTRIBUTION LICENSE Version 1.0 (CDDL-1.0) [See lincese.txt]
*/
include 'table2arr.php';
include 'functions.php';

// Obtain a list of countries
//$countries = _nyse_get_regions();

$results = array();
$country = 9; // default US selected
// Obtain a list of traded equities for the US.
$results[$country] = _nyse_get_listed_companies($country);

file_put_contents('data/nyse_'.time().'.json',json_encode($results[$country]));

//echo '<pre>'; print_r($results); echo '</pre>'; exit;
//echo '<pre>';

$suggested = array();
$total_dividend = 0.0;

// Parse the company list looking for companies that meet the minimum requirements
foreach($results[9] as $equity) {
	// find companies which offer dividends with a maximum $100 share price and
	// minimum 10% dividend return.

	if (isset($equity['quote']['close']) && isset($equity['quote']['div yield']) && $equity['quote']['close'] <= 100.00 && $equity['quote']['div yield'] >= 10) {
		$temp = array();
		$temp['symbol'] = $equity['symbol'];
		$temp['company'] = $equity['title'];
		$temp['url'] = $equity['url'];
		$temp['dividend'] = $equity['dividend'];
		$temp['yield'] = $equity['dividend_yield'];
		$temp['quote'] = json_encode($equity['quote']);
		$temp['eps'] = $equity['quote']['eps'];
		$temp['dividend_payout_ratio'] = $equity['ppr'];
		$suggested[] = $temp;
		//print_r($temp);
		$total_dividend += $equity['dividend'];
	}
}

//echo '</pre>';
// Save the results in a CSV file.
$fp = fopen('picks/nyse_suggested_'.$country.'_'.time().'.csv', 'w');

$total_suggested = count($suggested);

foreach ($suggested as $fields) {
	$fields['allocation'] = 1000000 * ($fields['dividend'] / $total_dividend);
    fputcsv($fp, $fields);
}

fclose($fp);

?>
