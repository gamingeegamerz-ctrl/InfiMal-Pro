<?php

namespace App\Console\Commands;

use Aws\Ses\SesClient;
use Aws\Sns\SnsClient;
use Illuminate\Console\Command;

class CreateSesInfrastructure extends Command
{
    protected $signature = 'infimail:setup-ses {webhook-url : Public HTTPS URL ending in /api/ses-webhook}';
    protected $description = 'Create the Infimail SES configuration set, SNS topic, subscription, and SES event destination.';

    public function handle(): int
    {
        $region = config('services.ses.region', env('AWS_DEFAULT_REGION', 'us-east-1'));
        $credentials = ['key' => env('AWS_ACCESS_KEY_ID'), 'secret' => env('AWS_SECRET_ACCESS_KEY')];
        $ses = new SesClient(['version' => '2010-12-01', 'region' => $region, 'credentials' => $credentials]);
        $sns = new SnsClient(['version' => '2010-03-31', 'region' => $region, 'credentials' => $credentials]);

        $topicArn = $sns->createTopic(['Name' => 'infimail-events'])->get('TopicArn');
        $this->info('SNS topic: '.$topicArn);

        $sns->subscribe([
            'TopicArn' => $topicArn,
            'Protocol' => 'https',
            'Endpoint' => $this->argument('webhook-url'),
        ]);
        $this->info('SNS HTTPS subscription requested. Confirm it by allowing the webhook to receive the SNS confirmation request.');

        try {
            $ses->createConfigurationSet(['ConfigurationSet' => ['Name' => 'infimail-tracker']]);
            $this->info('SES configuration set created: infimail-tracker');
        } catch (\Aws\Exception\AwsException $e) {
            if ($e->getAwsErrorCode() !== 'ConfigurationSetAlreadyExists') {
                throw $e;
            }
            $this->warn('SES configuration set already exists: infimail-tracker');
        }

        $ses->createConfigurationSetEventDestination([
            'ConfigurationSetName' => 'infimail-tracker',
            'EventDestination' => [
                'Name' => 'infimail-sns-events',
                'Enabled' => true,
                'MatchingEventTypes' => ['send', 'delivery', 'open', 'click', 'bounce', 'complaint'],
                'SNSDestination' => ['TopicARN' => $topicArn],
            ],
        ]);

        $this->info('SES event destination attached.');
        $this->line('Equivalent AWS CLI:');
        $this->line('aws sns create-topic --name infimail-events');
        $this->line('aws sns subscribe --topic-arn '.$topicArn.' --protocol https --notification-endpoint '.$this->argument('webhook-url'));
        $this->line('aws ses create-configuration-set --configuration-set Name=infimail-tracker');
        $this->line("aws ses create-configuration-set-event-destination --configuration-set-name infimail-tracker --event-destination 'Name=infimail-sns-events,Enabled=true,MatchingEventTypes=send,delivery,open,click,bounce,complaint,SNSDestination={TopicARN={$topicArn}}'");

        return self::SUCCESS;
    }
}
