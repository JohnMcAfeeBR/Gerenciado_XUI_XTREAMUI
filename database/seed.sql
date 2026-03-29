USE iptv_platform;

INSERT INTO users (name, email, password_hash, role)
VALUES ('Administrador', 'admin@iptv.local', '$2y$10$hnJ5JgBMYaMjaAHw75QGIecIru2VUdnq9UYlTiNsq2lv9ZeIvM0QK', 'admin')
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO plans (name, price, duration_days, provider_plan_code, is_active)
VALUES
  ('Plano Mensal', 29.90, 30, 'PKG_MENSAL', 1),
  ('Plano Trimestral', 79.90, 90, 'PKG_TRIMESTRAL', 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);
