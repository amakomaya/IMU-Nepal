<template>
    <div class="text-center">
        <div v-if="!data.username"> <h3>User Doesnot Exist !!! Please Create</h3> </div>
        <div v-if="data" width='100%"'>
            <br>
           <form>
                <div class="form-group row">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                    <input type="username" class="form-control" id="username" v-model.trim="data.username" placeholder="Username">
                    <div class="text-danger" v-if="!$v.data.username.required">Username Field is required</div>
                    <div class="text-danger" v-if="!$v.data.username.minLength">Username must have at least {{ $v.data.username.$params.minLength.min }} letters.</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputPassword3" v-model="data.changed_password" placeholder="Enter for new Password ( default password : 123456 )">
                    <div class="text-danger" v-if="!$v.data.changed_password.optionalPassword">Password Field is optional or must have 5 character</div>
                    </div>
                </div>
                <button type="button" @click.prevent="updateUser(data)" v-if="!$v.data.$invalid" class="btn btn-primary btn-lg btn-block">Update</button>
                <button type="button" @click="$dlg.close()" class="btn btn-secondary pull-right">Cancel</button>
           </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { required, minLength, helpers } from 'vuelidate/lib/validators'

const optionalPassword = helpers.regex('optionalPassword', /^(?:.{5,}|)$/);

export default {
    props: {
            data: Object,
        },
  data() {
    return {
       errorMessage : '',
    }
  },
  methods : {
        updateUser: function(data) {
            axios.put('/data/api/women/user/' + data.token, data)
                    .then((response) => {
                        if (response.data.success === 1) {
                            this.$dlg.close();
                            this.$dlg.toast(response.data.message,{
                                    language: 'en',
                                    messageType: 'success',
                                    closeTime: 3,
                                    position : 'topRight'
                            })
                        } else {
                            this.errorMessage = response.data.message;
                            this.$dlg.toast(this.errorMessage,{
                                    language: 'en',
                                    messageType: 'error',
                                    closeTime: 3,
                                    position : 'topRight'
                            })
                        }
                    })
        }
    },
    validations: {
      data : {  
        username: {
            required,
            minLength: minLength(5)
        },
        changed_password:{
            optionalPassword,
        }
    }
  },
}
</script>