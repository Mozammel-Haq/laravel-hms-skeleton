# Class 76: Real-time Notifications

## Introduction
When a Lab Result is ready, the Doctor should know instantly. When an Appointment is booked, the Receptionist should see it pop up.

## 1. Setup
We use **Laravel Reverb** (first-party WebSocket server) or Pusher.

```bash
php artisan install:broadcasting
```

## 2. Creating an Event
`LabResultApproved`.

```php
class LabResultApproved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $labRequest;

    public function __construct(LabRequest $labRequest)
    {
        $this->labRequest = $labRequest;
    }

    public function broadcastOn()
    {
        // Broadcast to the clinic channel
        return new PrivateChannel('clinic.' . $this->labRequest->clinic_id);
    }
}
```

## 3. Frontend (Vue/JS)
Listen for the event.

```javascript
Echo.private(`clinic.${clinicId}`)
    .listen('LabResultApproved', (e) => {
        alert(`Lab Result Ready for Patient #${e.labRequest.patient_id}`);
        // Refresh the dashboard list
    });
```

## Summary
WebSockets make the app feel alive and responsive.
