<template>
        <div class="card">
            <div class="card-header">
                <h3>Messages</h3>
                <form class="form-inline my-2 my-lg-0 w-100">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search"
                           aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                        class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">


                        <a v-for="conversationWithUser in sortedContacts" :key="conversationWithUser.id"
                           :href='`/conversation/${conversationWithUser.nickname}/${conversationWithUser.id}`'
                           class="subscribe-item mb-4 p-3" :class="{'new-msg-bg': conversationWithUser.unreadMessagesCount > 0}"
                           style="border: 1px solid rgba(0,0,0,0.9);display: block"
                        >
                            <div class="row align-items-center">
                                <div class="col-lg-1 ">
                                    <div class="wall-post-img">
                                        <a :href="'/profile/id/'+conversationWithUser.id">
                                            <img :src="conversationWithUser.photo" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-11">
                                    <div class="wall-post-author">
                                        <h6>
                                            <a :href="'/profile/id/'+conversationWithUser.id" v-html="fullName(conversationWithUser.name,conversationWithUser.nickname,conversationWithUser.surname)">
                                            </a>

                                            <span class="green" v-if="conversationWithUser.isOnline"><i class="fas fa-circle" ></i> Online</span>
                                            <span class="blocks" v-else><i class="fas fa-circle" ></i> Offline</span>

                                            <span class="green float-right" style="font-size: 20px"
                                            v-if="conversationWithUser.unreadMessagesCount > 0">+{{conversationWithUser.unreadMessagesCount}}</span>

                                        </h6>
                                        <div class="row align-items-center mt-3">
                                            <div class="col-sm-1">
                                                <img :src="authUser.photo" alt="" v-if="conversationWithUser.lastMessage.from == authUser.id" style="width: 60%">
                                            </div>
                                            <div class="col-sm-11 p-0">
                                                <p class="m-0">
                                                    {{conversationWithUser.lastMessage.text}}
                                                    <small class="float-right mr-3">{{conversationWithUser.lastMessage.created_at}}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>



                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import {userMixin} from '../mixins/userMixin'
    import {mapState} from 'vuex'
    export default {
        mixins:[userMixin],
        props:['authUser','messages'],
        data(){
            return{
                conversationsWithUsers: JSON.parse(JSON.stringify(this.messages)),
            }
        },
        mounted() {

        },
        methods:{
            addNewMessage(newMessage){
                for(let key in this.conversationsWithUsers){
                    if(this.conversationsWithUsers[key].id == newMessage.from_user.id){
                        this.conversationsWithUsers[key].unreadMessagesCount++;
                        this.conversationsWithUsers[key].lastMessage = {
                            'text':newMessage.message.text,
                            'created_at':newMessage.message.created_at,
                        };
                        break;
                    }
                }
            },
        },
        computed:{
          ...mapState(['isNewMessage']),
            sortedContacts(){
                return _.sortBy(this.conversationsWithUsers,[(user)=>{
                    return user.lastMessage.created_at;
                }]).reverse();
            }
        },
        watch:{
            isNewMessage(newValue,oldValue){
                this.addNewMessage(newValue);
            }
        }
    }
</script>
