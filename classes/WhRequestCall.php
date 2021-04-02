<?php

class WhRequestCall extends ObjectModel {
    public $id;
    public $id_whrequestcall;
    public $nom;
    public $prenom;
    public $email;
    public $tel;

    public static $definition = array(
        'table' => 'whrequestcall',
        'primary' => 'id_whrequestcall',
        'multilang' => false,
        'fields' => array(
            'nom' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'prenom' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'email' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'tel' => array(
                'type' => self::TYPE_STRING,
                'required' => false
            ),
        )
    );
}