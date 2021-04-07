<?php

namespace App\Controller;
use App\Service\Common;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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

     /**
     * @Route("/blogs", name="blogs")
     */
     public function BlogsPage(Request $request,Common $common,PaginatorInterface $paginator): Response
     {
         $page = $request->query->getInt('page', 1);
          $blogs = $common->fetchData('https://blogs.spacium.co/wp-json/wp/v2/posts',[
              'per_page' => 6,
              'page' => $page,
              'orderby' => 'date',
              'order' => 'desc',
              '_embed' => true,
          ],'GET');
          $total_items = array_fill(0, (int) $blogs['total'][0], 'mycontent');
         $pagination = $paginator->paginate(
             $total_items, /* query NOT result */
             $page, /*page number*/
             6 /*limit per page*/
         );

          return $this->render('main/blogs.html.twig', [
              'title' => 'Blogs & News - Spacium Technologies',
              'blogs' => $blogs['content'],
              'total' => $blogs['total'],
              'current_page' => $page,
              'pagination' => $pagination
          ]);
     }

     /**
     * @Route("/blog/{id}/{slug}", name="single-blog")
     */
     public function SingleBlogPage($id,$slug,Request $request,Common $common,PaginatorInterface $paginator): Response
     {
          $blogs = $common->fetchData('https://blogs.spacium.co/wp-json/wp/v2/posts/'.$id,[
              '_embed' => true,
          ],'GET');
         $blog_data = $blogs['content'];
         //echo '<pre/>';print_r($blog_data);die();
          return $this->render('main/single-blog.html.twig', [
              'title' => $blog_data['title']['rendered'].' - Spacium Technologies',
              'blog' => $blog_data,
          ]);
     }

     /**
     * @Route("/api/send", name="contact-send")
     */
     public function SendEmailApi(Request $request,Common $common,PaginatorInterface $paginator): Response
     {
         $name = $request->get('name');
         $email = $request->get('email');
         $phone = $request->get('phone');
         $message = $request->get('message');
         $token = $request->get('token');
       //  print_r($name);die();
         $response = $common->sendEmail($name,$phone,$email,$message,$token);
         return new JsonResponse($response);
     }



}
