<?php

namespace App\Controller;

use App\Form\ScanEmailType;
use App\Service\EmailChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScanController extends AbstractController
{
    public function __construct(
        private readonly EmailChecker $checker
    )
    {
    }


    /**
     * @param Request $request
     * @param EmailChecker $checker
     * @return Response|array
     */
    #[Route('/scan/email', name: 'app_scan_email')]
    public function index(Request $request, EmailChecker $checker): array|Response
    {
        $response = [];

        $form = $this->createForm(ScanEmailType::class,[
            'action' => $this->generateUrl('app_scan_email'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $userEmail = $form->get('email')->getData();
            $isValidEmail = $checker->isValidEmail($userEmail);

            if (is_bool($isValidEmail)) {
                $apiKey = $this->getParameter("app.hibp_api");
                $response = $this->checker->isEmailPwned($userEmail,$apiKey);
            } else {
                $response = [
                    'statusCode' => 999,
                    'message' => $isValidEmail
                ];
            }
        }

        return $this->render('scan/index.html.twig',[
            'form' => $form,
            'response' => $response
        ]);
    }
}
