<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <h6>Loan Details</h6>
        <div class="card text-white bg-info">
          <div class="card-body">
            <h6 class="card-title">Savings Balance: &#8358; {{ savings.bal | number_format }}</h6>
            <h6 class="card-title">Commodity Loan Balance: &#8358; {{ this.lastCommodityLoan ? this.lastCommodityLoan.bal : 0 | number_format }}</h6>
          </div>
        </div>
        <form @submit.prevent="submitForm">
          <div class="form-group row">
            <label for="ippis" class="col-sm-3 col-form-label">IPPIS</label>
            <div class="col-sm-9">
              <input v-model="loan.ippis" type="text" class="form-control" disabled />
              <small v-if="errors.ippis" class="text-danger">{{ errors.ippis[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="pf" class="col-sm-3 col-form-label">Loan Date</label>
            <div class="col-sm-9">
              <input v-model="loan.loan_date" type="date" class="form-control" />
              <small v-if="errors.loan_date" class="text-danger">{{ errors.loan_date[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="pf" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
              <input v-model="loan.ref" type="text" class="form-control" />
              <small v-if="errors.ref" class="text-danger">{{ errors.ref[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Number of months</label>
            <div class="col-sm-9">
              <!-- <input v-model="loan.no_of_months" type="text" class="form-control" /> -->
              <select @change="calMaxLoan($event)" v-model="loan.no_of_months" class="form-control">
                <option v-for="(period, index) in periods" v-bind:value="period.key" :key="index">
                  {{period.value}}
                </option>
              </select>
              <small v-if="errors.no_of_months" class="text-danger">{{ errors.no_of_months[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Amount</label>
            <div class="col-sm-9">
              <input v-model="loan.total_amount" type="text" class="form-control" />
              <!-- <small class="float-right">Max Loan Amt: &#8358; {{max_loan_amount|number_format}}</small> -->
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
      <div class="col-md-6">
        <h6>Repayment Details</h6>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td class="text-center">Month</td>
              <td class="text-right">Amount</td>
            </tr>
          </thead>
          <tr v-for="(item, index) in repayments" :key="index">
            <td class="text-center">{{ item.month }}</td>
            <td class="text-right">{{ item.amount }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NewCommodityLoan",
  props: {
    staff: {
      required: true
    }
  },
  data() {
    return {
      loan: {
        ref: null,
        loan_date: null,
        ippis: null,
        no_of_months: null,
        total_amount: null
      },
      errors: [],
      savings: null,
      periods: null,
      max_loan_amount: 0,
      lastCommodityLoan: 0,
    };
  },
  computed: {
    repayments: function() {
      let lastCommodityLoanBal = this.lastCommodityLoan ? this.lastCommodityLoan.bal : 0;
      let amt = (+this.loan.total_amount + +lastCommodityLoanBal) / this.loan.no_of_months;

      let repayArray = [];
      for (let i = 0; i < this.loan.no_of_months; i++) {
        repayArray.push({ month: i + 1, amount: amt });
      }

      return repayArray;
    }
  },
  methods: {
    calMaxLoan: function(event) {

      this.max_loan_amount = event.target.value == "36" ? this.savings.bal  * 3 : this.savings.bal  * 2;

    },
    submitForm: function() {
      // if (this.loan.total_amount > this.max_loan_amount) {
      //   alert('Loan amount is more than Maximum Allowed')
      //   return;
      // }
      const confirmation = confirm("Are you sure?");

      if (!confirmation) {
        return;
      }

      axios
        .post(`members/post-new-commodity-loan/${this.staff.ippis}`, this.loan)
        .then(res => {
          // console.log(res.data);
          var getUrl = window.location;
          var baseUrl =
            getUrl.protocol +
            "//" +
            getUrl.host +
            "/" +
            getUrl.pathname.split("/")[1];
            window.location.href = baseUrl + `/public/members/${this.staff.ippis}/commodity`;
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
    this.loan.ippis = this.staff.ippis;
      axios
        .get(`members/${this.staff.ippis}/new-commodity`, this.loan)
        .then(res => {
          // console.log(res.data);
          this.savings = res.data.savings        
          this.periods = res.data.periods 
          this.lastCommodityLoan = res.data.last_commodity_loan      
        })
        .catch(e => {
          console.log(e);
        });
  }
};
</script>

<style scoped>
</style>