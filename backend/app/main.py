from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from .database import engine, Base
from .routers import tours, categories, contacts, auth, admin

# Create database tables
Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="Coco Travel API",
    description="API for Coco Travel website",
    version="1.0.0"
)

# CORS configuration
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Allow all origins in development
    allow_credentials=False,  # Set to False when using allow_origins=["*"]
    allow_methods=["*"],
    allow_headers=["*"],
)

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