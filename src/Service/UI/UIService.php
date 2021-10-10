<?php

namespace App\Service\UI;

use Twig\Environment;

class UIService
{
    private PageService $page;
    private FormElementsService $forms;
    private Environment $twig;

    private $templates;

    public function __construct(
        PageService $page,
        FormElementsService $forms,
        Environment $twig
    ) {
        $this->page = $page;
        $this->forms = $forms;
        $this->twig = $twig;

        $this->templates = [
            'pagination'      =>  'UI/pagination.html.twig',
        ];
    }

    /**
     * Get the value of page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get the value of forms
     */
    public function getForms()
    {
        return $this->forms;
    }

    public function getPagination(int $page, int $pagesCount)
    {
        $currentRoute = $this->page->getcurrentRoute();

        return $this->twig->render(
            $this->templates['pagination'],
            [
                'page' => $page,
                'pagesCount' => $pagesCount,
                'pathName' => $currentRoute,
            ]
        );

        return $this->templateDir;
    }
}
