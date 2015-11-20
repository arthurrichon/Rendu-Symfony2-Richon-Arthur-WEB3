<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Form\LessonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LessonController extends Controller
{
    /**
     * @Route("/lesson", name="lesson")
     */
    public function indexAction()
    {
        // 1. Doctrine
        $em   = $this->getDoctrine()->getManager();
        // 2. Repository (LessonRepository)
        $repo = $em->getRepository('AppBundle:Lesson');
        // 3. findAll()
        $lessons = $repo->findAll();

        return $this->render('lesson/lessons.html.twig', [
            'lessons' => $lessons,
        ]);
    }

    /**
     * @Route("/lesson/create", name="lesson_create")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $lesson = new Lesson();
        $form   = $this->createForm(new LessonType(), $lesson);
        $form->add('submit', 'submit', [
            'label' => 'Create',
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lesson);
            $em->flush();

            return $this->redirectToRoute('lesson');
        }

        return $this->render('lesson/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/lesson/{id}/edit", name="lesson_edit")
     */
    public function updateAction(Request $request, $id)
    {
        $grade = $this->getDoctrine()->getManager()->getRepository('AppBundle:Lesson')->find($id);

        if (null === $lesson)
            throw $this->createNotFoundException(sprintf(
                'Lesson n°%d not found.',
                $id
            ));

        $form  = $this->createForm(new LessonType(), $lesson);
        $form->add('submit', 'submit', [
            'label' => 'Update',
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('lesson');
        }

        return $this->render('lesson/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/api/lesson", name="api_lesson")
     */
    public function apiIndexAction()
    {
        $repo   = $this->getDoctrine()->getManager()->getRepository('AppBundle:Lesson');
        $lessons = $repo->findAllArray();

        return new JsonResponse($lessons);
    }
}
