<?php
require_once __DIR__.'/../../core/Controller.php';
require_once __DIR__.'/../models/UserModel.php';
require_once __DIR__.'/../models/OrderModel.php';

class AccountController extends Controller {
    private UserModel $user;
    private OrderModel $order;
    public function __construct(){ $this->user=new UserModel(); $this->order=new OrderModel(); }

    public function index(): void {
        $this->requireLogin();
        $u      = $this->user->findById((int)$_SESSION['user_id']);
        $orders = $this->order->getByUser((int)$_SESSION['user_id']);
        $addrs  = $this->user->getAddresses((int)$_SESSION['user_id']);
        $csrf   = $this->csrfToken();
        $this->view('layouts/main',['title'=>'My Account','content_view'=>'account/dashboard',
            'u'=>$u,'orders'=>$orders,'addrs'=>$addrs,'csrf'=>$csrf]);
    }

    public function profile(): void {
        $this->requireLogin();
        $u = $this->user->findById((int)$_SESSION['user_id']);
        $csrf = $this->csrfToken();
        $this->view('layouts/main',['title'=>'Edit Profile','content_view'=>'account/profile','u'=>$u,'csrf'=>$csrf]);
    }

    public function updateProfile(): void {
        $this->requireLogin(); $this->verifyCsrf();
        $name  = $this->clean($_POST['name']??'');
        $phone = $this->clean($_POST['phone']??'');
        $this->user->updateProfile((int)$_SESSION['user_id'],$name,$phone);
        $_SESSION['user_name'] = $name;
        $this->flash('success','Profile updated successfully.');
        $this->redirect('account');
    }

    // Addresses
    public function addresses(): void {
        $this->requireLogin();
        $addrs = $this->user->getAddresses((int)$_SESSION['user_id']);
        $csrf  = $this->csrfToken();
        $this->view('layouts/main',['title'=>'My Addresses','content_view'=>'account/addresses','addrs'=>$addrs,'csrf'=>$csrf]);
    }

    public function addAddress(): void {
        $this->requireLogin(); $this->verifyCsrf();
        $this->user->addAddress((int)$_SESSION['user_id'],[
            'label'        => $_POST['label']??'home',
            'full_name'    => $this->clean($_POST['full_name']??''),
            'phone'        => $this->clean($_POST['phone']??''),
            'address_line' => $this->clean($_POST['address_line']??''),
            'area'         => $this->clean($_POST['area']??''),
            'city'         => $this->clean($_POST['city']??'Dhaka'),
            'district'     => $this->clean($_POST['district']??''),
            'postal_code'  => $this->clean($_POST['postal_code']??''),
            'is_default'   => !empty($_POST['is_default']),
        ]);
        $this->flash('success','Address added successfully.');
        $this->redirect('account/addresses');
    }

    public function editAddress(string $id): void {
        $this->requireLogin();
        $addr = $this->user->getAddress((int)$id,(int)$_SESSION['user_id']);
        if(!$addr) $this->redirect('account/addresses');
        $csrf = $this->csrfToken();
        $this->view('layouts/main',['title'=>'Edit Address','content_view'=>'account/edit_address','addr'=>$addr,'csrf'=>$csrf]);
    }

    public function updateAddress(string $id): void {
        $this->requireLogin(); $this->verifyCsrf();
        $this->user->updateAddress((int)$id,(int)$_SESSION['user_id'],[
            'label'        => $_POST['label']??'home',
            'full_name'    => $this->clean($_POST['full_name']??''),
            'phone'        => $this->clean($_POST['phone']??''),
            'address_line' => $this->clean($_POST['address_line']??''),
            'area'         => $this->clean($_POST['area']??''),
            'city'         => $this->clean($_POST['city']??'Dhaka'),
            'district'     => $this->clean($_POST['district']??''),
            'postal_code'  => $this->clean($_POST['postal_code']??''),
            'is_default'   => !empty($_POST['is_default']),
        ]);
        $this->flash('success','Address updated.');
        $this->redirect('account/addresses');
    }

    public function deleteAddress(string $id): void {
        $this->requireLogin();
        $this->user->deleteAddress((int)$id,(int)$_SESSION['user_id']);
        $this->flash('success','Address removed.');
        $this->redirect('account/addresses');
    }

    public function setDefault(string $id): void {
        $this->requireLogin();
        $this->user->setDefaultAddress((int)$id,(int)$_SESSION['user_id']);
        $this->json(['success'=>true]);
    }

    // Orders
    public function orders(): void {
        $this->requireLogin();
        $orders = $this->order->getByUser((int)$_SESSION['user_id']);
        $this->view('layouts/main',['title'=>'My Orders','content_view'=>'account/orders','orders'=>$orders]);
    }

    public function orderDetail(string $id): void {
        $this->requireLogin();
        $o = $this->order->getById((int)$id);
        if(!$o || (int)$o['user_id']!==(int)$_SESSION['user_id']) $this->redirect('account/orders');
        $items = $this->order->getItems((int)$id);
        $log   = $this->order->getStatusLog((int)$id);
        $this->view('layouts/main',['title'=>'Order #'.$o['order_number'],'content_view'=>'order/detail',
            'o'=>$o,'items'=>$items,'log'=>$log]);
    }
}
