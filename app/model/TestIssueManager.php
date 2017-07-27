<?php
/**
 * Created by PhpStorm.
 * User: deanamarekova
 * Date: 19.7.17
 * Time: 8:35
 */

namespace App\Model;

use Nette\Object;

/**
 * Model funkcie kalkulačky a JSON parser.
 * @package App\Model
 */
class TestIssueManager extends Object
{
    const PATTERN = '/(?:\-?\d+(?:\.?\d+)?[\+\-\*\/])+\-?\d+(?:\.?\d+)?/';

    const PARENTHESIS_DEPTH = 10;

    public function calculate($input){
        if(strpos($input, '+') != null || strpos($input, '-') != null || strpos($input, '/') != null || strpos($input, '*') != null){
            //  vyhladavanie aritmetickych operacii pomocou strpos()
            $input = str_replace(',', '.', $input); // nahradi desatinne ciarky -> desatinne bodky
            $input = preg_replace('[^0-9\.\+\-\*\/\(\)]', '', $input); // nahradi regularny vyraz za prazdny retazec


            $i = 0;
            while(strpos($input, '(') || strpos($input, ')')){ // vyhladavanie zatvoriek
                $input = preg_replace_callback('/\(([^\(\)]+)\)/', 'self::callback', $input);
                // Vykonajte regularneho vyrazu a nahradenie s pouzitim spätného volania

                $i++;
                if($i > self::PARENTHESIS_DEPTH){
                    break;
                }
            }

            //  vypocet vysledku
            if(preg_match(self::PATTERN, $input, $match)){
                return $this->compute($match[0]);
            }

            return 0;
        }

        return $input;
    }

    private function compute($input){
        $compute = create_function('', 'return '.$input.';');

        return 0 + $compute();
    }

    const PATTERN_JSON = '((?:\{[^\{\}\[\]]*\})|(?:\[[^\{\}\[\]]*\]))%';

    /**
     * @param $input
     * @return mixed
     */
    public function jsonDecode($input) {
        /*$rgxstr = '%("(?:[^"\\\\]*|\\\\\\\\|\\\\"|\\\\)*"|\'(?:[^\'\\\\]*|\\\\\\\\|\\\\\'|\\\\)*\')%';
        $chrs = array(chr(2),chr(1));
        $escs = array(chr(2).chr(2),chr(2).chr(1));
        $strings = array();


        $input = str_replace($chrs,$escs,$input);


        $pieces = preg_split($rgxstr,$input,-1,PREG_SPLIT_DELIM_CAPTURE);
        for($i=1;$i<count($pieces);$i+=2) {
            $strings []= str_replace($escs,$chrs,str_replace(array('\\\\','\\\'','\\"'),array('\\','\'','"'),substr($pieces[$i],1,-1)));
            $pieces[$i] = chr(2) . (count($strings)-1) . chr(2);
        }
        $input = implode($pieces); */

    }

}



