<template>

</template>

<script>
    export default {
        name: "ShowFlashMessage",
        props: ['message'],
        created() {
            if(this.message){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: this.message
                });
            }
        }
    }
</script>

<style scoped>

</style>
