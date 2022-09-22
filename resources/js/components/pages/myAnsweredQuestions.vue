<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <div v-if="myAnsweredQuestions.length>0">
            <div class="bg-primary mb-3 rounded d-flex" v-for="(question, q) in myAnsweredQuestions" :key="q">
                <router-link class="text-white flex-grow-1 p-1 " :to="{name: 'question', params:{id: question.id}}">
                    <div>Category: {{question.category.name}}</div>
                    <div class="text-center fs-6 flex-grow-1">{{question.question}}</div>
                </router-link>
            </div>
            <!-- Pagination -->
            <b-pagination v-if="totalQuestions>10" v-model="currentPage" :total-rows="totalQuestions"
                :per-page="itemPerPage" align="center">
            </b-pagination>
        </div>
        <div v-else>
            <h1>You have no questions yet</h1>
        </div>
    </div>
</template>

<script>
import { ref, watch } from 'vue'
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
import deleteModal from "../partials/deleteModal.vue";
export default {
    components: { deleteModal },
    setup() {

        const toast = useToast()

        //get questions
        const myAnsweredQuestions = ref([])
        const orderBy = ref('id');
        const ordering = ref('desc')
        const itemPerPage = ref(10)

        //pagination
        const currentPage = ref(1)
        watch(currentPage, () => {
            getMyAnsweredQuestions()
        })
        const totalQuestions = ref(0)

        const getMyAnsweredQuestions = async () => {
            const res = await useCallApi('get', `get_my_answered_questions?orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`)

            if (res.status == 200) {
                totalQuestions.value = res.data.total
                myAnsweredQuestions.value = res.data.data
            } else {
                toast.error(res.data.message)
            }
        }

        getMyAnsweredQuestions()

        return {
            myAnsweredQuestions,
            totalQuestions,
            itemPerPage,
            currentPage,
        }
    }
}
</script>