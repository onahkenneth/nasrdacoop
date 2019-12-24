<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <div class="card m-b-30">
                    <div class="card-body">

                        <form @submit.prevent="submitForm">
                            <div class="form-group row"><label for="date" class="col-sm-2 col-form-label">Date</label>
                                <div class="col-sm-10">
                                    <input v-model="ledger.date" class="form-control" type="text" id="date">
                                </div>
                            </div>

                            <div class="form-group row"><label for="ref" class="col-sm-2 col-form-label">Ref</label>
                                <div class="col-sm-10">
                                    <input v-model="ledger.ref" class="form-control" type="text" id="ref">
                                </div>
                            </div>

                            <br>

                            <div class="form-group row"><label class="col-sm-2 col-form-label">Entry Type</label>
                                <div class="col-sm-10">
                                    <!-- <a @click.prevent="activateSavings" href="#" class="btn btn-primary btn-sm">Savings</a> &nbsp
                                    <a @click.prevent="activateObligations" href="#" class="btn btn-primary btn-sm">Obligations</a> -->
                                    <select @change.prevent="showEntryType" v-model="choice" class="form-control" >
                                        <option
                                        v-for="(entry_type, index) in entry_types"
                                        v-bind:value="entry_type.id"
                                        :key="index"
                                        >{{ entry_type.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="showSavings">

                                <br>
                                <div class="alert alert-info" role="alert">
                                    <strong>Debit</strong> reduces savings balance. <br>
                                    <strong>Credit</strong> increases savings balance.
                                </div>

                                <div class="form-group row"><label class="col-sm-2 col-form-label">&nbsp</label>
                                    <div class="col-sm-10">
                                        <a @click.prevent="activateSavingsDebit" href="#" class="btn btn-danger btn-sm">Debit</a> &nbsp
                                        <a @click.prevent="activateSavingsCredit" href="#" class="btn btn-success btn-sm">Credit</a>
                                    </div>
                                </div>
                                <br>

                                <div v-if="showSavingsDebit" class="form-group row"><label for="ref" class="col-sm-2 col-form-label">Debit</label>
                                    <div class="col-sm-10">
                                        <input v-model="ledger.savings.debit" class="form-control" type="text" id="ref">
                                    </div>
                                </div>
                                <div v-if="showSavingsCredit" class="form-group row"><label for="ref" class="col-sm-2 col-form-label">Credit</label>
                                    <div class="col-sm-10">
                                        <input v-model="ledger.savings.credit" class="form-control" type="text" id="ref">
                                    </div>
                                </div>
                            </div>


                            <div v-if="showObligations">

                                <br>
                                <div class="alert alert-info" role="alert">
                                    <strong>Debit</strong> increases amount owed on loan. <br>
                                    <strong>Credit</strong> reduces amount owed on loan
                                </div>

                                <div class="form-group row"><label class="col-sm-2 col-form-label">&nbsp</label>
                                    <div class="col-sm-10">
                                        <a @click.prevent="activateObligationsDebit" href="#" class="btn btn-danger btn-sm">Debit</a> &nbsp
                                        <a @click.prevent="activateObligationsCredit" href="#" class="btn btn-success btn-sm">Credit</a>
                                    </div>
                                </div>
                                <br>

                                <div class="form-group row"><label class="col-sm-2 col-form-label">Obligation Type</label>
                                    <div class="col-sm-10">

                                        <select v-model="ledger.obligation.obligation_type" class="custom-select">
                                            <option
                                            v-for="(obligation, index) in obligations"
                                            v-bind:value="obligation.id"
                                            :key="index"
                                            >{{ obligation.name }}</option>
                                        </select>

                                    </div>
                                </div>

                                <div v-if="showObligationsDebit"  class="form-group row"><label for="ref" class="col-sm-2 col-form-label">Debit</label>
                                    <div class="col-sm-10">
                                        <input v-model="ledger.obligation.debit" class="form-control" type="text" id="ref">
                                    </div>
                                </div>
                                <div v-if="showObligationsCredit"  class="form-group row"><label for="ref" class="col-sm-2 col-form-label">Credit</label>
                                    <div class="col-sm-10">
                                        <input v-model="ledger.obligation.credit" class="form-control" type="text" id="ref">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class='btn btn-primary'> Submit</button>
                        </form>
                    </div>
                </div>
            </div><!-- end col -->
        </div>

    </div>
</template>

<script>
export default {
    name: "NewLedgerEntry",
    props: {
        staff: {
            required: true
        },
    },
    data() {
        return {
            showObligations: false,
            showSavings: false,
            showSavingsDebit: false,
            showSavingsCredit: true,
            showObligationsDebit: false,
            showObligationsCredit: true,
            choice: '',
            ledger: {
                date: '',
                ref: '',
                savings: {
                    debit: '',
                    credit: '',
                },
                obligation: {
                    obligation_type: null,
                    debit: '',
                    credit: '',
                },
            },
            entry_types: [
                {
                    id: 'savings',
                    name: 'Savings',
                },
                {
                    id: 'obligations',
                    name: 'Obligations',
                },
            ],
            obligations: [
                {
                    id: 'long_term_loan',
                    name: 'Long Term Loan',
                },
                {
                    id: 'short_term_loan',
                    name: 'Short Term Loan',
                },
                {
                    id: 'commodity',
                    name: 'Commodity',
                },
            ]
        }
    },
    methods: {
        activateSavings: function() {
            this.showSavings = true
            this.showObligations = false
            this.ledger.obligation.debit = ''
            this.ledger.obligation.credit = ''
        },
        activateObligations: function() {
            this.showObligations = true
            this.showSavings = false
            this.ledger.savings.debit = ''
            this.ledger.savings.credit = ''
        },
        showEntryType:function() {
            if(this.choice == 'savings') {
                this.activateSavings()
            }
            if(this.choice == 'obligations') {
                this.activateObligations()
            }
        },
        activateSavingsDebit: function() {
            this.showSavingsDebit = true
            this.showSavingsCredit= false
        },
        activateSavingsCredit: function() {
            this.showSavingsDebit = false
            this.showSavingsCredit= true
        },
        activateObligationsDebit: function() {
            this.showObligationsDebit = true
            this.showObligationsCredit= false
        },
        activateObligationsCredit: function() {
            this.showObligationsDebit = false
            this.showObligationsCredit= true
        },
        submitForm: function() {

            const confirmation = confirm("Are you sure?");

            if (!confirmation) {
                return;
            }

            axios
                .post(`ledger/${this.staff.ippis}/entry`, this.ledger)
                .then(res => {
                    // console.log(res.data);
                    var getUrl = window.location;
                    var baseUrl =
                        getUrl.protocol +
                        "//" +
                        getUrl.host +
                        "/" +
                        getUrl.pathname.split("/")[1];
                        window.location.href = baseUrl + `/public/members/${this.staff.ippis}/ledger`;
                })
                .catch(e => {
                    console.log(e);
                });
        }
    }
}
</script>
