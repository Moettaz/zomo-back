<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Google\Client as GoogleClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class Notifications extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'service_id',
        'type',
        'message',
        'status',
        'date_notification'
    ];
    public static function toSingleDevice($token = null, $titre = null, $body = null, $click_action = null, $data = null, $type = null)
    {
        // Get access token
        $accessToken = self::getAccessToken();
        if (!$accessToken) {
            Log::error('Failed to obtain access token.');
            return [
                'success' => false,
                'message' => 'Failed to obtain access token',
                'code' => 500
            ];
        }

        // Prepare notification payload
        $notification = null;

        $notification = [
            'title' => $titre,
            'body' => $body,
        ];


        // Prepare data payload
        $dataPayload = [];
        if (!is_null($data)) {
            foreach ($data as $k => $v) {
                $dataPayload[$k] = (string)$v; // Convert all values to strings as FCM requires
            }
        }

        // Add required fields to data payload
        $dataPayload['title'] = $titre;
        $dataPayload['body'] = $body;
        if ($type) {
            $dataPayload['type'] = $type;
        }

        // Prepare the FCM payload
        $fields = [
            'message' => [
                'token' => $token,
                'notification' => $notification,
                'data' => $dataPayload,
            ],
        ];

        // Log the payload
        Log::info('Payload: ' . json_encode($fields));

        // Create a Guzzle client
        $client = new Client();

        try {
            // Send the request
            $response = $client->post('https://fcm.googleapis.com/v1/projects/zomo-b0465/messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $fields,
            ]);

            // Log the response
            Log::info('FCM Response: ' . $response->getBody());

            // Check response status
            return [
                'success' => $response->getStatusCode() === 200,
                'message' => 'Notification sent successfully',
                'code' => $response->getStatusCode()
            ];
        } catch (RequestException $e) {
            // Handle request exception
            Log::error('Guzzle Request Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage(),
                'code' => 500
            ];
        }
    }
    private static function getAccessToken()
    {
        $client = new GoogleClient();
        $client->setAuthConfig(storage_path('app/zomo-b0465-firebase-adminsdk-fbsvc-869c7879e5.json'));
        $client->setScopes(['https://www.googleapis.com/auth/firebase.messaging', 'https://www.googleapis.com/auth/cloud-platform']);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithAssertion();
        }

        $accessToken = $client->getAccessToken();
        if (isset($accessToken['access_token'])) {
            return $accessToken['access_token'];
        }

        Log::error('Failed to fetch access token from Firebase');
        return null;
    }
}
