<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function insertContactOnHubSpot(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            if ($validation->fails()) {
                $errors = $validation->messages()->first();
                return redirect('contact')->with('warning', $errors);
            } else {

                $sendData = [
                    'properties' => [
                        [
                            'property' => 'firstname',
                            'value' => $request->first_name
                        ],
                        [
                            'property' => 'lastname',
                            'value' => $request->last_name
                        ],
                        [
                            'property' => 'email',
                            'value' => $request->email
                        ],
                    ]
                ];

                $post_json = json_encode($sendData);
                $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . env('HUBSPOT_API_KEY');


                // $client = new Client();
                // $response = $client->request('POST', $endpoint, [
                //     'headers'  => ['content-type' => 'application/json'],
                //     'body' => $post_json
                // ]);

                $response = Http::acceptJson()->post($endpoint, [
                    'properties' => [
                        [
                            'property' => 'firstname',
                            'value' => $request->first_name
                        ],
                        [
                            'property' => 'lastname',
                            'value' => $request->last_name
                        ],
                        [
                            'property' => 'email',
                            'value' => $request->email
                        ],
                    ]
                ]);

                if ($response->successful()) {

                    return redirect('contact')->with('success', "Thanks $request->first_name, you details are saved!");
                } elseif ($response->clientError() || ($response->getStatuscode() == 409)) {

                    Log::info("HubSpot contact creation failed | Duplicate record insertion " . $response);
                    return redirect('contact')->with('warning', 'This email is already registered with us!');
                    // $res = $response->json();
                    // if ($response->getStatuscode() == 409) {

                    //     return redirect('contact')->with('warning', 'This email is already registered to us!');
                    // } else {

                    //     return redirect('contact')->with('warning', $res['message']);
                    // }
                } else {

                    Log::info("HubSpot contact creation failed " . $response);
                    return redirect('contact')->with('danger', 'Something went wrong! Please try again/later!');
                }
            }
        } catch (Exception $excep) {

            Log::info("HubSpot insertion method failed " . json_encode($excep->getMessage()));
            return redirect('contact')->with('danger', 'Something went wrong! Please try later.');
        }
    }

    public function getContactsFromHubSpot()
    {
        $endpoint = 'https://api.hubapi.com/contacts/v1/lists/all/contacts/all?count=&hapikey=' . env('HUBSPOT_API_KEY');
        $response = Http::accept('application/json')->get($endpoint);

        // return $response->json();

        $results = json_decode($response->getbody()->getcontents());
        // $results = $results->json();

        $collection = new Collection($results);

        // return $collection;
        return view('allcontacts', compact('collection'));

    }
   
}
