<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Repositories\CustomOrderRepository;

class CustomOrderController extends Controller {
    private Request $request;
    private CustomOrderRepository $customOrderRepo;

    public function __construct() {
        $this->request = new Request();
        $this->customOrderRepo = new CustomOrderRepository();
        
        // Block non-admin attempts
        $roleId = Session::get('user_role') ? (int)Session::get('user_role') : null;
        if ($roleId !== 1) {
            header('Location: /login');
            exit();
        }
    }

    public function index(): void {
        $orders = $this->customOrderRepo->all();
        $this->render('admin/custom_orders', [
            'orders' => $orders,
            'success' => Session::get('flash_success')
        ]);
        Session::remove('flash_success');
    }

    public function updateStatus(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'Invalid token validation.');
            header('Location: /admin/custom-orders');
            exit();
        }

        $input = $this->request->getBody();
        $orderId = isset($input['order_id']) ? (int)$input['order_id'] : null;
        $status = $input['status'] ?? null;

        if ($orderId !== null && in_array($status, ['Accepted', 'Rejected'], true)) {
            $this->customOrderRepo->updateStatus($orderId, $status);
            Session::set('flash_success', 'Custom spec status updated successfully.');
        }

        header('Location: /admin/custom-orders');
        exit();
    }
}