<?php
require_once BASE_PATH . '/app/controllers/BaseAdminController.php';

class AdminAuthController extends BaseAdminController
{
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function loginPage()
    {
        if (isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        $this->view('admin/login', ['pageTitle' => 'Admin Login']);
    }

    public function login()
    {
        $username = htmlspecialchars(strip_tags(trim($_POST['username'] ?? '')));
        $password = $_POST['password'] ?? '';
        $adminModel = new AdminModel();
        $admin = $adminModel->findByUsername($username);
        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin'] = ['id' => $admin['id'], 'username' => $admin['username'], 'full_name' => $admin['full_name']];
            // $adminModel->db ?? null;
            Database::getInstance()->execute("UPDATE admins SET last_login=NOW() WHERE id=?", [$admin['id']], 'i');
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        $this->view('admin/login', ['error' => 'Invalid credentials.', 'pageTitle' => 'Admin Login']);
    }


    public function logout()
    {
        unset($_SESSION['admin_id'], $_SESSION['admin']);
        header('Location: ' . BASE_URL . '/admin/login');
        exit;
    }
}

class AdminDashboardController extends BaseAdminController
{
    public function index()
    {
        $this->requireAdmin();
        $orderModel = new OrderModel();
        $userModel = new UserModel();
        $productModel = new ProductModel();
        $this->adminView('dashboard', [
            'pageTitle' => 'Dashboard',
            'totalOrders' => $orderModel->countOrders(),
            'pendingOrders' => $orderModel->countOrders('pending'),
            'totalRevenue' => $orderModel->getTotalRevenue(),
            'totalCustomers' => $userModel->countUsers(),
            'totalProducts' => $productModel->countAll(),
            'recentOrders' => $orderModel->getRecentOrders(8),
        ]);
    }
}

class AdminProductController extends BaseAdminController
{
    private $productModel;
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $this->requireAdmin();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $search = htmlspecialchars(strip_tags($_GET['q'] ?? ''));
        $limit = 15;
        $offset = ($page - 1) * $limit;
        $products = $this->productModel->getAllWithDetails($limit, $offset, $search);
        $total = $this->productModel->countAll($search);
        $this->adminView('products/index', ['pageTitle' => 'Products', 'products' => $products, 'total' => $total, 'page' => $page, 'limit' => $limit, 'search' => $search]);
    }

    public function create()
    {
        $this->requireAdmin();
        $catModel = new CategoryModel();
        $this->adminView('products/form', ['pageTitle' => 'Add Product', 'categories' => $catModel->getActive(), 'product' => null]);
    }

    public function store()
    {
        $this->requireAdmin();
        $data = $this->getProductData();
        $id = $this->productModel->create($data);
        $this->handleSizesColors($id);
        $this->handleImages($id);
        $this->redirect('/admin/products?created=1');
    }

    public function edit($id)
    {
        $this->requireAdmin();
        $product = $this->productModel->find($id, 'product_id');
        $catModel = new CategoryModel();
        $this->adminView('products/form', [
            'pageTitle' => 'Edit Product',
            'product' => $product,
            'sizes' => $this->productModel->getSizes($id),
            'colors' => $this->productModel->getColors($id),
            'images' => $this->productModel->getImages($id),
            'categories' => $catModel->getActive(),
            'subcategories' => $catModel->getSubcategories($product['category_id'] ?? 0),
        ]);
    }

    public function update($id)
    {
        $this->requireAdmin();
        $data = $this->getProductData();
        $this->productModel->update($id, $data);
        $this->productModel->deleteSizes($id);
        $this->productModel->deleteColors($id);
        $this->handleSizesColors($id);
        if (!empty($_FILES['images']['name'][0])) {
            $this->productModel->deleteImages($id);
            $this->handleImages($id);
        }
        $this->redirect('/admin/products/edit/' . $id . '?updated=1');
    }

    public function delete($id)
    {
        $this->requireAdmin();
        $this->productModel->softDelete($id);
        $this->json(['success' => true]);
    }

    public function toggleFeatured()
    {
        $this->requireAdmin();
        $id = (int)($_POST['id'] ?? 0);
        $val = (int)($_POST['val'] ?? 0);
        $this->productModel->toggleFeatured($id, $val);
        $this->json(['success' => true]);
    }

    private function getProductData()
    {
        return [
            'product_name' => $this->post('product_name'),
            'sku' => $this->post('sku'),
            'material' => $this->post('material'),
            'brand' => $this->post('brand'),
            'category_id' => (int)$this->post('category_id'),
            'sub_category_id' => (int)$this->post('sub_category_id') ?: null,
            'product_stock' => (int)$this->post('product_stock'),
            'product_description' => $this->post('product_description'),
            'is_featured' => (int)($_POST['is_featured'] ?? 0),
        ];
    }

