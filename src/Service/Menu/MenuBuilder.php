<?php

declare(strict_types=1);

namespace App\Service\Menu;

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
        $menu->addChild($this->translator->trans('home'), ['route' => 'homepage']);
        $find = $menu->addChild('Catégorie', ['route' => 'homepage'])->setAttributes(['dropdown' => true]);
        $find->addChild('Équipement', ['route' => 'homepage']);
        $find->addChild('Services', ['route' => 'homepage']);

        return $menu;
    }
}