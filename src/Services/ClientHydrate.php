<?php

namespace App\Services;

use App\Entity\Client;
use App\Entity\Address;
use App\Entity\Comment;
use App\Entity\Contact;

class ClientHydrate{

    public function __construct(){

    }

    public function hydrate($clients){
        

        $output = [];
        foreach($clients as $client){

            $contactP = null;
            $contactPAddress = null;
            $comment = null;
            $commentClient = null;

            if($client->getContacts()){
                foreach($client->getContacts() as $contact){
                    if($contact->getIsPrincipal())
                        $contactP = $contact;
                }
            } 
            
            $commentClient = $client->getComment();
            if($commentClient){
                $client->setClientComment($commentClient->getContent());
            }

            if($contactP){
                $client->setFirstName($contactP->getFirstName());
                $client->setLastName($contactP->getLastName());
                $client->setPhone($contactP->getPhone());
                $client->setEmail($contactP->getEmail());
                $client->setFax($contactP->getFax());

                $contactPAddress = $contactP->getAddress();
            
                if($contactPAddress){
                    $client->setAddressName($contactPAddress->getName());
                    $client->setStreet($contactPAddress->getStreet());
                    $client->setZipCode($contactPAddress->getZipCode());
                    $client->setCity($contactPAddress->getCity());
                    $client->setCountry($contactPAddress->getCountry());

                    $comment = $contactPAddress->getComment();
        
                    if($comment)
                        $client->setAddressComment($comment->getContent());
                    else
                        $client->setAddressComment("");
                }
                else{
                    $client->setAddressName("");
                    $client->setStreet("");
                    $client->setZipCode("");
                    $client->setCity("");
                    $client->setCountry("");
                }
            }
            else{
                $client->setFirstName("");
                $client->setLastName("");
                $client->setPhone("");
                $client->setEmail("");
                $client->setFax(0);
            }
            array_push($output, $client);
        }

        return $output;
    }

    public function hydrateAddress(Address $address){

        $comment = $address->getComment();
        if($comment)
            $address->setContentComment($comment->getContent());
        
        return $address;
    }

    public function hydrateContact(Contact $contact){

       $contactAddress = $contact->getAddress();
        if($contactAddress){
            $contact->setAddressName($contactAddress->getName());
            $contact->setStreet($contactAddress->getStreet());
            $contact->setZipCode($contactAddress->getZipCode());
            $contact->setCity($contactAddress->getCity());
            $contact->setCountry($contactAddress->getCountry());
            $contact->setAddressComment($this->hydrateAddress($contactAddress)->getContentComment());
        }

         $comment = $contact->getComment();
        if($comment)
            $contact->setContentComment($comment->getContent());
        
        return $contact;
    }

    public function hydrateContactRelationFromForm(Contact $contact, $form){

        $contactAddress = $contact->getAddress();
        if(!$contactAddress)
            $contactAddress = new Address();

        $commentAddress = $contact->getComment();
        if(!$commentAddress)
                $commentAddress = new Comment();
        
        $comment = $contact->getComment();
        if(!$comment)
                $comment = new Comment();

        $commentAddress->setContent($form['AddressComment']);
        $commentAddress->setCreateAt(new \DateTime());

        $comment->setContent($form['ContentComment']);
        $comment->setCreateAt(new \DateTime());

        $contactAddress->setIsPrincipal(false);
        $contactAddress->setName($form['AddressName']);
        $contactAddress->setStreet($form['Street']);
        $contactAddress->setZipCode($form['ZipCode']);
        $contactAddress->setCity($form['City']);
        $contactAddress->setCountry($form['Country']);
        $contactAddress->setComment($commentAddress);

        $contact->setComment($comment);
        $contact->setAddress($contactAddress);
        
        return $contact;
    }

    public function hydrateAddressRelationFromForm(Address $address, $form){

        if(!$comment)
                $comment = new Comment();
            $comment->setContent($form['ContentComment']);
            $comment->setCreatedAt(new DateTime());

            $address->setComment($comment);
        
        return $address;
    }

    public function hydrateClientRelationFromForm(Client $client, $form){
        $client->setIsActivated(true);
        $contactPrincipal = null;
        
        foreach($client->getContacts() as $contact){
            if($contact->getIsPrincipal())
            $contactPrincipal = $contact;
        }

        if(!$contactPrincipal)
            $contactPrincipal = new Contact();
        
        $contactAddress = $contactPrincipal->getAddress();
        if(!$contactAddress)
            $contactAddress = new Address();

        $comment = $contactAddress->getComment();
        if(!$comment)
            $comment = new Comment();

        $clientComment = $client->getComment();
        if(!$clientComment)
            $clientComment = new Comment();
        
        $comment->setContent($form['AddressComment']);
        $comment->setCreateAt(new \DateTime());

        $clientComment->setContent($form['ClientComment']);
        $clientComment->setCreateAt(new \DateTime());

                
        $contactAddress->setIsPrincipal(true);
        $contactAddress->setName($form['AddressName']);
        $contactAddress->setStreet($form['Street']);
        $contactAddress->setZipCode($form['ZipCode']);
        $contactAddress->setCity($form['City']);
        $contactAddress->setCountry($form['Country']);
        $contactAddress->setComment($comment);

        $contactPrincipal->setFirstName($form['FirstName']);
        $contactPrincipal->setLastName($form['LastName']);
        $contactPrincipal->setPhone($form['Phone']);
        $contactPrincipal->setEmail($form['Email']);
        $contactPrincipal->setFax($form['Fax']);
        $contactPrincipal->setAddress($contactAddress);
        $contactPrincipal->setIsPrincipal(true);

        $client->addContact($contactPrincipal);
        $client->setComment($clientComment);

        if(!isset($form['IsProspect']))
            $client->setIsProspect(false);
              
        return $client;
    }

}