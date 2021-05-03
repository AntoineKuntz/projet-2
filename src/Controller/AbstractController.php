<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 15:38
 * PHP version 7
 */

namespace App\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{
    /**
     * @var Environment
     */
    protected Environment $twig;

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => !APP_DEV, // @phpstan-ignore-line
                'debug' => APP_DEV,
            ]
        );
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addGlobal('session', $_SESSION);
    }

    public function restrictAdmin()
    {
        //RESTRICTION D ACCES
        if (!isset($_SESSION['user'])) {
            header('Location:/auth/logIn');
        } elseif ($_SESSION['user']['status'] !== '1') {
            header('Location:../Home/index');
        };
    }

    public function restrictLogIn()
    {
        if (!isset($_SESSION['user'])) {
            header('Location:../auth/logIn');
        };
    }
}
