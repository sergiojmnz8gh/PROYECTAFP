<?php

namespace App\Controllers;

use League\Plates\Engine;
use App\Repositories\RepoEmpresa;

class LandingController {
    protected $templates;

    public function __construct(Engine $templates) {
        $this->templates = $templates;
    }

    public function landingPage() {
        $empresas = RepoEmpresa::findAll();

        echo $this->templates->render('Landing/landingPage', ['empresas' => $empresas]);
    }
}