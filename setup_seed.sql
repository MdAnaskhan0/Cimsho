-- ============================================================
-- Cimsho E-Commerce — Setup & Seed Script
-- Run this AFTER importing cimsho_db__.sql
-- ============================================================

USE cimsho;

-- ============================================================
-- 1. Create default Admin user
--    Username: admin | Password: admin123
-- ============================================================
INSERT INTO admins (username, password_hash, full_name, is_active)
VALUES (
  'admin',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXSi58Ei', -- password: admin123
  'Super Admin',
  1
) ON DUPLICATE KEY UPDATE username = username;

-- ============================================================
-- 2. Sample Categories
-- ============================================================
INSERT INTO categories (name, slug, description, is_active, sort_order) VALUES
('Men\'s Fashion', 'mens-fashion', 'Clothing and accessories for men', 1, 1),
('Women\'s Fashion', 'womens-fashion', 'Clothing and accessories for women', 1, 2),
('Electronics', 'electronics', 'Gadgets and electronic devices', 1, 3),
('Home & Living', 'home-living', 'Home décor and household items', 1, 4),
('Health & Beauty', 'health-beauty', 'Health, skincare and beauty products', 1, 5),
('Sports & Outdoor', 'sports-outdoor', 'Sports gear and outdoor essentials', 1, 6)
ON DUPLICATE KEY UPDATE name = name;

-- ============================================================
-- 3. Sample Sub-Categories
-- ============================================================
INSERT INTO sub_categories (category_id, name, slug, is_active, sort_order)
SELECT c.id, 'T-Shirts', 't-shirts', 1, 1 FROM categories c WHERE c.slug = 'mens-fashion'
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO sub_categories (category_id, name, slug, is_active, sort_order)
SELECT c.id, 'Trousers', 'trousers', 1, 2 FROM categories c WHERE c.slug = 'mens-fashion'
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO sub_categories (category_id, name, slug, is_active, sort_order)
SELECT c.id, 'Shoes', 'shoes-men', 1, 3 FROM categories c WHERE c.slug = 'mens-fashion'
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO sub_categories (category_id, name, slug, is_active, sort_order)
SELECT c.id, 'Dresses', 'dresses', 1, 1 FROM categories c WHERE c.slug = 'womens-fashion'
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO sub_categories (category_id, name, slug, is_active, sort_order)
SELECT c.id, 'Tops & Blouses', 'tops-blouses', 1, 2 FROM categories c WHERE c.slug = 'womens-fashion'
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO sub_categories (category_id, name, slug, is_active, sort_order)
SELECT c.id, 'Smartphones', 'smartphones', 1, 1 FROM categories c WHERE c.slug = 'electronics'
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO sub_categories (category_id, name, slug, is_active, sort_order)
SELECT c.id, 'Headphones', 'headphones', 1, 2 FROM categories c WHERE c.slug = 'electronics'
ON DUPLICATE KEY UPDATE name = name;

-- ============================================================
-- 4. Sample Products
-- ============================================================
INSERT INTO products (product_name, sku, material, brand, category_id, product_stock, product_description, is_featured, is_active)
SELECT 'Premium Cotton Polo T-Shirt', 'SKU-MF-001', '100% Combed Cotton', 'StyleBD', c.id, 50,
'Premium quality polo t-shirt made from 100% combed cotton. Perfect for casual and semi-formal occasions. Available in multiple colors and sizes.', 1, 1
FROM categories c WHERE c.slug = 'mens-fashion';

INSERT INTO products (product_name, sku, material, brand, category_id, product_stock, product_description, is_featured, is_active)
SELECT 'Classic Slim Fit Chinos', 'SKU-MF-002', 'Cotton-Poly Blend', 'StyleBD', c.id, 30,
'Comfortable slim fit chinos perfect for everyday wear. Made with a soft cotton-polyester blend for all-day comfort.', 1, 1
FROM categories c WHERE c.slug = 'mens-fashion';

INSERT INTO products (product_name, sku, material, brand, category_id, product_stock, product_description, is_featured, is_active)
SELECT 'Floral Print Summer Dress', 'SKU-WF-001', 'Chiffon', 'FashionBD', c.id, 40,
'Beautiful floral print chiffon dress, perfect for summer occasions. Lightweight and breathable fabric.', 1, 1
FROM categories c WHERE c.slug = 'womens-fashion';

