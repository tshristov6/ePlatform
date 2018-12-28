<?php
/**
 * Created by PhpStorm.
 * User: Tsvetelin
 * Date: 23/12/2018
 * Time: 14:03
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Test;
use AppBundle\Entity\User;
use AppBundle\Form\TestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends Controller
{
    /**
     * @Route("/tests", name="tests")
     */
    public function testsAction(){
        $tests = $this
            ->getDoctrine()
            ->getRepository(Test::class)
            ->findBy([], ['viewCount' => 'desc', 'dateAdded'=> 'desc']);

        return $this->render("tests/tests.html.twig",
            ['tests' => $tests]);
    }


    /**
     * @Route("/tests/create", name="test_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {

        $test = new Test();
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->getData()->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('test_directory'),
                    $fileName);
            } catch (FileException $ex) {

            }

            $test->setImage($fileName);
            $currentUser = $this->getUser();
            $test->setAuthor($currentUser);
            $test->setViewCount(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($test);
            $em->flush();

            return $this->redirectToRoute("tests");
        }

        return $this->render('tests/create.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/tests/{id}", name="tests_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewArticle($id)
    {

        $test = $this
            ->getDoctrine()
            ->getRepository(Test::class)
            ->find($id);

        $test->setViewCount($test->getViewCount() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($test);
        $em->flush();

        return $this->render("tests/view.html.twig",
            ['tests' => $test]);
    }

    /**
     * @Route("/tests/edit/{id}", name="tests_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $test = $this
            ->getDoctrine()
            ->getRepository(Test::class)
            ->find($id);

        if ($test === null) {
            return $this->redirectToRoute("tests");
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser->isAuthor($test) && !$currentUser->isAdmin()) {
            return $this->redirectToRoute("tests");
        }

        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var UploadedFile $file */
            $file = $form->getData()->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('test_directory'),
                    $fileName);
            } catch (FileException $ex) {

            }

            $test->setImage($fileName);

            $currentUser = $this->getUser();
            $test->setAuthor($currentUser);
            $em = $this->getDoctrine()->getManager();
            $em->merge($test);
            $em->flush();

            return $this->redirectToRoute("tests");
        }

        return $this->render('tests/edit.html.twig',
            ['form' => $form->createView(),
                'test' => $test]);
    }

    /**
     * @Route("/tests/delete/{id}", name="tests_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $id)
    {
        $test = $this
            ->getDoctrine()
            ->getRepository(Test::class)
            ->find($id);

        if ($test === null) {
            return $this->redirectToRoute("tests");
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser->isAuthor($test) && !$currentUser->isAdmin()) {
            return $this->redirectToRoute("tests");
        }

        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->getUser();
            $test->setAuthor($currentUser);
            $em = $this->getDoctrine()->getManager();
            $em->remove($test);
            $em->flush();

            return $this->redirectToRoute("tests");
        }

        return $this->render('tests/delete.html.twig',
            ['form' => $form->createView(),
                'test' => $test]);
    }

    /**
     * @Route("/myTests", name="myTests")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function myArticles()
    {
        $tests = $this->getDoctrine()
            ->getRepository(Test::class)
            ->findBy(['author' => $this->getUser()]);

        return $this->render("tests/myTests.html.twig",
            ['tests' => $tests]);
    }

}