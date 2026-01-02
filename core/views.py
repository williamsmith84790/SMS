from django.shortcuts import render, get_object_or_404
from .models import SliderImage, Notice, Page, FacultyMember, GalleryAlbum, Alumni, Video

def home(request):
    slider_images = SliderImage.objects.filter(is_active=True)
    notices = Notice.objects.all()[:5]
    return render(request, 'core/home.html', {
        'slider_images': slider_images,
        'notices': notices,
    })

def page_detail(request, slug):
    page = get_object_or_404(Page, slug=slug)
    return render(request, 'core/page.html', {'page': page})

def faculty_list(request):
    faculty = FacultyMember.objects.all()
    return render(request, 'core/faculty.html', {'faculty': faculty})

def gallery_list(request):
    albums = GalleryAlbum.objects.all()
    videos = Video.objects.all()
    return render(request, 'core/gallery.html', {'albums': albums, 'videos': videos})

def alumni_list(request):
    alumni = Alumni.objects.all()
    return render(request, 'core/alumni.html', {'alumni': alumni})

def gallery_detail(request, pk):
    album = get_object_or_404(GalleryAlbum, pk=pk)
    return render(request, 'core/gallery_detail.html', {'album': album})
