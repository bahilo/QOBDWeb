<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Utility{

    protected $errorHandler;
    protected $session;

    public function __construct(ErrorHandler $errorHandler, SessionInterface $session)
    {
        $this->errorHandler = $errorHandler;
        $this->session = $session;
    }

    public function getAbsoluteRootPath(){
        return dirname(__FILE__) . '/../../..';
    }
   
    public function getMonthOfYear($date){
        //Convert the date string into a unix timestamp.
        $unixTimestamp = strtotime($date->format('Y-m-d H:i:s'));
        
        //Get the month of the year using PHP's date function.
        $monthOfYear = date("F", $unixTimestamp);
        
        //Print out the month that our date fell on.
        return  $monthOfYear;
    }
   
    public function getDayOfWeek($date){
       //Convert the date string into a unix timestamp.
        $unixTimestamp = strtotime($date->format('Y-m-d H:i:s'));
        
        //Get the day of the week using PHP's date function.
        $dayOfWeek = date("l", $unixTimestamp);
        
        //Print out the day that our date fell on.
        return  $dayOfWeek;
    }
   
    public function getWeekOfYear($date){
       //Convert the date string into a unix timestamp.
        $unixTimestamp = strtotime($date->format('Y-m-d H:i:s'));

        //Get the week of the year using PHP's date function.
        $weekOfYear = date("W", $unixTimestamp);
        
        //Print out the week that our date fell on.
        return  $weekOfYear;
    }
   
    public function getYear($date){
       //Convert the date string into a unix timestamp.
        $unixTimestamp = strtotime($date->format('Y-m-d H:i:s'));

        //Get the week of the year using PHP's date function.
        $weekOfYear = date("Y", $unixTimestamp);
        
        //Print out the week that our date fell on.
        return  $weekOfYear;
    }
   
    public function getWhenFromToday(\DateTime $date){
        if(!empty($date)){
            $_date = mktime(0, 0, 0, $date->format("m"), $date->format("d"), $date->format("Y"));
            $oneWeek = mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"));
            $oneDayAgo = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
            $today = date("d/m/Y");

            if ($date->format('d/m/Y') == $today) {
                return $date->format('H:i:s');
            }elseif ($_date >= $oneDayAgo) {
                return 'Hier ' . $date->format('H:i:s');                
            }elseif ($_date >= $oneWeek) {
                    return $this->getDayOfWeek($date) . ' '  . $date->format('d') . ', ' . $date->format('H:i:s');
            }else {
                 return $date->format('d/m/Y H:i:s');
            }
        }
        else{
            return $date;
        }
    }

    public function checkCSRFAttack(Controller $ctrl, Request $request){
        $token = $request->get("token");
        if (!$ctrl->isCsrfTokenValid('upload', $token)) {
            $this->errorHandler->error("CSRF failure");

            return  true;
        } 
        return false;
    }

    public function uploadFile($file, string $saveDir, $defaultFilename = null){
        
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            if(empty($defaultFilename)){
                $defaultFilename = $newFilename;
            }

            try {
                $file->move(
                    $saveDir,
                    $defaultFilename
                );
            } catch (FileException $e) {
                $this->errorHandler->error("Une erreur s'est produite durant la sauvegarde de votre fichier!");
            }

            return $defaultFilename;
        }
        return null;
    }

    /**
     * Vérifie si un object exist dans un array
     */
    public function in_array($sourceArray, $object){        
        foreach($sourceArray as $key => $obj){
            if($obj->getId() == $object->getId())
                return $key;
        }
        return false;
    }

    /**
     * Vérifie si un string exist dans un array
     */
    public function str_in_array($sourceArray, $string){        
        foreach($sourceArray as $key => $obj){
            if($obj == $string)
                return $key;
        }
        return -1;
    }

    /**
     * supprime un object d'un array
     */
    public function removeFromArray($sourceArray, $object){
        $index = $this->in_array($sourceArray, $object);
        if ($index != -1)
            unset($sourceArray[$index]);
        return $sourceArray;
    }

    /**
     * Vérifie si un setting exist dans un array
     */
    public function in_arrayByCode($sourceArray, $object){        
        foreach($sourceArray as $key => $code){
            if($code == $object->getCode())
                return true;
        }
        return false;
    }

    /**
     * Récupère les données d'un array sans doublon
     */
    public function getDistinct($sourceArray){

        $outputArray = [];
        foreach($sourceArray as $key => $obj){
            if(!$this->in_array($outputArray, $obj))
                $outputArray[] = $obj;
        }
        return $outputArray;
    }

    /**
     * Récupère les données du setting sans doublon
     */
    public function getDistinctByCode($sourceArray){
        $outputArray = [];
        foreach($sourceArray as $key => $obj){
            if(!$this->in_arrayByCode($outputArray, $obj))
                $outputArray[] = $obj->getCode();
        }
        return $outputArray;
    }

    public function replaceSpecialChars(array $array_in)
    {
        $outputArray = array();
        foreach ($array_in as $objectKey => $objectArray) {
            if (is_array($objectArray)) {
                array_push($outputArray, $this->encodeBase64($objectArray));
            }
            else{
                $outputArray = $this->encodeBase64($array_in);
                break;
            }            
        }

        return $outputArray;
    }


    function encodeBase64(array $array_in)
    {
        $object = array();
        foreach ($array_in as $key => $value) {
            if (!is_object($value) && !is_array($value) && !is_numeric($value)) {
                $object[$key] = base64_encode($value);
            } 
            elseif(is_object($value)){
                //dump($value); die();
            }
            elseif (is_array($value)) {
                $object[$key] = $this->encodeBase64($value);
            } else {
                $object[$key] = $value;
            }
        }
        return $object;
    }


    function decodeBase64($array_in)
    {
        $object = array();
        foreach ($array_in as $objectKey => $objectValue) {
            if (is_array($objectValue)) {
                $object[$objectKey] = $this->decodeBase64($objectValue);
            } else {
                if (!empty($objectValue) and $this->is_base64($objectValue)) {
                    $object[$objectKey] = base64_decode($objectValue);
                } else {
                    $object[$objectKey] = $objectValue;
                }
            }
        }
        return $object;
    }


    public function restoreSpecialChars(array $array_in)
    {
        $res = $this->decodeBase64($array_in);
        return $res;
    }


    function unique_multidim_array($array, $searchKey)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as  $val) {
            if (!in_array($val[$searchKey], $key_array)) {
                $key_array[$i] = $val[$searchKey];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }


    function is_base64($s)
    {
        if (base64_encode(base64_decode($s, true)) === $s) return true;
        return false;
    }


}