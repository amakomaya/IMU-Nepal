<template>
<div>
  <div>
    <button class="btn btn-primary btn-sm pull-right" @click="print" title="Print"><i class="fa fa-print"></i> Print</button>
  </div>
    <hr>
  <div id="qr-printMe">
      <div class="qr-card">
        <img src="/images/immunization_card.png" width="350px" height="200px">
        <div class="qr_code">
          <qr-code 
              :text=data.token
              size=85
          ></qr-code>
        </div>
        <div class="municipal-info">
            <p class="qr-label">{{ municipality }}<br> स्वास्थ्य महाशाखा </p>
        </div>
        <div class="personal-info">
            <p>Baby Name: {{ data.baby_name }}</p>
            <p>Mother Name: {{ data.mother_name }}</p>
            <p>Contact No: {{ data.contact_no }}</p>
        </div>
      </div>
  </div>
</div>
</template>
<script>
    import axios from 'axios'

export default {
    props: {
            data: Object,
        },
  data() {
    return {
      municipality : String,
    }
  },
  created() {
            this.fetch()
        },
  methods: {
    fetch() {
                let url = window.location.protocol + '/api/municipality';
                axios.get(url)
                    .then((response) => {
                        var response = response.data;
                        const newArray = Object.keys(response).map(i => response[i]);
                        this.municipality = newArray[this.data.organizational_information.municipality_id-1].municipality_name;
                    })
                    .catch((error) => {
                        console.error(error)
                    })
                    .finally(() => {
                    })
            },
    print() {
     // Get HTML to print from element
      const prtHtml = document.getElementById('qr-printMe').innerHTML;

      // Get all stylesheets HTML
      let stylesHtml = '';
      for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
        stylesHtml += node.outerHTML;
      }

      // Open the print window
      const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

      WinPrint.document.write(`<!DOCTYPE html>
      <html>
        <head>
          ${stylesHtml}
        </head>
        <body>
          ${prtHtml}
        </body>
      </html>`);

      WinPrint.document.close();
      WinPrint.focus();
      // WinPrint.print();
      // WinPrint.close();
      }
    } 
  }
</script> 

<style>

.qr-card {
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  display: grid;
  font-family: 'Trebuchet MS', sans-serif;
  height: 200px;
  margin: 20px auto;
  width: 350px;
}

.qr_code{
    position: absolute;
    margin: 3.7em 0 0 2.4em;
}
 
.qr-label{ 
  line-height: 1pc;
  font-weight: bolder;
  color: #212121;

} 

.personal-info p:nth-of-type(1) {
  font-size: 10px;
  padding: 2px 0 0 30px;
  margin: 55px 0 0 0;
  }
.personal-info p:nth-of-type(2) {
  font-size: 10px;
  padding: 2px 0 0 30px;
  margin: 15px 0 0 0;
  }

  .personal-info p:nth-of-type(3) {
  font-size: 10px;
  padding: 2px 0 0 30px;
  margin: 15px 0 0 0;
  }

img{
    margin: 0;
    padding: 0;
}

.municipal-info{
  position: absolute;
  padding: 0;
  line-height: 5px;
  margin: 6px 0 0 0;
  padding: 2px 0 0 30px;
  word-wrap: break-word;
}

.municipal-info p:nth-of-type(1){
  text-align: center;
  font-size: 11px;
  margin: 3em 0 0 16em;
}
.personal-info{
    color:#212121;
    font-weight: bolder;
    position: absolute;
    margin-top: 2em;
    margin-left: 11.2em;
    padding: 0;
    line-height: 5px;
}
</style>