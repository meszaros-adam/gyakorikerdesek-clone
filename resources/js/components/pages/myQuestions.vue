<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <div class="bg-primary mb-3 p-2 rounded" v-for="(question, q) in myQuestions" :key="q">
            <router-link class="text-white" :to="{name: 'question', params:{id: question.id}}">
                <div>{{question.question}}</div>
            </router-link>
        </div>
         <!-- Pagination -->
      <b-pagination v-model="currentPage" :total-rows="totalMyQuestions" :per-page="itemPerPage" align="center">
      </b-pagination>
      <!-- Pagination -->
    </div>
</template>

<script>
import { ref, watch } from 'vue'
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
import deleteModal from "../partials/deleteModal.vue";
export default {
    setup() {

        const toast = useToast()

        //get questions
        const myQuestions = ref([])
        const orderBy = ref('id');
        const ordering = ref('desc')
        const itemPerPage = ref(10)

        //pagination
        const currentPage = ref(1)
        watch(currentPage, () => {
            getMyQuestions()
        })
        const totalMyQuestions = ref(0)

        const getMyQuestions = async () => {
            const res = await useCallApi('get', `get_my_questions?orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`)

            if (res.status == 200) {
                totalMyQuestions.value = res.data.total
                myQuestions.value = res.data.data
            } else {
                toast.error(res.data.message)
            }
        }

        getMyQuestions()

        return{
            myQuestions,
            totalMyQuestions,
            itemPerPage,
            currentPage,
        }
    }
}
</script>