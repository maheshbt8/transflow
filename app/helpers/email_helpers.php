<?php
//namespace App\helpers;
//use App\User;
//use Auth;
use App\EmailSettings;
//use Spatie\Permission\Models\Role;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Gate;
//use App\Http\Controllers\Controller;
//use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\EmailDemo;
use App\Mail\Statusmail;
use App\Mail\Quotemail;
use App\Mail\Sendupdatemail;
use App\Mail\Clientemail;
use App\Mail\Vendoremail;
use App\Mail\Vtstatusemail;
use App\Mail\Purches_Order;
use App\Mail\Clientcron;
use App\Mail\Clientpaymentinvoice;
use Symfony\Component\HttpFoundation\Response;

function sendmail($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new EmailDemo($mailData));
	return true;
}
function sendstatusmail($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new Statusmail($mailData));
	return true;
}
function sendquotemail($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new Quotemail($mailData));
	return true;
}
function sendstupdatemail($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new Sendupdatemail($mailData));
	return true;
}
function sendvtstatusemail($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new Vtstatusemail($mailData));
	return true;
}
function clientinvoicemail($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new Clientemail($mailData));
	return true;
}
function vendorinvoicemail($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new Vendoremail($mailData));
	return true;
}
function clientpmntinvoice($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new Clientpaymentinvoice ($mailData));
	return true;
}
function sendpurches($tomail,$mailData,$ccmail=[],$bccmail=[]){
	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new  Purches_Order ($mailData));
	return true;
}
// function clientcronjob($tomail,$mailData,$ccmail=[],$bccmail=[]){
// 	Mail::to($tomail)->cc($ccmail)->bcc($bccmail)->send(new Clientcron($mailData));
// 	return true;
// }