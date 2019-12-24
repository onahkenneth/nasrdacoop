<?php

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
    return redirect('dashboard');
});

Auth::routes();

// users
Route::resource('/users', 'UsersController');

// staff
Route::resource('/staff', 'StaffController');

// members
Route::get('/members', 'MembersController@index')->name('members.index');
Route::get('/members/{ippis}', 'MembersController@show')->name('members.show');
Route::get('/members/{ippis}/ledger', 'MembersController@ledger')->name('members.ledger');
Route::get('/members/{ippis}/ledger-excel', 'LedgerController@memberLedgerExcel')->name('members.ledgerExcel');
Route::get('/members/{ippis}/ledger-pdf', 'LedgerController@memberLedgerPdf')->name('members.ledgerPdf');
Route::get('/members/{ippis}/ledger-print', 'LedgerController@memberLedgerPrint')->name('members.ledgerPrint');
Route::get('/members/add', 'MembersController@addMember')->name('addMember');
Route::post('/members/add/save', 'MembersController@saveMember')->name('saveMember');
Route::get('/members/{ippis}/dashboard', 'MembersController@dashboard')->name('members.dashboard');
Route::get('/members/{ippis}/status', 'MembersController@status')->name('members.status');

// monthly savings
Route::get('/members/{ippis}/savings', 'MonthlySavingController@monthlySavings')->name('members.savings');
Route::get('/members/{ippis}/new-savings', 'MonthlySavingController@newSavings')->name('members.newSavings');
Route::post('/members/post-new-savings/{ippis}', 'MonthlySavingController@postNewSavings')->name('members.postNewSavings');

Route::get('/members/{ippis}/savings-change-obligation', 'MonthlySavingController@savingsChangeObligation')->name('members.savingsChangeObligation');
Route::post('/members/post-savings-change-obligation/{ippis}', 'MonthlySavingController@postSavingsChangeObligation')->name('members.postSavingsChangeObligation');

Route::get('/members/{ippis}/savings-withdrawal', 'MonthlySavingController@savingsWithrawal')->name('members.savingsWithrawal');
Route::post('/members/post-savings-withdrawal/{ippis}', 'MonthlySavingController@postSavingsWithrawal')->name('members.postSavingsWithrawal');

// short term loans
Route::get('/members/{ippis}/short-term', 'ShortTermController@shortTermLoans')->name('members.shortTermLoans');
Route::get('/members/{ippis}/new-short-loan', 'ShortTermController@newShortLoan')->name('members.newShortLoan');
Route::post('/members/post-new-short-loan/{ippis}', 'ShortTermController@postNewShortLoan')->name('members.postNewShortLoan');
Route::get('/members/short-term-loan-repayment/{ippis}', 'ShortTermController@shortLoanRepayment')->name('members.shortLoanRepayment');
Route::post('/members/short-term-loan-repayment/{ippis}', 'ShortTermController@postShortLoanRepayment')->name('members.postShortLoanRepayment');
Route::get('/members/{loan_id}/short-term-loan-details', 'ShortTermController@loanDetails')->name('members.shortLoanDetails');

// commodities
Route::get('/members/{ippis}/commodity', 'CommodityController@commodity')->name('members.commodity');
Route::get('/members/{ippis}/new-commodity', 'CommodityController@newCommodityLoan')->name('members.newCommodityLoan');
Route::post('/members/post-new-commodity-loan/{ippis}', 'CommodityController@postNewCommodityLoan')->name('members.postNewCommodityLoan');
Route::get('/members/commodity-loan-repayment/{ippis}', 'CommodityController@commodityLoanRepayment')->name('members.commodityLoanRepayment');
Route::post('/members/commodity-loan-repayment/{ippis}', 'CommodityController@postCommodityLoanRepayment')->name('members.postCommodityLoanRepayment');
Route::get('/members/{loan_id}/commodity-term-loan-details', 'CommodityController@loanDetails')->name('members.commodityLoanDetails');

// long term loan
Route::get('/members/{ippis}/long-term', 'LongTermController@longTermLoans')->name('members.longTermLoans');
Route::get('/members/{ippis}/new-long-loan', 'LongTermController@newLongLoan')->name('members.newLongLoan');
Route::post('/members/post-new-long-loan/{ippis}', 'LongTermController@postNewLongLoan')->name('members.postNewLongLoan');
Route::get('/members/long-term-loan-repayment/{ippis}', 'LongTermController@longLoanRepayment')->name('members.longLoanRepayment');
Route::post('/members/long-term-loan-repayment/{ippis}', 'LongTermController@postLongLoanRepayment')->name('members.postLongLoanRepayment');
Route::get('/members/{loan_id}/long-term-loan-details', 'LongTermController@loanDetails')->name('members.longLoanDetails');

// ledger
Route::get('/import-initial-ledger-summary', 'LedgerController@getImportInitialLedgerSummary')->name('getImportInitialLedgerSummary');
Route::post('/import-initial-ledger-summary', 'LedgerController@postImportInitialLedgerSummary')->name('postImportInitialLedgerSummary');
Route::get('/import-initial-ledger', 'LedgerController@getImportInitialLedger')->name('getImportInitialLedger');
Route::post('/import-initial-ledger', 'LedgerController@postImportInitialLedger')->name('postImportInitialLedger');
Route::get('/ledger/{ippis}/entry', 'LedgerController@newledgerEntry')->name('newledgerEntry');
Route::post('/ledger/{ippis}/entry', 'LedgerController@postNewLedgerEntry')->name('postNewLedgerEntry');
Route::get('/ledger/{ledger_id}/entry/edit', 'LedgerController@editLedgerEntry')->name('editLedgerEntry');
Route::put('/ledger/{ippis}/entry', 'LedgerController@updateLedgerEntry')->name('updateLedgerEntry');

// export and import ippis files
Route::get('/ledger/generate-deductions', 'LedgerController@generateDeductions')->name('generateDeductions');
Route::get('/ledger/export-to-ippis', 'LedgerController@exportToIppis')->name('exportToIppis');
Route::post('/ledger/import-from-ippis', 'LedgerController@importFromIppis')->name('importFromIppis');

// roles
Route::post('/role-permissions', 'RolesPermissionsController@rolePermissions')->name('rolePermissions');

// reports
Route::get('/reports', 'ReportsController@index')->name('reports');
Route::get('/monthly-defaults', 'ReportsController@monthlyDefaults')->name('reports.monthlyDefaults');
Route::get('/loan-defaults', 'ReportsController@loanDefaults')->name('reports.loanDefaults');

// centers
Route::resource('/centers', 'CenterController');

// users
Route::get('/users/{id}/delete', 'UsersController@delete')->name('users.delete');
Route::resource('/users', 'UsersController');

// dashboard
Route::get('/dashboard', 'HomeController@index')->name('dashboard');

// awesomplete
Route::post('members/awesomplete', 'MembersController@awesomplete');
