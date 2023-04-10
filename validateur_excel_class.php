<?php
//Interface qui permettra la création de nos sous class de traitement des csv
abstract class ValidateurExcels
{
    protected $emplacement;
	protected $table;
	protected $adresse_accuse;
    protected $erreurs=array();
    protected $regles=array("header" => array() , "contenu" => array());
	protected $ses;
    abstract protected function genereErreurs();
    abstract public function __construct($adresse_fichier,$table,$adresse_accuse,$ses);
    abstract protected function recupRegles();
}
?>