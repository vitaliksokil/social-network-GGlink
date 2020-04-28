<template>
    <div class="message-composer">
        <div class="row">
            <div class="col-lg-11">
                <textarea class="form-control" v-model="message" placeholder="Message..."
                @keydown.enter="send"></textarea>
            </div>
            <div class="col-lg-1">
                <button class="btn btn-primary h-100 m-0" @click="send">Send</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "messageComposer",
        data(){
            return{
                message:''
            }
        },
        methods:{
            send(e){
                e.preventDefault();
                if(this.message == ''){
                    return
                }
                this.$emit('send',this.message);
                this.message=""
            }
        }
    }
</script>

<style scoped>

</style>
