<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\RegistrationType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class IndexController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {

            if (!$form->isValid()) {
                $this->addFlash('error', 'It seems that your email address is invalid. Please try again.');
                return $this->redirectToRoute('app');
            }

            $submittedToken = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('registration', $submittedToken)) {
                throw $this->createAccessDeniedException('Invalid CSRF token');
            }

            $registration = $form->getData();
            $registration->setCreatedAt(new DateTimeImmutable());
            $em->persist($registration);
            $em->flush();

            $this->addFlash('success', 'Thank you for registering! We will send you an email when the Kickstarter is live!');
            return $this->redirectToRoute('app');
        }

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
