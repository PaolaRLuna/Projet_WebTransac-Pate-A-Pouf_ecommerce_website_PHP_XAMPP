<?php
    require_once('../bd/connexion.inc.php');

    function Mdl_Ajouter($membre,$mdp){
        global $connexion;
        $nom = $membre->getNom(); 
        $prenom = $membre->getPrenom();
        $courriel = $membre->getCourriel();
        $sexe = $membre->getSexe();
        $daten = $membre->getDaten();
        $msg = "";
        try{
            // Tester si le courriel existe déjà
            $requete = "SELECT * FROM membres WHERE courriel=?";
            $stmt = $connexion->prepare($requete);
            $stmt->bind_param("s", $courriel);
            $stmt->execute();
            $reponse =   $stmt->get_result();
            if ($reponse->num_rows == 0) { // OK, courriel n'existe pas
                $requete = "INSERT INTO membres VAlUES (0,?,?,?,?,?)";
                $stmt = $connexion->prepare($requete);
                $stmt->bind_param("sssss", $nom,$prenom,$courriel,$sexe,$daten);
                $stmt->execute();
                $idm = $connexion->insert_id;

                $requete = "INSERT INTO connexion VAlUES (?,?,?,'M','A')";
                $stmt = $connexion->prepare($requete);
                $stmt->bind_param("iss", $idm,$courriel,$mdp);
                $stmt->execute();
                $msg="<h3>Membre ".$membre->getPrenom().", ".$membre->getNom()." bien enregistré.<h3>";
            } else { // Courriel existe déjà
                $msg = "<br><b style='color:red'>Ce courriel est déja utilisé</b></br>";
            }
        }catch(Exception $e) {
            $msg = 'Erreur : '.$e->getMessage().'<br>';
        }finally{
            return $msg;
        }
    }
?>