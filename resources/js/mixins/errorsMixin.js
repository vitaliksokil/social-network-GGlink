export const errorsMixin ={
    methods:{
        showError(error){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.response.data.message,
            });
        }
    }
}
