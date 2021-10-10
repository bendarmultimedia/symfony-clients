<?php

namespace App\Controller\Admin;

use App\Controller\MainController;
use App\Service\ClientService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends MainController
{
    protected $templateDir = 'admin';

    /**
     * @Route("/", name="admin_index")
     */
    public function index()
    {
        return $this->redirectToRoute('admin_clients');
    }

    /**
     * @Route("/klienci/{page}", name="admin_clients")
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
