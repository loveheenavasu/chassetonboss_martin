<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});
Route::get('privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('terms-conditions', function () {
    return view('terms-conditions');
});

$to = '/connections';

//Route::redirect('/', $to);

Route::middleware(['auth:sanctum', 'verified'])->group(function () use ($to) {
    Route::redirect('/dashboard', $to)->name('dashboard');

    Route::get('test/generate-emails', [\App\Http\Controllers\TestController::class, 'generateEmails']);
    Route::resource('gmailconnection', \App\Http\Controllers\GmailConnectionController::class)->only(['index', 'create', 'edit']);
    Route::get('deletegmailconnection', [\App\Http\Controllers\GmailConnectionController::class, 'deletegmailconnection']);
    Route::get('getallgorups', [\App\Http\Controllers\GmailConnectionController::class, 'listAllGroups']);
    Route::get('savegmailfilter', [\App\Http\Controllers\GmailConnectionController::class, 'saveGmailConnectionFilter']);
    Route::get('listsavedfilter', [\App\Http\Controllers\GmailConnectionController::class, 'listSavedFilter']);
    Route::get('testconnection', [\App\Http\Controllers\GmailConnectionController::class, 'testconnection']);
    Route::get('refreshtoken', [\App\Http\Controllers\GmailConnectionController::class, 'refreshtoken']);
    Route::get('multiplecheck', [\App\Http\Controllers\GmailConnectionController::class, 'getcheckedvalue']);
    Route::get('multiProxycheck', [\App\Http\Controllers\GmailConnectionController::class, 'getproxycheckedvalue']);
    Route::get('gmailconnection_import', [\App\Http\Controllers\GmailConnectionController::class,'import']);
    Route::get('createdgroups', [\App\Http\Controllers\GroupsController::class,'selectgroup']);
    Route::resource('groups', \App\Http\Controllers\GroupsController::class)->only(['index', 'create', 'show','edit']);
    Route::resource('eventcalender', \App\Http\Controllers\EventCalender::class)->only(['index', 'create','edit']);
    Route::get('cutter', [\App\Http\Controllers\CutterController::class, 'index'])->name('cutter.index');
    Route::resource('connections', \App\Http\Controllers\ConnectionController::class)->only(['index', 'create', 'show']);
    
    Route::resource('leadvalidator', \App\Http\Controllers\LeadValidatorController::class)->only(['index', 'create', 'show']);
    Route::resource('listings', \App\Http\Controllers\ListingController::class)->only(['index', 'create', 'show']);
    Route::get('deletelist', [\App\Http\Controllers\ListingController::class, 'deletelist']);
    Route::get('notes', [\App\Http\Controllers\ListingController::class, 'savenotesvalue']);
    Route::resource('eventlistings', \App\Http\Controllers\EventListingController::class)->only(['index', 'create', 'show']);
    Route::resource('proxy', \App\Http\Controllers\ProxyController::class)->only(['index', 'create', 'show']);
    Route::get('eventnotes', [\App\Http\Controllers\EventListingController::class, 'savenotesvalue']);
    Route::get('refresh-mautic', [\App\Http\Controllers\RuleController::class, 'mauticStages']);
    Route::get('notes', [\App\Http\Controllers\ListingController::class, 'savenotesvalue']);
    Route::get('leadvalidatornotes', [\App\Http\Controllers\LeadValidatorController::class, 'savenotesvalue']);
    Route::resource('rules', \App\Http\Controllers\RuleController::class)->only(['index', 'create', 'show']);
    Route::get('deleteCheckedRule', [\App\Http\Controllers\RuleController::class, 'deleteCheckedRule']);
    Route::resource('eventlinktemplate', \App\Http\Controllers\EventLinkController::class)->only(['index', 'create', 'show']);
    Route::resource('eventtemplate', \App\Http\Controllers\EventTemplateController::class)->only(['index', 'create', 'show']);
    Route::get('contenttemplate', [\App\Http\Controllers\EventTemplateController::class, 'contenttemplate']);
    Route::get('sendtesttemplate', [\App\Http\Controllers\EventTemplateController::class, 'sendTestTemplate']);
    Route::get('emailSyncedList', [\App\Http\Controllers\RuleController::class, 'emailSyncedList']);
    Route::resource('invalidemail', \App\Http\Controllers\InvalidEmailController::class)->only(['index']);
    //Mautic logs

    Route::resource('mauticlogs', \App\Http\Controllers\MauticLogsController::class)->only(['index']);

    //invalid
    Route::resource('eventinvalidemail', \App\Http\Controllers\EventInvalidEmailController::class)->only(['index']); 
    Route::resource('emaillogs', \App\Http\Controllers\EmailLogsController::class)->only(['index']);

    //valid
    Route::resource('eventemaillogs', \App\Http\Controllers\EventEmailLogsController::class)->only(['index']);
    Route::post('emailfilter', [\App\Http\Livewire\EmailLogsList::class, 'getEmaillogsProperty']);
    Route::resource('templates', \App\Http\Controllers\TemplateController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('pages', \App\Http\Controllers\PageController::class)->only(['index', 'create', 'edit']);
    Route::get('deletepage', [\App\Http\Controllers\PageController::class, 'deletepage']);
    Route::resource('contents', \App\Http\Controllers\ContentController::class)->only(['index', 'create', 'edit']);
    Route::resource('syndications', \App\Http\Controllers\SyndicationController::class)->only(['index', 'create', 'edit']);
    Route::get('DeleteEmailLogs', [\App\Http\Controllers\EmailLogsController::class, 'DeleteEmailLogs']);
    Route::get('DeleteEmailLogs_invalid_email', [\App\Http\Controllers\EmailLogsController::class, 'DeleteEmailLogs_invalid_email']);

    //delete
    Route::get('DeleteEventLogs_invalid_email', [\App\Http\Controllers\EventInvalidEmailController::class, 'DeleteEventLogs_invalid_email']);

    //delete
    Route::get('DeleteEventLogs_valid_email', [\App\Http\Controllers\EventEmailLogsController::class, 'DeleteEventLogs_valid_email']);
    //Premium Template
    Route::resource('premiumtemplates', \App\Http\Controllers\PremiumTemplatesController::class)->only(['index', 'create', 'show', 'edit']);

    //Premium Pages
    Route::resource('premiumpages', \App\Http\Controllers\PremiumPagesController::class)    ->only(['index', 'create', 'show', 'edit']);
    Route::get('deletepremiumpage', [\App\Http\Controllers\PremiumPagesController::class, 'deletepremiumpage']);


    Route::get('SetLogsDeleteCron', [\App\Http\Controllers\EmailLogsController::class, 'SetLogsDeleteCron']);
    Route::get('Delete-logs-manaully', [\App\Http\Controllers\EmailLogsController::class, 'DeletelogsManaully']);
    Route::resource('cron', \App\Http\Controllers\CronController::class)->only(['index']);
    Route::get('SetLogsResetCron',[\App\Http\Controllers\CronController::class, 'SetLogsResetCron']);
    Route::resource('keyword', \App\Http\Controllers\Keywords_Controller::class)->only(['index', 'create', 'show']);
    Route::resource('profession', \App\Http\Controllers\ProfessionController::class)->only(['index', 'create', 'show']);

    //ProjectCotroller
    Route::resource('projectlist', \App\Http\Controllers\ProjectController::class)->only(['index', 'create', 'show',]);

    //blacklist
    Route::resource('blacklist', \App\Http\Controllers\BlacklistController::class)->only(['index', 'create', 'show']);

    //user login details
    Route::resource('/logindetails', \App\Http\Controllers\UserLoginDetailController::class)->only(['index']);
});
