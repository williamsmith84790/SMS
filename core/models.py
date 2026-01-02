from django.db import models
from ckeditor.fields import RichTextField

class SliderImage(models.Model):
    title = models.CharField(max_length=200, blank=True)
    image = models.ImageField(upload_to='slider/')
    order = models.PositiveIntegerField(default=0)
    is_active = models.BooleanField(default=True)

    class Meta:
        ordering = ['order']

    def __str__(self):
        return self.title or f"Slider Image {self.id}"

class TickerItem(models.Model):
    content = models.CharField(max_length=500)
    created_at = models.DateTimeField(auto_now_add=True)
    is_active = models.BooleanField(default=True)

    def __str__(self):
        return self.content[:50]

class UrgentAlert(models.Model):
    title = models.CharField(max_length=200)
    message = models.TextField()
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return self.title

class Notice(models.Model):
    title = models.CharField(max_length=200)
    content = RichTextField()
    file = models.FileField(upload_to='notices/', blank=True, null=True)
    date_posted = models.DateField(auto_now_add=True)
    is_pinned = models.BooleanField(default=False)

    class Meta:
        ordering = ['-is_pinned', '-date_posted']

    def __str__(self):
        return self.title

class Page(models.Model):
    slug = models.SlugField(unique=True, help_text="URL identifier, e.g., 'about-vision'")
    title = models.CharField(max_length=200)
    content = RichTextField()

    def __str__(self):
        return self.title

class FacultyMember(models.Model):
    name = models.CharField(max_length=200)
    designation = models.CharField(max_length=200)
    department = models.CharField(max_length=200, blank=True)
    image = models.ImageField(upload_to='faculty/')
    bio = RichTextField(blank=True)
    order = models.PositiveIntegerField(default=0)

    class Meta:
        ordering = ['order', 'name']

    def __str__(self):
        return self.name

class GalleryAlbum(models.Model):
    title = models.CharField(max_length=200)
    description = models.TextField(blank=True)
    cover_image = models.ImageField(upload_to='gallery/covers/')
    created_at = models.DateField(auto_now_add=True)

    def __str__(self):
        return self.title

class GalleryImage(models.Model):
    album = models.ForeignKey(GalleryAlbum, related_name='images', on_delete=models.CASCADE)
    image = models.ImageField(upload_to='gallery/images/')
    caption = models.CharField(max_length=200, blank=True)

    def __str__(self):
        return f"Image in {self.album.title}"

class Alumni(models.Model):
    name = models.CharField(max_length=200)
    batch = models.CharField(max_length=50)
    image = models.ImageField(upload_to='alumni/')
    achievement = models.TextField(blank=True)

    class Meta:
        verbose_name_plural = "Alumni"

    def __str__(self):
        return self.name

class Video(models.Model):
    title = models.CharField(max_length=200)
    video_url = models.URLField(help_text="YouTube or Vimeo URL")
    description = models.TextField(blank=True)

    def __str__(self):
        return self.title
