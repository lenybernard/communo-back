<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuBuilder
{
    public function __construct(private FactoryInterface $factory, private TranslatorInterface $translator)
    {
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'mainMenu navbar-nav mr-auto']);
        $menu->addChild($this->translator->trans('front'), ['uri' => 'http://127.0.0.1:3000']);
        $apiItem = $menu->addChild('API', ['route' => 'api_entrypoint'])->setAttributes(['dropdown' => true]);
        $apiItem->addChild('SWAGGER', ['route' => 'api_entrypoint']);
        $apiItem->addChild('GRAPHQL', ['route' => 'api_graphql_entrypoint']);

        return $menu;
    }
}