<div class="row">
    <div class="col-md-12">
        <h5>Member's details</h5>
        <table class="table table-condensed table-hover table-bordered">
            <tbody>
                <tr>
                    <td>Name: </td>
                    <td>{{ $member->full_name }}</td>
                </tr>
                <tr>
                    <td>IPPIS: </td>
                    <td>{{ $member->ippis }}</td>
                </tr>
                <tr>
                    <td>Current Monthly Contribution: </td>
                    <td>&#8358;
                        {{ count($member->monthly_savings) > 0 ? number_format($member->monthly_savings->last()->amount, 2) : 0.00 }}
                    </td>
                </tr>
                <tr>
                    <td>Pay point: </td>
                    <td>{{ $member->member_pay_point ? $member->member_pay_point->name : '' }}</td>
                </tr>
                <tr>
                    <td>Coop No: </td>
                    <td>{{ $member->coop_no }}</td>
                </tr>
                <tr>
                    <td>Phone: </td>
                    <td>{{ $member->phone }}</td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2"><b>Next of Kin Information</b></td>
                </tr>
                <tr>
                    <td>Next of Kin Name: </td>
                    <td>{{ $member->nok_name }}</td>
                </tr>
                <tr>
                    <td>Next of Kin Phone: </td>
                    <td>{{ $member->nok_phone }}</td>
                </tr>
                <tr>
                    <td>Next of Kin Address: </td>
                    <td>{{ $member->nok_address }}</td>
                </tr>
                <tr>
                    <td>Relationship with Next of Kin : </td>
                    <td>{{ $member->nok_rship }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
