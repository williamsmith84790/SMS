from django.test import TestCase, Client
from django.urls import reverse
from .models import Page, Notice

class CoreTests(TestCase):
    def setUp(self):
        self.client = Client()
        self.page = Page.objects.create(title="About Us", slug="about-us", content="About page content")
        self.notice = Notice.objects.create(title="Test Notice", content="Test content")

    def test_home_page(self):
        response = self.client.get(reverse('home'))
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "EduPortal")
        self.assertContains(response, "Test Notice")

    def test_page_detail(self):
        response = self.client.get(reverse('page_detail', args=['about-us']))
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "About Us")
