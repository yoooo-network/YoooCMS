<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PageController extends Controller
{
    /**
     * Static views that may be served by this controller.
     *
     * Keeping this list here prevents a route parameter from selecting an
     * arbitrary file under app/Views.
     */
    private const PAGES = [
        'about' => ['metaTitle' => 'About Yooo.App', 'metaDescription' => 'Learn about Yooo.App and the people and technology behind the platform.'],
        'advertising' => ['metaTitle' => 'Advertise with Yooo.App', 'metaDescription' => 'Explore advertising opportunities with Yooo.App.'],
        'contact' => ['metaTitle' => 'Contact Yooo.App', 'metaDescription' => 'Get in touch with the Yooo.App team.'],
        'faq' => ['metaTitle' => 'Frequently Asked Questions | Yooo.App', 'metaDescription' => 'Find answers about Yooo.App, memberships, verification, payments, and safety.'],
        'packages' => ['metaTitle' => 'Membership Packages | Yooo.App', 'metaDescription' => 'Explore Yooo.App membership plans and pricing.'],
        'privacy-policy' => ['metaTitle' => 'Privacy Policy | Yooo.App', 'metaDescription' => 'Read the Yooo.App privacy policy.'],
        'refund-policy' => ['metaTitle' => 'Refund Policy | Yooo.App', 'metaDescription' => 'Read the Yooo.App refund policy.'],
        'report-misuse' => ['metaTitle' => 'Report Misuse | Yooo.App', 'metaDescription' => 'Report misuse or unsafe activity on Yooo.App.'],
        'safety-guidelines' => ['metaTitle' => 'Safety Guidelines | Yooo.App', 'metaDescription' => 'Read Yooo.App safety guidance and reporting information.'],
        'terms-and-conditions' => ['metaTitle' => 'Terms and Conditions | Yooo.App', 'metaDescription' => 'Read the Yooo.App terms and conditions.'],
    ];

    /**
     * Help is the entry point for pages/index.php.
     */
    public function index(): string
    {
        return view('pages/index', [
            'language' => $this->currentLanguage(),
            'metaTitle' => 'Help Centre | Yooo.App',
            'metaDescription' => 'Find Yooo.App help, policies, safety guidance, membership information, and contact options.',
            'metaKeywords' => 'Yooo.App help, FAQ, safety, policies, contact',
        ]);
    }

    public function view(string $slug): string
    {
        if (!isset(self::PAGES[$slug])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($slug);
        }

        return view('pages/' . $slug, array_merge([
            'language' => $this->currentLanguage(),
        ], self::PAGES[$slug]));
    }

    private function currentLanguage(): string
    {
        $language = $this->request->getUri()->getSegment(1);

        return in_array($language, supported_languages(), true) ? $language : 'en';
    }
}
