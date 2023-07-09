<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database
    ){  
    }


    public function createComponentLogoutForm(): Form {
        $form = new Form;
        $form->addSubmit("logout", "logout");
        
        $form->onSuccess[] = [$this, "logoutFormSucceeded"];
        return $form;
    }

    public function logoutFormSucceeded() {
        $this->user->logout();
        $this->redirect("Home:");
    }

}