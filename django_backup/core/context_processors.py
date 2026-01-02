from .models import TickerItem, UrgentAlert

def global_context(request):
    return {
        'ticker_items': TickerItem.objects.filter(is_active=True).order_by('-created_at'),
        'active_alert': UrgentAlert.objects.filter(is_active=True).first(),
    }
