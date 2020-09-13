<?php


namespace App\Controller;


use App\Entity\TargetList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChecklistController extends AbstractController
{
//    /**
//     * @Route("/{day_of_week<monday|tuesday|wednesday|thursday|friday|saturday|sunday>}",name="day",methods="GET")
//     */
    /**
     * @Route("/list/",name="list", methods="GET")
     */
    public function list()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $allTargets = $this->getDoctrine()
            ->getRepository(TargetList::class)
            ->findBy(['user' => $user]);
        $params = ['allTargets' => $allTargets];
        return $this->render('targets.html.twig', $params);
    }

    /**
     * @Route ("/addTarget/", name="add")
     */
    public function addTarget(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $newTarget = $request->request->get('new-target');

        if (strlen($newTarget) > 0 && strlen($newTarget) < 50) {
            $entityManager = $this->getDoctrine()->getManager();
            $targetList = new TargetList();
            $targetList->setTarget($newTarget);
            $targetList->setStatus(false);
            $targetList->setUser($user);
            $entityManager->persist($targetList);
            $entityManager->flush();
        }
        return $this->redirect("/list/");
    }

    /**
     * @Route ("/removeTarget/{id}", name="remove")
     */
    public function removeTarget($id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $entityManager = $this->getDoctrine()->getManager();

        $target = $entityManager->getRepository(TargetList::class)->find($id);
        if (is_null($target)) {
            throw $this->createNotFoundException('No target found for id ' . $id);
        }

        $entityManager->remove($target);
        $entityManager->flush();

        return $this->json(['id' => $id]);
    }

    /**
     * @Route ("/changeTarget/", name="change")
     */
    public function changeTarget(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $id = $request->request->get('id');
        $newName = $request->request->get('newName');
        $data = ['id' => $id, 'newName' => $newName];
        $entityManager = $this->getDoctrine()->getManager();
        $target = $this->getDoctrine()->getRepository(TargetList::class)->find($id);
        if (!$target)
            throw $this->createNotFoundException('No target found for id' . $id);
        $target->setTarget($newName);
        $entityManager->flush();
        return $this->json($data);
    }

    /**
     * @Route ("/changeStatus/", name="status")
     */
    public function changeStatus(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $id = $request->request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $target = $this->getDoctrine()->getRepository(TargetList::class)->find($id);
        if (!$target)
            throw $this->createNotFoundException('No target found for id' . $id);

        $target->setStatus(!$target->getStatus());

        $entityManager->flush();
        return $this->json([]);
    }

}