<link href="{{ public_path('assets/css/bootstrap.min.css') }}" rel="stylesheet">
@if($member->ledgers->count() > 0)

<div class="row">
    <div>
        <h4 class="text-center">{{$member->full_name}}</h4>
        <h5 class="text-center">{{$member->ippis}}</h5>
        <h5 class="text-center">{{$member->member_pay_point ? $member->member_pay_point->name : ''}}</h5>
    </div>
</div>

<table class="table table-bordered table-hover mb-0">
    <thead>
        <tr>
            <th class="text-center"></th>
            <th class="text-center">PARTICULARS</th>
            <th class="text-center" colspan="3">SAVINGS</th>
            <th class="text-center" colspan="3">LONG TERM</th>
            <th class="text-center" colspan="3">SHORT TERM</th>
            <th class="text-center" colspan="3">COMMODITY</th>
            <!-- <th class="text-center">&nbsp</th> -->
        </tr>
    </thead>
    <tbody>
        <tr>
            <th class="text-left">DATE</th>
            <th class="text-left">DESCRIPTION</th>
            <th class="text-right">DR</th>
            <th class="text-right">CR</th>
            <th class="text-right">BALANCE</th>
            <th class="text-right">DR</th>
            <th class="text-right">CR</th>
            <th class="text-right">BALANCE</th>
            <th class="text-right">DR</th>
            <th class="text-right">CR</th>
            <th class="text-right">BALANCE</th>
            <th class="text-right">DR</th>
            <th class="text-right">CR</th>
            <th class="text-right">BALANCE</th>
            <!-- <th class="text-center">EDIT</th> -->
        </tr>
        @foreach($member->ledgers as $ledger)
        <tr>
            <th class="text-left" scope="row">{{ $ledger->date }}</th>
            <td>{{ $ledger->ref }}</td>
            <td class="text-right">
                {{ number_format($ledger->savings_dr, 2) }}</td>
            <td class="text-right">
                {{ number_format($ledger->savings_cr, 2) }}</td>
            <td class="text-right">
                {{ number_format($ledger->savings_bal, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->long_term_dr, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->long_term_cr, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->long_term_bal, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->short_term_dr, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->short_term_cr, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->short_term_bal, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->commodity_dr, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->commodity_cr, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($ledger->commodity_bal, 2) }}
            </td>
            <!-- <td class="text-center">
                                    <a href="{{ route('editLedgerEntry', $ledger->id) }}"><i class="ion ion-md-create"></i></a>
                                </td> -->
        </tr>
        @endforeach
    </tbody>
</table>

@else
<p>No records found</p>
@endif
