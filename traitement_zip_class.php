<?php
class TraitementZip{
    private $emplacement;

    protected function parcourir_dossier($dossier) {
        // Ouvrir le dossier
        $handle = opendir($dossier);
    
        // Parcourir le dossier
        while (false !== ($entry = readdir($handle))) {
            // Vérifier si l'entrée est un fichier
            if (is_file("$dossier/$entry")) {
                // Vérifier si le nom de fichier correspond à la regex
                if (preg_match('/.*/', $entry)) {
                    // Afficher le nom de fichier
                    echo "$entry<br>";
                }
            }
        }
    
        // Fermer le dossier
        closedir($handle);
    }
    
}

?>