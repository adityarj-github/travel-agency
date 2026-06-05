<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactInquiry;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function store(StoreContactRequest $request)
    {
        ContactInquiry::create($request->validated());

        return back()->with('success', 'Thank you for reaching out! We will get back to you shortly.');
    }
}
