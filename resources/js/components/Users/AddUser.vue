<template>
  <div>
    <div class="row">
      <div class="col-md-8">
        <h6>Add User</h6>
        <form @submit.prevent="submitForm">
          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Select member</label>
            <div class="col-sm-9">
              <v-select v-model="user.staff" label="full_name" :options="staffs" @input="onSelectStaff($event)"></v-select>
              <small v-if="errors.ippis" class="text-danger">{{ errors.ippis[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Select Role(s)</label>
            <div class="col-sm-9">
              <v-select multiple v-model="user.roles" label="name" :options="roles" @input="onSelectRole($event)"></v-select>
              <small v-if="errors.roles" class="text-danger">{{ errors.roles[0] }}</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="coop_no" class="col-sm-3 col-form-label">&nbsp</label>
            <div class="col-sm-9">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-4">
        <h6>Permissions</h6>

            <ul v-for="(permission, index) in permissions" :key="index" class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{permission.name}}
                    <a @click.prevent="removePermission(index)"><span class="badge badge-danger badge-pill">remove</span></a>
                </li>
            </ul>
        
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
export default {
  name: "AddUser",
  components: {
      'multiselect' : Multiselect
  },
  data() {
    return {
      user: {
        staff: null,
        ippis: null,
        roles: [],
        droppedPermissions: [],
      },
      errors: [],
      staffs: [],
      roles: [],
      permissions: [],
    };
  },
  methods: {
    removePermission: function (index) {
      this.user.droppedPermissions.push(this.permissions[index])
      this.permissions.splice(index, 1)
    },
      onSelectStaff: function(staff) {
        console.log(staff)
        this.user.ippis = staff.ippis
      },
      onSelectRole: function(roles) {
        // let role = roles[roles.length - 1]
        // console.log(role)
        axios
            .post(`role-permissions`, {roles})
            .then(res => {
            console.log(res.data);
            this.permissions = res.data
            })
            .catch(e => {
            console.log(e);
            });
      },
    submitForm: function() {

      axios
        .post(`users`, this.user)
        .then(res => {
          // console.log(res.data);
          var getUrl = window.location;
          var baseUrl =
            getUrl.protocol +
            "//" +
            getUrl.host +
            "/" +
            getUrl.pathname.split("/")[1];
            window.location.href = baseUrl + `/public/users`;
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
      axios
        .get(`users/create`)
        .then(res => {
          // console.log(res.data);
          this.staffs = res.data.staffs     
          this.roles = res.data.roles    
        })
        .catch(e => {
          console.log(e);
        });
  }
};
</script>

<style scoped>
</style>