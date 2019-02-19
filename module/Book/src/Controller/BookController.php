<?php


namespace Book\Controller;

use Book\Form\BookForm;
use Doctrine\ORM\EntityManager;
use Book\Entity\Book;
use Doctrine\ORM\OptimisticLockException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BookController extends AbstractActionController
{
    private $entitymanager;
    private $bookform;
    private $book;

    public function __construct(EntityManager $entitymanager, BookForm $bookForm, Book $book)
    {
        $this->entitymanager = $entitymanager;
        $this->bookform = $bookForm;
        $this->book = $book;
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
            $this->bookform->setData($this->getRequest()->getPost());
            if ($this->bookform->isValid()) {
                $data = $this->bookform->getData();
                $this->book->setNazov($data['nazov']);
                $this->book->setAutor($data['autor']);
                $this->book->setDatum($data['datum']);
                $this->book->setPopis($data['popis']);
                $this->book->setCena($data['cena']);
                $this->book->setKategoria($data['kategoria']);
                $this->entitymanager->persist($this->book);
                try {
                    $this->entitymanager->flush();
                    $this->redirect()->toUrl('index');
                } catch (OptimisticLockException $e) {
                    echo $e;
                };
            }

        }
        return new ViewModel(['form' => $this->bookform]);

    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id', -1);
        if($id!=-1){
            $book=$this->entitymanager->getRepository(Book::class)->find($id);
            return new ViewModel(['book'=>$book, 'form'=>$this->bookform->setAttribute('action','/book/delete')]);
        }
        $this->redirect()->toUrl('index');
    }
    public function deleteAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $kniha=$this->entitymanager->getRepository(Book::class)->find($this->getRequest()->getPost('id'));
            if($kniha){
                $this->entitymanager->remove($kniha);
                try {
                    $this->entitymanager->flush();
                } catch (OptimisticLockException $e) {
                }
                $this->redirect()->toUrl('index');
            }else
                echo 'nenasla';

        }

        /*$id = $this->params()->fromRoute('id', -1);
        if ($id == -1) {
            return $this->redirect()->toUrl('index');
        } else {
            $book = $this->entitymanager->getRepository(Book::class)->find($id);
            return new ViewModel(['book' => $book]);
        }*/
    }

}
