<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SpamCheckerController extends AbstractController
{
    private const SPAM_DOMAINS = ['test.com', 'gmail.net', 'outlo.ok'];

    #[Route('/api/check', name: 'api_spam_checker', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = $request->toArray();

        // 1 - Vérifier le bon format du corps de la requête
        if (!isset($data['email'])) {
            return $this->json([
                'message' => "L'email est obligatoire"
            ], Response::HTTP_BAD_REQUEST);
        }

        $email = $data['email'];

        // 2 - Une fois l'email extrait du corps de la requête,
        // Validation de son format
        // Note : on pourrait utiliser le composant egulias/email-validator
        // Note bis : on pourrait utiliser le Validator de Symfony
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return $this->json([
                'message' => "Email invalide"
            ], Response::HTTP_BAD_REQUEST);
        }

        $domain = ltrim(strstr($email, '@'), '@');

        $spam = in_array($domain, self::SPAM_DOMAINS);

        return $this->json(['result' => $spam ? 'spam' : 'ok']);
    }
}
