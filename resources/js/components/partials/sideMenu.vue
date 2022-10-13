<template>
    <div class="bg-secondary mt-5 mx-3 p-3">
        <h1 class="text-black">Menu</h1>
        <ul v-if="user.getUser" class="side-menu">
            <li v-if="user.getUser.admin">
                <a class="admin-panel" data-bs-toggle="collapse" href="#collapseExample" role="button">
                    Admin Panel
                </a>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body bg-primary">
                        <router-link class="router-link" to="/admin/questions">Questions</router-link>
                        <router-link class="router-link" to="/admin/users">Users</router-link>
                        <router-link class="router-link" to="/admin/categories">Categories</router-link>
                        <router-link class="router-link" to="/admin/tags">Tags</router-link>
                    </div>
                </div>
            </li>
            <li class="pointer-cursor" @click="createQuestionModal = true">Create question</li>
            <li>
                <div @click="showQuestions.show(`/get_my_questions?nothing=${'nothing'}`, 'My Questions')"
                    class="router-link">My Questions</div>
            </li>
            <li>
                <router-link class="router-link" to="/messages">Messages <span v-if="unreadedMessageCount.getCount > 0"
                        class="badge bg-primary">{{ unreadedMessageCount.getCount }}</span></router-link>
            </li>
            <div @click="showQuestions.show(`/get_my_answered_questions?nothing=${'nothing'}`, 'My Answered Questions')"
                class="router-link">My Answered Questions</div>
            <div @click="showQuestions.show(`/get_all_questions?nothing=${'nothing'}`, 'All Questions')"
                class="router-link">All Questions</div>
        </ul>
        <div v-else>
            <router-link to="/login">
                <button type="button" class="btn btn-primary my-3">Login</button>
            </router-link>
        </div>
    </div>
    <createQuestionModal v-model="createQuestionModal" @newQuestionCreated="$emit('newQuestionCreated')">
    </createQuestionModal>
</template>

<script>
import { ref } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
import { useUserStore } from "../../stores/user";
import { useUnreadedMessageCount } from "../../stores/unreadedMessageCount";
import createQuestionModal from "./createQuestionModal.vue";
import useShowQuestions from '../composables/useShowQuestions'
export default {
    components: { createQuestionModal },
    emits: ['newQuestionCreated'],
    //props needed becouse it drops error without it
    setup(props, context) {
        const toast = useToast()
        const user = useUserStore();
        const unreadedMessageCount = useUnreadedMessageCount();
        const showQuestions = useShowQuestions();

        //create question
        const createQuestionModal = ref(false);

        //unreaded messages
        const getUnreadedCount = async () => {
            if (user.getUser) {
                const res = await useCallApi('get', '/unreaded_messages_count')

                if (res.status == 200) {
                    unreadedMessageCount.setCount(res.data)
                } else {
                    toast.error('Cannot load the unreaded message count')
                }
            }
        }

        getUnreadedCount();

        return { createQuestionModal, user, unreadedMessageCount, showQuestions }
    }
}
</script>