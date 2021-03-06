<template>
    <div class="row justify-content-sm-center">
        <div class="col-lg-10 col-sm-10 col-xxl-12">
            <div class="card conversation-wrap">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-1 mb-sm-1" style="border-right:1px solid rgba(0,0,0,0.9)">
                            <a href="/messages" class="btn m-0" style="background: rgba(255,255,255,0.6)">
                                <i class="fas fa-long-arrow-alt-left"></i>
                                Back
                            </a>
                        </div>
                        <div class="col-lg-1 col-sm-2">
                            <a :href="`/profile/id/${userConversationWith.id}`">
                                <img :src="`/${userConversationWith.photo}`" alt="">
                            </a>
                        </div>
                        <div class="col-lg-6 col-sm-8">
                            <a :href="`/profile/id/${userConversationWith.id}`">
                                <h3
                                    v-html="fullName(userConversationWith.name,userConversationWith.nickname,userConversationWith.surname)"
                                ></h3>
                            </a>
                        </div>

                    </div>
                </div>
                <div class="card-body conversation" ref="conversation" id="conversation">
                    <div class="row">
                        <div class="col-lg-12 ">
                            <div v-for="message in allMessages" class="subscribe-item">
                                <div class="row align-items-start">
                                    <div class="col-lg-1 col-sm-2">
                                        <div class="wall-post-img">
                                            <a :href="`/profile/id/${userConversationWith.id}`"
                                               v-if="message.from == userConversationWith.id">
                                                <img :src="`/${userConversationWith.photo}`" alt="">
                                            </a>
                                            <a :href="`/profile/id/${authUser.id}`" v-else>
                                                <img :src="`/${authUser.photo}`" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-11 col-sm-10">
                                        <div v-if="message.from == userConversationWith.id">
                                            <a :href="`/profile/id/${userConversationWith.id}`"
                                               v-html="fullName(userConversationWith.name,userConversationWith.nickname,userConversationWith.surname)">
                                            </a>
                                            <small class="float-right">{{message.created_at}}</small>
                                        </div>
                                        <div v-else>
                                            <a :href="`/profile/id/${authUser.id}`"
                                               v-html="fullName(authUser.name,authUser.nickname,authUser.surname)">
                                            </a>
                                        </div>

                                        <p class="ml-4 mt-2" :class='{"new-msg-bg" : !m.is_read}' :data-msg-from="message.from" v-for="m in message.messages">
                                            {{m.text}}<small
                                            class="float-right ">{{m.created_at}}</small>
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2 position-relative" >
                        <div class="col-lg-12 position-absolute">
                            <!--                    -->
                            <div class="grey" v-show="isTyping">
                        <span
                            v-html="fullName(userConversationWith.name,userConversationWith.nickname,userConversationWith.surname)"></span>
                                <span>typing... <i class="fas fa-pencil-alt"></i></span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <message-composer @send="sendMessage" @typing="typing" v-if="canMessageSend"></message-composer>
                    <div v-else>
                        The user blocked his DM
                        <i class="fas fa-lock" ></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import {userMixin} from '../../mixins/userMixin'
    import MessageComposer from './MessageComposer'
    import {mapState} from 'vuex'

    export default {
        components: {
            'message-composer': MessageComposer
        },
        mixins: [userMixin],
        props: ['messages', 'userConversationWith', 'authUser'],
        data() {
            return {
                currentSender: '',
                allMessages: JSON.parse(JSON.stringify(this.messages)),
                isTyping: false,
                canMessageSend:false,
            }
        },
        created() {
            this.sendTypingFalse = debounce(this.sendTypingFalse, 3000);

            Echo.private(`messages.${this.authUser.id}`)
                .listen('NewMessage', (data) => {
                    this.handleIncoming(data.message);
                });
            // listening authUser channel with his id channel
            Echo.private(`typing.${this.authUser.id}.${this.userConversationWith.id}`)
                .listenForWhisper('typing', (response) => {
                    this.isTyping = response.isTyping;
                    this.scrollToBottom();
                });
            this.scrollToBottom();
            this.canSendMessage();
            $(document).ready(() => {
                this.scrollHandler = debounce(this.scrollHandler,400);
                $('#conversation').scroll(this.scrollHandler);

            });

        },
        methods: {
            scrollHandler(e){
                let scrollTopPoint = e.target.scrollHeight;
                if(e.target.scrollTop == 0){
                    this.loadMoreMessages();
                    setTimeout(()=>{
                        e.target.scrollTop = e.target.scrollHeight - scrollTopPoint;
                    },100)

                }
            },
            async loadMoreMessages(){
                let skip = this.countAllMessagesCount();
              axios.get(`/conversation/${this.userConversationWith.nickname}/${this.userConversationWith.id}`,{
                  params:{
                      skip:skip
                  }
              }).then((response)=>{
                  for(let item of response.data.messages){
                      this.allMessages.unshift(item);
                  }
              })
            },
            countAllMessagesCount(){
                let count = 0;
                for(let item of this.allMessages){
                    count += item.messages.length;
                }
                return count;
            },
            canSendMessage(){
                axios.post('/conversation/can-send-message',{
                    'userConversationWith':this.userConversationWith
                }).then((response)=>{
                    this.canMessageSend = response.data;
                });
            },
            typing() {
                if($(`p.new-msg-bg[data-msg-from="${this.userConversationWith.id}"]`).length){
                    this.updateMessagesAsRead();
                    this.setMessagesAsRead(this.userConversationWith.id);
                }
                // sending 'typing' to user with whom we get current conversation
                Echo.private(`typing.${this.userConversationWith.id}.${this.authUser.id}`).whisper('typing', {isTyping: true});
                this.sendTypingFalse(); // it will be send after 3 seconds ( used debounce)
            },
            updateMessagesAsRead() {
                // set messages as read in db, we don't need any response!!! we will got notification!!!
                axios.put(`/conversation/set-messages-as-read/${this.userConversationWith.id}`);
            },
            sendTypingFalse() {
                Echo.private(`typing.${this.userConversationWith.id}.${this.authUser.id}`).whisper('typing', {isTyping: false});
            },
            handleIncoming(message) {
                if (message.from == this.userConversationWith.id) {
                    this.addNewMessage(message);
                }
            },
            sendMessage(message) {
                axios.post('/conversation/send', {
                    'to': this.userConversationWith.id,
                    'text': message
                }).then((response) => {
                    this.addNewMessage(response.data);
                }).catch(error=>{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.response.data.message,
                    });
                });
            },
            addNewMessage(newMessage) {
                //check who was the last sender
                let lastMessage = this.allMessages.pop();
                if (newMessage.from == lastMessage.from) {
                    // if it was me -> add to my stack of messages
                    lastMessage.messages.push({
                        'text': newMessage.text,
                        'created_at': newMessage.created_at,
                    });
                    this.allMessages.push(lastMessage);
                } else {
                    // if not , create a new message
                    //firstly pushing back our last messages that we popped
                    this.allMessages.push(lastMessage);
                    this.allMessages.push({
                        'from': newMessage.from,
                        'to': newMessage.to,
                        'messages': [{
                            'text': newMessage.text,
                            'created_at': newMessage.created_at
                        }]
                    })
                }

                this.scrollToBottom();
            },
            scrollToBottom() {
                setTimeout(() => {
                    this.$refs.conversation.scrollTop = this.$refs.conversation.scrollHeight - this.$refs.conversation.clientHeight;
                }, 50);
            },
            setMessagesAsRead(from) {
                $(`p.new-msg-bg[data-msg-from="${from}"]`).removeClass('new-msg-bg')
            }
        },
        computed: {
            ...mapState(['isReadMessages']),
        },
        watch: {
            isReadMessages(newValue, oldValue) {
                if (newValue) {
                    this.setMessagesAsRead(newValue.from);
                }
            }
        }
    }
</script>
