<?php
class Regle
{
	public $regle;
	protected $regle_spe="";
	protected $param_regle_spe=null;
	
	public function __construct($regle,$regle_spe=""){
		$this->regle=$regle;
		$this->regle_spe=$regle_spe;
	}
	
	public function respectee($valeur_case){
		return (($this->regle_spe!="" ? $this->regle_spe() : true) && preg_match($this->regle,$valeur_case));
	}
}
?>