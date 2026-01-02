from django.db import models

class StudentResult(models.Model):
    roll_number = models.CharField(max_length=50)
    session = models.CharField(max_length=50)
    student_name = models.CharField(max_length=200)
    father_name = models.CharField(max_length=200, blank=True)
    # Storing result data as JSON for flexibility, or you could have fixed fields
    result_data = models.TextField(help_text="JSON format or simple text marks", blank=True)
    total_marks = models.CharField(max_length=20, blank=True)
    obtained_marks = models.CharField(max_length=20, blank=True)
    grade = models.CharField(max_length=10, blank=True)
    result_file = models.FileField(upload_to='results/', blank=True, null=True, help_text="Upload individual result card PDF if available")

    class Meta:
        unique_together = ('roll_number', 'session')

    def __str__(self):
        return f"{self.student_name} ({self.roll_number})"
