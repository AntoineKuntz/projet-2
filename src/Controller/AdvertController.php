<?php

// Controlleur du crud Annonce.  

namespace App\Controller;

use App\Model\AdvertManager;
use App\Controller\CheckForm;

class AdvertController extends AbstractController
{

    //check les erreurs du form

    public function checkAdvertForm()
    {  
        $checkForm = new CheckForm();
        
        if($checkForm->displayEmptyErrors() <= 0){
            return $errors = array();
        }
   
        $errors = $checkForm->displayEmptyErrors();
        return $errors;
    }

// liste les annonces disponibles

    public function index()
    {
        $advertManager = new AdvertManager();
        $advert = $advertManager->selectAll('title');
        if (!isset($_SESSION['user'])) {
            header('Location:../auth/logIn');
        } else {
          return $this->twig->render('Advert/index.html.twig', ['advert' => $advert]);  
        }
    }

// montre les informations disponibles pour une annonces spécifiques

    public function show(int $id): string
    {
        $advertManager = new AdvertManager();
        $advert = $advertManager->selectOneById($id);

        return $this->twig->render('Advert/show.html.twig', ['advert' => $advert]);
    }

    
      

// Ajouter une nouvelle annonce via un form

    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         
            // clean $_POST data
            $advertDatas = array_map('trim', $_POST);

            if(count($this->checkAdvertForm()) == 0) {
                $advertManager = new AdvertManager();
                $advertManager->insert($advertDatas);
                header('Location:/user/userShow/'.$_SESSION['user']['id']);
            } else {
                return $this->twig->render('Advert/add.html.twig', [
                    'advert' => $advertDatas,
                    'errors' => $this->checkAdvertForm()
                ]);
            }

        }

        return $this->twig->render('Advert/add.html.twig');

    }
    
// modiffication des annonces existantes

    public function edit(int $id): string
    {
        $advertManager = new AdvertManager();
        $advert = $advertManager->selectOneById($id);
            
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $advertDatas = array_map('trim', $_POST);
            
            //transmission des nouvelles informations et redirection sur la vue de l'annonce
            if(count($this->checkAdvertForm()) == 0) {
                $advertManager->update($advertDatas);
                header('Location: /Advert/show/' . $id);
            } else {
                return $this->twig->render('Advert/edit.html.twig', [
                    'advert' => $advertDatas,
                    'errors' => $this->checkAdvertForm()
                ]);
            }
        }
        return $this->twig->render('Advert/edit.html.twig', [ 'advert' => $advert]);
    }
    // supression d'une annonce
   public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $advertManager = new AdvertManager();
            $advertManager->delete($id);
            header('Location:/user/userShow/'.$_SESSION['user']['id']);
        }
    }
}