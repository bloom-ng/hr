<?php

use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

defined('BASEPATH') or exit('No direct script access allowed');

class Notify extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->isLoggedIn();
    }

    public function index()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/notify');
        $this->load->view('admin/footer');
    }

    public function send_social_notification()
    {
        $platform = $this->input->post('platform');
        $link = $this->input->post('link') ?? null;
        $message = $this->input->post('message') ?? null;

        try {
            $this->send_push_notification([
                'type' => 'social',
                'platform' => $platform,
                'link' => $link,
                'message' => $message
            ]);

            $this->session->set_flashdata('success', 'Notification sent successfully');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Failed to send notification: ' . $e->getMessage());
        }

        redirect('/notify');
    }

    public function send_general_notification()
    {
        $title = $this->input->post('title');
        $message = $this->input->post('notification_message');

        try {
            // Here you would integrate with your mobile app push notification service
            $this->send_push_notification([
                'type' => 'general',
                'title' => $title,
                'message' => $message
            ]);

            $this->session->set_flashdata('success', 'General notification sent successfully');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Failed to send general notification: ' . $e->getMessage());
        }

        redirect('/notify');
    }

    public function send_meeting_notification()
    {
        $title = $this->input->post('title');
        $time = $this->input->post('time');
        $link = $this->input->post('link');

        try {
            // Here you would integrate with your mobile app push notification service
            $this->send_push_notification([
                'type' => 'meeting',
                'title' => $title,
                'time' => $time,
                'link' => $link
            ]);

            $this->session->set_flashdata('success', 'Meeting notification sent successfully');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Failed to send meeting notification: ' . $e->getMessage());
        }

        redirect('/notify');
    }

    protected function send_push_notification($data)
    {
        $defaultRecipients = $this->PushToken_model->getAllActiveTokens();
        $receivers = array_column($defaultRecipients, 'token');

        // print_r($receivers);
        // exit;

        if ($data['type'] == 'social') {
            switch ($data['platform']) {
                case 'instagram':
                    $message = (new ExpoMessage([
                        'title' => 'Bloom Digital',
                        'body' => 'A social media post was made',
                    ]))
                        ->setTitle('An Instagram post was made')
                        ->setBody($data['message'])
                        ->setData(['page' => 'bloom://Instagram/', 'url' => $data['link']])
                        ->setChannelId('default')
                        ->setBadge(0)
                        ->playSound();

                    (new Expo)->send($message)->to($receivers)->push();

                    break;

                case 'twitter':
                    $message = (new ExpoMessage([
                        'title' => 'Bloom Digital',
                        'body' => 'A social media post was made',
                    ]))
                        ->setTitle('A Twitter post was made')
                        ->setBody($data['message'])
                        ->setData(['page' => 'bloom://Twitter/', 'url' => $data['link']])
                        ->setChannelId('default')
                        ->setBadge(0)
                        ->playSound();

                    (new Expo)->send($message)->to($receivers)->push();

                    break;

                case 'youtube':
                    $message = (new ExpoMessage([
                        'title' => 'Bloom Digital',
                        'body' => 'A social media post was made',
                    ]))
                        ->setTitle('A Youtube video was uploaded')
                        ->setBody($data['message'])
                        ->setData(['page' => 'bloom://Youtube/', 'url' => $data['link']])
                        ->setChannelId('default')
                        ->setBadge(0)
                        ->playSound();

                    (new Expo)->send($message)->to($receivers)->push();

                    break;

                case 'facebook':
                    $message = (new ExpoMessage([
                        'title' => 'Bloom Digital',
                        'body' => 'A social media post was made',
                    ]))
                        ->setTitle('A Facebook post was made')
                        ->setBody($data['message'])
                        ->setData(['page' => 'bloom://Facebook/', 'url' => $data['link']])
                        ->setChannelId('default')
                        ->setBadge(0)
                        ->playSound();

                    (new Expo)->send($message)->to($receivers)->push();

                    break;

                case 'linkedin':
                    $message = (new ExpoMessage([
                        'title' => 'Bloom Digital',
                        'body' => 'A social media post was made',
                    ]))
                        ->setTitle('A Linkedin post was made')
                        ->setBody($data['message'])
                        ->setData(['page' => 'bloom://Linkedin/', 'url' => $data['link']])
                        ->setChannelId('default')
                        ->setBadge(0)
                        ->playSound();

                    (new Expo)->send($message)->to($receivers)->push();

                    break;

                default:
                    # code...
                    break;
            }
        } elseif ($data['type'] == 'general') {
            $message = (new ExpoMessage([
                'title' => 'Bloom Digital',
                'body' => 'Hey Bloomer',
            ]))
                ->setTitle($data['title'])
                ->setBody($data['message'])
                ->setChannelId('default')
                ->setBadge(0)
                ->playSound();

            (new Expo)->send($message)->to($receivers)->push();
        } elseif ($data['type'] == 'meeting') {
            $message = (new ExpoMessage([
                'title' => 'Bloom Digital',
                'body' => 'Hey Bloomer',
            ]))
                ->setTitle($data['title'])
                ->setBody('Meeting starts at ' . $data['time'])
                ->setData(['externalLink' => $data['link']])
                ->setChannelId('default')
                ->setBadge(0)
                ->playSound();

            (new Expo)->send($message)->to($receivers)->push();
        }
    }
}
