from django.db import models

class DocumentCategory(models.Model):
    name = models.CharField(max_length=100)

    class Meta:
        verbose_name_plural = "Document Categories"

    def __str__(self):
        return self.name

class Document(models.Model):
    title = models.CharField(max_length=200)
    category = models.ForeignKey(DocumentCategory, related_name='documents', on_delete=models.CASCADE)
    file = models.FileField(upload_to='downloads/')
    uploaded_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return self.title
