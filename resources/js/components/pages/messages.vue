<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <b-tabs content-class="mt-3">
            <b-tab title="Incoming" active>
                <h1 class="mb-3">Incoming Messages:</h1>
                <div v-for="(message, m) in incomingMessages" :key="m">
                    <div class="d-flex justify-content-between align-items-center">
                        <div @click="setMessageToReaded(message, m)"
                            :style="[ message.readed ? {'background-color':  '#6c757d'} : {'background-color':  '#0d6efd'}]"
                            class="mb-3 p-2 rounded pointer-cursor flex-fill" data-bs-toggle="collapse"
                            :data-bs-target="'#message'+message.id">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="border-bottom">From: {{ message.sender.nickname }}</div>
                                <div>{{ message.created_at }}</div>
                            </div>
                            <div class="mb-3">Subject: {{message.subject}}</div>
                            <div class="collapse collapse-horizontal text-black" :id="'message'+message.id">
                                <div class="card card-body" style="width: 300px;">
                                    {{message.message}}
                                </div>
                            </div>
                        </div>
                        <i @click="showMessageModal(message.sender)" title="Reply"
                            class="bi bi-reply mx-2 pointer-cursor fs-5 "></i>
                    </div>
                </div>
                <!-- Pagination -->
                <b-pagination v-if="totalIncoming > itemPerPage" v-model="currentPageIncoming"
                    :total-rows="totalIncoming" :per-page="itemPerPage" align="center"></b-pagination>
                <!-- Pagination -->
            </b-tab>
            <b-tab title="Sended">
                <h1 class="mb-3">Sended Messages:</h1>
                <div class="bg-primary mb-3 p-2 rounded" v-for="(message, m) in sendedMessages" :key="m">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="border-bottom"> To: {{ message.addressee.nickname }}</div>
                        <div>{{ message.created_at }}</div>
                    </div>
                    <div class="mb-3">Subject: {{message.subject}}</div>
                    <div>Message: {{ message.message }}</div>
                </div>
                <!-- Pagination -->
                <b-pagination v-if="totalSended > itemPerPage" v-model="currentPageSended" :total-rows="totalSended"
                    :per-page="itemPerPage" align="center"></b-pagination>
                <!-- Pagination -->
            </b-tab>
        </b-tabs>
    </div>
    <messageModal v-model="messageModal" :addressee="addressee"></messageModal>
</template>

<script>
import { ref, watch } from 'vue'
import useCallApi from '../composables/useCallApi'
import { useToast } from 'vue-toastification'
import sideMenu from "../partials/sideMenu.vue";
import messageModal from '../partials/messageModal.vue';
import { useUnreadedMessageCount } from "../../stores/unreadedMessageCount";

export default {
    components: { sideMenu, messageModal },
    setup() {
        const toast = useToast()
        const itemPerPage = ref(10)
        const unreadedMessageCount = useUnreadedMessageCount();

        //incoming messages
        const incomingMessages = ref([])
        const orderByIncoming = ref('id');
        const orderingIncoming = ref('desc');
        const totalIncoming = ref(0)
        const currentPageIncoming = ref(1)

        const getIncomingMessages = async () => {
            const res = await useCallApi('get', `/incoming_messages?orderBy=${orderByIncoming.value}&ordering=${orderingIncoming.value}&page=${currentPageIncoming.value}&itemPerPage=${itemPerPage.value}`)

            if (res.status == 200) {
                incomingMessages.value = res.data.data
                totalIncoming.value = res.data.total
            }
            else {
                toast.error(res.data.message)
            }
        }

        //pagination on incomingMessages
        watch(currentPageIncoming, () => {
            getIncomingMessages()
        })

        getIncomingMessages()

        //sended messages
        const sendedMessages = ref([])
        const orderBySended = ref('id')
        const orderingSended = ref('desc')
        const currentPageSended = ref(1)
        const totalSended = ref(0)

        const getSendedMessages = async () => {
            const res = await useCallApi('get', `sended_messages?orderBy=${orderBySended.value}&ordering=${orderingSended.value}&page=${currentPageSended.value}&itemPerPage=${itemPerPage.value}`)

            if (res.status == 200) {
                sendedMessages.value = res.data.data
                totalSended.value = res.data.total
            } else {
                toast.error(res.data.message)
            }
        }

        const setMessageToReaded = async (message, index) => {
            if (!message.readed) {
                const res = await useCallApi('post', '/set_message_to_readed', { id: message.id })

                if (res.status == 200) {
                    //change unreaded count in pinia
                    unreadedMessageCount.readMessage()
                    incomingMessages.value[index].readed = true
                }
            }
        }

        //pagination on sendedMessages
        watch(currentPageSended, () => {
            getSendedMessages()
        })

        getSendedMessages()

        //messageModal
        const addressee = ref({})
        const messageModal = ref(false)

        const showMessageModal = (user) => {
            addressee.value = user
            messageModal.value = true
        }


        return { incomingMessages, addressee, messageModal, showMessageModal, sendedMessages, itemPerPage, totalIncoming, currentPageIncoming, totalSended, currentPageSended, setMessageToReaded }
    }
}
</script>