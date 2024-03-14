<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Family;
use App\Notifications\NewPersonAdded;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PersonController extends Controller
{
    public function index()
    {
        $people = Person::all();
        return view('people.index', compact('people'));
    }

    public function create()
    {
        $families = Family::all();
        return view('people.create', compact('families'));
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string',
        'birth_date' => 'required|date',
        'family_id' => 'nullable|exists:families,id',
    ]);

    $person = Person::create($validatedData);

    if (Auth::check()) {
        $user = Auth::user();
        $user->notify(new NewPersonAdded($person));
    }
    
    $this->sendSlackNotification($person);

    return redirect()->route('people.index')->with('success', 'Person added successfully.');
}


    public function show(Person $person)
    {
        return view('people.show', compact('person'));
    }

    public function edit(Person $person)
    {
        $families = Family::all();
        return view('people.edit', compact('person', 'families'));
    }

    public function update(Request $request, Person $person)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'birth_date' => 'required|date',
            'family_id' => 'nullable|exists:families,id',
        ]);

        $person->update($validatedData);

        return redirect()->route('people.index')->with('success', 'Person updated successfully.');
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return redirect()->route('people.index')->with('success', 'Person deleted successfully.');
    }

    public function showFamilyTree(Person $person)
    {
        $familyTree = $person->getFamilyTree();
        return view('people.family-tree', compact('person', 'familyTree'));
    }

    private function sendSlackNotification(Person $person)
    {
        $slackWebhookUrl = env('SLACK_WEBHOOK_URL');
        
        $message = [
            'text' => 'New person added: ' . $person->name,
            'attachments' => [
                [
                    'title' => 'Person Details',
                    'title_link' => route('people.show', $person->id),
                    'fields' => [
                        [
                            'title' => 'Name',
                            'value' => $person->name,
                            'short' => true,
                        ],
                        [
                            'title' => 'Birth Date',
                            'value' => $person->birth_date,
                            'short' => true,
                        ],
                    ],
                ],
            ],
        ];
        Http::post($slackWebhookUrl, ['payload' => json_encode($message)]);
    }

}
