<?php

namespace App\Controller;

use App\Form\ScanEmailType;
use App\Form\ScanPasswordType;
use App\Service\EmailChecker;
use App\Service\PasswordChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScanController extends AbstractController
{
    public function __construct(
        private readonly EmailChecker $emailChecker,
        private readonly PasswordChecker $passwordChecker
    )
    {
    }


    /**
     * @param Request $request
     * @return Response|array
     */
    #[Route('/scan/email', name: 'app_scan_email')]
    public function scanEmail(Request $request): array|Response
    {
        $response = [];

        $form = $this->createForm(ScanEmailType::class,[
            'action' => $this->generateUrl('app_scan_email'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $userEmail = $form->get('email')->getData();
            $isValidEmail = $this->emailChecker->isValidEmail($userEmail);

            if ($isValidEmail === true) {
                $apiKey = $this->getParameter("app.hibp_api");
                $response = $this->emailChecker->isEmailPwned($userEmail,$apiKey);
            } else {
                $response = [
                    'statusCode' => 999,
                    'message' => $isValidEmail
                ];
            }
        }

        //dd($response);
        return $this->render('scan/email.html.twig',[
            'form' => $form,
            'response' => $response
        ]);
    }

    /**
     * @param Request $request
     * @return Response|array
     */
    #[Route('/scan/password', name: 'app_scan_password')]
    public function scanPassword(Request $request): array|Response
    {
        $response = [];

        $form = $this->createForm(ScanPasswordType::class,[
            'action' => $this->generateUrl('app_scan_password'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $userPassword = $form->get('password')->getData();
            $isValidPassword = $this->passwordChecker->isValidPassword($userPassword);

            if ($isValidPassword === true) {
                $isPasswordSafe = $this->passwordChecker->isPasswordSafe($userPassword);

                if($isPasswordSafe['statusCode'] === 200 || $isPasswordSafe['statusCode'] === 404){
                    $response = $isPasswordSafe;
                } else {
                    $response = [
                        "statusCode" => 0
                    ];
                }
            } else {
                $response = [
                    'statusCode' => 999,
                    'message' => $isValidPassword
                ];
            }
        }

        return $this->render('scan/password.html.twig',[
            'form' => $form,
            'response' => $response
        ]);
    }
}
