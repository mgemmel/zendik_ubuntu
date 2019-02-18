<?php


namespace Book\Controller;

use Book\Form\BookForm;
use Doctrine\ORM\EntityManager;
use Book\Entity\Book;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BookController extends AbstractActionController
{
    private $entitymanager;
    private $bookform;

    public function __construct(EntityManager $entitymanager, BookForm $bookForm)
    {
        $this->entitymanager = $entitymanager;
        $this->bookform = $bookForm;
    }

    /**
     * @return array|ViewModel
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function indexAction()
    {
        $books = $this->entitymanager->getRepository(Book::class)->findAll();
        return new ViewModel(['books' => $books]);
    }

    public function addAction()
    {
        if ($this->getRequest()->isPost()) {

        } else {
            return new ViewModel(['form'=>$this->bookform]);
        }

    }

}
