<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <h1 class="mb-3">{{title}}</h1>
        <div v-for="(question, q) in questions" :key="q" class="bg-primary mb-3 rounded p-2 ">
            <router-link class="router-link" :to="{ name: 'question', params: { id: question.id } }">
                <div>{{question.question}}</div>
            </router-link>
        </div>

        <!-- Pagination -->
        <b-pagination v-model="currentPage" :total-rows="totalQuestions" :per-page="itemPerPage" align="center">
        </b-pagination>
        <!-- Pagination -->
    </div>
</template>

<script>
import { useToast } from "vue-toastification";
import { ref, watch } from "vue";
import useCallApi from '../composables/useCallApi';

export default {
    props: ['get_url', 'title'],
    setup(props) {
        const toast = useToast();

        //get questions
        const questions = ref([]);
        const orderBy = ref('id');
        const ordering = ref('desc')
        const itemPerPage = ref(10)

        //questions pagination
        const currentPage = ref(1)
        watch(currentPage, () => {
            getQuestions()
        })
        const totalQuestions = ref(0)

        const getQuestions = async () => {
            const res = await useCallApi('get', `${props.get_url}&orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`)

            if (res.status == 200) {
                questions.value = res.data.data
                totalQuestions.value = res.data.total
            } else {
                toast.error(res.data.message)
            }
        }

        watch(() => props.get_url, () => {
            getQuestions()
        })

        getQuestions();

        return { questions, itemPerPage, currentPage, totalQuestions }

    }
}
</script>