-- ============================================================
-- Mid Night Club (club_id = 1) - Complete Database Seed SQL Dump
-- Imperial Nightclub Management System
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1. ROLES (Admin System Roles)
-- ------------------------------------------------------------
INSERT INTO `roles` (`id`, `name`, `type`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'system', '[\"*\"]', NOW(), NOW()),
(2, 'Club Manager', 'custom', '[\"dashboard\",\"bookings\",\"tables\",\"events\",\"clients\",\"reviews\",\"reports\",\"promos\"]', NOW(), NOW()),
(3, 'Floor Supervisor', 'custom', '[\"dashboard\",\"bookings\",\"tables\",\"check_in\"]', NOW(), NOW()),
(4, 'Receptionist', 'custom', '[\"bookings\",\"clients\",\"check_in\"]', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `permissions`=VALUES(`permissions`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 2. USERS (Admin & Staff Accounts)
-- ------------------------------------------------------------
-- Default Password for all seeded users: password (Bcrypt Hash: $2y$12$4mSrqQjF7h53tV7tY48tO.5lS8wD5a3h82f3uE2gH0qK8eX8VdO5W)
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `avatar`, `role_id`, `user_type`, `is_active`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@admin.com', '9876543210', NULL, '1', 'admin', 1, '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', NOW(), NOW()),
(2, 'Vikram Oberoi', 'manager@midnightclub.com', '9811223344', NULL, '2', 'admin', 1, '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', NOW(), NOW()),
(3, 'Arjun Kapoor', 'supervisor@midnightclub.com', '9822334455', NULL, '3', 'admin', 1, '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', NOW(), NOW()),
(4, 'Simran Kaur', 'reception@midnightclub.com', '9833445566', NULL, '4', 'admin', 1, '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `role_id`=VALUES(`role_id`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 3. MOBILE APP ROLES
-- ------------------------------------------------------------
INSERT INTO `mobile_app_roles` (`id`, `name`, `type`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'system', '[\"*\"]', NOW(), NOW()),
(2, 'Staff', 'custom', '[\"qr_scan\",\"check_in\",\"view_bookings\"]', NOW(), NOW()),
(3, 'VIP Member', 'custom', '[\"vip_access\",\"priority_booking\",\"exclusive_discounts\"]', NOW(), NOW()),
(4, 'Member', 'custom', '[\"standard_booking\",\"view_events\"]', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `permissions`=VALUES(`permissions`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 4. CLUBS (Mid Night Club, club_id = 1)
-- ------------------------------------------------------------
INSERT INTO `clubs` (`id`, `name`, `description`, `address`, `city`, `logo`, `average_rating`, `review_count`, `rating_5_percent`, `rating_4_percent`, `rating_3_percent`, `rating_2_percent`, `rating_1_percent`, `image`, `featured_image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mid Night Club', 'Best & Most Happening Night Club of Gurugram, Opens Till 7 AM. Premium lounge, world-class sound, signature cocktails & VIP experiences. 📞 For Reservations: +91 9711891515', 'SCO 34-36, Sector 29', 'Gurugram', 'clubs/midnight_logo.png', 4.8, 15, 73.33, 20.00, 6.67, 0.00, 0.00, 'clubs/midnight_banner.jpg', 'clubs/midnight_featured.jpg', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `description`=VALUES(`description`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 5. BRANCHES & FLOORS
-- ------------------------------------------------------------
INSERT INTO `branches` (`id`, `club_id`, `name`, `description`, `address`, `phone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Midnight Club - Main Arena', 'Flagship nightlife destination featuring acoustic engineering and luxury lounges.', 'SCO 34-36, Sector 29, Gurugram, Haryana 122002', '+91 9711891515', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `updated_at`=NOW();

INSERT INTO `floors` (`id`, `branch_id`, `name`, `level`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ground Floor - Main Dance Arena & Island Bar', 0, 1, NOW(), NOW()),
(2, 1, '1st Floor - VIP Mezzanine & DJ Lounge', 1, 1, NOW(), NOW()),
(3, 1, 'Rooftop Sky Bar & Open Terrace', 2, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 6. TABLES (Exact User Specified Data)
-- ------------------------------------------------------------
INSERT INTO `tables` (`id`, `club_id`, `total_tables`, `cover_charge`, `name`, `label`, `price`, `capacity`, `status`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 0.00, 'VIP table', '40k+ above spend', 40000.00, 6, 'active', 'tables/a3da41A5j9VK0VNJu0kKjH5uzxAcXmjMDI3jHMcj.jpg', '2026-07-20 19:32:06', '2026-07-31 07:03:20'),
(4, 1, 6, 0.00, 'Standing Table', '20k+ above spend', 20000.00, 5, 'active', 'tables/8OLjKhS1PJoxw6uibLNN8TnAZYKEgvLoY4vpWNpX.webp', '2026-07-21 23:29:21', '2026-07-31 07:03:20'),
(5, 1, 4, 0.00, 'Normal Table', '60k+ above spend', 60000.00, 5, 'active', 'tables/v1riUt2uWUzKs1yJ0Z74ECrnxaelc2vzqNwbhUeC.png', '2026-07-21 23:51:05', '2026-07-31 07:03:20')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `price`=VALUES(`price`), `capacity`=VALUES(`capacity`), `image`=VALUES(`image`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 7. EVENTS
-- ------------------------------------------------------------
INSERT INTO `events` (`id`, `club_id`, `name`, `description`, `event_date`, `is_active`, `image`, `featured_image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bollywood Night ft. DJ Shadow', 'Experience the biggest Bollywood dance explosion in Gurugram featuring award-winning DJ Shadow.', '2026-08-01', 1, 'events/bollywood_night.jpg', 'events/bollywood_night_feat.jpg', NOW(), NOW()),
(2, 1, 'Neon EDM Carnival & Glow Party', 'Ultraviolet lasers, glow paint artists, immersive bass drops, and headline electronic dance music producers.', '2026-08-02', 1, 'events/neon_edm.jpg', 'events/neon_edm_feat.jpg', NOW(), NOW()),
(3, 1, 'Midnight Saturday Extravaganza', 'Our signature weekend residency with celebrity guest bartenders, acrobatic aerialists, and resident DJ sets.', '2026-08-08', 1, 'events/saturday_party.jpg', 'events/saturday_party_feat.jpg', NOW(), NOW()),
(4, 1, 'Techno Underground: Deep Minimal Sessions', 'A dedicated deep-tech journey with hypnotic lighting and German underground techno grooves.', '2026-08-14', 1, 'events/techno_underground.jpg', 'events/techno_underground_feat.jpg', NOW(), NOW()),
(5, 1, 'Independence Eve Gala 2026', 'Patriotic laser choreography, luxury VIP table packages, and high-energy commercial hits all night long.', '2026-08-15', 1, 'events/independence_gala.jpg', 'events/independence_gala_feat.jpg', NOW(), NOW()),
(6, 1, 'Ladies & Champagne Night', 'Complimentary welcome bubbles for ladies, artisanal tapas, and irresistible R&B and Hip-Hop jams.', '2026-08-20', 1, 'events/ladies_night.jpg', 'events/ladies_night_feat.jpg', NOW(), NOW()),
(7, 1, 'Retro 90s & 2000s Pop Blast', 'A nostalgic throwback celebration honoring the golden anthems of pop, disco, and rock classics.', '2026-08-22', 1, 'events/retro_night.jpg', 'events/retro_night_feat.jpg', NOW(), NOW()),
(8, 1, 'Sunburn Club Showcase - Live in Gurugram', 'Official festival club takeover with state-of-the-art CO2 cannons and international festival DJs.', '2026-08-29', 1, 'events/sunburn_showcase.jpg', 'events/sunburn_showcase_feat.jpg', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `event_date`=VALUES(`event_date`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 8. PROMO CODES
-- ------------------------------------------------------------
INSERT INTO `promo_codes` (`id`, `event_id`, `code`, `label`, `description`, `visibility`, `type`, `value`, `start_date`, `end_date`, `min_spend`, `max_discount`, `usage_limit`, `used_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'MIDNIGHT20', '20% Off Weekend Special', 'Get 20% discount on bookings over 20,000 across all tables.', 'public', 'percentage', 20.00, '2026-06-01', '2026-12-31', 20000.00, 8000.00, 500, 42, 1, NOW(), NOW()),
(2, NULL, 'FLAT5000', 'Flat 5,000 Off on VIP Table', 'Instant 5,000 cash discount on VIP & Normal table reservations.', 'public', 'fixed', 5000.00, '2026-07-01', '2026-10-31', 40000.00, 5000.00, 200, 19, 1, NOW(), NOW()),
(3, NULL, 'VIPNIGHT', '10,000 VIP Executive Discount', 'Exclusive promo for high-roller VIP table packages above 60,000.', 'private', 'fixed', 10000.00, '2026-05-01', '2026-12-31', 60000.00, 10000.00, 100, 15, 1, NOW(), NOW()),
(4, NULL, 'WEEKEND15', '15% Off Friday & Saturday', 'Weekend vibes with 15% off on all reservations.', 'public', 'percentage', 15.00, '2026-07-15', '2026-09-30', 20000.00, 5000.00, 300, 28, 1, NOW(), NOW()),
(5, 1, 'BOLLYWOOD50', 'Bollywood Gala Pass 3,000 Off', 'Valid exclusively for Bollywood Night with DJ Shadow.', 'public', 'fixed', 3000.00, '2026-07-20', '2026-08-05', 20000.00, 3000.00, 150, 35, 1, NOW(), NOW()),
(6, 2, 'EDM25', '25% Off Neon EDM Festival', 'Special early bird voucher for the Neon EDM Carnival.', 'public', 'percentage', 25.00, '2026-07-22', '2026-08-05', 20000.00, 10000.00, 100, 22, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `code`=VALUES(`code`), `value`=VALUES(`value`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 9. CLIENTS
-- ------------------------------------------------------------
INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `avatar`, `age`, `gender`, `password`, `role_id`, `google_id`, `fcm_token`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Aarav Singhania', 'aarav.singhania@gmail.com', '+919811001122', 'avatars/client_1.jpg', '28', 'Male', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 3, NULL, NULL, 1, NOW(), NOW()),
(2, 'Ananya Verma', 'ananya.verma@yahoo.com', '+919822113344', 'avatars/client_2.jpg', '25', 'Female', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 4, NULL, NULL, 1, NOW(), NOW()),
(3, 'Kabir Sethi', 'kabir.sethi@outlook.com', '+919833224455', 'avatars/client_3.jpg', '31', 'Male', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 3, NULL, NULL, 1, NOW(), NOW()),
(4, 'Riya Sen', 'riya.sen@gmail.com', '+919844335566', 'avatars/client_4.jpg', '27', 'Female', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 4, NULL, NULL, 1, NOW(), NOW()),
(5, 'Vikramaditya Rao', 'vikram.rao@enterprise.com', '+919855446677', 'avatars/client_5.jpg', '35', 'Male', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 3, NULL, NULL, 1, NOW(), NOW()),
(6, 'Isha Oberoi', 'isha.oberoi@gmail.com', '+919866557788', 'avatars/client_6.jpg', '24', 'Female', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 4, NULL, NULL, 1, NOW(), NOW()),
(7, 'Siddharth Mehra', 'siddharth.m@gmail.com', '+919877668899', 'avatars/client_7.jpg', '29', 'Male', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 4, NULL, NULL, 1, NOW(), NOW()),
(8, 'Pooja Bhattacharya', 'pooja.bhatt@gmail.com', '+919888779900', 'avatars/client_8.jpg', '26', 'Female', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 4, NULL, NULL, 1, NOW(), NOW()),
(9, 'Devendra Rajput', 'dev.rajput@gmail.com', '+919899880011', 'avatars/client_9.jpg', '33', 'Male', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 3, NULL, NULL, 1, NOW(), NOW()),
(10, 'Natasha Gulati', 'natasha.gulati@gmail.com', '+919811223355', 'avatars/client_10.jpg', '25', 'Female', '$2y$12$6PqfB6Gj0rVlTj5sJ3s2QeK9qY0Wk2gH4qK8eX8VdO5W987654321', 4, NULL, NULL, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `email`=VALUES(`email`), `phone`=VALUES(`phone`), `updated_at`=NOW();

-- ------------------------------------------------------------
-- 10. SETTINGS
-- ------------------------------------------------------------
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Mid Night Club Imperial Portal', 'string', NOW(), NOW()),
(2, 'contact_email', 'reservations@midnightclub.com', 'string', NOW(), NOW()),
(3, 'contact_phone', '+91 9711891515', 'string', NOW(), NOW()),
(4, 'currency_symbol', '₹', 'string', NOW(), NOW()),
(5, 'currency_code', 'INR', 'string', NOW(), NOW()),
(6, 'default_tax_rate', '5.00', 'decimal', NOW(), NOW()),
(7, 'opening_hours', '08:00 PM - 07:00 AM', 'string', NOW(), NOW()),
(8, 'cancellation_cutoff_hours', '6', 'integer', NOW(), NOW()),
(9, 'enable_fcm_notifications', '1', 'boolean', NOW(), NOW()),
(10, 'enable_sms_alerts', '1', 'boolean', NOW(), NOW())
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`), `updated_at`=NOW();

SET FOREIGN_KEY_CHECKS = 1;
