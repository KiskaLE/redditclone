<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;

final class SignPresenter extends Nette\Application\UI\Presenter {
    public function __construct(
        private Nette\Database\Explorer $database,
        private Passwords $passwords,
        ) {
        
    }

    protected function createComponentSignInForm(string $name): Form {
        $form = new Form;
        $form->addText("username", "email")->setRequired(); 
        $form->addPassword("password", "password")->setRequired(); 
        $form->addSubmit("submit", "login");

        $form->onSuccess[] = [$this, "signInFormSucceeded"];
        return $form;
    }

    public function signInFormSucceeded(Form $form, \stdClass $data): void {
        $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
        $user = $this->database->table("auth")->where("username", $data->username);
        //$hash = $user->password;
        // if ($passwords->verify($data->password, $hash)) {
            
        // };

    }

    protected function createComponentCreateForm(string $name): Form {
        $form = new Form;
        $form->addText("username", "email")->setRequired(); 
        $form->addPassword("password", "password")->setRequired(); 
        $form->addSubmit("submit", "login");

        $form->onSuccess[] = [$this, "createFormSucceeded"];
        return $form;
    }

    public function createFormSucceeded(Form $form, \stdClass $data): void {
        try {
            $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
            $hash = $passwords->hash($data->password);
            $this->database->table("auth")->insert([
                "username" => $data->username,
                "password" => $hash,
            ]);
            $this->redirect("Home:");
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError("email already exists");
        }
    }
}