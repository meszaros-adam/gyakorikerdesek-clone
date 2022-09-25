<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <div class="container bg-dark text-light">
            <div v-if="question" class="bg-secondary container mb-3 rounded">
                <div class="border-bottom pt-3 pb-1">
                    <h1 class="text-center mb-3">{{ question.question }}</h1>
                    <p class="text-center">{{ question.description }}</p>
                    <div class="d-flex">
                        <div v-for="tag in question.tags" :key="tag.id">
                            <div class="me-2 px-1 bg-primary rounded">
                                #{{tag.name}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <span>{{ question.user.nickname }}</span>
                    </div>
                    <div>
                        <div v-if="user.getUser.id == question.user.id">
                            Your Question!
                        </div>
                        <div v-else @click="showMessageModal(question.user)" class="pointer-cursor">
                            <span class="mx-2">Send Message </span><i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    <div>{{ question.created_at }}</div>
                </div>
            </div>
            <!-- Pagination -->
            <b-pagination v-if="itemPerPage < totalAnswers" class="mb-3" v-model="currentPage"
                :total-rows="totalAnswers" :per-page="10" align="center"></b-pagination>
            <!-- Pagination -->
            <div v-if="answers.length == 0" class="bg-secondary p-3 rounded mb-3">
                <h3>There is no answer yet</h3>
            </div>
            <div v-else class="mb-3 container" v-for="(answer, a ) in answers" :key="a">
                <div class="bg-primary rounded-top p-1 d-flex justify-content-between">
                    <div>
                        {{ answer.user.nickname }}
                    </div>
                    <div>
                        <div v-if="user.getUser.id == answer.user.id">
                            Your Answer!
                        </div>
                        <div v-else @click="showMessageModal(answer.user)" class="pointer-cursor">
                            <span class="mx-2">Send Message</span>
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    <div>
                        {{ answer.created_at }}
                    </div>
                </div>
                <div class="bg-secondary p-3 rounded-bottom">
                    <p>{{ answer.answer }}</p>
                </div>
            </div>
            <!-- Pagination -->
            <b-pagination v-if="itemPerPage < totalAnswers" v-model="currentPage" :total-rows="totalAnswers"
                :per-page="10" align="center"></b-pagination>
            <!-- Pagination -->
            <div class="container">
                <label for="answer" class="form-label">
                    <h3>Your Answer</h3>
                </label>
                <textarea v-model="answer.answer" placeholder="Your Answer..." class="form-control" id="answer"
                    rows="3"></textarea>
                <Button @click="sendAnswer" :loading="answering" type="primary" class="mt-3">Send
                    Answer</Button>
            </div>
        </div>
    </div>

    <message-modal v-model="messageModal" :addressee="addressee"></message-modal>

    <deleteModal delete_url="/delete_answer" item_name="answer" :item_id="itemId" :delete_index="deleteIndex">
    </deleteModal>
</template>

<script>
import { ref, watch } from 'vue'
import { useToast } from 'vue-toastification'
import useCallApi from '../composables/useCallApi'
import { useRoute } from 'vue-router'
import { useUserStore } from "../../stores/user";
import messageModal from '../partials/messageModal.vue'
import router from '../../router';
import sideMenu from "../partials/sideMenu.vue";
import deleteModal from "../partials/deleteModal.vue";
export default {
    components: { messageModal, sideMenu, deleteModal },
    setup() {
        const toast = useToast()
        const question = ref(null)
        const answers = ref([])

        //get id from route param
        const route = useRoute()

        //answers pagination
        const currentPage = ref(1)
        const itemPerPage = ref(10)
        watch(currentPage, () => {
            getQuestion()
        })
        const totalAnswers = ref(0)

        const getQuestion = async () => {
            const res = await useCallApi('get', `/get_single_question?id=${route.params.id}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`)

            if (res.status == 200) {
                totalAnswers.value = res.data.answers.total
                question.value = res.data.question
                answers.value = res.data.answers.data
            } else {
                toast.error('Failed to load the question!')
            }
        }

        getQuestion()

        //Answering
        const answer = ref({});
        const answering = ref(false)
        const user = useUserStore()

        const sendAnswer = async () => {
            if (answer.value.answer.trim().length < 2) return toast.warning('Your answer must be at least 2 characters!')

            console.log(route.params.id)

            answer.value.question_id = route.params.id
            answering.value = true

            const res = await useCallApi('post', '/create_answer', answer.value)

            if (res.status == 201) {
                answers.value.push(res.data)
                answer.value = {}
                toast.success('Sucessful answering!')
            } else {
                toast.error(res.data.message)
            }
            answering.value = false
        }

        //message
        const messageModal = ref(false)
        const addressee = ref({})

        const showMessageModal = (user) => {
            if (!user.getUser) {
                router.push({ path: '/login' })
                toast.warning('You must login to send messages!')
            }
            else {
                addressee.value = user
                messageModal.value = true
            }
        }

        //delete
        const deleteIndex = ref(null)
        const itemId = ref(null)


        return { question, answers, answer, sendAnswer, answering, currentPage, totalAnswers, getQuestion, itemPerPage, messageModal, addressee, showMessageModal, user, deleteIndex, itemId }
    }
}
</script>