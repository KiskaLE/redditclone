<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


final class HomePresenter extends BasePresenter
{
    public function __construct( private Nette\Database\Explorer $database)
    {
       
    }

    public function renderDefault(): void
    {
        
        $this->template->usernames = $this->database->table("auth");
        $this->template->posts = $this->database
            ->table('posts')
            ->order('created_at DESC')
            ->limit(5);
    }

    
}