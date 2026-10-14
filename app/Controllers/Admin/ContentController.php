<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use App\Services\UploadService;
use PDO;

class ContentController extends Controller {
    private Request $request;
    private PDO $db;
    private UploadService $uploadService;

    public function __construct() {
        $this->request = new Request();
        $this->db = Database::getConnection();
        $this->uploadService = new UploadService();

        // Enforce RBAC [1.1.1]
        $roleId = Session::get('user_role') ? (int)Session::get('user_role') : null;
        if ($roleId !== 1) {
            header('Location: /login');
            exit();
        }
    }

    public function index(): void {
        $this->render('admin/content', [
            'success' => Session::get('flash_success'),
            'error'   => Session::get('flash_error')
        ]);
        Session::remove('flash_success');
        Session::remove('flash_error');
    }

    /**
     * Process secure CMS updates
     */
    public function update(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /admin/content');
            exit();
        }

        $input = $this->request->getBody();
        $stmt = $this->db->prepare("INSERT INTO settings (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = :value_update");

        // Process standard text inputs
        $allowedKeys = ['about_us_content', 'policy_terms_content', 'policy_privacy_content', 'policy_refund_content', 'contact_phone', 'contact_email', 'social_instagram'];
        foreach ($allowedKeys as $key) {
            if (isset($input[$key])) {
                $stmt->execute([
                    'key' => $key,
                    'value' => $input[$key],
                    'value_update' => $input[$key]
                ]);
            }
        }

        // Process FAQ accordion elements
        if (isset($input['faq_questions']) && is_array($input['faq_questions'])) {
            $faqArray = [];
            foreach ($input['faq_questions'] as $index => $q) {
                $a = $input['faq_answers'][$index] ?? '';
                if (!empty($q) && !empty($a)) {
                    $faqArray[] = ['question' => $q, 'answer' => $a];
                }
            }
            $faqJson = json_encode($faqArray);
            $stmt->execute([
                'key' => 'faq_items',
                'value' => $faqJson,
                'value_update' => $faqJson
            ]);
        }

        // Process brand logo file upload securely [1.1.1]
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            try {
                $logoName = $this->uploadService->uploadImage($_FILES['site_logo'], 'public/img');
                if ($logoName) {
                    $stmt->execute([
                        'key' => 'site_logo',
                        'value' => $logoName,
                        'value_update' => $logoName
                    ]);
                }
            } catch (\Exception $e) {
                Session::set('flash_error', "Logo Upload Failed: " . $e->getMessage());
                header('Location: /admin/content');
                exit();
            }
        }

        Session::set('flash_success', 'Web content and policies updated successfully.');
        header('Location: /admin/content');
        exit();
    }
}
