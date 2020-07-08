<template>
    <div>
        <p>Please enter number to generate QR Code</p>
        <input class="form-control" v-model="number" placeholder="Enter Number">
        <br>
        <button id="btn-no-of-qr" @click.prevent="QrCodeGenerate(number)" class="btn btn-success pull-right">
            Generate
        </button>
    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        data() {
            return {
                number : ''
            }
        },

        methods: {
            QrCodeGenerate(number){
                if(!/^([1-9][0-9]{1,3}|10000)$/.test(number)){
                    this.$swal("Error!", "Please Enter Valid Number from 10 to 10,000", "error");
                    return false;
                }
                this.$swal({
                    title: 'Are you sure?',
                    text: "Do you want to make "+ number +" number of QrCodes ?",
                    footer: 'This will generate and download',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<i class="fa fa-download"></i> Yes, Generate !',
                }).then((willGenerate) => {
                    if (willGenerate.value) {
                            const key = this.$dlg.mask('Processing, please hold on a moment...');
                            axios.post('/api/aamakomaya-qrcode-download',{
                                number : this.number
                            },{
                                responseType: 'blob'
                            })
                                .then(response => {
                                    const url = window.URL.createObjectURL(new Blob([response.data]), { type: 'application/x-zip' });
                                    const link = document.createElement('a');
                                    link.href = url;
                                    link.setAttribute('download', 'qr-codes.zip'); //or any other extension
                                    document.body.appendChild(link);
                                    link.click();
                                    this.$dlg.close(key);
                                    this.$swal.fire({
                                        position: 'top-end',
                                        title: 'Zip file has been saved',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    location.reload();
                                })
                    }else {
                        this.$swal("Error!", "Failed to Generate!", "error");
                    }
                }).catch(() => {
                    this.$swal("Error!", "Failed to Generate!", "error");
                })            
            }
        }
    }
</script>