    private function handleSizesColors($productId)
    {
        $sizeNames = $_POST['size_name'] ?? [];
        $regularPrices = $_POST['regular_price'] ?? [];
        $salePrices = $_POST['sale_price'] ?? [];
        foreach ($sizeNames as $i => $sizeName) {
            if (!$sizeName) continue;
            $this->productModel->addSize($productId, [
                'size_name' => $sizeName,
                'height' => $_POST['height'][$i] ?? null,
                'width' => $_POST['width'][$i] ?? null,
                'weight' => $_POST['weight'][$i] ?? null,
                'regular_price' => $regularPrices[$i] ?? 0,
                'sale_price' => $salePrices[$i] ?? null,
                'sort_order' => $i,
            ]);
        }
        $colorNames = $_POST['color_name'] ?? [];
        $colorCodes = $_POST['color_code'] ?? [];
        foreach ($colorNames as $i => $colorName) {
            if (!$colorName) continue;
            $this->productModel->addColor($productId, ['color_name' => $colorName, 'color_code' => $colorCodes[$i] ?? null]);
        }
    }

    private function handleImages($productId)
    {
        if (empty($_FILES['images']['name'][0])) return;
        $uploadDir = BASE_PATH . '/public/assets/images/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if (!$tmp) continue;
            $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
            $filename = uniqid('img_') . '.' . $ext;
            if (move_uploaded_file($tmp, $uploadDir . $filename)) {
                $this->productModel->addImage($productId, $filename, $_FILES['images']['name'][$i], $i === 0 ? 1 : 0, $i);
            }
        }
    }
}

class AdminCategoryController extends BaseAdminController
{
    public function index()
    {
        $this->requireAdmin();
        $catModel = new CategoryModel();
        $this->adminView('categories/index', ['pageTitle' => 'Categories', 'categories' => $catModel->getWithSubcategories()]);
    }

    public function store()
    {
        $this->requireAdmin();
        $catModel = new CategoryModel();
        $name = $this->post('name');
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $catModel->create(['name' => $name, 'slug' => $slug, 'description' => $this->post('description'), 'sort_order' => (int)$this->post('sort_order')]);
        $this->redirect('/admin/categories?created=1');
    }

    public function update($id)
    {
        $this->requireAdmin();
        $catModel = new CategoryModel();
        $name = $this->post('name');
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $catModel->update($id, ['name' => $name, 'slug' => $slug, 'description' => $this->post('description'), 'sort_order' => (int)$this->post('sort_order')]);
        $this->redirect('/admin/categories?updated=1');
    }

    public function delete($id)
    {
        $this->requireAdmin();
        (new CategoryModel())->delete($id);
        $this->json(['success' => true]);
    }

    // Add these methods inside AdminCategoryController class

    public function subcategories($categoryId)
    {
        $this->requireAdmin();
        $catModel = new CategoryModel();
        $category = $catModel->find($categoryId, 'id');
        if (!$category) {
            $this->redirect('/admin/categories');
        }

        $subCatModel = new SubCategoryModel();
        $subcategories = $subCatModel->getAllByCategory($categoryId, false);

        $this->adminView('categories/subcategories', [
            'pageTitle' => 'Subcategories - ' . htmlspecialchars($category['name']),
            'category' => $category,
            'subcategories' => $subcategories
        ]);
    }

    public function createSubcategory()
    {
        $this->requireAdmin();
        $categoryId = (int)($this->post('category_id') ?? 0);
        $name = $this->post('name');
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($name)));
        $slug = trim($slug, '-');

        $subCatModel = new SubCategoryModel();
        $subCatModel->create([
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $slug,
            'description' => $this->post('description'),
            'sort_order' => (int)$this->post('sort_order'),
            'is_active' => (int)($this->post('is_active') ?? 1)
        ]);

        $this->redirect('/admin/categories/subcategories/' . $categoryId . '?created=1');
    }

    public function updateSubcategory($id)
    {
        $this->requireAdmin();
        $subCatModel = new SubCategoryModel();
        $subcategory = $subCatModel->find($id);

        if (!$subcategory) {
            $this->json(['error' => 'Subcategory not found'], 404);
            return;
        }

        $name = $this->post('name');
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($name)));
        $slug = trim($slug, '-');

        $subCatModel->update($id, [
            'category_id' => (int)($this->post('category_id') ?? $subcategory['category_id']),
            'name' => $name,
            'slug' => $slug,
            'description' => $this->post('description'),
            'sort_order' => (int)$this->post('sort_order'),
            'is_active' => (int)($this->post('is_active') ?? 1)
        ]);

        $this->redirect('/admin/categories/subcategories/' . $subcategory['category_id'] . '?updated=1');
    }

    public function deleteSubcategory($id)
    {
        $this->requireAdmin();
        $subCatModel = new SubCategoryModel();
        $subcategory = $subCatModel->find($id);

        if ($subcategory) {
            $subCatModel->delete($id);
            $this->json(['success' => true, 'category_id' => $subcategory['category_id']]);
        } else {
            $this->json(['success' => false, 'error' => 'Not found'], 404);
        }
    }

    public function toggleSubcategoryStatus($id)
    {
        $this->requireAdmin();
        $subCatModel = new SubCategoryModel();
        $subcategory = $subCatModel->find($id);

        if ($subcategory) {
            $newStatus = $subcategory['is_active'] ? 0 : 1;
            $subCatModel->toggleStatus($id, $newStatus);
            $this->json(['success' => true, 'is_active' => $newStatus]);
        } else {
            $this->json(['success' => false], 404);
        }
    }
}

