<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function confirm(ContactRequest $request)
    {
        $inputs = $request->all();

        $categories = [
            1 => '商品のお届けについて',
            2 => '商品の交換について',
            3 => '商品トラブル',
            4 => 'ショップへのお問い合わせ',
            5 => 'その他'
        ];

        return view('confirm', compact('inputs', 'categories'));
    }

    public function store(Request $request)
    {
        if ($request->input('action') === 'back') {
            return redirect('/')->withInput($request->except('action'));
        }

        $contact = $request->only(['category_id', 'first_name', 'last_name', 'gender', 'email', 'tel1', 'tel2', 'tel3', 'address', 'building', 'detail']);

        $contact['tel'] = "{$contact['tel1']}-{$contact['tel2']}-{$contact['tel3']}";

        Contact::create('$contact');

        return redirect('/thanks');
    }
}
