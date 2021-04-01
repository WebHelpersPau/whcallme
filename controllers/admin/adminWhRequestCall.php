<?php
//on appelle ici aussi notre classe "ObjectModel" que l'on va utiliser.
require_once _PS_MODULE_DIR_ . 'whcallme/classes/WhRequestCall.php';

class AdminWhRequestCallController extends ModuleAdminController {

    //configuration de l'objet a utiliser et des champ à afficher
    public function __construct() {
        $this->bootstrap = true; //Gestion de l'affichage en mode bootstrap
        $this->table = WhRequestCall::$definition['table']; //Table de l'objet
        $this->identifier = WhRequestCall::$definition['primary']; //Clé primaire de l'objet
        $this->className = WhRequestCall::class; //Classe de l'objet
        $this->lang = false; //Flag pour dire si utilisation de langues ou non
        $this->_defaultOrderBy = WhRequestCall::$definition['primary'];
        //Appel de la fonction parente
        parent::__construct();

        //Liste des champs de l'objet à afficher dans la liste
        $this->fields_list = array(
            'id_whrequestcall' => array(//nom du champ sql
                'title' => $this->module->l('ID'), //Titre
                'align' => 'center', // Alignement
                'class' => 'fixed-width-xs', //classe css de l'élément
            ),
            'nom' => array(
                'title' => $this->module->l('Lastname'),
                'align' => 'left',
            ),
            'prenom' => array(
                'title' => $this->module->l('Firstname'),
                'align' => 'left',
            ),
            'email' => array(
                'title' => $this->module->l('Email'),
                'align' => 'left',
            ),
            'tel' => array(
                'title' => $this->module->l('Tél'),
                'align' => 'left',
            ),
        );
    }

    //configuration du formulaire d'ajout/edition d'une ligne de la table
    //utiliser l'URL de votre admin + "index.php?controller=AdminPatterns" pour la liste des champs disponibles
    public function renderForm() {
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Edit Request Call Form'),
            ],
            'input' => [
                [
                    'type' => 'hidden',
                    'label' => null,
                    'name' => 'id_whrequestcall',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Nom'),
                    'name' => 'nom',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Prénom'),
                    'name' => 'prenom',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Email'),
                    'name' => 'email',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Tél'),
                    'name' => 'tel',
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ]
        ];
        return parent::renderForm();
    }

    //permet d'ajouter le bouton de suppression pour chaque ligne
    public function renderList() {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $lists = parent::renderList();
        parent::initToolbar();

        return $lists;
    }

}