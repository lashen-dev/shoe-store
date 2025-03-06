<?php
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/OrderController.php';
require_once 'app/controllers/PurchaseController.php';
require_once 'app/controllers/AdminController.php';

global $pdo;
$homeController = new HomeController();
$authController = new AuthController();
$orderController = new OrderController();
$purchaseController = new PurchaseController($pdo);
$adminController = new AdminController($pdo);

function getRequestedPath() {
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return rtrim($urlPath, '/');
}

$validPaths = [
    '/project/login' => 'login',
    '/project/register' => 'register',
    '/project/logout' => 'logout',
    '/project/my-orders' => 'myOrders',
    '/project/delete_order' => 'deleteOrder',
    '/project/checkout' => 'checkout',
    '/project/admin/dashboard' => 'dashboard',
    '/project/admin/users' => 'manageUsers',
    '/project/admin/delete-user' => 'deleteUser',
    '/project/admin/delete-order' => 'deleteOrder',
    '/project/admin/orders' => 'manageOrders',
    '/project/admin/products' => 'manageProducts',
    '/project/admin/delete-product' => 'deleteProduct',
    '/project/admin/addProduct' => 'showAddProductForm', // ✅ أضفنا هذا المسار
    '/project/confirm-purchase' => 'showConfirmPage',
    '/project/' => 'index'
];

$urlPath = getRequestedPath();

if (array_key_exists($urlPath, $validPaths)) {
    switch ($urlPath) {
        case '/project/login':
            $authController->login();
            break;
        case '/project/register':
            $authController->register();
            break;
        case '/project/logout':
            $authController->logout();
            break;
        case '/project/confirm-purchase':
            $purchaseController->showConfirmPage();
            break;
        case '/project/my-orders':
            $orderController->myOrders();
            break;
        case '/project/delete_order':
            $orderController->deleteOrder();
            break;
        case '/project/checkout':
            $orderController->checkout();
            break;
        case '/project/admin/dashboard':
            $adminController->dashboard();
            break;
        case '/project/admin/users':
            $adminController->manageUsers();
            break;
        case '/project/admin/delete-user':
            $adminController->deleteUser();
            break;
        case '/project/admin/orders':
            $adminController->manageOrders();
            break; 
        case '/project/admin/delete-order':
            $adminController->deleteOrder();
            break; 
        case '/project/admin/products':
            $adminController->manageProducts();
            break;
        case '/project/admin/delete-product':
            $adminController->deleteProduct();
            break;
        case '/project/admin/addProduct':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $adminController->addProduct();
            } else {
                $adminController->showAddProductForm();
            }
            break;     
        case '/project/':
            $homeController->index();
            break;
    }
} else {
    $homeController->index();
}
?>
