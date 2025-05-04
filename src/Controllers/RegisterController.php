<?php

namespace LouisStanley\Ci4ShieldUsernameSuggest\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegister;

class RegisterController extends ShieldRegister
{
    protected $config;

    public function __construct()
    {
        $this->config = config('UserGeneratorConfig');
        helper('username');
    }

    /**
     * Handle AJAX requests for username suggestions
     */
    public function suggestUsernames(): ResponseInterface
    {
        $email = $this->request->getPost('email');

        if (! empty($email)) {
            $suggestions = generate_username_suggestions($email, $this->config->suggestionCount ?? 5);

            return $this->response->setJSON([
                'success'     => true,
                'suggestions' => $suggestions,
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No email provided',
        ]);
    }
}
