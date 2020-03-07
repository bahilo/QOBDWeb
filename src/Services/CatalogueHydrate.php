<?php

namespace App\Services;

use App\Entity\Item;
use App\Entity\Comment;
use App\Entity\EanCode;
use App\Entity\ImeiCode;

class CatalogueHydrate{

protected $catalogue_dir;

    public function __construct($catalogue_dir){

        $this->catalogue_dir = $catalogue_dir;

    }

    public function hydrateItem($items){

        $output = [];
        foreach($items as $item){
            $group = $item->getItemGroupe();
            $brand = $item->getItemBrand();
            $comment = $item->getComment();
            $imei = $item->getImeiCode();

            if($brand)
                $item->setItemBrandName((string)$brand);
            // else
            //     $item->setItemBrandName("");


            if($group)
                $item->setItemGroupeName((string)$group);
            // else
            //     $item->setItemGroupeName("");
            
            if($comment)
                $item->setContentComment($comment->getContent());
            // else
            //     $item->setContentComment("");

            if ($imei){
                $item->setImei($imei->getCode());
                $ean = $imei->getEanCode();
                if($ean)
                    $item->setEan($ean->getCode());
            }

            if(!empty($item->getPicture()))
                $item->setFullPathPicture($this->catalogue_dir .'/'. $item->getPicture());

            array_push($output, $item);
        }
        return $output;
    }

    public function hydrateItemRelationFromForm(Item $item){
        
        $comment = $item->getComment();
        $imei = $item->getImeiCode();
               
       if(!empty($item->getContentComment())){
            if (!empty($comment))
                $comment = new Comment();

            $comment->setContent($item->getContentComment());
            $comment->setCreateAt(new \DateTime());
            $item->setComment($comment);
       }

        if(!empty($item->getImei())){
            if (empty($imei))
                $imei = new ImeiCode;
            $imei->setCode($item->getImei());               
        }

        if (!empty($item->getEan())) {
            if (empty($imei)){
                $imei = new ImeiCode;
                $imei->setCode("");
            }
            $ean = $imei->getEanCode();
            if (empty($ean))
                $ean = new EanCode;

            $ean->setCode($item->getEan());             
            $imei->setEanCode($ean);
        }

        $item->setImeiCode($imei);   

        
        $item->setCreatedAt(new \DateTime());
        $item->setIsErasable(true);

        return $item;
    }
}