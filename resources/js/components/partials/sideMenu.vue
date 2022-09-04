<template>
    <div class="bg-secondary rounded p-3">
        <h1>Menu</h1>
        <ul class="side-menu">
            <li>
                <a data-bs-toggle="collapse" href="#collapseExample" role="button">
                    Admin Panel
                </a>
            </li>
            <div class="collapse" id="collapseExample">
                <div class="card card-body bg-primary">
                    <router-link class="router-link" to="/admin/categories">Categories</router-link>
                    <router-link class="router-link" to="/admin/questions">Questions</router-link>
                    <router-link class="router-link" to="/admin/users">Users</router-link>
                    <router-link class="router-link" to="/admin/tags">Tags</router-link>
                </div>
            </div>
            <li class="pointer-cursor" @click="showCreateQuestionModal">Create question</li>
            <li class="pointer-cursor">My questions</li>
            <router-link @click="setMessagesToReaded" class="router-link" to="/messages">Messages <span
                    v-if="unreadedCount > 0" class="badge bg-primary">{{ unreadedCount }}</span></router-link>
            <li class="pointer-cursor">My answered questions</li>
        </ul>
    </div>
    <!--Create Question Modal-->
    <b-modal v-model="createQuestionModal" hide-footer size="lg" title="Create Question">
        <div class="mb-3">
            <label for="Question" class="form-label">Question</label>
            <input v-model="question.question" type="string" class="form-control" id="Question"
                placeholder="Your question..." />
        </div>
        <div class="mb-3">
            <label for="Description" class="form-label">Description</label>
            <textarea v-model="question.description" class="form-control" id="Description" rows="3"
                placeholder="Description..."></textarea>
        </div>
        <div class="mb-3">
            <label for="Category" class="form-label">Category</label>
            <b-form-select id="Category" v-model="question.category_id" value-field="id" text-field="name"
                :options="categories"></b-form-select>
        </div>
        <div class="d-flex justify-content-end">
            <Button class="mx-2" @click="createQuestionModal = false">Cancel</Button>
            <Button @click="createQuestion()" :loading="creatingQuestion" type="primary">Save</Button>
        </div>
    </b-modal>
    <!--Create Question Modal-->
</template>

<script>
import { ref } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
import { useUserStore } from "../../stores/user";
export default {
    emits: ['newQuestionCreated'],
    //props needed becouse it drops error without it
    setup(props, context) {
        const toast = useToast()
        const user = useUserStore();

        //create question
        const createQuestionModal = ref(false);
        const creatingQuestion = ref(false);
        const question = ref({});
        const createQuestion = async () => {
            if (question.value.question.trim() == "")
                return toast.warning("You must enter a question!");
            if (question.value.question.trim().length < 6)
                return toast.warning(
                    "Your question must be at least six characters long!"
                );
            if (typeof question.value.category_id !== "number")
                return toast.warning("You must enter one category!");

            creatingQuestion.value = true;

            const res = await useCallApi("post", "/create_question", question.value);

            if (res.status == 201) {
                context.emit('newQuestionCreated')
                toast.success("Question created sucessfully!");
                createQuestionModal.value = false;
            } else {
                toast.error(res.data.message);
            }
            question.value = {};
            creatingQuestion.value = false;
        };

        const categories = ref([]);
        const getCategories = async () => {
            const res = await useCallApi("get", "/get_all_categories");
            if (res.status == 200) {
                categories.value = res.data;
            } else {
                toast.error(res.data.message);
            }
        };

        const showCreateQuestionModal = () => {
            getCategories()
            createQuestionModal.value = true
        }

        //unreaded messages
        const unreadedCount = ref(null)

        const getUnreadedCount = async () => {
            if (user.getUser) {
                const res = await useCallApi('get', '/unreaded_messages_count')

                if (res.status == 200) {
                    unreadedCount.value = res.data
                } else {
                    toast.error('Cannot load the unreaded message count')
                }
            }
        }

        const setMessagesToReaded = async () => {
            const res = useCallApi('post', '/set_messages_to_readed')

            if (!res.status == 200) {
                toast.error('Failed to set messages to unread!')
            }
        }


        getUnreadedCount();

        return { createQuestion, categories, createQuestionModal, creatingQuestion, question, categories, showCreateQuestionModal, unreadedCount, setMessagesToReaded }
    }
}
</script>