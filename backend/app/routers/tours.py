from fastapi import APIRouter, Depends, HTTPException, Query, UploadFile, File
from sqlalchemy.orm import Session
from typing import List, Optional
import shutil
import os
from pathlib import Path
from ..database import get_db
from ..models.tour import Tour, Category
from ..routers.auth import get_current_user
from pydantic import BaseModel
from datetime import datetime

router = APIRouter()

class TourBase(BaseModel):
    title: str
    description: str
    price: float
    duration: str
    location: str
    images: List[str]
    includes: List[str] = []
    itinerary: List[str] = []
    featured: bool = False
    category_id: int

class TourCreate(TourBase):
    pass

class TourUpdate(TourBase):
    pass

class TourResponse(TourBase):
    id: int
    slug: str
    created_at: datetime
    updated_at: Optional[datetime]
    is_active: bool
    category_id: int

    class Config:
        from_attributes = True

def create_slug(title: str) -> str:
    return title.lower().replace(" ", "-")

# Uploads dizinini oluştur
UPLOAD_DIR = Path("uploads/tours")
UPLOAD_DIR.mkdir(parents=True, exist_ok=True)

@router.get("/tours", response_model=List[TourResponse])
async def get_tours(
    skip: int = 0,
    limit: int = 10,
    category: Optional[str] = None,
    featured: Optional[bool] = None,
    db: Session = Depends(get_db)
):
    query = db.query(Tour).filter(Tour.is_active == True)
    
    if category:
        query = query.join(Category).filter(Category.slug == category)
    if featured is not None:
        query = query.filter(Tour.featured == featured)
    
    tours = query.offset(skip).limit(limit).all()
    return tours or []

@router.get("/tours/search", response_model=List[TourResponse])
async def search_tours(
    q: str = Query(None, min_length=2),
    category: Optional[str] = None,
    db: Session = Depends(get_db)
):
    query = db.query(Tour).filter(Tour.is_active == True)
    
    if q:
        search = f"%{q}%"
        query = query.filter(
            (Tour.title.ilike(search)) |
            (Tour.description.ilike(search)) |
            (Tour.location.ilike(search))
        )
    
    if category:
        query = query.join(Category).filter(Category.slug == category)
    
    tours = query.all()
    return tours or []

@router.get("/tours/featured", response_model=List[TourResponse])
async def get_featured_tours(db: Session = Depends(get_db)):
    tours = db.query(Tour).filter(Tour.featured == True, Tour.is_active == True).all()
    return tours or []

@router.get("/tours/category/{category_slug}", response_model=List[TourResponse])
async def get_tours_by_category(category_slug: str, db: Session = Depends(get_db)):
    tours = db.query(Tour)\
        .join(Category)\
        .filter(Category.slug == category_slug, Tour.is_active == True)\
        .all()
    return tours or []

@router.get("/tours/{tour_id}", response_model=TourResponse)
async def get_tour(tour_id: int, db: Session = Depends(get_db)):
    tour = db.query(Tour).filter(Tour.id == tour_id, Tour.is_active == True).first()
    if not tour:
        raise HTTPException(status_code=404, detail="Tour not found")
    return tour

@router.post("/tours", response_model=TourResponse)
async def create_tour(
    tour: TourCreate,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    db_tour = Tour(
        **tour.dict(),
        slug=create_slug(tour.title)
    )
    db.add(db_tour)
    db.commit()
    db.refresh(db_tour)
    return db_tour

@router.put("/tours/{tour_id}", response_model=TourResponse)
async def update_tour(
    tour_id: int,
    tour: TourUpdate,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    db_tour = db.query(Tour).filter(Tour.id == tour_id).first()
    if not db_tour:
        raise HTTPException(status_code=404, detail="Tour not found")
    
    update_data = tour.dict(exclude_unset=True)
    if "title" in update_data:
        update_data["slug"] = create_slug(update_data["title"])
    
    for key, value in update_data.items():
        setattr(db_tour, key, value)
    
    db.commit()
    db.refresh(db_tour)
    return db_tour

@router.delete("/tours/{tour_id}")
async def delete_tour(
    tour_id: int,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    db_tour = db.query(Tour).filter(Tour.id == tour_id).first()
    if not db_tour:
        raise HTTPException(status_code=404, detail="Tour not found")
    
    db_tour.is_active = False
    db.commit()
    return {"message": "Tour deleted successfully"}

# Yeni dosya yükleme endpoint'i
@router.post("/tours/upload")
async def upload_tour_image(
    file: UploadFile = File(...),
    current_user: dict = Depends(get_current_user)
):
    try:
        # Dosya uzantısını kontrol et
        file_extension = file.filename.split('.')[-1].lower()
        if file_extension not in ['jpg', 'jpeg', 'png', 'gif']:
            raise HTTPException(status_code=400, detail="Only image files (jpg, jpeg, png, gif) are allowed")
        
        # Benzersiz dosya adı oluştur
        filename = f"{datetime.now().timestamp()}_{file.filename}"
        file_path = UPLOAD_DIR / filename
        
        # Dosyayı kaydet
        with file_path.open("wb") as buffer:
            shutil.copyfileobj(file.file, buffer)
        
        # Dosya URL'ini döndür
        return {"url": f"/uploads/tours/{filename}"}
    
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))