INSERT INTO `user_type` (`id`, `name`)
VALUES
	(1, 'Super Admin'),
	(2, 'Admin'),
	(3, 'User'),
	(4, 'Editor');


-- Default demo admin account. Username: admin / Password: password_0
-- Change this password immediately after your first login.
INSERT INTO `user` (`id`, `user_type_id`, `username`, `password`, `email`, `auth_key`, `password_reset_token`, `status`,`created_at`, `updated_at`)
VALUES
	(1, 1, 'admin', '$2y$13$KOlIR4tD0SK7dsj9ZKrW8eRWdZl8V1TcdXm2WNKhJZUiD5hdUsXUG', 'admin@example.com', NULL, NULL, 10, NOW(), NOW());

INSERT INTO `task_type` (`id`, `name`)
VALUES
	(1, 'Text'),
	(2, 'AR Target'),
	(3, 'Speech-to-text'),
	(4, 'Web URL'),
	(5, '360 Video'),
	(6, '360 Image');
