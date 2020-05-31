<?php

namespace App\Services;

use App\Entity\Item;
use App\Entity\Comment;
use App\Entity\EanCode;
use App\Entity\ImeiCode;
use Doctrine\Common\Persistence\ObjectManager;

class CatalogueHydrate{

protected $catalogue_dir;
protected $manager;

    public function __construct($catalogue_dir, ObjectManager $manager){

        $this->catalogue_dir = $catalogue_dir;
        $this->manager = $manager;

    }

    public function hydrateItem($items){

        $output = [];
        foreach($items as $item){
            if(!empty($item->getPicture()))
                $item->setFullPathPicture($this->catalogue_dir .'/'. $item->getPicture());

            array_push($output, $item);
        }
        return $output;
    }
}