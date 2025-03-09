from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from sqlalchemy import func
from typing import List
from ..database import get_db
from ..models.tour import Tour, Category
from ..models.contact import Message
from ..models.user import User
from .auth import get_current_user
from pydantic import BaseModel
from datetime import datetime, timedelta

router = APIRouter()

class DashboardStats(BaseModel):
    totalTours: int
    activeBookings: int
    unreadMessages: int
    totalCustomers: int

class Activity(BaseModel):
    id: int
    timestamp: datetime
    description: str
    type: str

@router.get("/admin/dashboard/stats", response_model=DashboardStats)
async def get_dashboard_stats(
    db: Session = Depends(get_db),
    current_user: User = Depends(get_current_user)
):
    if not current_user.is_superuser:
        raise HTTPException(status_code=403, detail="Not authorized")
    
    total_tours = db.query(func.count(Tour.id)).filter(Tour.is_active == True).scalar() or 0
    unread_messages = db.query(func.count(Message.id)).filter(Message.is_read == False).scalar() or 0
    
    # Placeholder values for now
    active_bookings = 0
    total_customers = 0
    
    return DashboardStats(
        totalTours=total_tours,
        activeBookings=active_bookings,
        unreadMessages=unread_messages,
        totalCustomers=total_customers
    )

@router.get("/admin/dashboard/activities", response_model=List[Activity])
async def get_recent_activities(
    db: Session = Depends(get_db),
    current_user: User = Depends(get_current_user)
):
    if not current_user.is_superuser:
        raise HTTPException(status_code=403, detail="Not authorized")
    
    # For now, return empty list as we haven't implemented activity tracking yet
    return []