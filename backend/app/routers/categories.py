from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from typing import List, Optional
from ..database import get_db
from ..models.tour import Category
from ..routers.auth import get_current_user
from pydantic import BaseModel
from datetime import datetime

router = APIRouter()

class CategoryBase(BaseModel):
    name: str
    description: Optional[str] = None

class CategoryCreate(CategoryBase):
    pass

class CategoryUpdate(CategoryBase):
    pass

class CategoryResponse(CategoryBase):
    id: int
    slug: str
    is_active: bool
    created_at: datetime
    updated_at: Optional[datetime]

    class Config:
        from_attributes = True

def create_slug(name: str) -> str:
    return name.lower().replace(" ", "-")

@router.get("/categories", response_model=List[CategoryResponse])
async def get_categories(
    skip: int = 0,
    limit: int = 100,
    active_only: bool = True,
    db: Session = Depends(get_db)
):
    query = db.query(Category)
    if active_only:
        query = query.filter(Category.is_active == True)
    categories = query.offset(skip).limit(limit).all()
    return categories or []

@router.get("/categories/{category_id}", response_model=CategoryResponse)
async def get_category(category_id: int, db: Session = Depends(get_db)):
    category = db.query(Category).filter(Category.id == category_id).first()
    if not category:
        raise HTTPException(status_code=404, detail="Category not found")
    return category

@router.post("/categories", response_model=CategoryResponse)
async def create_category(
    category: CategoryCreate,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    db_category = Category(
        **category.dict(),
        slug=create_slug(category.name)
    )
    db.add(db_category)
    db.commit()
    db.refresh(db_category)
    return db_category

@router.put("/categories/{category_id}", response_model=CategoryResponse)
async def update_category(
    category_id: int,
    category: CategoryUpdate,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    db_category = db.query(Category).filter(Category.id == category_id).first()
    if not db_category:
        raise HTTPException(status_code=404, detail="Category not found")
    
    update_data = category.dict(exclude_unset=True)
    if "name" in update_data:
        update_data["slug"] = create_slug(update_data["name"])
    
    for key, value in update_data.items():
        setattr(db_category, key, value)
    
    db.commit()
    db.refresh(db_category)
    return db_category

@router.delete("/categories/{category_id}")
async def delete_category(
    category_id: int,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    db_category = db.query(Category).filter(Category.id == category_id).first()
    if not db_category:
        raise HTTPException(status_code=404, detail="Category not found")
    
    # Soft delete
    db_category.is_active = False
    db.commit()
    return {"message": "Category deleted successfully"}