<?php
require_once dirname(__DIR__).'/autoload.php';

class Controller extends AbstractController {

    private $directory;

    public function __construct(){
        $this->directory = "home";
    }

    public function getHomePage(){
        $pageTitle = "Page d'accueil";
        $pageDescription = "";

        $this->renderView($this->directory,"home", [
            "pageTitle" => $pageTitle,
            "pageDescription" => $pageDescription,
        ]);

    }

    public function extractEmail() {
        $response = [];
        $response['success'] = false;
        $response['error']['msg'] = "";
        $response['error']['param'] = "";

        $data = [];
        $data['text_input'] = ValidatorTools::sanitizePostParam('text_input',PARAM_STR);
        $data['domain_selector'] = ValidatorTools::sanitizePostParam('domain_selector',PARAM_STR);
        $data['token'] =  ValidatorTools::sanitizePostParam('token',PARAM_STR); 

        if($data['token'] !== $_SESSION['token']){
            $response['success'] = false;
            $response['error']['msg'] = "Une erreur est survenue";
            $response['error']['param'] = "global";
            echo json_encode($response); 
            return;
        }

        $pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
        preg_match_all($pattern, $data['text_input'], $matches);

        $emails = $matches[0];

        if (!empty($data['domain_selector']) && $data['domain_selector'] != 'all') {
            $emails = array_filter($emails, function ($email) use ($data) {
                return strpos($email, $data['domain_selector']) !== false;
            });
        }
    
        if (!empty($emails)) {
            $response['success'] = true;
            $response['emails'] = implode("\n", $emails);
            $response['count_emails'] = count($emails);
        } else {
            $response['success'] = false;
            $response['error']['msg'] = "Aucun email trouvé";
            $response['error']['param'] = "text_input";
        }
    
        echo json_encode($response);
    }

    public function generateText(){
        $response = [];
        $response['success'] = false;
        $response['error']['msg'] = "";
        $response['error']['param'] = "";

        $data = [];
        $data['email_count'] = ValidatorTools::sanitizePostParam('email_count',PARAM_INT);
        $data['domain_selector'] = ValidatorTools::sanitizePostParam('domain_selector',PARAM_STR);
        $data['token'] =  ValidatorTools::sanitizePostParam('token',PARAM_STR);
    
        if($data['token'] !== $_SESSION['token']){
            $response['success'] = false;
            $response['error']['msg'] = "Une erreur est survenue";
            $response['error']['param'] = "global";
            echo json_encode($response); 
            return;
        }

        if ($data['email_count'] > 8900) {
            $response['success'] = false;
            $response['error']['msg'] = "Le nombre maximal d'emails pouvant être généré est de 8900.";
            $response['error']['param'] = "email_count";
            echo json_encode($response);
            return;
        }

        $wordCount = rand(50, 100) * $data['email_count'];

        $loremIpsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. ";
        $baseText = str_repeat($loremIpsum, ceil($wordCount / 8));
        $words = explode(' ', $baseText);

        for ($i = 0; $i < $data['email_count']; $i++) {
            $randomPosition = rand(0, count($words) - 1);
            $email = "email{$i}{$data['domain_selector']}";
            array_splice($words, $randomPosition, 0, $email);
        }

        $generatedText = implode(' ', $words);

        if (!empty($generatedText)) {
            $response['success'] = true;
            $response['content'] = $generatedText;
        } else {
            $response['error']['msg'] = "Impossible de générer le texte";
            $response['error']['param'] = "text_generation";
        }
            
        echo json_encode($response);

    }



}
