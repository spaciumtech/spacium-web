<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'title' => 'Spacium Technologies',
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function AboutPage(): Response
    {
        return $this->render('main/about.html.twig', [
            'title' => 'About us - Spacium Technologies',
        ]);
    }
     /**
     * @Route("/services", name="services")
     */
     public function ServicesPage(): Response
     {
         return $this->render('main/services.html.twig', [
             'title' => 'Services - Spacium Technologies',
         ]);
     }

     /**
     * @Route("/contact", name="contact")
     */
     public function ContactPage(): Response
     {
          return $this->render('main/contact.html.twig', [
              'title' => 'Contact Us - Spacium Technologies',
          ]);
     }

     /**
     * @Route("/faq", name="faq")
     */
     public function FaqPage(): Response
     {
          return $this->render('main/faq.html.twig', [
              'title' => 'Faq - Spacium Technologies',
          ]);
     }

     /**
     * @Route("/privacy-policy", name="privacy-policy")
     */
     public function PrivacyPolicyPage(): Response
     {
          return $this->render('main/privacy-policy.html.twig', [
              'title' => 'privacy-policy - Spacium Technologies',
          ]);
     }

     /**
     * @Route("/terms-of-use", name="terms-of-use")
     */
     public function TermsOfUsePage(): Response
     {
          return $this->render('main/terms-of-use.html.twig', [
              'title' => 'terms-of-use - Spacium Technologies',
          ]);
     }

     /**
     * @Route("/terms-of-service", name="terms-of-service")
     */
     public function TermsOfServicePage(): Response
     {
          return $this->render('main/terms-of-service.html.twig', [
              'title' => 'terms-of-service - Spacium Technologies',
          ]);
     }


}
