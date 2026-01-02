from django.shortcuts import render
from .models import DocumentCategory

def download_center(request):
    categories = DocumentCategory.objects.prefetch_related('documents').all()
    return render(request, 'downloads/index.html', {'categories': categories})
