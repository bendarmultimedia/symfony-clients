<?php

namespace App\Service;

use App\Entity\Departament;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\HotelRepository;
use App\Service\UI\UIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ClientService extends MainService
{


    public function __construct(
        ClientRepository $mainRepository,
        Security $security,
        EntityManagerInterface $entityManager,
        UIService $ui,
        SessionInterface $session
    ) {
        parent::__construct($mainRepository, $security, $entityManager, $ui, $session);
    }
}
