<?php
include "validateur_excel_produit_class.php";
$ses=new PDO('mysql:host=localhost;dbname=edi_octo', "root", "root");
$test=new ValidateurExcel("MOS_PRODUITS_0000001_20230404160000.csv","regle_validation_produit","",$ses);
?>