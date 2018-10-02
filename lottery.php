<?php
# This program gets the latest draws of various lotteries.
# At the command line, you can choose the type of lottery: elgoro, euro, loto
# And you can specify a JSON file name to save the results.

// Library loading for program operation
require_once 'classes.inc.php';

// Reserved for debugging:
// $url1 = "https://www.elgordo.com/results/euromillonariaen.asp";
// $file1 = "elgoro.json";
// $lot1 = new ElGordo($url1, $file1);
// $url2 = "https://www.lotto.pl/eurojackpot/wyniki-i-wygrane";
// $file2 = "eurojackpot.json";
// $lot2 = new Eurojackpot($url2, $file2);
// $url3 = "https://www.lotto.pl/lotto/wyniki-i-wygrane";
// $file3 = "lotto.json";
// $lot3 = new Lotto($url3, $file3);

// Getting data from the command line
if (isset($argv) && is_array($argv)) {
    
    // file name
    if (isset($argv[2])) {
        $file = $argv[2];
    } else
        $file = "lottery.json";
    
    // lottery type
    switch ($argv[1]) {
        case "elgoro":
            $url = "https://www.elgordo.com/results/euromillonariaen.asp";
            $lot = new ElGordo($url, $file);
            break;
        case "euro":
            $url = "https://www.lotto.pl/eurojackpot/wyniki-i-wygrane";
            $lot = new Eurojackpot($url, $file);
            break;
        case "lotto":
            $url = "https://www.lotto.pl/lotto/wyniki-i-wygrane";
            $lot = new Lotto($url, $file);
            break;
        default:
            $url = "https://www.lotto.pl/lotto/wyniki-i-wygrane";
            $lot = new Lotto($url, $file);
            break;
    }
    
}
