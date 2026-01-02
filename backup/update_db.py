import pymysql
import os

# Connect to database using env vars or defaults (matching config.php logic)
db = pymysql.connect(
    host=os.environ.get('DB_HOST', 'localhost'),
    user=os.environ.get('DB_USER', 'root'),
    password=os.environ.get('DB_PASSWORD', ''),
    database=os.environ.get('DB_NAME', 'edu_portal'),
    port=int(os.environ.get('DB_PORT', 3306)),
    cursorclass=pymysql.cursors.DictCursor
)

try:
    with db.cursor() as cursor:
        # 1. Add link to ticker_items
        try:
            cursor.execute("ALTER TABLE ticker_items ADD COLUMN link VARCHAR(255) DEFAULT NULL")
            print("Added link to ticker_items")
        except Exception as e:
            print(f"ticker_items update skipped: {e}")

        # 2. Create events table
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS events (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                date DATE NOT NULL,
                time VARCHAR(50),
                location VARCHAR(255),
                description TEXT,
                image VARCHAR(255),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        """)
        print("Created events table")

        # 3. Modify gallery_images
        try:
            cursor.execute("ALTER TABLE gallery_images ADD COLUMN media_type ENUM('image', 'video') DEFAULT 'image'")
            print("Added media_type to gallery_images")
        except Exception as e:
            print(f"gallery_images media_type update skipped: {e}")

        try:
            cursor.execute("ALTER TABLE gallery_images ADD COLUMN video_embed TEXT DEFAULT NULL")
            print("Added video_embed to gallery_images")
        except Exception as e:
            print(f"gallery_images video_embed update skipped: {e}")

    db.commit()
    print("Database updates committed.")
finally:
    db.close()
