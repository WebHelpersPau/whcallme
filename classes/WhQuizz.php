<?php
//on définis les champs correspondant à ceux utilisé dans la fonction "installSql" du fichier "whchatbot.php"
class WhRequestCall extends ObjectModel {
    public $id;
    public $id_whrequestcall;
    public $question;
    public $choice1;
    public $choice2;
    public $choice3;
    public $choice4;
    public $sentmessage;
    public $active;

    public static $definition = array(
        'table' => 'whrequestcall',
        'primary' => 'id_whrequestcall',
        'multilang' => false,
        'fields' => array(
            'question' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'choice1' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'choice2' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'choice3' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'choice4' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'sentmessage' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'required' => true
            ),
        )
    );
}