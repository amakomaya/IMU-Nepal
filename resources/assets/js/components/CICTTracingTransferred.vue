<template>
  <div>
    <filterable v-bind="filterable">
      <thead slot="thead">
      <tr>
        <th>Case ID</th>
        <th>Name</th>
        <th>Age</th>
        <th title="Gender">G</th>
        <th>District</th>
        <th>Municipality</th>
        <th>Positive Result Date</th>
        <th><i class="fa fa-mobile" aria-hidden="true"></i> / <i class="fa fa-desktop fa-sm" aria-hidden="true"></i></th>
        <th></th>
      </tr>
      </thead>
      <tr slot-scope="{item, removeItemOnSuccess}">
        <td>{{ item.case_id }}</td>
        <td>{{ item.name }}</td>
        <td>{{ item.age }}</td>
        <td>{{ sex(item.sex )}}</td>
        <td>{{ item.district.district_name }}</td>
        <td>{{ item.municipality.municipality_name }}</td>
        <td>
          {{ customDate(item.suspected_case) }}
        </td>
        <td>
            <span v-if="item.regdev === 'web'" title="Web" class="label label-info">W</span>
            <span v-else title="Mobile" class="label label-success">M</span>
        </td>
        <td>
            <button v-on:click="cictUpdateData(item.case_id)" class="btn btn-warning btn-sm" title="CICT Create">
                <i class="fa fa-plus" aria-hidden="true"> Create</i>
            </button>
            <button v-if="permission == 1" v-on:click="deletePatientData(item, removeItemOnSuccess)" class="btn btn-danger btn-sm" title="Delete Data">
            <i class="fa fa-trash" aria-hidden="true"> Delete</i>
            </button>
        </td>
      </tr>
    </filterable>
  </div>
</template>

<script type="text/javascript">
import Filterable from './CICTFilterable.vue'
import axios from "axios";

export default {
  components: {Filterable},
  data() {
    return {
      permission: this.$permissionId,
      filterable: {
        url: '/data/api/cict-transferred-list',
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
              {title: 'Created At', name: 'created_at', type: 'datetime'}
            ]
          }
        ]
      }
    }
  },
  methods: {
    cictUpdateData : function (id){
      window.open(
          '/admin/cict-tracing/section-one?case_id=' + id,
          '_self'
      );
    },
    contactList : function (id){
      window.open(
          '/admin/cict-tracing/contact-list/' + id,
          '_self'
      );
    },
    cictReport : function (id){
      window.open(
          '/admin/cict-tracing/report/' + id,
          '_self'
      );
    },

    deletePatientData: function (item, removeItemOnSuccess) {
      this.$swal({
        title: "Are you sure?",
        text: "Your data will be deleted.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      }).then((result) => {
        if (result.value) {
          axios.post('/admin/cict-tracing/' + item.token, {_method: 'DELETE'})
              .then((response) => {
                if (response.data.message === 'success') {
                  removeItemOnSuccess(item);
                  this.$swal({
                    title: 'Record Deleted',
                    type: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                  })
                  this.healthpostSelected = null;
                  
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
          this.$swal("Cancelled", "Data not deleted :)", "error");
        }
      })
    },
    sex(type) {
      switch (type) {
        case '1':
          return 'M';
        case '2':
          return 'F';
        default:
          return 'O';
      }
    },
    checkEditButton(){
      var arr = this.$userPermissions.split(',');
      return this.$userRole === 'healthworker';
    },

    formattedDate(data) {
      if(data == null) {
        return '';
      }else {
        var date_array = data.split('-');
        switch (date_array[1]) {
          case '1':
          case '01':
            return date_array[2] + " Baishakh, " + date_array[0];

          case '2':
          case '02':
            return  date_array[2] + " Jestha, " + date_array[0];

          case '3':
          case '03':
            return  date_array[2] + " Ashad, " + date_array[0];

          case '4':
          case '04':
            return  date_array[2] + " Shrawan, " + date_array[0];

          case '5':
          case '05':
            return  date_array[2] + " Bhadra, " + date_array[0];

          case '6':
          case '06':
            return  date_array[2] + " Ashwin, " + date_array[0];

          case '7':
          case '07':
            return  date_array[2] + " Karthik, " + date_array[0];

          case '8':
          case '08':
            return  date_array[2] + " Mangsir, " + date_array[0];

          case '9':
          case '09':
            return  date_array[2] + " Poush, " + date_array[0];

          case '10':
            return  date_array[2] + " Magh, " + date_array[0];

          case '11':
            return  date_array[2] + " Falgun, " + date_array[0];

          case '12':
            return  date_array[2] + " Chaitra, " + date_array[0];

          default:
            return '';
        }
      }
    },
    customDate(data){
      if(data == null) {
        return '';
      }else{
        if(data.latest_anc == null){
          return '';
        }else{
          return this.formattedDate(data.latest_anc.sample_test_date_np);
        }
      }
    }
  }
}
</script>