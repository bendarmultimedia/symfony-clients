<?php

namespace App\Controller\Pub;

use App\Controller\MainController;
use App\Service\ClientService;
use App\Service\HotelService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Annotation;

class PublicController extends MainController
{

    protected $templateDir = 'public';
    /**
     * @Route("/", name="public_index")
     */
    public function index()
    {
        return $this->redirectToRoute('public_clients');
    }

    /**
     * @Route("/klienci/{page}", name="public_clients")
     */
    public function clients(ClientService $clientService, $page = 1)
    {
        $clientList = $clientService->findAllWithPagination($page);
        $pagination = $this->ui->getPagination($page, $clientList['pagesCount']);

        return $this->render(
            $this->page->getTemplate(),
            [
                'clients_list' => $clientList,
                'clients' => $clientList['items'],
                'pagination' => $pagination,
            ]
        );
    }
}
