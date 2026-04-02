<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Contact; 

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->get();
        return view('admin.contacts.index', compact('contacts'));
    }

    public function markAsRead($id)
    {
        $contact = Contact::findOrFail($id);

        // đảo trạng thái
        $contact->is_read = !$contact->is_read;

        $contact->save();

        return redirect()->back();

    }
}