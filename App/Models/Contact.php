<?php

namespace Modules\Contact\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mail;
use Modules\Contact\App\Mail\ContactMail;

class Contact extends Model {
	
	use HasFactory;

	protected $fillable = ['name', 'email', 'phone', 'subject', 'message'];

}