INSERT INTO products (product_name, sku, material, brand, category_id, product_stock, product_description, is_featured, is_active)
SELECT 'Wireless Bluetooth Earbuds', 'SKU-EL-001', 'ABS Plastic', 'TechBD', c.id, 20,
'High-quality wireless earbuds with noise cancellation, 24hr battery life and IPX5 water resistance. Clear sound quality.', 1, 1
FROM categories c WHERE c.slug = 'electronics';

INSERT INTO products (product_name, sku, material, brand, category_id, product_stock, product_description, is_featured, is_active)
SELECT 'Ceramic Mug Set (4 pcs)', 'SKU-HL-001', 'Ceramic', 'HomeBD', c.id, 35,
'Beautiful set of 4 ceramic mugs. Microwave and dishwasher safe. Perfect for home and office use.', 0, 1
FROM categories c WHERE c.slug = 'home-living';

INSERT INTO products (product_name, sku, material, brand, category_id, product_stock, product_description, is_featured, is_active)
SELECT 'Men\'s Running Sneakers', 'SKU-SP-001', 'Mesh + Rubber Sole', 'SportBD', c.id, 25,
'Lightweight and breathable running sneakers with cushioned insole for maximum comfort during workouts.', 0, 1
FROM categories c WHERE c.slug = 'sports-outdoor';

INSERT INTO products (product_name, sku, material, brand, category_id, product_stock, product_description, is_featured, is_active)
SELECT 'Natural Aloe Vera Gel', 'SKU-HB-001', 'Natural Extract', 'GlowBD', c.id, 60,
'Pure and natural aloe vera gel, perfect for skin and hair care. No harmful chemicals. Suitable for all skin types.', 0, 1
FROM categories c WHERE c.slug = 'health-beauty';

INSERT INTO products (product_name, sku, material, brand, category_id, product_stock, product_description, is_featured, is_active)
SELECT 'Ethnic Cotton Panjabi', 'SKU-MF-003', '100% Cotton', 'DeshiBD', c.id, 45,
'Traditional Bengali cotton panjabi with beautiful embroidery. Perfect for Eid, weddings and cultural events.', 1, 1
FROM categories c WHERE c.slug = 'mens-fashion';

-- ============================================================
-- 5. Add Sizes & Prices to Products
-- ============================================================
-- Polo T-Shirt
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'S', 650.00, 550.00, 0 FROM products p WHERE p.sku = 'SKU-MF-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'M', 650.00, 550.00, 1 FROM products p WHERE p.sku = 'SKU-MF-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'L', 680.00, 580.00, 2 FROM products p WHERE p.sku = 'SKU-MF-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'XL', 700.00, 600.00, 3 FROM products p WHERE p.sku = 'SKU-MF-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'XXL', 720.00, 620.00, 4 FROM products p WHERE p.sku = 'SKU-MF-001';

-- Chinos
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '28', 1200.00, 999.00, 0 FROM products p WHERE p.sku = 'SKU-MF-002';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '30', 1200.00, 999.00, 1 FROM products p WHERE p.sku = 'SKU-MF-002';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '32', 1250.00, 1050.00, 2 FROM products p WHERE p.sku = 'SKU-MF-002';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '34', 1250.00, 1050.00, 3 FROM products p WHERE p.sku = 'SKU-MF-002';

-- Dress
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'S', 1800.00, 1499.00, 0 FROM products p WHERE p.sku = 'SKU-WF-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'M', 1800.00, 1499.00, 1 FROM products p WHERE p.sku = 'SKU-WF-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'L', 1850.00, 1550.00, 2 FROM products p WHERE p.sku = 'SKU-WF-001';

-- Earbuds
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'Standard', 3500.00, 2999.00, 0 FROM products p WHERE p.sku = 'SKU-EL-001';

-- Mug Set
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'Set of 4', 950.00, 799.00, 0 FROM products p WHERE p.sku = 'SKU-HL-001';

-- Sneakers
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '40', 2500.00, 2100.00, 0 FROM products p WHERE p.sku = 'SKU-SP-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '41', 2500.00, 2100.00, 1 FROM products p WHERE p.sku = 'SKU-SP-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '42', 2500.00, 2100.00, 2 FROM products p WHERE p.sku = 'SKU-SP-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '43', 2550.00, 2150.00, 3 FROM products p WHERE p.sku = 'SKU-SP-001';

