from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.staticfiles import StaticFiles
from pathlib import Path
from .database import engine, Base
from .routers import tours, categories, contacts, auth, admin

# Create database tables
Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="Coco Travel API",
    description="API for Coco Travel website",
    version="1.0.0"
)

# CORS configuration - Development and Production
origins = [
    "http://localhost",
    "http://localhost:5173",  # Vite dev server
    "http://localhost:80",    # Production
    "http://127.0.0.1:5173",
    "http://frontend:5173",   # Docker container name
    "http://frontend:80",     # Docker container name in production
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Mount uploads directory for static file serving
uploads_path = Path("uploads")
uploads_path.mkdir(parents=True, exist_ok=True)
app.mount("/uploads", StaticFiles(directory="uploads"), name="uploads")

# Include routers
app.include_router(auth.router, prefix="/api", tags=["auth"])
app.include_router(tours.router, prefix="/api", tags=["tours"])
app.include_router(categories.router, prefix="/api", tags=["categories"])
app.include_router(contacts.router, prefix="/api", tags=["contacts"])
app.include_router(admin.router, prefix="/api", tags=["admin"])

@app.get("/")
async def root():
    return {
        "message": "Welcome to Coco Travel API",
        "docs": "/docs",
        "version": "1.0.0"
    }