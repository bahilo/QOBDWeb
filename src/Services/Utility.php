<?php

namespace App\Services;

use App\Services\Serializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Utility{

    protected $logger;
    protected $serializer;

    public function __construct(LoggerInterface $logger, Serializer $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }
   
    public function getDayOfWeek($date){
       //Convert the date string into a unix timestamp.
        $unixTimestamp = strtotime($date);
        
        //Get the day of the week using PHP's date function.
        $dayOfWeek = date("l", $unixTimestamp);
        
        //Print out the day that our date fell on.
        return  $dayOfWeek;
    }
   
    public function getWhenFromToday($date){
        $_date = mktime(0, 0, 0, $date->format("m"), $date->format("d"), $date->format("Y"));
        $oneWeek = mktime(0, 0, 0, date("m"), date("d") -7, date("Y"));
        $oneDayAgo = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
        $today = date("d/m/Y");

        if ($_date < $oneWeek) {
            return $this->getDayOfWeek($date) + ' ' + $date->format('H:i:s');
        }
        elseif($_date < $oneDayAgo){
            return 'Hier ' + $date->format('H:i:s');
        }
        elseif($date->format('d/m/Y') == $today){
            return $date->format('H:i:s');
        }
        else{
            return $date->format('d/m/Y H:i:s');
        }
    }

    public function checkCSRFAttack(Controller $ctrl, Request $request){
        $token = $request->get("token");
        if (!$ctrl->isCsrfTokenValid('upload', $token)) {
            $this->logger->info("CSRF failure");

            return  true;
        } 
        return false;
    }

    public function uploadFile($file, string $saveDir){

        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            try {
                $file->move(
                    $saveDir,
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            return $newFilename;
        }
        return null;
    }

    public function in_array($sourceArray, $object){        
        foreach($sourceArray as $key => $obj){
            if($obj->getId() == $object->getId())
                return true;
        }
        return false;
    }

    public function in_arrayByCode($sourceArray, $object){        
        foreach($sourceArray as $key => $code){
            if($code == $object->getCode())
                return true;
        }
        return false;
    }

    public function getDistinct($sourceArray){
        $outputArray = [];
        foreach($sourceArray as $key => $obj){
            if(!$this->in_array($outputArray, $obj))
                $outputArray[] = $obj;
        }
        return $outputArray;
    }

    public function getDistinctByCode($sourceArray){
        $outputArray = [];
        foreach($sourceArray as $key => $obj){
            if(!$this->in_arrayByCode($outputArray, $obj))
                $outputArray[] = $obj->getCode();
        }
        return $outputArray;
    }

    public function extractData($sourceArray){
        $outputArray = [];
        foreach($sourceArray as $key => $obj){
            if (!\array_key_exists($obj->getCode(), $outputArray)) {
                $outputArray[$obj->getCode()] = [];                
            }             
            \array_push($outputArray[$obj->getCode()], $obj);
        }
        return $outputArray;
    }

    public function getSettingDataSource($sourceArray){
        $outputArray = [];
        $res = $this->extractData($sourceArray);        
        foreach($res as $key => $obj){
            $outputArray[$key] = $this->serializer->serialize([
                'object_array' => $obj,
                'format' => 'json',
                'group' => 'class_property'
            ]);
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
            if (!is_object($value) && !is_array($value)  && !is_numeric($value)) {
                $object[$key] = base64_encode($value);
            } 
            elseif(is_object($value)){
                dump($value); die();
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