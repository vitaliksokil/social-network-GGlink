<template>
    <div class="card">
        <div class="card-header pt-0">
            <div class="actions-panel mb-3">
                <ul class="nav d-flex justify-content-between">
                    <li>
                        <a :href='`/rooms/of/${game_short_address}`' class="nav-link ">
                            <i class="fas fa-arrow-left blue"></i>
                            Go back
                        </a>
                    </li>
                    <li class="mr-auto" v-if="authUserID == room.creator_id">
                        <a class="nav-link" @click="lockTheRoom">
                            <i class="fas fa-lock orange"></i>
                            Lock/unlock the room
                        </a>
                    </li>

                    <li v-if="authUserID == room.creator_id">
                        <a class="nav-link " @click.prevent="deleteRoom(room.id)">
                            <i class="fas fa-times red"></i>
                            Delete room
                        </a>
                    </li>
                </ul>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <div>
                    <h3>{{room.title}}</h3>
                    <h5>Room #{{room.id}}</h5>


                    <a class="btn btn-primary" @click.prevent="joinToTheTeam" v-if="canJoin()">
                        <i class="fas fa-plus"></i>
                        Click to join the team
                    </a>
                </div>
                <div>
                    <i class="fas fa-lock red" style="font-size: 60px" v-if="isLocked" title="Room is locked"></i>
                    <i class="fas fa-lock-open green" style="font-size: 60px" v-else title="Room is unlocked"></i>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 mb-3" v-for="member in teamMembers">
                    <div class="card text-center h-100">
                        <a :href="`/profile/id/${member.member_id}`" target="_blank">
                            <img :src="`/${member.photo}`" class="card-img-top members-size" alt="">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <a :href="`/profile/id/${member.member_id}`" target="_blank">
                                <small class="card-text"> <span class="pink">{{member.nickname}}</span></small>
                            </a>
                            <h4 class="green mt-auto" v-if="member.member_id == room.creator_id">CREATOR</h4>
                            <button class="btn btn-danger mt-auto"
                                    v-if="room.creator_id == authUserID && member.member_id != room.creator_id"
                                    @click.prevent="kickUser(member.member_id)">Kick
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2  mb-3" v-for="i in room.count_of_members - teamMembers.length">
                    <div class="card text-center h-100">
                        <i class="fas fa-plus card-img-top room-member-plus members-size h-100"
                           title="Join to the team"></i>
                    </div>
                </div>

            </div>

        </div>
        <div class="card-footer">
            <div class="conversation" style="height: 300px" ref="chat">
                <div class="row w-100">
                    <div class="col-lg-12 ">
                        <div v-for="message_item in allMessages" class="subscribe-item">
                            <div class="row align-items-start">
                                <div class="col-lg-1 ">
                                    <div class="wall-post-img">
                                        <a :href="`/profile/id/${message_item.sender_id}`" target="_blank">
                                            <img :src="`/${message_item.photo}`" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-11">
                                    <div>
                                        <a :href="`/profile/id/${message_item.sender_id}`"
                                           v-html="fullName(message_item.name,message_item.nickname,message_item.surname)"
                                           target="_blank">
                                        </a>
                                    </div>
                                    <p class="ml-4 mt-2">
                                        {{message_item.message}}
                                        <small class="float-right ">{{ message_item.created_at}}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <message-composer @send="sendMessage"></message-composer>
        </div>
        <div class="list-of-joined-users card">
            <h4 class="text-center">Joined users</h4>
            <ul class="nav d-flex flex-column">
                <li class="nav-link " v-for="member in members">
                    <div class="row align-items-center">
                        <div class="col-lg-3">
                            <a :href="`/profile/id/${member.member_id}`" target="_blank">
                                <img :src="`/${member.photo}`" alt="">
                            </a>
                        </div>
                        <a :href="`/profile/id/${member.member_id}`" target="_blank" class="col-lg-7"
                           v-html="fullName(member.name, member.nickname, member.surname)"
                           style="font-size: 13px"
                        >

                        </a>
                        <div class="col-lg-1" v-if="room.creator_id == authUserID && member.member_id != authUserID"
                             @click.prevent="kickUser(member.member_id)">
                            <i class="fas fa-user-minus red" style="cursor:pointer" title="Kick user from room"></i>
                        </div>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</template>

