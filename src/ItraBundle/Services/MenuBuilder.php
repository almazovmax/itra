<?php
namespace ItraBundle\Services;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, \Doctrine\ORM\EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public function createAdminMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'memenu skyblue');

        $menu->addChild('Home', array('route' => 'homepage', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Catalog', array('route' => 'catalog', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Product', array('route' => 'product_index', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Category', array('route' => 'category_index', 'attributes' => array('class' => 'grid')));
        $menu->addChild('User', array('route' => 'user_index', 'attributes' => array('class' => 'grid')));

        return $menu;
    }

    public function createModeratorMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'memenu skyblue');

        $menu->addChild('Home', array('route' => 'homepage', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Catalog', array('route' => 'catalog', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Product', array('route' => 'product_index', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Category', array('route' => 'category_index', 'attributes' => array('class' => 'grid')));

        return $menu;
    }

    public function createUserMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'memenu skyblue');

        $menu->addChild('Home', array('route' => 'homepage', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Catalog', array('route' => 'catalog', 'attributes' => array('class' => 'grid')));

        return $menu;
    }

    public function createGuestMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'memenu skyblue');

        $menu->addChild('Home', array('route' => 'homepage', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Login', array('route' => 'login', 'attributes' => array('class' => 'grid')));
        $menu->addChild('Register', array('route' => 'register', 'attributes' => array('class' => 'grid')));

        return $menu;
    }
}