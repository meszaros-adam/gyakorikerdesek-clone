<template>
    <div class="container my-5 p-5 bg-dark text-light">
        <div class="row g-5">
            <div class="col-md-3">
                <sideMenu></sideMenu>
            </div>
            <div class="col-md-9">
                <div class="container bg-dark text-light">
                    <b-tabs content-class="mt-3">
                        <b-tab title="Incoming" active>
                            <h1 class="mb-3">Incoming Messages:</h1>
                            <div class="bg-primary mb-3 p-2 rounded d-flex justify-content-between"
                                v-for="(message, m) in incomingMessages" :key="m">
                                <div>
                                    <span> {{ message.sender.nickname + ': ' }}</span>
                                    <span> {{ message.message }}</span>
                                </div>
                                <div>
                                    <i @click="showMessageModal(message.sender)" title="Reply"
                                        class="bi bi-reply mx-2 pointer-cursor fs-5 "></i>
                                </div>
                            </div>
                        </b-tab>
                        <b-tab @click="getSendedMessages" title="Sended">
                            <h1 class="mb-3">Sended Messages:</h1>
                            <div class="bg-primary mb-3 p-2 rounded d-flex justify-content-between"
                                v-for="(message, m) in sendedMessages" :key="m">
                                <div>
                                    <span> {{ message.sender.nickname + ': ' }}</span>
                                    <span> {{ message.message }}</span>
                                </div>
                                <div>
                                    <i @click="showMessageModal(message.sender)" title="Reply"
                                        class="bi bi-reply mx-2 pointer-cursor fs-5 "></i>
                                </div>
                            </div>
                        </b-tab>
                    </b-tabs>

                </div>
            </div>
        </div>
    </div>
    <messageModal v-model="messageModal" :addressee="addressee"></messageModal>
</template>

<script>
import { ref } from 'vue'
import useCallApi from '../composables/useCallApi'
import { useToast } from 'vue-toastification'
import sideMenu from "../partials/sideMenu.vue";
import messageModal from '../partials/messageModal.vue';
export default {
    components: { sideMenu, messageModal },
    setup() {
        const toast = useToast()

        //incoming messages
        const incomingMessages = ref([])

        const getIncomingMessages = async () => {
            const res = await useCallApi('get', '/incoming_messages')

            if (res.status == 200) {
                incomingMessages.value = res.data
            }
            else {
                toast.error('Failed to load incoming messages!')
            }
        }

        getIncomingMessages()

        //sended messages

        const sendedMessages = ref([])

        const getSendedMessages = async () => {
            const res = await useCallApi('get', 'sended_messages')

            if(res.status == 200){
                sendedMessages.value = res.data
            }else{
                toast.error('Failed to load sended messages!')
            }
        }

        //messageModal
        const addressee = ref({})
        const messageModal = ref(false)

        const showMessageModal = (user) => {
            addressee.value = user
            messageModal.value = true
        }


        return { incomingMessages, addressee, messageModal, showMessageModal, sendedMessages, getSendedMessages }
    }
}
</script>