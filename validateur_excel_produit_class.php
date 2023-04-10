<?php
// require_once $_SERVER["DOCUMENT_ROOT"]."/yapi.php";
include "validateur_excel_class.php";
include "regle_excel_class.php";
class ValidateurExcel extends ValidateurExcels
{
	
	public function __construct($emplacement,$table,$adresse_accuse,$ses){
		$this->table=$table;
		$this->ses=$ses;
		$this->adresse_accuse=$adresse_accuse;
        $this->emplacement=$emplacement;
		$this->recupRegles();
		$this->genereErreurs();
    }
	
	protected function genereErreurs(){
		$accuse_reception = fopen(substr($this->emplacement,0,(strlen($this->emplacement)-3))."_AR.csv", "w+");
		$fichier=fopen($this->emplacement, 'r');
		$num_ligne=1;
		
		$ligne=fgetcsv($fichier, 1024,";");
		
		$ligne_ok=true;
		
		fputcsv($accuse_reception,array("EAN","STATUT","MESSAGE"),";");
		
		foreach($ligne as $colonne=>$case){
			if($case==null){
				$case="";
			}
			if(!$this->regles["header"][$colonne]->respectee($case) && $ligne_ok){
				$this->erreurs[]=array("Erreur à la ligne: ".$num_ligne." colonne: ".($colonne+1)." ( ".implode(" ", $ligne)." )","ligne" =>$num_ligne);
				fputcsv($accuse_reception,array("KO","Erreur a la ligne: ".$num_ligne." colonne: ".($colonne+1)." ( ".implode(" ", $ligne)." )"),";");
				$ligne_ok=false;
			}
		}
		
		if($ligne_ok)
			fputcsv($accuse_reception,array("Entete","OK"),";");
		$num_ligne++;
		
		while(!feof($fichier)){
			$ligne_ok=true;
			$ligne=fgetcsv($fichier, 1024,";");
			foreach($ligne as $colonne=>$case){
				if($case==null){
					$case="";
				}
				if(!$this->regles["contenu"][$colonne+1]->respectee($case) && $ligne_ok){
					$this->erreurs[]=array("Erreur a la ligne: ".$num_ligne." colonne: ".($colonne+1)." ( ".implode(" ", $ligne)." )","ligne" =>$num_ligne);
					fputcsv($accuse_reception,array($ligne[1],"KO","Erreur a la ligne: ".$num_ligne." colonne: ".($colonne+1)." (".implode(" ", $ligne).")"),";");
					$ligne_ok=false;
				}
				
			}
			if($ligne_ok)
					fputcsv($accuse_reception,array($ligne[1],"OK"),";");
			$num_ligne++;
		}
		
		fclose($accuse_reception);
		fclose($fichier);
	}
	
	protected function recupRegles(){
		

		$requete_pour_recuperer_les_regles="SELECT * FROM edi_octo.".$this->table." ORDER BY position";
		$mon_statement=$this->ses->prepare($requete_pour_recuperer_les_regles);
		$mon_statement->execute();
		$tableau_contenant_toutes_les_regles=$mon_statement->fetchAll();
		
		foreach($tableau_contenant_toutes_les_regles as $cle=>$tableau_contenant_la_regle_courante){
			$this->regles["header"][]=new Regle("/^".$tableau_contenant_la_regle_courante["titre"]."$/","");
			$this->regles["contenu"][$tableau_contenant_la_regle_courante["position"]]=new Regle($tableau_contenant_la_regle_courante["format"]);
		}
		
	}
    
}

?>