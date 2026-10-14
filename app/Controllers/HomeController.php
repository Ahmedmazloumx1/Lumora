<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    /**
     * Dispatch core homepage template payload
     */
    public function index(): void {
        $this->render('home', [
            'page_title' => 'Premium Acrylic Wall Art & Personalized Décor'
        ]);
    }
}