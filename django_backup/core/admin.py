from django.contrib import admin
from .models import SliderImage, TickerItem, UrgentAlert, Notice, Page, FacultyMember, GalleryAlbum, GalleryImage, Alumni, Video

class GalleryImageInline(admin.TabularInline):
    model = GalleryImage
    extra = 1

@admin.register(GalleryAlbum)
class GalleryAlbumAdmin(admin.ModelAdmin):
    inlines = [GalleryImageInline]
    list_display = ('title', 'created_at')

@admin.register(Page)
class PageAdmin(admin.ModelAdmin):
    list_display = ('title', 'slug')
    prepopulated_fields = {'slug': ('title',)}

admin.site.register(SliderImage)
admin.site.register(TickerItem)
admin.site.register(UrgentAlert)
admin.site.register(Notice)
admin.site.register(FacultyMember)
admin.site.register(Alumni)
admin.site.register(Video)
