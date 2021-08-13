<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Images;
use App\Entity\Files;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/crud")
 */
class CrudController extends AbstractController
{
    /**
     * @Route("/", name="crud_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, Request $request ): Response
    {
    // Définition du nombre par page
    $limit = 4;

    // Récuperation numéro de page
    $page = (int)$request->query->get("page", 1);

    // Récuperation des filtres
    $filters = $request->get("categories");
        
    //Récuperation des produits de la page
    $products = $productRepository->getPaginatedAnnonces($page, $limit, $filters);
     
    // Récuperation du nombre total de produits
    $total = $productRepository->getTotalAnnonces($filters);

    // Récuperation des catégories
    $categories = $categoryRepository->findAll();

    // Verification de la présence d'une requête Ajax 
        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->render('crud/_content.html.twig', compact('products', 'total', 'limit','page'))
            ]);
        }

        return $this->render('crud/index.html.twig', compact('products', 'total', 'limit','page', 'categories'));
    }

    /**
     * @Route("/new", name="crud_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // récupération des images et des manuels
            $images = $form->get('images')->getData();
            $files = $form->get('files')->getData();
            // On boucle au cas où il y ait plusieurs images ou manuels
            foreach($images as $image){
                //nouveau nom de fichier pour éviter les doublons
                $fichier = md5(uniqid()). '.' . $image->guessExtension();

                // copie du fichier vers uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // stocke en bdd (nom)
                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);
            }
            foreach($files as $file){
                //nouveau nom de fichier pour éviter les doublons
                $manual = md5(uniqid()). '.' . $file->guessExtension();

                // copie du fichier vers uploads
                $file->move(
                    $this->getParameter('files_directory'),
                    $manual
                );

                // stocke en bdd (nom)
                $doc = new Files();
                $doc->setName($manual);
                $product->addFile($doc);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('crud/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        // récupération des images
        $images = $form->get('images')->getData();
        $files = $form->get('files')->getData();
        // On boucle au cas où il y ait plusieurs images
        foreach($images as $image){
            //nouveau nom de fichier pour éviter les doublons
            $fichier = md5(uniqid()). '.' . $image->guessExtension();
        
            // copie du fichier vers uploads
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
        
            // stocke en bdd (nom)
            $img = new Images();
            $img->setName($fichier);
            $product->addImage($img);
        }

        foreach($files as $file){
            //nouveau nom de fichier pour éviter les doublons
            $manual = md5(uniqid()). '.' . $file->guessExtension();

            // copie du fichier vers uploads
            $file->move(
                $this->getParameter('files_directory'),
                $manual
            );

            // stocke en bdd (nom)
            $doc = new Files();
            $doc->setName($manual);
            $product->addFile($doc);
        }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/supprime/image/{id}", name="product_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Images $image, Request $request){
        $data = json_decode($request->getContent(), true);
        
        // Validité du token
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            //recupération nom
            $name = $image->getName();
            //suppression
            unlink($this->getParameter('images_directory').'/'.$name);

            // suppression de la bdd
            $em =$this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            //reponse en json_decode
            return new JsonResponse(['Suppression effectuée' => 1]);
        }else{
            return new JsonResponse(['Erreur' => 'Token Invalide'], 400);
        }
    }

    /**
     * @Route("/supprime/file/{id}", name="product_delete_file", methods={"DELETE"})
     */

    public function deleteFile(Files $file, Request $request){
        $data = json_decode($request->getContent(), true);
        
        // Validité du token
        if($this->isCsrfTokenValid('delete'.$file->getId(), $data['_token'])){
            //recupération nom
            $nom = $file->getName();
            //suppression
            unlink($this->getParameter('files_directory').'/'.$nom);

            // suppression de la bdd
            $em =$this->getDoctrine()->getManager();
            $em->remove($file);
            $em->flush();

            //reponse en json_decode
            return new JsonResponse(['Suppression effectuée' => 1]);
        }else{
            return new JsonResponse(['Erreur' => 'Token Invalide'], 400);
        }
    }
}


