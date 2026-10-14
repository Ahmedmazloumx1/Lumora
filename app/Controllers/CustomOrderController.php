<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\UploadService;
use App\Repositories\CustomOrderRepository;

class CustomOrderController extends Controller {
    private Request $request;
    private UploadService $uploadService;
    private CustomOrderRepository $customOrderRepo;

    public function __construct() {
        $this->request = new Request();
        $this->uploadService = new UploadService();
        $this->customOrderRepo = new CustomOrderRepository();
    }

    public function index(): void {
        if (!Session::get('user_id')) {
            Session::set('flash_error', 'Please log in to configure a custom design request.');
            header('Location: /login');
            exit();
        }

        $this->render('custom/index', [
            'error' => Session::get('flash_error'),
            'success' => Session::get('flash_success')
        ]);
        Session::remove('flash_error');
        Session::remove('flash_success');
    }

    public function submit(): void {
        $userId = Session::get('user_id') ? (int)Session::get('user_id') : null;

        if (!$userId) {
            header('Location: /login');
            exit();
        }

        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'Verification of secure security token failed.');
            header('Location: /custom-order');
            exit();
        }

        $input = $this->request->getBody();
        $imageName = null;

        if (isset($_FILES['reference_photo']) && $_FILES['reference_photo']['error'] === UPLOAD_ERR_OK) {
            try {
                $imageName = $this->uploadService->uploadImage($_FILES['reference_photo'], 'public/img');
            } catch (\Exception $e) {
                Session::set('flash_error', $e->getMessage());
                header('Location: /custom-order');
                exit();
            }
        }

        $orderData = [
            'user_id'     => $userId,
            'brand'       => $input['brand'] ?? '',
            'model'       => $input['model'] ?? '',
            'style'       => $input['art_style'] ?? 'Silhouette',
            'size'        => $input['art_size'] ?? 'Medium',
            'custom_text' => $input['custom_text'] ?? null,
            'image'       => $imageName
        ];

        $this->customOrderRepo->create($orderData);

        Session::set('flash_success', 'Bespoke specification request submitted successfully.');
        header('Location: /custom-order/status');
        exit();
    }

    public function status(): void {
        $userId = Session::get('user_id') ? (int)Session::get('user_id') : null;

        if (!$userId) {
            header('Location: /login');
            exit();
        }

        $orders = [];
        try {
            $orders = $this->customOrderRepo->findByUserId($userId);
        } catch (\Exception $e) {
            // Safe fallback if database table doesn't exist yet
            $orders = [];
        }

        $this->render('custom/status', [
            'orders' => $orders
        ]);
    }
}