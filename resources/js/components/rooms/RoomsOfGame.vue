<template>
    <div class="row justify-content-sm-center">
        <div class="col-lg-10 col-sm-10 col-xxl-12">
            <div class="card">
                <div class="card-header pt-0">
                    <div class="actions-panel mb-3">
                        <ul class="nav d-flex">
                            <li>
                                <a href='/rooms/all' class="nav-link ">
                                    <i class="fas fa-arrow-left blue"></i>
                                    Go back
                                </a>
                            </li>
                            <li>
                                <a class="nav-link " data-toggle="modal" data-target="#addRoom">
                                    <i class="fas fa-plus green"></i>
                                    Add new room
                                </a>
                            </li>
                            <li v-if="myRoom" class="ml-auto">
                                <a class="nav-link" :href="`/room/${myRoom_GameShortAddress}/${myRoom.id}`">
                                    <i class="fas fa-door-closed cyan"></i>
                                    My room
                                </a>
                            </li>
                        </ul>
                    </div>
                    <hr>
                    <h3>{{game.title + '\'s rooms'}}</h3>

                    <div class="modal fade" id="addRoom" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-center">
                                        Add new room
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form @submit.prevent="submitNewRoom">
                                        <div class="form-group row">
                                            <label for="title"
                                                   class="col-md-4 col-form-label text-md-right">Title of a room </label>
                                            <div class="col-md-6">
                                                <input id="title" v-model="newRoom.title" type="text" class="form-control" :class="{'is-invalid':errors.title}"
                                                       required>
                                                <small v-if="errors.title" class="red" v-for="errorMessage in errors.title">{{errorMessage}}</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="countOfMembers"
                                                   class="col-md-4 col-form-label text-md-right">
                                                Count of members
                                                <i class="fas fa-info-circle orange" title="From 2 to 10(including both)"></i>
                                            </label>
                                            <div class="col-md-6">
                                                <input id="countOfMembers" v-model="newRoom.count_of_members" type="number"
                                                       class="form-control" min="2" max="10" required :class="{'is-invalid':errors.count_of_members}">
                                                <small v-if="errors.count_of_members" class="red" v-for="errorMessage in errors.count_of_members">{{errorMessage}}</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card mb-3"
                         v-for="room in allRooms"  >
                        <div class="p-2" v-if="authUserId == room.creator_id" >
                            <span class="blue">Your room</span>
                            <a @click.prevent="deleteRoom(room.id)" >
                                <i class="fas fa-times red float-right" title="Delete my room"></i>
                            </a>
                        </div>
                        <a class="card-header d-flex align-items-center justify-content-between" :href='`/room/${game.short_address}/${room.id}`'>
                        <span class="col-lg-1 col-sm-3">
                            <img :src="`/${room.creator.photo}`" alt="">
                        </span>
                            <span class="col-lg-8 col-sm-6">
                                {{room.title}}
                        </span>
                            <span class="col-lg-3 col-sm-3">
                                    Members: <span class="pink">{{room.members_count}}</span>
                            <hr>
                                    Max members: <span class="pink">{{room.count_of_members}}</span>
                        </span>
                        </a>
                    </div>
                    <div v-show="!(Array.isArray(allRooms) && allRooms.length > 0)">
                        <h4>No rooms found</h4>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "RoomsOfGame",
        props: ['game','rooms','authUserRoom','myRoomGameShortAddress'],
        data() {
            return {
                newRoom: {
                    title: '',
                    count_of_members: 2,
                },
                allRooms:JSON.parse(JSON.stringify(this.rooms)),
                errors:{},
                authUserId:$('meta[name="auth-user-id"]').attr('content'),
                myRoom:JSON.parse(JSON.stringify(this.authUserRoom)),
                myRoom_GameShortAddress:JSON.parse(JSON.stringify(this.myRoomGameShortAddress)),
            }
        },
        mounted() {
            // room.of.game.{game_short_address}
            Echo.channel(`rooms.of.game.${this.game.short_address}`)
                .listen('RoomEvent', (data) => {
                    if(data.newRoom){
                        this.addNewRoom(data.newRoom);
                    }else if(data.updateMembersCount){
                        this.updateMembersCount(data.updateMembersCount);
                    } else if(data.deletedRoomID){
                        this.deleteRoomFromTheList(data.deletedRoomID);
                    }else if(data.lockRoom){
                        if(data.lockRoom.is_locked == 0){
                            this.addNewRoom(data.lockRoom);
                        }else{
                            this.deleteRoomFromTheList(data.lockRoom.id);
                        }
                    }
                });
        },
        methods: {
            submitNewRoom() {
                axios.post('/room/create', {
                    ...this.newRoom,
                    game_id: this.game.id
                }).then((response) => {
                    $('#addRoom').modal('hide');

                    this.newRoom.title='';
                    this.newRoom.count_of_members=2;

                    this.errors = {}

                    this.myRoom = response.data;
                    this.myRoom_GameShortAddress = this.game.short_address;
                }).catch(error=>{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.response.data.message,
                    });
                    this.errors = {};
                    if(error.response.data.errors){
                        this.errors = error.response.data.errors
                    }
                });
            },
            addNewRoom(newRoom){
                this.allRooms.unshift(newRoom);
            },
            deleteRoomFromTheList(roomID){
                for(let key in this.allRooms){
                    if(this.allRooms[key].id == roomID){
                        this.allRooms.splice(key,1);
                        return
                    }
                }
            },
            deleteRoom(roomID){
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
                        axios.delete('/room/delete/'+this.game.short_address + '/'+ roomID).then((response)=>{
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!!!',
                                text: response.data.message,
                            });
                            this.myRoom = undefined;
                            this.deleteRoomFromTheList(response.data.roomID);
                        }).catch(error=>{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error.response.data.message,
                            });
                        });
                    }
                })
            },
            updateMembersCount(updateData){
                for(let key in this.allRooms){
                    if(this.allRooms[key].id == updateData.room_id){
                        this.allRooms[key].members_count = updateData.members_count;
                        return;
                    }
                }
            },
        }
    }
</script>

<style scoped>

</style>