<script>
    import {userMixin} from "../../mixins/userMixin";
    import {errorsMixin} from "../../mixins/errorsMixin";

    import MessageComposer from "../messages/MessageComposer"

    export default {
        name: "Room",
        mixins: [userMixin, errorsMixin],
        components: {
            'message-composer': MessageComposer
        },
        props: ['room', 'game_short_address', 'inTeamMembers', 'joinedMembers', 'messages'],
        data() {
            return {
                authUserID: $('meta[name="auth-user-id"]').attr('content'),
                members: JSON.parse(JSON.stringify(this.joinedMembers)),
                teamMembers: JSON.parse(JSON.stringify(this.inTeamMembers)),
                isLocked: JSON.parse(JSON.stringify(this.room.is_locked)),
                allMessages: JSON.parse(JSON.stringify(this.messages)),
                isRoomDeleted: false,
                _wasPageCleanedUp: false
            }
        },
        created() {
            this.sendTypingFalse = debounce(this.sendTypingFalse, 3000);
            this.scrollToBottom();


            $(window).on('beforeunload', () => {
                //this will work only for Chrome
                this.leaving();
            });

            $(window).on("unload", () => {
                //this will work for other browsers
                this.leaving();
            });
            //
            // window.onbeforeunload = this.leaving;  // for chrome
            // window.unload = this.leaving;  // for other browsers

                // join the room
            axios.post('/room/new/member', {
                'member_id': this.authUserID,
                'room_id': this.room.id
            }).then(response => {
                //
            }).catch(error => {
                Swal.fire({
                    title: error.response.data.message,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    location.assign("/rooms/of/" + this.game_short_address);
                });
                setTimeout(() => {
                    location.assign("/rooms/of/" + this.game_short_address);
                }, 5000);
                return;
            });


            Echo.channel(`room.${this.room.id}`)
                .listen('RoomInsideEvent', (data) => {
                    if (data.newMember) {
                        this.addNewMember(data.newMember);
                    } else if (data.deletedMember) {
                        if (data.deletedMember.member_id == this.authUserID && data.deletedMember.is_kicked) {
                            // if the user was kicked from the room -> redirect him to rooms of game page with message
                            Swal.fire({
                                title: 'You have been kicked from the room!!!',
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                location.assign("/rooms/of/" + this.game_short_address);
                            });
                            setTimeout(() => {
                                location.assign("/rooms/of/" + this.game_short_address);
                            }, 5000);
                            return;
                        }
                        if (data.deletedMember.is_joined == 0) {
                            this.deleteMemberFromList(data.deletedMember);
                        } else {
                            this.removeFromTeamList(data.deletedMember);
                        }
                    } else if (data.joinTeam) {
                        this.deleteMemberFromList(data.joinTeam);
                        this.addMemberToTeamList(data.joinTeam);
                    } else if (data.lockRoom != undefined) {
                        this.isLocked = data.lockRoom;
                    } else if (data.newMessage) {
                        this.addNewMessage(data.newMessage);
                    }
                });


        },
        methods: {
            joinToTheTeam() {
                axios.post('/room/add/member/to/team', {
                    'room_id': this.room.id
                }).catch(error => {
                    this.showError(error);
                })
            },
            addMemberToTeamList(member) {
                this.teamMembers.push(member);
            },
            removeFromTeamList(member) {
                for (let key in this.teamMembers) {
                    if (this.teamMembers[key].member_id == member.member_id) {
                        this.teamMembers.splice(key, 1);
                        return
                    }
                }
            },


            deleteMemberFromList(deletedMember) {
                for (let key in this.members) {
                    if (this.members[key].member_id == deletedMember.member_id) {
                        this.members.splice(key, 1);
                        return
                    }
                }
            },
            addNewMember(newMember) {
                this.members.push(newMember)
            },


            leaving() {
                let async = navigator.userAgent.indexOf('Firefox') != -1 ? false : true;

                if (!this.isRoomDeleted) { // check if room was not deleted!
                    // for mozilla async : false, chrome, others - true
                    if (!this._wasPageCleanedUp) {
                        $.ajax({
                            type: 'DELETE',
                            async: async,
                            url: '/room/delete/member',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: {
                                'member_id': this.authUserID,
                                'room_id': this.room.id,
                            },
                            success: function () {
                                this._wasPageCleanedUp = true;
                            }
                        });
                    }
                }
            },
            deleteRoom(roomID) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/room/delete/' + this.game_short_address + '/' + roomID).then((response) => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!!!',
                                text: response.data.message,
                            });
                            this.isRoomDeleted = true;
                            //redirect to rooms with the game
                            window.location.href = "/rooms/of/" + this.game_short_address;

                        }).catch(error => {
                            this.showError(error);
                        });
                    }
                })
            },
            canJoin() {
                // check if user is user is in team -> if so return false
                for (let key in this.teamMembers) {
                    if (this.teamMembers[key].member_id == this.authUserID && this.teamMembers[key].is_joined == 1) {
                        return false;
                    }
                }
                // check if there is no empty slots for join return false
                if (this.teamMembers.length == this.room.count_of_members) return false;
                // check if user is in the room and his param is_joined = 0
                for (let key in this.members) {
                    if (this.members[key].member_id == this.authUserID && this.members[key].is_joined == 0 || this.members[key].is_joined == null) {
                        return true;
                    }
                }

            },
            lockTheRoom() {
                if (this.room.creator_id == this.authUserID) {
                    // request to lock the room!!!
                    axios.put('/room/lock/unlock', {
                        'room': this.room
                    }).then(response => {

                    }).catch(error => {
                        this.showError(error);
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You are not a creator of the room!'
                    });
                    return;
                }
            },
            kickUser(userID) {
                if (this.room.creator_id == this.authUserID) {
                    // request to lock the room!!!
                    axios.delete('/room/kick/member/' + userID + '/' + this.room.id).then(response => {

                    }).catch(error => {
                        this.showError(error);
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You are not a creator of the room!'
                    });
                    return;
                }
            },

            sendMessage(message) {
                axios.post('/room/add/message', {
                    'room_id': this.room.id,
                    'message': message
                }).catch(error => {
                    this.showError(error);
                });
            },
            addNewMessage(newMessage) {
                this.allMessages.push(newMessage);
                this.scrollToBottom();
            },
            scrollToBottom() {
                setTimeout(() => {
                    this.$refs.chat.scrollTop = this.$refs.chat.scrollHeight - this.$refs.chat.clientHeight;
                }, 50);
            }


        }
    }
</script>

<style scoped>

</style>
