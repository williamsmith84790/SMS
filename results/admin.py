from django.contrib import admin
from django.shortcuts import render, redirect
from django.urls import path
from django.contrib import messages
from .models import StudentResult
import pandas as pd
from django import forms

class CsvImportForm(forms.Form):
    csv_file = forms.FileField()

@admin.register(StudentResult)
class StudentResultAdmin(admin.ModelAdmin):
    list_display = ('roll_number', 'student_name', 'session', 'total_marks', 'obtained_marks')
    search_fields = ('roll_number', 'student_name')
    list_filter = ('session',)
    change_list_template = "admin/results_changelist.html"

    def get_urls(self):
        urls = super().get_urls()
        my_urls = [
            path('import-csv/', self.import_csv),
        ]
        return my_urls + urls

    def import_csv(self, request):
        if request.method == "POST":
            csv_file = request.FILES["csv_file"]

            if not csv_file.name.endswith(('.csv', '.xlsx', '.xls')):
                messages.error(request, "Please upload a CSV or Excel file.")
                return redirect("..")

            try:
                if csv_file.name.endswith('.csv'):
                    df = pd.read_csv(csv_file)
                else:
                    df = pd.read_excel(csv_file)

                # Expected columns: roll_number, session, student_name, father_name, total_marks, obtained_marks, grade
                for index, row in df.iterrows():
                    StudentResult.objects.update_or_create(
                        roll_number=str(row['roll_number']),
                        session=str(row['session']),
                        defaults={
                            'student_name': row['student_name'],
                            'father_name': row.get('father_name', ''),
                            'total_marks': row.get('total_marks', ''),
                            'obtained_marks': row.get('obtained_marks', ''),
                            'grade': row.get('grade', ''),
                            # Add other fields mapping if necessary
                        }
                    )
                messages.success(request, "Results imported successfully")
                return redirect("..")
            except Exception as e:
                messages.error(request, f"Error importing file: {e}")
                return redirect("..")

        form = CsvImportForm()
        payload = {"form": form}
        return render(
            request, "admin/csv_form.html", payload
        )
