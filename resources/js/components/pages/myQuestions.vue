<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <div class="bg-primary mb-3 p-2 rounded d-flex" v-for="(question, q) in myQuestions" :key="q">
            <router-link class="text-white flex-grow-1" :to="{name: 'question', params:{id: question.id}}">
                <div>Category: {{question.category.name}}</div>
                <div class="text-center fs-6 flex-grow-1">{{question.question}}</div>
            </router-link>
            <i class="bi bi-trash pointer-cursor mx-1 align-self-center" title="Delete" @click="showDeleteModal(question.id, q)" > </i>
        </div>
        <!-- Pagination -->
        <b-pagination v-model="currentPage" :total-rows="totalMyQuestions" :per-page="itemPerPage" align="center">
        </b-pagination>
        <!-- Pagination -->
        <deleteModal delete_url="/delete_question" item_name="question" :item_id="deleteId" v-model="deleteModal"
        :delete_index="deleteIndex" @successfulDelete="removeDeletedItem"></deleteModal>
    </div>
</template>

<script>
import { ref, watch } from 'vue'
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
import deleteModal from "../partials/deleteModal.vue";
export default {
    components:{deleteModal},
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

        //Delete

        const deleteModal = ref(false)
        const deleteId = ref(null)
        const deleteIndex = ref (null)

        const showDeleteModal = (id, index) =>{
            deleteId.value = id
            deleteIndex.value = index
            deleteModal.value = true
        }

        const removeDeletedItem = (index) =>{
            myQuestions.value.splice(index,1)
        }

        return {
            myQuestions,
            totalMyQuestions,
            itemPerPage,
            currentPage,
            deleteModal,
            deleteId,
            deleteIndex,
            showDeleteModal,
            removeDeletedItem,
        }
    }
}
</script>