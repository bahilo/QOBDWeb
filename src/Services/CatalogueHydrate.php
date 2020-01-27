<?php

namespace App\Services;

use App\Entity\Comment;

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

            if($brand)
                $item->setItemBrandName((string)$brand);
            else
                $item->setItemBrandName("");


            if($group)
                $item->setItemGroupeName((string)$group);
            else
                $item->setItemGroupeName("");
            
            if($comment)
                $item->setContentComment($comment->getContent());
            else
                $item->setContentComment("");
            
            if(!empty($item->getPicture()))
                $item->setFullPathPicture($this->catalogue_dir .'/'. $item->getPicture());

            array_push($output, $item);
        }
        return $output;
    }

    public function hydrateItemRelationFromForm($item, $form){

        $group = $item->getItemGroupe();
        $brand = $item->getItemBrand();
        $comment = $item->getComment();
       
        if(!$comment)
            $comment = new Comment();
        $comment->setContent($form['ContentComment']);
        $comment->setCreateAt(new \DateTime());

        $item->setComment($comment);
        $item->setCreatedAt(new \DateTime());
        $item->setIsErasable(true);

        return $item;
    }
}