<?php

namespace App\Services;

use App\Entity\Item;
use App\Entity\Provider;
use App\Services\Serializer;
use App\Repository\SettingRepository;
use Doctrine\Common\Persistence\ObjectManager;

class SettingManager{


    protected $settingRepo;
    protected $utility;
    protected $manager;
    protected $serializer;

    public function __construct(SettingRepository $settingRepo, 
                                ObjectManager $manager, 
                                Utility $utility,
                                Serializer $serializer)
    {
        $this->settingRepo = $settingRepo;
        $this->utility = $utility;
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    public function get($code, $name){
        return $this->settingRepo->findOneBy(['Code' => $code, 'Name' => $name]);
    }

    public function importCatalogueItem($file, $tmpDir){
        $fileName = $this->utility->uploadFile($file, $tmpDir);
        $this->extgractCatalogueItem($fileName, $tmpDir);
    }

    public function importCatalogueBrand($file, $tmpDir)
    {
        return $this->importCatalogueInfo($file, $tmpDir, 'Marque');
    }

    public function importCatalogueCategorie($file, $tmpDir)
    {
        return $this->importCatalogueInfo($file, $tmpDir, 'Famille');
    }

    public function importCatalogueProvider($file, $tmpDir)
    {
        return $this->importCatalogueInfo($file, $tmpDir, 'Fournisseur');
    }

    private function extgractCatalogueItem($fileName, $tmpDir){

        if (!empty($fileName)) {
            $fileFullPath = $tmpDir . '/' . $fileName;
            if (($handle = fopen($fileFullPath, "r")) !== FALSE) {
                $skip = 0;
                $header = [];

                $error = [
                    'statut' => 0,
                    'message' => ''
                ];

                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {

                    if ($skip == 0) {
                        $header = explode('|', strtolower(implode('|', $data)));
                        $skip += 1;
                    } else {
                        $item = new Item();

                        $header_keys = array_keys($header, strtolower('ref'));
                        if (count($header_keys) > 0)
                            $item->setRef($data[$header_keys[0]]);
                        else {
                            $error = [
                                'message' => 'Vous devez renseigner la colonne[ref] référence produit!',
                                'statut' => 500
                            ];
                        }

                        $header_keys = array_keys($header, strtolower('picture'));
                        if (count($header_keys) > 0)
                            $item->setPicture($data[$header_keys[0]]);

                        $header_keys = array_keys($header, strtolower('purchase_price'));
                        if (count($header_keys) > 0)
                            $item->setPurchasePrice($data[$header_keys[0]]);
                        else {
                            $error = [
                                'message' => 'Vous devez renseigner la colonne[purchase_price] prix d\'achat produit!',
                                'statut' => 500
                            ];
                        }

                        $header_keys = array_keys($header, strtolower('sell_price'));
                        if (count($header_keys) > 0)
                            $item->setSellPrice($data[$header_keys[0]]);
                        else {
                            $error = [
                                'message' => 'Vous devez renseigner la colonne[sell_price] prix de vente produit!',
                                'statut' => 500
                            ];
                        }

                        $header_keys = array_keys($header, strtolower('Name'));
                        if (count($header_keys) > 0)
                            $item->setName($data[$header_keys[0]]);
                        else {
                            $error = [
                                'message' => 'Vous devez renseigner la colonne[Name] nom produit!',
                                'statut' => 500
                            ];
                        }

                        $header_keys = array_keys($header, strtolower('Stock'));
                        if (count($header_keys) > 0)
                            $item->setStock($data[$header_keys[0]]);
                        else {
                            $error =  [
                                'message' => 'Vous devez renseigner la colonne[Stock] stock produit!',
                                'statut' => 500
                            ];
                        }

                        $item->setIsErasable(true);
                        $item->setCreatedAt(new \Datetime());

                        $this->manager->persist($item);
                    }
                }
                if ($error['statut'] == 0)
                    $this->manager->flush();

                fclose($handle);
            }

            unlink($fileFullPath);
        }

        return $error;
    }

    public function importCatalogueInfo($file, $tmpDir, $target)
    {
        $fileName = $this->utility->uploadFile($file, $tmpDir);
        return $this->extgractCatalogueInfo($fileName, $tmpDir, $target);
    }

    private function extgractCatalogueInfo($fileName, $tmpDir, $target)
    {

        if (!empty($fileName)) {
            $fileFullPath = $tmpDir . '/' . $fileName;
            if (($handle = fopen($fileFullPath, "r")) !== FALSE) {
                $skip = 0;
                $header = [];

                $error = [
                    'statut' => 0,
                    'message' => ''
                ];

                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {

                    if ($skip == 0) {
                        $header = explode('|', strtolower(implode('|', $data)));
                        $skip += 1;
                    } else {
                        $provider = new Provider();

                        $header_keys = array_keys($header, strtolower('Name'));
                        if (count($header_keys) > 0)
                            $provider->setName($data[$header_keys[0]]);
                        else {
                            $error = [
                                'message' => '[Import '. $target.'] Vous devez renseigner la colonne[Name] nom du '. $target.'!',
                                'statut' => 500
                            ];
                        }

                        $provider->setIsEnabled(true);

                        $this->manager->persist($provider);
                    }
                }
                if ($error['statut'] == 0)
                    $this->manager->flush();

                fclose($handle);
            }

            unlink($fileFullPath);
        }

        return $error;
    }

    public function extractData($sourceArray, $SearchKey)
    {
        $outputArray = [];
        foreach ($sourceArray as $key => $obj) {
            if (!\array_key_exists($obj->{'get' . $SearchKey}(), $outputArray)) {
                $outputArray[$obj->{'get' . $SearchKey}()] = [];
            }
            \array_push($outputArray[$obj->{'get' . $SearchKey}()], $obj);
        }
        return $outputArray;
    }

    public function getSerializedObject($obj){
        return  $this->serializer->serialize([
            'object_array' => $obj,
            'format' => 'json',
            'group' => 'class_property'
        ]);
    }

    public function getDataSource($sourceArray, $code)
    {
        $outputArray = [];
        $res = $this->extractData($sourceArray, $code);
        foreach ($res as $key => $obj) {
            $outputArray[$key] = $this->getSerializedObject($obj);
        }
        return $outputArray;
    }

    public function getGeneralSettingDataSource($sourceArray)
    {
        return $this->getDataSource($sourceArray, 'Code');
    }

    public function getSettingDataSource($sourceArray){
        return $this->getSerializedObject($sourceArray);
    }

}