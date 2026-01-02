from django.urls import path
from . import views

urlpatterns = [
    path('', views.result_search, name='result_search'),
]
