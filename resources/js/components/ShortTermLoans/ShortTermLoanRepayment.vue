<template>
  <div>
    <div class="row">
      <div class="col-12">
        <h6>Loan Details</h6>
        <div class="card text-white bg-info">
          <div class="card-body">
            <h5 class="card-title">Savings Balance: &#8358; {{ savings_bal | number_format }}</h5>
          </div>
        </div>
        <form @submit.prevent="submitForm">
          <div class="form-group row">
            <label for="ippis" class="col-sm-3 col-form-label">IPPIS</label>
            <div class="col-sm-9">
              <input v-model="repayment.ippis" type="text" class="form-control" disabled />
              <small v-if="errors.ippis" class="text-danger">{{ errors.ippis[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="pf" class="col-sm-3 col-form-label">Repayment Date</label>
            <div class="col-sm-9">
              <input v-model="repayment.deposit_date" type="date" class="form-control" />
              <small v-if="errors.deposit_date" class="text-danger">{{ errors.deposit_date[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="pf" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
              <input v-model="repayment.ref" type="text" class="form-control" />
              <small v-if="errors.ref" class="text-danger">{{ errors.ref[0] }}</small>
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Repayment type</label>
            <div class="col-sm-9">
              <select v-model="repayment.repayment_mode" class="form-control">
                <option v-for="(r, index) in repayment_modes" v-bind:value="r.key" :key="index">
                  {{r.value}}
                </option>
              </select>
              <small v-if="errors.repayment_mode" class="text-danger">{{ errors.repayment_mode[0] }}</small>
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Amount</label>
            <div class="col-sm-9">
              <input v-model="repayment.total_amount" type="text" class="form-control" :max="max_deductable_savings_amount" />
              <small class="float-right">Max deductible from savings: &#8358; {{ max_deductable_savings_amount | number_format }}</small>
              <small v-if="errors.total_amount" class="text-danger">{{ errors.total_amount[0] }}</small>
            </div>
          </div>

          <div class="form-group row">
            <label for="coop_no" class="col-sm-3 col-form-label">&nbsp</label>
            <div class="col-sm-9">
              <button class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NewLongTermLoanRepayment",
  props: {
    staff: {
      required: true
    }
  },
  data() {
    return {
      repayment: {
        ref: null,
        deposit_date: null,
        ippis: null,
        repayment_mode: '',
        total_amount: null
      },
      errors: [],
      savings: null,
      repayment_modes: [],
      max_deductable_savings_amount: 0,
      last_payment: 0,
      savings_bal: 0,
      last_short_term_loan_payment: 0,
    };
  },
  methods: {
    submitForm: function() {

      if (this.repayment.repayment_mode == 'savings') {
        if (this.repayment.total_amount > this.max_deductable_savings_amount) {
          alert('Amount exceeds Maximum Allowed from savings')
          return;
        }
      }

        if (this.repayment.total_amount > this.last_short_term_loan_payment.bal) {
            alert(`Amount exceeds loan balance: ${this.last_short_term_loan_payment.bal}`)
          return;
        }
      
      const confirmation = confirm("Are you sure?");

      if (!confirmation) {
        return;
      }

      axios
        .post(`members/short-term-loan-repayment/${this.staff.ippis}`, this.repayment)
        .then(res => {
          // console.log(res.data);
          var getUrl = window.location;
          var baseUrl =
            getUrl.protocol +
            "//" +
            getUrl.host +
            "/" +
            getUrl.pathname.split("/")[1];
            window.location.href = baseUrl + `/public/members/${this.staff.ippis}/short-term`;
        })
        .catch(e => {
          // console.log(e);
          if (e.response.status == 422) {
            this.errors = e.response.data.errors;
            // Vue.toasted.error("There are errors");
          }
        });
    }
  },
  created() {
    this.repayment.ippis = this.staff.ippis;
      axios
        .get(`members/short-term-loan-repayment/${this.staff.ippis}`, this.loan)
        .then(res => {
          // console.log(res.data);
          this.repayment_modes = res.data.repayment_modes        
          this.last_payment = res.data.last_long_term_payment
          this.savings_bal = res.data.savings_bal
          this.last_short_term_loan_payment = res.data.last_short_term_loan_payment
          this.max_deductable_savings_amount = this.savings_bal - (this.last_payment.bal / 2)
        })
        .catch(e => {
          console.log(e);
        });
  }
};
</script>

<style scoped>
</style>