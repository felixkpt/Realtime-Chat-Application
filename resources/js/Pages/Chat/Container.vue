<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MessageContainer from './MessageContainer.vue';
import InputMessage from './InputMessage.vue';
import axios from 'axios';
import ChatRoomSelection from './ChatRoomSelection.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <ChatRoomSelection v-if="currentRoom.id" :rooms="chatRooms" :currentRoom="currentRoom"
                    @roomchanged="setRoom($event)" />
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <MessageContainer :messages="messages" />
                    <InputMessage :room="currentRoom" v-on:messagesent="getMessages()" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
    data: function () {
        return {
            chatRooms: [],
            currentRoom: [],
            messages: []
        }
    },
    watch: {
        currentRoom: {
            handler(val, oldVal) {
                if (oldVal.id) {
                    this.disconnect(oldVal);
                }
                this.connect();
            }
        }
    },
    methods: {
        connect() {
            if (this.currentRoom.id) {
                let vm = this;
                this.getMessages();
                window.Echo.private('chat.' + this.currentRoom.id)
                    .listen('NewChatMessage', e => {
                        vm.getMessages();
                    });
            }
        },
        disconnect(room) {
            window.Echo.leave('chat.' + room.id);
        },
        getRooms() {
            axios.get('/chat/rooms')
                .then(reponse => {
                    this.chatRooms = reponse.data;
                    this.setRoom(this.chatRooms[0]);
                })
                .catch(error => {
                    console.log(error);
                })
        },
        setRoom(room) {
            this.currentRoom = room;
        },
        getMessages() {
            axios.get('/chat/room/' + this.currentRoom.id + '/messages')
                .then(response => {
                    this.messages = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
        }
    },
    created() {
        this.getRooms();
    },
    components: { ChatRoomSelection, ChatRoomSelection }
}
</script>