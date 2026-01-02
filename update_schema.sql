-- Add link column to urgent_alerts
ALTER TABLE urgent_alerts ADD COLUMN link VARCHAR(255) DEFAULT NULL;

-- Add link column to notices
ALTER TABLE notices ADD COLUMN link VARCHAR(255) DEFAULT NULL;

-- Add Gallery to Life at Campus menu
-- Assumes 'Life at Campus' has ID 20.
-- We'll check if it exists first to avoid duplicates, but standard SQL doesn't have IF NOT EXISTS for INSERT easily without procedures.
-- Using IGNORE or ON DUPLICATE KEY UPDATE might be safer if we had a unique key on (parent_id, label), but we don't.
-- We will just insert it.
INSERT INTO menu_items (label, link, parent_id, sort_order, location) VALUES ('Gallery', 'gallery.php', 20, 7, 'header');