class AdminOrderController extends BaseAdminController
{
    public function index()
    {
        $this->requireAdmin();
        $orderModel = new OrderModel();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $status = htmlspecialchars(strip_tags($_GET['status'] ?? ''));
        $search = htmlspecialchars(strip_tags($_GET['q'] ?? ''));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $orders = $orderModel->getAllOrders($limit, $offset, $status, $search);
        $total = $orderModel->countOrders($status);
        $this->adminView('orders/index', ['pageTitle' => 'Orders', 'orders' => $orders, 'total' => $total, 'page' => $page, 'limit' => $limit, 'statusFilter' => $status]);
    }

    public function detail($orderNumber)
    {
        $this->requireAdmin();
        $orderModel = new OrderModel();
        $order = $orderModel->getByNumber($orderNumber);
        if (!$order) $this->redirect('/admin/orders');
        $items = $orderModel->getItems($order['order_id']);
        $log = $orderModel->getStatusLog($order['order_id']);
        $this->adminView('orders/detail', ['pageTitle' => 'Order #' . $orderNumber, 'order' => $order, 'items' => $items, 'log' => $log]);
    }

    public function updateStatus()
    {
        $this->requireAdmin();
        $orderId = (int)($_POST['order_id'] ?? 0);
        $status = htmlspecialchars(strip_tags($_POST['status'] ?? ''));
        $note = htmlspecialchars(strip_tags($_POST['note'] ?? ''));
        $orderModel = new OrderModel();
        $orderModel->updateStatus($orderId, $status);
        $order = $orderModel->find($orderId, 'order_id');
        $orderModel->addStatusLog($orderId, $order['order_number'] ?? '', $status, $note);
        $this->json(['success' => true]);
    }
}

class AdminCouponController extends BaseAdminController
{
    public function index()
    {
        $this->requireAdmin();
        $coupons = (new CouponModel())->getAll();
        $this->adminView('coupons/index', ['pageTitle' => 'Coupons', 'coupons' => $coupons]);
    }

    public function store()
    {
        $this->requireAdmin();
        (new CouponModel())->create([
            'code' => strtoupper($this->post('code')),
            'discount_pct' => (float)$this->post('discount_pct'),
            'min_order' => (float)$this->post('min_order'),
            'max_uses' => (int)$this->post('max_uses'),
            'expires_at' => $this->post('expires_at') ?: null,
        ]);
        $this->redirect('/admin/coupons?created=1');
    }

    public function delete($id)
    {
        $this->requireAdmin();
        (new CouponModel())->delete($id);
        $this->json(['success' => true]);
    }
}

class AdminCustomerController extends BaseAdminController
{
    public function index()
    {
        $this->requireAdmin();
        $userModel = new UserModel();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $users = $userModel->getAllUsers($limit, $offset);
        $total = $userModel->countUsers();
        $this->adminView('customers/index', ['pageTitle' => 'Customers', 'users' => $users, 'total' => $total, 'page' => $page, 'limit' => $limit]);
    }

    public function detail($id)
    {
        $this->requireAdmin();
        $userModel = new UserModel();
        $user = $userModel->find($id, 'user_id');
        $orders = (new OrderModel())->getUserOrders($id);
        $this->adminView('customers/detail', ['pageTitle' => 'Customer Detail', 'user' => $user, 'orders' => $orders]);
    }
}

// class AdminSettingsController extends BaseAdminController
// {
//     public function index()
//     {
//         $this->requireAdmin();
//         $delivery = (new DeliverySettingsModel())->get();
//         $this->adminView('settings/index', ['pageTitle' => 'Settings', 'delivery' => $delivery]);
//     }

