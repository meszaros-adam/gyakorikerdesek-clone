<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <div class="d-flex justify-content-between mb-3">
            <h1>Search: {{title}}</h1>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="text-nowrap me-3">Order By:</div>
                    <select v-model="orderBy" @change="getQuestions" class="form-select me-3"
                        aria-label="Default select example">
                        <option value="id">Creation time</option>
                        <option value="latest_answer_at">Last Answer</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-for="(question, q) in questions" :key="q">
            <div class="d-flex align-items-center">
                <div class="bg-primary mb-3 rounded flex-fill">
                    <router-link class="router-link" :to="{ name: 'question', params: { id: question.id } }">
                        <div class="p-2">
                            <div class="d-flex justify-content-between">
                                <div>{{question.question}}</div>
                                <div>Category: {{question.category.name}}</div>
                            </div>
                            <div class="d-flex">
                                <div class="fst-italic me-2" v-for="(tag, t) in question.tags" :key="t">
                                    #{{tag.name}}</div>
                            </div>
                        </div>
                    </router-link>
                </div>
                <i v-if="showDeleteButton" @click="showDeleteModal(question.id, q)" title="Delete"
                    class="bi bi-trash mb-3 mx-2 pointer-cursor"></i>
            </div>

        </div>
        <!-- Pagination -->
        <b-pagination v-model="currentPage" :total-rows="totalQuestions" :per-page="itemPerPage" align="center">
        </b-pagination>
        <!-- Pagination -->
    </div>
    <delete-modal v-model="deleteModal" @successfulDelete="removeDeletedQuestion" delete_url="/delete_question"
        item_name="Question" :item_id="deleteId" :delete_index="deleteIndex">
    </delete-modal>
</template>

<script>
import deleteModal from '../partials/deleteModal.vue'
import { useToast } from "vue-toastification";
import { useRoute } from 'vue-router'
import { ref, watch, computed } from "vue";
import useCallApi from '../composables/useCallApi';
export default {
    components: { deleteModal },
    props: ['get_url', 'title'],
    setup(props) {
        const toast = useToast();
        const route = useRoute();

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

        //delete question 
        const showDeleteButton = computed(() => route.path == '/my-questions' ? true : false)
        const deleteId = ref()
        const deleteIndex = ref()
        const deleteModal = ref(false)

        const showDeleteModal = async (id, index) => {
            deleteId.value = id
            deleteIndex.value = index
            deleteModal.value = true
        }

        const removeDeletedQuestion = (index) => {
            questions.value.splice(index, 1)
        }

        return {
            questions,
            itemPerPage,
            currentPage,
            totalQuestions,
            orderBy,
            ordering,
            getQuestions,
            showDeleteButton,
            deleteId,
            deleteIndex,
            deleteModal,
            showDeleteModal,
            removeDeletedQuestion
        }

    }
}
</script>