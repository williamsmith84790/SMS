from django.urls import path
from . import views

urlpatterns = [
    path('', views.home, name='home'),
    path('page/<slug:slug>/', views.page_detail, name='page_detail'),
    path('faculty/', views.faculty_list, name='faculty'),
    path('gallery/', views.gallery_list, name='gallery'),
    path('gallery/<int:pk>/', views.gallery_detail, name='gallery_detail'),
    path('alumni/', views.alumni_list, name='alumni'),
]
