<?php
/*
*
*  @author Web Helpers <contact@web-helpers.io>
*  @copyright  2021 Web Helpers

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

//on appelle ici notre classe "ObjectModel" que l'on va utiliser.
require_once _PS_MODULE_DIR_ . 'whcallme/classes/WhRequestCall.php';

class WhCallMe extends Module
{
    public function __construct()
    {
        $this->name = 'whcallme';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Web Helpers';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Request Call Back');
        $this->description = $this->l('Module enabling to request CallBack via Btn on the front Office');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->installSQL() ||
            !$this->installTab() ||
            !$this->registerHook('header') ||
            //!$this->registerHook('displayFooter') ||
            !$this->registerHook('displayHome') ||
            !Configuration::updateValue('WHPCALLME_CALLTOACTION', $this->l('Rappelez moi !')) ||
            !Configuration::updateValue('WHPCALLME_MAILDEST', 'contact@louisauthie.fr')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            //!$this->uninstallSql() ||
            !$this->uninstallTab() ||
            !$this->unregisterHook('header') ||
            //!$this->unregisterHook('displayFooter') ||
            !$this->unregisterHook('displayHome') ||
            !Configuration::deleteByName('WHPCALLME_CALLTOACTION') ||
            !Configuration::deleteByName('WHPCALLME_MAILDEST')){
            return false;
        }
        return true;
    }

    //création de la table dans la base de données.
    protected function installSql(){
        
        $sqlCreate = "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . WhRequestCall::$definition["table"] . " (
            " . WhRequestCall::$definition["primary"] . " int(11) unsigned NOT NULL AUTO_INCREMENT,
            nom varchar(255) DEFAULT NULL,
            prenom varchar(255) DEFAULT NULL,
            email varchar(255) DEFAULT NULL,
            tel varchar(255) DEFAULT NULL,
            PRIMARY KEY (" . WhRequestCall::$definition["primary"] . ")
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        return Db::getInstance()->execute($sqlCreate);
    }

    //suppression de la table dans la base de données
    protected function uninstallSql(){
        $sql = "DROP TABLE " . _DB_PREFIX_ . WhRequestCall::$definition["table"];
        return Db::getInstance()->execute($sql);
    }

    //création de l'onglet dans le menu de l'administration
    protected function installTab(){
        $tab = new Tab();
        $tab->class_name = 'AdminWhRequestCall';
        $tab->module = $this->name;
        $tab->icon = 'settings_applications';
        $tab->id_parent = (int) Tab::getIdFromClassName('DEFAULT');
        $languages = Language::getLanguages();
        foreach ($languages as $lang) {
            $tab->name[$lang['id_lang']] = $this->displayName;
        }
        try {
            $tab->save();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
        return true;
    }

    //suppression de l'onglet dans le menu de l'admnistration.
    protected function uninstallTab(){
        $idTab = (int) Tab::getIdFromClassName('AdminWhRequestCall');
        if ($idTab) {
            $tab = new Tab($idTab);
            try {
                $tab->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }
        return true;
    }
    
    // sauvegarde de la configuration du module
    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('btnSubmit')) {

            $calltoaction = strval(Tools::getValue('WHPCALLME_CALLTOACTION'));
            $emailNotif = strval(Tools::getValue('WHPCALLME_MAILDEST'));

            if (
                !$emailNotif || empty($emailNotif) ||
                !$calltoaction || empty($calltoaction)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration values'));
            } else {
                Configuration::updateValue('WHPCALLME_CALLTOACTION', $calltoaction);
                Configuration::updateValue('WHPCALLME_MAILDEST', $emailNotif);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output.$this->displayForm();
    }
    
    public function displayForm()
    {
        // Récupère la langue par défaut
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Initialise les champs du formulaire dans un tableau
        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Contenu du bouton de la page d\'accueil'),
                        'name' => 'WHPCALLME_CALLTOACTION',
                        'size' => 250,
                        'required' => true
                    ],
                    
                    [
                        'type' => 'text',
                        'label' => $this->l('Email destinataire'),
                        'name' => 'WHPCALLME_MAILDEST',
                        'size' => 100,
                        'required' => true
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'name'  => 'btnSubmit'
                ]
            ],
        ];

        $helper = new HelperForm();

        // Module, token et currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Langue
        $helper->default_form_language = $defaultLang;

        $helper->fields_value['WHPCALLME_CALLTOACTION'] = Configuration::get('WHPCALLME_CALLTOACTION');
        $helper->fields_value['WHPCALLME_MAILDEST'] = Configuration::get('WHPCALLME_MAILDEST');

        return $helper->generateForm(array($form));
    }
    
    public function postProcess(){
    }


    // front: chargement des ressources si Order
    public function hookHeader()
    {
        if (!isset($this->context)) {
            $this->context = Context::getContext();
        }
            $this->context->controller->registerStylesheet(
                'whcallme-css',
                'modules/'.$this->name.'/views/css/whcallme.css',
                [
                    'media' => 'all',
                    'position' => 'head',
                    'priority' => 200,
                ]
            );


            $this->context->controller->registerJavascript(
                'whcallme-form-js',
                'modules/'.$this->name.'/views/js/whcallme.js',
                [
                    'position' => 'bottom',
                    'priority' => 150
                ]
            );
    }
    
    public function hookDisplayHome($params)
    {
        if(Tools::isSubmit('btnCallMe')){
            if(!empty(Tools::getValue('nom')) && !empty(Tools::getValue('prenom')) && !empty(Tools::getValue('tel'))){
                
                if(Tools::getValue('rgpd_ok') && Tools::getValue('rgpd_ok')==1){
                    $whrequestcall = new WhRequestCall();
                    $whrequestcall->nom = Tools::getValue('nom');
                    $whrequestcall->prenom = Tools::getValue('prenom');
                    $whrequestcall->tel = Tools::getValue('tel');
                    $whrequestcall->email = Tools::getValue('email');
                    $whrequestcall->save();
                    
                    Mail::Send(
                        (int)(Configuration::get('PS_LANG_DEFAULT')),
                        'callme', // email template file to be use
                        'Demande de rappel depuis le site', // email subject
                        array(
                            '{email}' => Configuration::get('PS_SHOP_EMAIL'), // sender email address
                            '{message}' => 'Vous avez recu une demande de rappel de '.$whrequestcall->nom.' '.$whrequestcall->prenom.' - Tél: '.$whrequestcall->tel.' - Email: '.$whrequestcall->email // email content
                        ),
                        Configuration::get('WHPCALLME_MAILDEST'), // receiver email address
                        NULL, //receiver name
                        NULL, //from email address
                        NULL,  //from name
                        NULL, //file attachment
                        NULL, //mode smtp
                        _PS_MODULE_DIR_ . 'whcallme/mails' //custom template path
                    );
                    
                    $this->smarty->assign([
                        'msg'=>$this->l('Nous vous rappelerons dans les plus brefs délais. Merci de votre inscription'),
                        'type'=>'success',
                    ]);
                }else{
                    $this->smarty->assign([
                        'msg'=>$this->l('Le rappel nécessite l\'acceptation du stockage et de l\'exploitation des données personnelles !'),
                        'type'=>'error',
                    ]);
                }
            }else{
                $this->smarty->assign([
                    'msg'=>$this->l('Veuillez vérifier que vous avez bien entré vos nom, prenom et téléphone !'),
                    'type'=>'error',
                ]);
            }
        }
        
        $this->smarty->assign([
            'call_to_action'=>Configuration::get('WHPCALLME_CALLTOACTION'),
            'texte_accroche'=>$this->l('Laissez-nous vos coordonnées on vous rappelle'),
        ]);
        return $this->fetch('module:whcallme/views/front/hook/displayHome.tpl');
    }
}
