<table class="table">
    <thead>
        <tr>
            <th class="text-center" colspan="2"><strong>{{ $loan->staff->full_name }}</strong></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td scope="row">Ref</td>
            <td>{{ $loan->ref }}</td>
        </tr>
        <tr>
            <td scope="row">IPPIS</td>
            <td>{{ $loan->ippis }}</td>
        </tr>
        <tr>
            <td scope="row">Loan Start Date</td>
            <td>{{ $loan->loan_date ? $loan->loan_date->toFormattedDateString() : '' }}</td>
        </tr>
        <tr>
            <td scope="row">Loan End Date</td>
            <td>{{ $loan->loan_end_date ? $loan->loan_end_date->toFormattedDateString() : '' }}</td>
        </tr>
        <tr>
            <td scope="row">Duration</td>
            <td>{{ $loan->no_of_months }} months</td>
        </tr>
        <tr>
            <td scope="row">Amount</td>
            <td>&#8358; {{ number_format($loan->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td scope="row">Monthly Amount</td>
            <td>&#8358; {{ number_format($loan->monthly_amount, 2)}}</td>
        </tr>
        <tr>
            <td scope="row">Balance</td>
            <td> &#8358; {{ $loan->payments->last() ? number_format($loan->payments->last()->bal, 2) : '0.00'  }}</td>
        </tr>
    </tbody>
</table>