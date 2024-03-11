<?php
require_once dirname(__DIR__).'/autoload.php';

class Router {
    

    private function getControllerInstance(string $controllerName) {
        switch ($controllerName) {
            case 'Controller':
                return new Controller();
                default:
                throw new Exception("Controller not found: " . $controllerName);
        }
    }

    public function run(){
        try {
            if (isset($_GET['page']) && !empty($_GET['page'])) {
                $url = explode("/", filter_input(INPUT_GET, 'page', FILTER_SANITIZE_URL));

                $page = $url[0];

                switch ($page) {
                    case HOME_PAGE:
                        $this->getControllerInstance('Controller')->getHomePage();
                        break;
                    case API:
                        if(isset($url[1])){
                            switch($url[1]){
                                case GENERATE_TEXT:
                                    $this->getControllerInstance('Controller')->generateText();
                                    break;
                                case EXTRACT_EMAIL:
                                    $this->getControllerInstance('Controller')->extractEmail();
                                    break;
    
                            }
                        }
                        break;
                    default:
                    http_response_code(404);
                    throw new Exception("Nous sommes désolés, la page que vous recherchez n'existe pas sur notre site Web ! ");
                }
            } else {
                $this->getControllerInstance('Controller')->getHomePage();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
