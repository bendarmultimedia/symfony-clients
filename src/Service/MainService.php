<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Nexo\NexoApiService;
use App\Service\UI\UIService;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Throwable;

class MainService
{
    protected $security;
    protected $entityManager;
    protected $mainRepository;
    protected $ui;
    protected ?User $user;
    protected array $messages = [];
    protected $session;

    protected $itemsPerPage = 10;

    public function __construct(
        $mainRepository,
        Security $security,
        EntityManagerInterface $entityManager,
        UIService $ui,
        SessionInterface $session
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->user = $this->security->getUser();
        $this->ui = $ui;

        // === fake user in services ===
        $userRepository = $this->entityManager->getRepository(User::class);
        // $this->user = $userRepository->find(72);
        // === end fake user ===

        $this->mainRepository = $mainRepository;
        $this->session = $session;
    }

    public function sendItemToDatabase($item = null, $flush = true, $return_response = true)
    {
        if ($item) {
            try {
                $this->entityManager->persist($item);
                if ($flush) {
                    $this->entityManager->flush();
                    return $item->getId();
                }
            } catch (\Throwable $th) {
                new Response(
                    'Error - błąd podczas dodawania do bazy danych - error try|catch',
                    Response::HTTP_NOT_ACCEPTABLE
                );
                return $th;
            }
        } else {
            new Response('Error - błąd podczas dodawania do bazy danych', Response::HTTP_NOT_ACCEPTABLE);
            return false;
        }
    }

    public function removeItem($item = null, $flush = true)
    {
        if ($item) {
            try {
                $this->entityManager->remove($item);
                if ($flush) {
                    $this->entityManager->flush();
                    return true;
                }
                return true;
            } catch (\Throwable $th) {
                return $th;
            }
        } else {
            new Response('Error - Niepoprawny element', Response::HTTP_NOT_ACCEPTABLE);
            return false;
        }
    }

    public function flushDB()
    {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function findAll()
    {
        return $this->mainRepository->findAll();
    }

    public function findAllWithPagination(?int $page = 1): array
    {
        $offset = $this->itemsPerPage * ($page - 1);
        $query = $this->mainRepository->createQueryBuilder('i')
            ->orderBy('i.id', 'DESC')
            ->getQuery();

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        $totalItemsCount = count($paginator);
        $pagesCount = ceil($totalItemsCount / $this->itemsPerPage);

        $paginator
            ->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($this->itemsPerPage);

        return [
            'items' => $paginator,
            'totalItemsCount' => $totalItemsCount,
            'pagesCount' => $pagesCount
        ];
    }

    public function getQueryPagination(Query $query, ?int $page = 1): array
    {
        $offset = $this->itemsPerPage * ($page - 1);

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $totalItemsCount = count($paginator);
        $pagesCount = ceil($totalItemsCount / $this->itemsPerPage);

        $paginator
            ->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($this->itemsPerPage);

        return [
            'items' => $paginator,
            'totalItemsCount' => $totalItemsCount,
            'pagesCount' => $pagesCount
        ];
    }

    public function find(int $id)
    {
        return $this->mainRepository->find($id);
    }

    public function findBy(array $findParams, array $resultOrder = null)
    {
        return $this->mainRepository->findBy($findParams, $resultOrder);
    }

    public function findOneBy(array $findParams)
    {
        return $this->mainRepository->findOneBy($findParams);
    }

    public function getMainRepository()
    {
        return $this->mainRepository;
    }

    public function setMessage(string $type, string $text, ?string $kind = null)
    {
        $this->messages[] = ['type' => $type, 'text' => $text, 'kind' => $kind];
        return $text;
    }

    public function getSpecificAttrubutes(object $item, array $attributes): array
    {
        $arr = [];
        foreach ($attributes as $attrName) {
            property_exists($item, $attrName);
            $methodName = 'get' . ucfirst($attrName);
            $arr[$attrName] = $item->$methodName();
        }
        return $arr;
    }

    public function getMessagesKinds()
    {
        array_column($this->messages, 'kind');
        return array_column($this->messages, 'kind');
    }

    public function getMessagesByKind(string $kind): array
    {
        $messages = [];
        foreach ($this->messages as $message) {
            if (isset($message['kind']) && $message['kind'] == $kind) {
                $messages[] = $message;
            }
        }
        return $messages;
    }

    public function setItemAttributes(array $itemattrs, &$item)
    {
        foreach ($itemattrs as $key => $value) {
            if (property_exists($item, $key)) {
                $setterName = 'set' . ucfirst($key);
                $item->$setterName($value);
            }
        }
        return $item;
    }

    public function getItemsAttributesArray(array $arrayOfItems, string $propName)
    {
        $props = [];
        $getterName = 'get' . ucfirst($propName);
        foreach ($arrayOfItems as $item) {
            $props[] = $item->$getterName();
        }
        return $props;
    }
}
