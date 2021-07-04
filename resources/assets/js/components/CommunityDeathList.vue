<template>
  <div>
    <filterable v-bind="filterable">
      <thead slot="thead">
      <tr>
        <th width="10px"></th>
        <th>Name</th>
        <th>Age</th>
        <th>Phone</th>
        <th>Address</th>
        <th v-show="checkReportingUser()">Organization</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
      </thead>
      <tr slot-scope="{item}">
        <td>
        </td>
        <td>{{item.name}}</td>
        <td>{{item.age}}</td>
        <td>{{item.phone}}</td>
        <td>{{item.municipality?(item.municipality.district_name +', '+ item.municipality.municipality_name+ '-'+item.ward)+', ':''}}{{ item.address }}</td>
        <td v-show="checkReportingUser()">{{item.organization.name}}</td>
        <td>{{ item.created_at }}</td>
        <td>
          <div v-show="checkEditButton()">
            <button v-on:click="editData(item.id)" class="btn btn-primary btn-sm" title="Update Data">
              <i class="fa fa-edit" aria-hidden="true"> Update</i>
            </button>
          </div>
          <div v-show="checkDeleteButton()">
            <button v-on:click="deleteData(item.id)" class="btn btn-danger btn-sm" title="Delete Data">
              <i class="fa fa-trash" aria-hidden="true"> Delete</i>
            </button>
          </div>
        </td>
        <!-- </div>             -->
      </tr>
      <!--            <span>Selected Ids: {{ item }}</span>-->

    </filterable>
  </div>
</template>

<script type="text/javascript">
import Filterable from './CasesPaymentFilterable.vue'
import axios from "axios";

export default {
  components: {Filterable},
  data() {
    return {
      filterable: {
        url: '/data/api/community-deaths',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Created At', name: 'created_at'}
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'phone', type: 'string'},
              {title: 'Created At', name: 'created_at', type: 'datetime'}
            ]
          }
        ]
      }
    }
  },
  methods: {
    editData : function (id){
      window.open(
          '/admin/community-deaths/create?token=' + id,
          '_blank'
      );
    },
    deleteData : function(id){
      this.$swal({
        title: "Are you sure?",
        text: "You won\'t able to to retrieve this data.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      }).then((result) => {
        if (result.value) {
          axios.post('/api/v1/community-deaths/delete', {'id':id})
              .then((response) => {
                if (response.data.message === 'success') {
                  this.$swal({
                    title: 'Record Deleted',
                    type: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  })
                  if (this.item){
                    this.$dlg.closeAll(function(){
                      // do something after all dialog closed
                    })
                  }
                } else {
                  this.$swal({
                    title: 'Oops. No record found.',
                    type: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  })
                }
              })
        } else {
          this.$swal("Cancelled", "Your imaginary data is safe :)", "error");
        }
      })
    },
    checkEditButton(){
      var arr = this.$userPermissions.split(',');
      return this.$userRole === 'healthpost' || this.$permissionId === '1';
    },
    checkDeleteButton() {
      // main super admin token
      return this.$userSessionToken === '5a4425' || this.$permissionId === '1';
    },
    checkReportingUser(){
      return this.$userRole==='main'||this.$userRole==='center'||this.$userRole==='province'||this.$userRole==='dho'||this.$userRole==='municipality';
    }
  }
}
</script>