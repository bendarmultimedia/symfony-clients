<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\UI\UIService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MainController extends AbstractController
{
    protected $request;
    protected $httpClientInterface;
    protected $ui;
    protected $page;
    protected $templateDir = '';
    protected $em;
    protected ContainerInterface $containerInterface;

    public function __construct(
        RequestStack $requestStack,
        HttpClientInterface $httpClientInterface,
        UIService $ui,
        EntityManagerInterface $entityManager,
        ContainerInterface $containerInterface
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->httpClientInterface = $httpClientInterface;
        $this->ui = $ui;
        $this->page = $this->ui->getPage();
        $this->page->setAttributes(['templateDir' => $this->templateDir]);
        $this->em = $entityManager;
        $this->containerInterface = $containerInterface;
    }
}
