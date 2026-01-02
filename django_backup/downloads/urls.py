from django.urls import path
from . import views

urlpatterns = [
    path('', views.download_center, name='download_center'),
]
