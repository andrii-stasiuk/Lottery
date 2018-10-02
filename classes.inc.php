<?php

// The main abstract class for the class library
abstract class Main
{
    
    // Method that loads data from the site page
    private function getInfoFromURL($localUrl)
    {
        $ch      = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $localUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }
    
    // Method for saving processing results
    private function saveResultsToJSON($localHtml, $localJsonFile)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($localHtml);
        $processedData = $this->processData($dom);
        $encodedString = json_encode($processedData);
        file_put_contents($localJsonFile, $encodedString);
        var_dump($encodedString); // reserved for testing purposes
    }
    
    // Method for processing the received data (own for each extended class)
    protected function processData($localDOM)
    {
    }
    
    // The class constructor calls the appropriate functions to work
    function __construct($url, $jsonfile)
    {
        $html = $this->getInfoFromURL($url);
        $this->saveResultsToJSON($html, $jsonfile);
    }
    
}



// Class for processing data received from the ElGordo lottery
class ElGordo extends Main
{
    
    // Method that redefines work of the same Main class method for ElGordo lottery
    protected function processData($localDOM)
    {
        $json_data = array(
            "ElGordo" => array()
        );
        
        foreach ($localDOM->getElementsByTagName('span') as $link) {
            if ($link->getAttribute('class') == "int-num") {
                $json_data["ElGordo"][] = $link->nodeValue;
                // echo $link->nodeValue."\n"; reserved for testing purposes
            }
        }
        
        return $json_data;
    }
    
}



// Class for processing data received from the Eurojackpot lottery
class Eurojackpot extends Main
{
    
    // Method that redefines work of the same Main class method for Eurojackpot lottery
    protected function processData($localDOM)
    {
        $json_data = array(
            "Eurojackpot" => array()
        );
        
        $i = 0;
        while ($localDOM->getElementsByTagName('div')->item($i)->getAttribute('class') != "number text-center") {
            $i++;
        }
        // var_dump($localDOM->getElementsByTagName('div')->item($i)->nodeValue); reserved for testing purposes
        
        for ($j = 0; $j <= 7; $j++) {
            if ($localDOM->getElementsByTagName('div')->item($i + $j)->nodeValue != '+') {
                $json_data["Eurojackpot"][] = $localDOM->getElementsByTagName('div')->item($i + $j)->nodeValue;
                // echo $localDOM->getElementsByTagName('div')->item($i+$j)->nodeValue."\n"; reserved for testing purposes
            }
        }
        
        return $json_data;
    }
    
}



// Class for processing data received from the LottoPlus lottery
class Lotto extends Main
{
    
    // Method that redefines work of the same Main class method for LottoPlus lottery
    protected function processData($localDOM)
    {
        $json_data = array(
            "Lotto" => array(),
            "LottoPlus" => array(),
            "LottoSzansa" => array()
        );
        
        $i = 0;
        while (strpos($localDOM->getElementsByTagName('div')->item($i)->getAttribute('class'), "resultsItem lotto dymek_kulki") === false) {
            $i++;
        }
        // var_dump($localDOM->getElementsByTagName('div')->item($i)->nodeValue); reserved for testing purposes
        for ($j = 0; $j <= 5; $j++) {
            $json_data["Lotto"][] = $localDOM->getElementsByTagName('div')->item($i + $j + 2)->nodeValue;
            // echo $localDOM->getElementsByTagName('div')->item($i+$j+2)->nodeValue."\n"; reserved for testing purposes
        }
        
        while (strpos($localDOM->getElementsByTagName('div')->item($i)->getAttribute('class'), "resultsItem lottoPlus dymek_kulki") === false) {
            $i++;
        }
        //var_dump($localDOM->getElementsByTagName('div')->item($i)->nodeValue); reserved for testing purposes
        for ($j = 0; $j <= 5; $j++) {
            $json_data["LottoPlus"][] = $localDOM->getElementsByTagName('div')->item($i + $j + 2)->nodeValue;
            // echo $localDOM->getElementsByTagName('div')->item($i+$j+2)->nodeValue."\n"; reserved for testing purposes
        }
        
        while (strpos($localDOM->getElementsByTagName('div')->item($i)->getAttribute('class'), "resultsItem lottoSzansa") === false) {
            $i++;
        }
        //var_dump($localDOM->getElementsByTagName('div')->item($i)->nodeValue); reserved for testing purposes
        for ($j = 0; $j <= 6; $j++) {
            $json_data["LottoSzansa"][] = $localDOM->getElementsByTagName('div')->item($i + $j + 2)->nodeValue;
            // echo $localDOM->getElementsByTagName('div')->item($i+$j+2)->nodeValue."\n"; reserved for testing purposes
        }
        
        return $json_data;
    }
    
}
