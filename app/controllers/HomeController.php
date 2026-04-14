<?php
// ============================================================
//  ShopController
// ============================================================
require_once __DIR__.'/../../core/Controller.php';
require_once __DIR__.'/../models/ProductModel.php';
require_once __DIR__.'/../models/SettingsModel.php';

class ShopController extends Controller {
    private ProductModel $pm;
    private SettingsModel $sm;
    public function __construct(){ $this->pm=new ProductModel(); $this->sm=new SettingsModel(); }

    public function index(): void {
        $filters = [];
        if(!empty($_GET['category'])) $filters['category_id'] = (int)$_GET['category'];
        if(!empty($_GET['sub']))      $filters['sub_category_id'] = (int)$_GET['sub'];
        if(!empty($_GET['q']))        $filters['search'] = $this->clean($_GET['q']);
        $page    = max(1,(int)($_GET['page']??1));
        $limit   = 12;
        $offset  = ($page-1)*$limit;
        $products = $this->pm->getAll($filters,$limit,$offset);
        $total    = $this->pm->countAll($filters);
        $pages    = (int)ceil($total/$limit);
        $cats     = $this->sm->getCategories();
        $this->view('layouts/main',['title'=>'Shop','content_view'=>'shop/index',
            'products'=>$products,'total'=>$total,'page'=>$page,'pages'=>$pages,
            'filters'=>$filters,'cats'=>$cats]);
    }

    public function product(string $id): void {
        $p = $this->pm->getBySlugOrId($id);
        if(!$p) { http_response_code(404); require __DIR__.'/../views/404.php'; return; }
        $images   = $this->pm->getImages((int)$p['product_id']);
        $sizes    = $this->pm->getSizes((int)$p['product_id']);
        $colors   = $this->pm->getColors((int)$p['product_id']);
        $reviews  = $this->pm->getReviews((int)$p['product_id']);
        $avgRating= $this->pm->getAvgRating((int)$p['product_id']);
        $related  = $this->pm->getRelated((int)$p['product_id'],(int)$p['category_id']);
        $csrf     = $this->csrfToken();
        $this->view('layouts/main',['title'=>$p['product_name'],'content_view'=>'product/detail',
            'p'=>$p,'images'=>$images,'sizes'=>$sizes,'colors'=>$colors,
            'reviews'=>$reviews,'avgRating'=>$avgRating,'related'=>$related,'csrf'=>$csrf]);
    }

    public function submitReview(): void {
        if(!$this->isLoggedIn()){ $this->json(['success'=>false,'message'=>'Please login to leave a review.']); }
        $pid    = (int)($_POST['product_id']??0);
        $rating = (int)($_POST['rating']??5);
        $review = $this->clean($_POST['review']??'');
        $this->pm->addReview($pid,(int)$_SESSION['user_id'],$rating,$review);
        $this->json(['success'=>true,'message'=>'Review submitted. Thank you!']);
    }
}

// ============================================================
//  CartController
// ============================================================
class CartController extends Controller {
    private ProductModel $pm;
    public function __construct(){ $this->pm=new ProductModel(); }

    public function index(): void {
        $cart = $_SESSION['cart']??[];
        $items = $this->buildCartItems($cart);
        $this->view('layouts/main',['title'=>'Shopping Cart','content_view'=>'cart/index','items'=>$items,'cart'=>$cart]);
    }

