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

}