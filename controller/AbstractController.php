<?php

require_once dirname(__DIR__).'/autoload.php';

abstract class AbstractController {
    protected $template;


    /**
     * 
     * Affiche un fichier de vue à partir du répertoire spécifié.
     * @param string $directory Le nom du répertoire où se trouve le fichier de vue.
     * @param string $viewName Le nom du fichier de vue, sans l'extension '.phtml'.
     * @param array $variables Un tableau de variables à extraire et rendre disponibles pour la vue.
     * 
     * @return void
    */
    public function renderView(string $directory,string $viewName, array $variables = []): void {
        extract($variables);
        require_once "views/$directory/$viewName.phtml";
    }

   
}