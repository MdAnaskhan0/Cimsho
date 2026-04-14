-- ============================================================
--  EcomAdmin — Seed File
--  Run this AFTER importing ecom.sql
-- ============================================================

-- Default admin account
-- Username : admin
-- Password : Admin@1234
-- (Change immediately after first login via the "Change Password" option)

INSERT INTO `admins` (`username`, `password_hash`, `full_name`, `is_active`)
VALUES (
    'admin',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- bcrypt of 'Admin@1234'
    'Super Admin',
    1
);

-- ============================================================
--  HOW TO GENERATE YOUR OWN PASSWORD HASH (PHP):
--  <?php echo password_hash('YourPassword', PASSWORD_BCRYPT, ['cost'=>12]); ?>
-- ============================================================
