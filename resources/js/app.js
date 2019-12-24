require('./bootstrap');

window.Vue = require('vue');

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
Vue.component('v-select', vSelect)

Vue.filter('number_format', function (number, decimals = 2, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
});


// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('member-ledger', require('./components/Ledger/MemberLedger.vue').default);
Vue.component('new-ledger-entry', require('./components/Ledger/NewLedgerEntry.vue').default);
Vue.component('edit-ledger-entry', require('./components/Ledger/EditLedgerEntry.vue').default);
Vue.component('new-long-term-loan', require('./components/LongTermLoans/NewLongTermLoan.vue').default);
Vue.component('long-term-loan-repayment', require('./components/LongTermLoans/LongTermLoanRepayment.vue').default);
Vue.component('new-short-term-loan', require('./components/ShortTermLoans/NewShortTermLoan.vue').default);
Vue.component('short-term-loan-repayment', require('./components/ShortTermLoans/ShortTermLoanRepayment.vue').default);
Vue.component('new-commodity-loan', require('./components/Commodity/NewCommodityLoan.vue').default);
Vue.component('commodity-loan-repayment', require('./components/Commodity/CommodityLoanRepayment.vue').default);
Vue.component('monthly-savings-withdrawal', require('./components/MonthlySavings/Withdrawal.vue').default);
Vue.component('add-user', require('./components/Users/AddUser.vue').default);

const app = new Vue({
    el: '#app',
});
