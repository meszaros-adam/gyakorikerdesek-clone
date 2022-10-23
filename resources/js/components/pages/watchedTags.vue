<template>
    <div class="container my-5 p-3 bg-dark text-light">
        <div v-for="(tag, t ) in watchedTags" :key="t">
            <div class="d-flex align-items-center  mb-3">
                <div class="container bg-primary p-2 rounded">
                    <router-link class="text-white router-link"
                        :to="{name:'questions', params:{getUrl: `get_questions_by_tag?tag_id=${tag.id}`, title: tag.name }}">
                        {{tag.name}}
                    </router-link>
                </div>
                <i @click="showDeleteModal(tag.id, t)" class="bi bi-trash3 ms-3 pointer-cursor"
                    title="Delete tag from watchlist"></i>
            </div>
        </div>
        <!-- Pagination -->
        <b-pagination v-model="currentPage" :total-rows="totalWatchedTags" :per-page="itemPerPage" align="center">
        </b-pagination>
        <!-- Pagination -->

        <deleteModal v-model="deleteModal" @successfulDelete="removeDeletedElement" delete_url="/delete_watched_tag"
            item_name="Watched tag" :item_id="deleteId"></deleteModal>
    </div>
</template>

<script>
import { ref, watch } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
import deleteModal from "../partials/deleteModal.vue";
import { useUserStore } from "../../stores/user";

export default {
    components: { deleteModal },
    setup() {
        const toast = useToast();
        const user = useUserStore();

        //get watched tags
        const watchedTags = ref([]);
        const orderBy = ref('id');
        const ordering = ref('desc')
        const itemPerPage = ref(10)

        //pagination
        const currentPage = ref(1)
        watch(currentPage, () => {
            getWatchedTags()
        })
        const totalWatchedTags = ref(0)

        const getWatchedTags = async () => {
            const res = await useCallApi("get", `/get_watched_tags?orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`);

            if (res.status == 200) {
                watchedTags.value = res.data.data
                totalWatchedTags.value = res.data.total
            } else {
                toast.error(res.data.message)
            }
        }

        getWatchedTags()

        //remove from watched list
        const deleteId = ref(null)
        const deleteIndex = ref(null)
        const deleteModal = ref(false)

        const showDeleteModal = (id, index) => {
            deleteId.value = id
            deleteIndex.value = index
            deleteModal.value = true
        }

        const removeDeletedElement = () => {
            watchedTags.value.splice(deleteIndex.value, 1)
            user.removeTagFromWatchlist(deleteId.value)
        }

        return {
            watchedTags,
            currentPage,
            totalWatchedTags,
            itemPerPage,
            showDeleteModal,
            deleteId,
            deleteModal,
            removeDeletedElement,
        }
    }
}
</script>