-- Aloe Vera
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '100ml', 350.00, 299.00, 0 FROM products p WHERE p.sku = 'SKU-HB-001';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, '200ml', 600.00, 499.00, 1 FROM products p WHERE p.sku = 'SKU-HB-001';

-- Panjabi
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'M', 1500.00, 1299.00, 0 FROM products p WHERE p.sku = 'SKU-MF-003';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'L', 1550.00, 1350.00, 1 FROM products p WHERE p.sku = 'SKU-MF-003';
INSERT INTO product_sizes (product_id, size_name, regular_price, sale_price, sort_order)
SELECT p.product_id, 'XL', 1600.00, 1399.00, 2 FROM products p WHERE p.sku = 'SKU-MF-003';

-- ============================================================
-- 6. Sample Colors
-- ============================================================
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'White', '#FFFFFF', 0 FROM products p WHERE p.sku = 'SKU-MF-001';
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Navy Blue', '#1a3a6b', 1 FROM products p WHERE p.sku = 'SKU-MF-001';
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Black', '#111111', 2 FROM products p WHERE p.sku = 'SKU-MF-001';
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Red', '#e94560', 3 FROM products p WHERE p.sku = 'SKU-MF-001';

INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Beige', '#d4b896', 0 FROM products p WHERE p.sku = 'SKU-MF-002';
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Navy', '#1a1a2e', 1 FROM products p WHERE p.sku = 'SKU-MF-002';
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Olive', '#6b7c45', 2 FROM products p WHERE p.sku = 'SKU-MF-002';

INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Pink Floral', '#f8a4c0', 0 FROM products p WHERE p.sku = 'SKU-WF-001';
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Blue Floral', '#93c5fd', 1 FROM products p WHERE p.sku = 'SKU-WF-001';

INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'White', '#FFFFFF', 0 FROM products p WHERE p.sku = 'SKU-MF-003';
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Sky Blue', '#87ceeb', 1 FROM products p WHERE p.sku = 'SKU-MF-003';
INSERT INTO product_colors (product_id, color_name, color_code, sort_order)
SELECT p.product_id, 'Light Yellow', '#fef9c3', 2 FROM products p WHERE p.sku = 'SKU-MF-003';

-- ============================================================
-- 7. Sample Coupons
-- ============================================================
INSERT INTO coupons (code, discount_pct, min_order, max_uses, expires_at, is_active)
VALUES
  ('WELCOME10', 10.00, 500.00, 500, '2026-12-31', 1),
  ('SAVE20', 20.00, 1500.00, 200, '2026-12-31', 1),
  ('EID2026', 15.00, 1000.00, 1000, '2026-07-01', 1)
ON DUPLICATE KEY UPDATE code = code;

-- ============================================================
-- 8. Delivery Settings (default)
-- ============================================================
INSERT INTO delivery_settings (id, inside_dhaka_charge, outside_dhaka_charge, free_delivery_min_amount, express_delivery_charge)
VALUES (1, 60.00, 120.00, 2000.00, 150.00)
ON DUPLICATE KEY UPDATE
  inside_dhaka_charge = 60.00,
  outside_dhaka_charge = 120.00,
  free_delivery_min_amount = 2000.00,
  express_delivery_charge = 150.00;

-- ============================================================
-- 9. Sample Demo User
--    Email: demo@cimsho.com | Password: demo1234
-- ============================================================
INSERT INTO users (name, email, phone, password_hash, is_active)
VALUES (
  'Demo User',
  'demo@cimsho.com',
  '01700000000',
  '$2y$10$TKh8H1.PfuBifdYlQklz9e67UBvbcnHbQgvVXN5lqNV0ZPLnfQ7.S', -- demo1234
  1
) ON DUPLICATE KEY UPDATE email = email;

SELECT '✅ Setup complete!' AS status;
SELECT 'Admin Login → username: admin | password: admin123' AS admin_credentials;
SELECT 'Demo User  → email: demo@cimsho.com | password: demo1234' AS demo_credentials;
SELECT 'Coupons available: WELCOME10, SAVE20, EID2026' AS coupons;
