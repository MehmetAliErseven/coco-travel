import os
import sys
from pathlib import Path

# Add the project root directory to Python path
backend_dir = Path(__file__).resolve().parent.parent
sys.path.append(str(backend_dir))

from sqlalchemy.orm import Session
from app.database import SessionLocal
from app.models.user import User
from app.routers.auth import get_password_hash

def create_admin_user():
    db = SessionLocal()
    try:
        # Check if admin already exists
        admin = db.query(User).filter(User.username == os.getenv("ADMIN_USERNAME", "admin")).first()
        if admin:
            print("Admin user already exists!")
            return

        # Create admin user
        admin = User(
            email=os.getenv("ADMIN_EMAIL", "admin@cocotravel.com"),
            username=os.getenv("ADMIN_USERNAME", "admin"),
            hashed_password=get_password_hash(os.getenv("ADMIN_PASSWORD", "admin123")),
            is_active=True,
            is_superuser=True
        )
        db.add(admin)
        db.commit()
        print("Admin user created successfully!")
    
    except Exception as e:
        print(f"Error creating admin user: {e}")
    finally:
        db.close()

if __name__ == "__main__":
    create_admin_user()