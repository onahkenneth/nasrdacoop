<template>
  <div>
    <div class="row">
      <div class="col-12">
        <h6>Loan Details</h6>
        <form @submit.prevent="submitForm">
          <div class="form-group row">
            <label for="ippis" class="col-sm-3 col-form-label">IPPIS</label>
            <div class="col-sm-9">
              <input v-model="withdrawal.ippis" type="text" class="form-control" disabled />
              <small v-if="errors.ippis" class="text-danger">{{ errors.ippis[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="pf" class="col-sm-3 col-form-label">Withdrawal Date</label>
            <div class="col-sm-9">
              <input v-model="withdrawal.withdrawal_date" type="date" class="form-control" />
              <small v-if="errors.withdrawal_date" class="text-danger">{{ errors.withdrawal_date[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="pf" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
              <input v-model="withdrawal.ref" type="text" class="form-control" />
              <small v-if="errors.ref" class="text-danger">{{ errors.ref[0] }}</small>
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Amount</label>
            <div class="col-sm-9">
              <input
                v-model="withdrawal.total_amount"
                type="text"
                class="form-control"
                :max="max_deductable_savings_amount"
              />
              <small
                class="float-right"
              >Max deductible from savings: &#8358; {{ max_deductable_savings_amount | number_format }}</small>
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
  name: "Withdrawal",
  props: {
    staff: {
      required: true
    }
  },
  data() {
    return {
      withdrawal: {
        ref: null,
        withdrawal_date: null,
        ippis: null,
        total_amount: null
      },
      errors: [],
      max_deductable_savings_amount: 0,
      last_long_term_payment: 0,
      last_monthly_saving: 0
    };
  },
  methods: {
    submitForm: function() {
      if (this.withdrawal.total_amount > this.max_deductable_savings_amount) {
        alert("Amount exceeds Maximum Allowed from savings");
        return;
      }

      const confirmation = confirm("Are you sure?");

      if (!confirmation) {
        return;
      }

      axios
        .post(
          `members/post-savings-withdrawal/${this.staff.ippis}`,
          this.withdrawal
        )
        .then(res => {
          // console.log(res.data);
          var getUrl = window.location;
          var baseUrl =
            getUrl.protocol +
            "//" +
            getUrl.host +
            "/" +
            getUrl.pathname.split("/")[1];
          window.location.href =
            baseUrl + `/public/members/${this.staff.ippis}/savings`;
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
    this.withdrawal.ippis = this.staff.ippis;
    axios
      .get(`members/${this.staff.ippis}/savings-withdrawal`, this.loan)
      .then(res => {
        // console.log(res.data);
        this.last_long_term_payment = res.data.last_long_term_payment
          ? res.data.last_long_term_payment
          : 0;
        this.last_monthly_saving = res.data.last_monthly_saving;
        if (this.last_long_term_payment == 0) {
          this.max_deductable_savings_amount = this.last_monthly_saving.bal;
        } else {
          this.max_deductable_savings_amount =
            this.last_monthly_saving.bal - this.last_long_term_payment.bal / 2;
        }
      })
      .catch(e => {
        console.log(e);
      });
  }
};
</script>

<style scoped>
</style>