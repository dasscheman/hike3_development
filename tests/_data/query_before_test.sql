-- INSERT IGNORE INTO `user` (`id`, `username`, `voornaam`, `achternaam`, `email`,
-- `password_hash`, `birthdate`, `created_at`, `create_user_ID`, `updated_at`,
-- `confirmed_at`, `update_user_ID`, `selected_event_ID`, `auth_key`) VALUES
-- (1, 'organisatie', 'organisatie', 'organisatie', 'organisatie@kiwi.run',
-- '$2y$10$CQms9gV5UPyUbRKQiCc2DuEVERZYD7ZwqZ/FblrIpkY/tcnDSlZUC', '1980-01-01',
-- 1510489576, NULL, 1510489576, 1510489976, NULL, NULL, 'test100key'),
-- (2, 'deelnemera', 'deelnemera', 'deelnemera', 'deelnemera@kiwi.run',
-- '$2y$10$CQms9gV5UPyUbRKQiCc2DuEVERZYD7ZwqZ/FblrIpkY/tcnDSlZUC', '1911-04-21',
-- 1510489576, NULL, 1510489576, 1510489976, NULL, NULL, 'test101key'),
-- (3, 'deelnemerb', 'deelnemerb', 'deelnemerb', 'deelnemerb@kiwi.run',
-- '$2y$10$CQms9gV5UPyUbRKQiCc2DuEVERZYD7ZwqZ/FblrIpkY/tcnDSlZUC', '1911-04-21',
-- 1510489576, NULL, 1510489576, 1510489976, NULL, NULL, 'test102key'),
-- (4, 'post', 'post', 'post', 'post@kiwi.run', '$2y$10$CQms9gV5UPyUbRKQiCc2DuEVERZYD7ZwqZ/FblrIpkY/tcnDSlZUC',
-- '1911-04-21', 1510489576, NULL, 1510489576, 1510489976, NULL, NULL, 'test103key');
TRUNCATE TABLE tbl_bonuspunten;
TRUNCATE TABLE tbl_qr_check;
TRUNCATE TABLE tbl_qr;
TRUNCATE TABLE tbl_post_passage;
TRUNCATE TABLE tbl_posten;
TRUNCATE TABLE tbl_time_trail_check;
TRUNCATE TABLE tbl_time_trail_item;
TRUNCATE TABLE tbl_time_trail;
TRUNCATE TABLE tbl_open_nood_envelop;
TRUNCATE TABLE tbl_open_vragen_antwoorden;
INSERT IGNORE INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('gebruiker', '1', NULL),
('gebruiker', '2', NULL),
('gebruiker', '3', NULL),
('gebruiker', '4', NULL);
