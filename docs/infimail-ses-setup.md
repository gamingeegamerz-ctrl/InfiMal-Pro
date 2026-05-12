# Infimail Amazon SES + SNS Setup

## AWS CLI setup

Replace `REGION`, `ACCOUNT_ID`, and `https://YOUR_DOMAIN/api/ses-webhook` with your values.

```bash
aws sns create-topic --name infimail-events
aws sns subscribe \
  --topic-arn arn:aws:sns:REGION:ACCOUNT_ID:infimail-events \
  --protocol https \
  --notification-endpoint https://YOUR_DOMAIN/api/ses-webhook
aws ses create-configuration-set --configuration-set Name=infimail-tracker
aws ses create-configuration-set-event-destination \
  --configuration-set-name infimail-tracker \
  --event-destination 'Name=infimail-sns-events,Enabled=true,MatchingEventTypes=send,delivery,open,click,bounce,complaint,SNSDestination={TopicARN=arn:aws:sns:REGION:ACCOUNT_ID:infimail-events}'
```

You can also run the Laravel helper command:

```bash
php artisan infimail:setup-ses https://YOUR_DOMAIN/api/ses-webhook
```

## Local webhook testing with ngrok

1. Start Laravel locally:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

2. Expose it with ngrok:

```bash
ngrok http 8000
```

3. Copy the HTTPS forwarding URL, for example `https://abc123.ngrok-free.app`.
4. Create the SNS HTTPS subscription using `https://abc123.ngrok-free.app/api/ses-webhook`.
5. Keep the Laravel server and ngrok running. SNS will send a `SubscriptionConfirmation` request, and Infimail's webhook confirms it automatically.
6. Send a test email through the API or queued job and watch `storage/logs/laravel.log` plus the analytics dashboard.

## Scheduler

Run the Laravel scheduler every minute in production:

```bash
* * * * * cd /path/to/infimail && php artisan schedule:run >> /dev/null 2>&1
```

The scheduler updates warm-up limits hourly, resets `today_sent` at midnight, and monitors bounce/complaint rates hourly.