    public function add(): void {
        $pid   = (int)($_POST['product_id']??0);
        $size  = $this->clean($_POST['size']??'');
        $color = $this->clean($_POST['color']??'');
        $qty   = max(1,(int)($_POST['qty']??1));
        $price = (float)($_POST['price']??0);
        if(!$pid||!$size||$price<=0){ $this->json(['success'=>false,'message'=>'Invalid selection.']); }
        $key = $pid.'_'.$size.'_'.str_replace('#','',$color);
        if(!isset($_SESSION['cart'])) $_SESSION['cart']=[];
        if(isset($_SESSION['cart'][$key])){
            $_SESSION['cart'][$key]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$key]=['product_id'=>$pid,'size'=>$size,'color'=>$color,'qty'=>$qty,'price'=>$price];
        }
        $count = array_sum(array_column($_SESSION['cart'],'qty'));
        $this->json(['success'=>true,'count'=>$count,'message'=>'Added to cart!']);
    }

    public function update(): void {
        $key = $_POST['key']??'';
        $qty = max(0,(int)($_POST['qty']??0));
        if($qty===0){ unset($_SESSION['cart'][$key]); }
        else { if(isset($_SESSION['cart'][$key])) $_SESSION['cart'][$key]['qty']=$qty; }
        $this->json(['success'=>true,'count'=>array_sum(array_column($_SESSION['cart']??[],'qty'))]);
    }

    public function remove(): void {
        $key = $_POST['key']??'';
        unset($_SESSION['cart'][$key]);
        $this->json(['success'=>true,'count'=>array_sum(array_column($_SESSION['cart']??[],'qty'))]);
    }

    public function count(): void {
        $this->json(['count'=>array_sum(array_column($_SESSION['cart']??[],'qty'))]);
    }

    private function buildCartItems(array $cart): array {
        $items=[];
        foreach($cart as $key=>$c){
            $p = $this->pm->getBySlugOrId((string)$c['product_id']);
            $img = $this->pm->getImages($c['product_id']);
            if($p) $items[$key]=['product'=>$p,'image'=>$img[0]??null,'size'=>$c['size'],'color'=>$c['color'],'qty'=>$c['qty'],'price'=>$c['price'],'subtotal'=>$c['qty']*$c['price']];
        }
        return $items;
    }
}

// ============================================================
//  CheckoutController
// ============================================================
class CheckoutController extends Controller {
    private ProductModel $pm;
    private UserModel $um;
    private OrderModel $om;
    private SettingsModel $sm;

    public function __construct(){
        require_once __DIR__.'/../models/UserModel.php';
        require_once __DIR__.'/../models/OrderModel.php';
        require_once __DIR__.'/../models/SettingsModel.php';
        $this->pm=new ProductModel();
        $this->um=new UserModel();
        $this->om=new OrderModel();
        $this->sm=new SettingsModel();
    }

    public function index(): void {
        if(empty($_SESSION['cart'])) { $this->redirect('cart'); }
        $cart     = $_SESSION['cart'];
        $delivery = $this->sm->getDelivery();
        $addrs    = $this->isLoggedIn() ? $this->um->getAddresses((int)$_SESSION['user_id']) : [];
        $default  = $this->isLoggedIn() ? $this->um->getDefaultAddress((int)$_SESSION['user_id']) : null;
        $csrf     = $this->csrfToken();
        $this->view('layouts/main',['title'=>'Checkout','content_view'=>'checkout/index',
            'cart'=>$cart,'delivery'=>$delivery,'addrs'=>$addrs,'default'=>$default,'csrf'=>$csrf]);
    }

    public function applyCoupon(): void {
        $code   = strtoupper(trim($_POST['code']??''));
        $amount = (float)($_POST['amount']??0);
        $result = $this->om->applyCoupon($code,$amount);
        if(!$result) $this->json(['success'=>false,'message'=>'Invalid or expired coupon code.']);
        elseif(isset($result['error'])) $this->json(['success'=>false,'message'=>$result['error']]);
        else {
            $_SESSION['coupon'] = $result;
            $this->json(['success'=>true,'discount_pct'=>$result['discount_pct'],'code'=>$result['code']]);
        }
    }