//     public function save()
//     {
//         $this->requireAdmin();
//         $db = Database::getInstance()->getConnection();
//         $inside = (float)($_POST['inside_dhaka_charge'] ?? 60);
//         $outside = (float)($_POST['outside_dhaka_charge'] ?? 120);
//         $free = (float)($_POST['free_delivery_min_amount'] ?? 2000);
//         $express = (float)($_POST['express_delivery_charge'] ?? 150);
//         $db->query("INSERT INTO delivery_settings (id,inside_dhaka_charge,outside_dhaka_charge,free_delivery_min_amount,express_delivery_charge) VALUES (1,$inside,$outside,$free,$express) ON DUPLICATE KEY UPDATE inside_dhaka_charge=$inside,outside_dhaka_charge=$outside,free_delivery_min_amount=$free,express_delivery_charge=$express");
//         $this->redirect('/admin/settings?saved=1');
//     }
// }

class AdminSettingsController extends BaseAdminController
{
    private $settingsModel;

    public function __construct()
    {
        parent::__construct();
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        $this->requireAdmin();
        $delivery = (new DeliverySettingsModel())->get();
        $settings = $this->settingsModel->getAll();

        $this->adminView('settings/index', [
            'pageTitle' => 'Settings',
            'delivery' => $delivery,
            'settings' => $settings
        ]);
    }

    public function save()
    {
        $this->requireAdmin();

        // Save delivery settings
        $db = Database::getInstance()->getConnection();
        $inside = (float)($_POST['inside_dhaka_charge'] ?? 60);
        $outside = (float)($_POST['outside_dhaka_charge'] ?? 120);
        $free = (float)($_POST['free_delivery_min_amount'] ?? 2000);
        $express = (float)($_POST['express_delivery_charge'] ?? 150);
        $db->query("INSERT INTO delivery_settings (id,inside_dhaka_charge,outside_dhaka_charge,free_delivery_min_amount,express_delivery_charge) VALUES (1,$inside,$outside,$free,$express) ON DUPLICATE KEY UPDATE inside_dhaka_charge=$inside,outside_dhaka_charge=$outside,free_delivery_min_amount=$free,express_delivery_charge=$express");

        // Save site settings
        if (isset($_POST['site_name'])) {
            $this->settingsModel->set('site_name', $_POST['site_name']);
        }

        $this->redirect('/admin/settings?saved=1');
    }

    public function uploadLogo()
    {
        $this->requireAdmin();

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            $fileExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowed)) {
                $_SESSION['error'] = 'Invalid file type. Allowed: ' . implode(', ', $allowed);
                $this->redirect('/admin/settings');
                return;
            }

            // Create upload directory if not exists
            $uploadDir = BASE_PATH . '/public/assets/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $type = $_POST['logo_type'] ?? 'site_logo';
            $filename = $type . '_' . time() . '.' . $fileExt;
            $filepath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $filepath)) {
                // Save to database
                $logoPath = '/assets/images/' . $filename;
                $this->settingsModel->set($type, $logoPath);
                $_SESSION['success'] = 'Logo uploaded successfully!';
            } else {
                $_SESSION['error'] = 'Failed to upload logo.';
            }
        } else {
            $_SESSION['error'] = 'Please select a file to upload.';
        }

        $this->redirect('/admin/settings');
    }

    public function removeLogo()
    {
        $this->requireAdmin();
        $type = $_POST['logo_type'] ?? 'site_logo';

        // Get current logo path
        $currentLogo = $this->settingsModel->get($type);
        if ($currentLogo) {
            // Delete file
            $filepath = BASE_PATH . '/public' . $currentLogo;
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }

        // Remove from database
        $this->settingsModel->set($type, '');
        $_SESSION['success'] = 'Logo removed successfully!';
        $this->redirect('/admin/settings');
    }
}

class AdminReviewController extends BaseAdminController
{
    public function index()
    {
        $this->requireAdmin();
        $reviews = Database::getInstance()->fetchAll("SELECT pr.*, u.name as user_name, p.product_name FROM product_reviews pr JOIN users u ON u.user_id=pr.user_id JOIN products p ON p.product_id=pr.product_id ORDER BY pr.created_at DESC LIMIT 100");
        $this->adminView('reviews/index', ['pageTitle' => 'Reviews', 'reviews' => $reviews]);
    }

    public function delete($id)
    {
        $this->requireAdmin();
        Database::getInstance()->execute("DELETE FROM product_reviews WHERE id=?", [$id], 'i');
        $this->json(['success' => true]);
    }
}
