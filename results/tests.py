from django.test import TestCase, Client
from django.urls import reverse
from .models import StudentResult

class ResultTests(TestCase):
    def setUp(self):
        self.client = Client()
        self.result = StudentResult.objects.create(
            roll_number="123",
            session="2023-2024",
            student_name="John Doe",
            total_marks="1100",
            obtained_marks="900",
            grade="A"
        )

    def test_result_search_view(self):
        response = self.client.get(reverse('result_search'))
        self.assertEqual(response.status_code, 200)

    def test_result_search_query(self):
        response = self.client.get(reverse('result_search'), {'roll_number': '123', 'session': '2023-2024'})
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "John Doe")
        self.assertContains(response, "900")

    def test_result_not_found(self):
        response = self.client.get(reverse('result_search'), {'roll_number': '999', 'session': '2023-2024'})
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "Result not found")
