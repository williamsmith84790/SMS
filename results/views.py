from django.shortcuts import render, get_object_or_404
from .models import StudentResult

def result_search(request):
    result = None
    error = None
    if request.method == 'GET' and 'roll_number' in request.GET and 'session' in request.GET:
        roll_number = request.GET.get('roll_number')
        session = request.GET.get('session')
        try:
            result = StudentResult.objects.get(roll_number=roll_number, session=session)
        except StudentResult.DoesNotExist:
            error = "Result not found for the given Roll Number and Session."

    return render(request, 'results/search.html', {'result': result, 'error': error})
