<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\SettingsHelper;

class PageController extends Controller {
    public function about(): void {
        $this->render('pages/about', [
            'content' => SettingsHelper::get('about_us_content', 'Welcome to Lumora. We craft high-gloss optical acrylic wall art and personalized decor prints engineered specifically for true design enthusiasts.')
        ]);
    }

    public function faq(): void {
        $faqJson = SettingsHelper::get('faq_items', '[]');
        $faqItems = json_decode($faqJson, true) ?: [
            ['question' => 'How thick is the acrylic panel?', 'answer' => 'All our custom wall art panels are constructed using premium 3mm exhibition-grade optical cast acrylic.'],
            ['question' => 'Is Cash on Delivery supported?', 'answer' => 'Yes, Cash on Delivery is supported across all Egyptian governorates. Delivery takes 3-5 working days.']
        ];

        $this->render('pages/faq', [
            'faq_items' => $faqItems
        ]);
    }

    public function policies(): void {
        $this->render('pages/policies', [
            'terms' => SettingsHelper::get('policy_terms_content', 'Standard terms of service and transactional purchase conditions apply globally.'),
            'privacy' => SettingsHelper::get('policy_privacy_content', 'Your personal identity and delivery details are safely processed inside encrypted channels.'),
            'refund' => SettingsHelper::get('policy_refund_content', 'Bespoke custom order silhouettes are backed by our secure transport coverage guarantee.')
        ]);
    }
}