    public function place(): void {
        $this->verifyCsrf();
        if(empty($_SESSION['cart'])) { $this->redirect('cart'); }

        $cart   = $_SESSION['cart'];
        $coupon = $_SESSION['coupon']??null;

        // Build items
        $items=[]; $subtotal=0;
        foreach($cart as $c){
            $p=$this->pm->getBySlugOrId((string)$c['product_id']);
            if($p){ $items[]=['product_id'=>$c['product_id'],'size'=>$c['size'],'color'=>$c['color'],'qty'=>$c['qty'],'unit_price'=>$c['price']]; $subtotal+=$c['qty']*$c['price']; }
        }

        // Delivery
        $delivery   = $this->sm->getDelivery();
        $city       = strtolower($this->clean($_POST['city']??'dhaka'));
        $shipping   = stripos($city,'dhaka')!==false ? (float)$delivery['inside_dhaka_charge'] : (float)$delivery['outside_dhaka_charge'];
        if($subtotal >= (float)$delivery['free_delivery_min_amount']) $shipping=0;

        // Discount
        $discount=0;
        if($coupon) { $discount = $subtotal*($coupon['discount_pct']/100); $this->om->useCoupon($coupon['id']); }
        $total = $subtotal - $discount + $shipping;

        $data=['items'=>$items,'total_amount'=>$total,'shipping_charge'=>$shipping,
               'payment_method'=>$_POST['payment_method']??'cod',
               'notes'=>$this->clean($_POST['notes']??'')];

        if($this->isLoggedIn()){
            $data['user_id']    = (int)$_SESSION['user_id'];
            $data['address_id'] = !empty($_POST['address_id']) ? (int)$_POST['address_id'] : null;
            // Save new address if provided
            if(empty($_POST['address_id'])&&!empty($_POST['full_name'])){
                $aid=$this->um->addAddress((int)$_SESSION['user_id'],['label'=>'home','full_name'=>$this->clean($_POST['full_name']),'phone'=>$this->clean($_POST['phone']),'address_line'=>$this->clean($_POST['address_line']),'area'=>$this->clean($_POST['area']??''),'city'=>$this->clean($_POST['city']),'district'=>$this->clean($_POST['district']??''),'postal_code'=>$this->clean($_POST['postal_code']??''),'is_default'=>false]);
                $data['address_id']=$aid;
            }
        } else {
            $data['user_id']=0; $data['address_id']=null;
            $data['guest_name']    = $this->clean($_POST['full_name']??'');
            $data['guest_email']   = $this->clean($_POST['email']??'');
            $data['guest_phone']   = $this->clean($_POST['phone']??'');
            $data['guest_address'] = $this->clean($_POST['address_line']??'').', '.$this->clean($_POST['city']??'');
        }

        $oid = $this->om->create($data);
        $o   = $this->om->getById($oid);

        unset($_SESSION['cart'],$_SESSION['coupon']);
        $_SESSION['last_order'] = $oid;

        $this->redirect('order/success/'.$o['order_number']);
    }
}

// ============================================================
//  OrderController
// ============================================================
class OrderController extends Controller {
    private OrderModel $om;
    public function __construct(){ require_once __DIR__.'/../models/OrderModel.php'; $this->om=new OrderModel(); }

    public function success(string $num): void {
        $o = $this->om->getByNumber($num);
        if(!$o){ $this->redirect(''); }
        $items = $this->om->getItems((int)$o['order_id']);
        $this->view('layouts/main',['title'=>'Order Confirmed','content_view'=>'order/success','o'=>$o,'items'=>$items]);
    }

    public function track(): void {
        $o=$items=$log=null; $error=null;
        if(!empty($_POST['order_number'])){
            $num     = $this->clean($_POST['order_number']);
            $contact = $this->clean($_POST['contact']??'');
            $o = $this->om->getByNumberAndContact($num,$contact);
            if(!$o) $error='Order not found. Please check your order number and contact.';
            else { $items=$this->om->getItems((int)$o['order_id']); $log=$this->om->getStatusLog((int)$o['order_id']); }
        }
        $csrf=$this->csrfToken();
        $this->view('layouts/main',['title'=>'Track Order','content_view'=>'order/track','o'=>$o,'items'=>$items,'log'=>$log,'error'=>$error,'csrf'=>$csrf]);
    }
}

// ============================================================
//  HomeController
// ============================================================
class HomeController extends Controller {
    private ProductModel $pm;
    private SettingsModel $sm;
    public function __construct(){ $this->pm=new ProductModel(); $this->sm=new SettingsModel(); }

    public function index(): void {
        $featured = $this->pm->getFeatured(8);
        $latest   = $this->pm->getAll([],8,0);
        $cats     = $this->sm->getCategories();
        $this->view('layouts/main',['title'=>'Home','content_view'=>'home/index',
            'featured'=>$featured,'latest'=>$latest,'cats'=>$cats]);
    }
}
