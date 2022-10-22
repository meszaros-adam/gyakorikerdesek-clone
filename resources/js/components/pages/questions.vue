<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <div class="d-flex justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <h1>{{route.params.title}}</h1>
                <div v-if="showTagAddButton" class="mx-3">
                    <i v-if="!user.getUser.watched_tags.map(tag => String(tag)).includes(String(tagId))"
                        @click="addTagToWatchlist(tagId)" title="Add tag to watchlist"
                        class="bi bi-plus-lg pointer-cursor"></i>
                    <i v-else @click="removeTagFromWatchlist(tagId)" title="Remove tag from watchlist"
                        class="bi bi-dash-lg pointer-cursor"></i>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="text-nowrap me-3">Order By:</div>
                    <select v-model="orderBy" @change="getQuestions" class="form-select"
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
import { useUserStore } from "../../stores/user";
import { ref, watch, computed } from "vue";
import useCallApi from '../composables/useCallApi';
import { useRoute } from 'vue-router'
export default {
    components: { deleteModal },
    setup(props) {
        const toast = useToast();
        const user = useUserStore();
        const route = useRoute()

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
            const res = await useCallApi('get', `/${route.params.getUrl}&orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`)

            if (res.status == 200) {
                questions.value = res.data.data
                totalQuestions.value = res.data.total
            } else {
                toast.error(res.data.message)
            }
        }

        watch(() => route.params.getUrl, () => getQuestions())

        getQuestions();

        //delete question 
        const showDeleteButton = computed(() => false)
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

        //tags watched list
        const showTagAddButton = computed(() => route.path.includes('get_questions_by_tag'))

        const tagId = computed(() => new URLSearchParams(String(route.params.getUrl).substring(route.params.getUrl.indexOf('?') + 1)).get('tag_id'))

        const addTagToWatchlist = async (tag) => {
            const res = await useCallApi('post', '/add_tag_to_watchlist', { tag_id: tag })

            if (res.status == 201) {
                user.addTagToWatchlist(tag)
                toast.success('Tag added to watchlist successfully!')
            } else {
                toast.error(res.data.message)
            }
        }

        const removeTagFromWatchlist = async (tag) => {
            const res = await useCallApi('post', '/remove_tag_from_watchlist', { tag_id: tag })

            if (res.status == 200) {
                user.removeTagFromWatchlist(tag)
                toast.success('Tag removed from watchlist successfully!')
            } else {
                toast.error(res.data.message)
            }
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
            removeDeletedQuestion,
            showTagAddButton,
            user,
            addTagToWatchlist,
            removeTagFromWatchlist,
            route,
            tagId,
        }
    }
}
</script>