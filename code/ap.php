<?php
    include('bdd.php');

    class ap{
        private $bdd;

        public function __construct(){
            $this-> bdd = new BDD;
        }

        public function affichePage($lapage){
            if(!$this -> bdd ->connexion()){
                echo "une erreur est survenue lors de la connexion";
                return;
            }

            if ($lapage ==1) $this -> page1();
            else if ($lapage == 2)$this -> page2();

        }

        public function page1() {
            echo "première page"; 
        }

        public function page2(){
            echo "deuxième page";
        }
    }