<?php

namespace App\Controller\Admin;

use App\Controller\MainController;
use App\Entity\Client;
use App\Form\ClientType;
use App\Service\ClientService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/klienci")
 */
class AdminClientsController extends AdminController
{

    /**
     * @Route("/edycja/{client}", name="admin_client_edit")
     */
    public function clientEdit(ClientService $clientService, Client $client)
    {

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $clientService->sendItemToDatabase($client);

                $this->addFlash('success', 'Data saved');
            } catch (\Throwable $th) {
                $this->addFlash('error', $th->getMessage());
            }
        }

        $this->page->setAttributes([
            'title' => $client->getFullName() . ' - Edycja klienta',
            'templateDir' => $this->templateDir,
            'returnPath' => ['admin_clients'],
        ]);

        return $this->render($this->page->getTemplate(), [
            'page' => $this->page,
            'form' => $form->createView(),
            'client' => $client
        ]);

        return $this->render(
            $this->page->getTemplate(),
            []
        );
    }
}
