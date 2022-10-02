<template>
    <div>
        <div class="container my-5 p-3 bg-dark text-light">
            <div class="d-flex justify-content-between">
                <Button @click="createModal = true"><i class="bi bi-plus-lg"></i> Create Question</Button>
                <div class="d-flex align-items-center">
                    <div class="text-nowrap me-3">Order By:</div>
                    <select v-model="orderBy" @change="getQuestions" class="form-select me-3"
                        aria-label="Default select example">
                        <option value="id">ID</option>
                        <option value="question">Question</option>
                        <option value="category_name">Category</option>
                        <option value="user_nickname">User</option>
                    </select>
                    <i v-show="ordering == 'desc'" @click="changeOrdering('asc')" class="bi bi-arrow-up pointer-cursor" title="Ascending Order"></i>
                    <i v-show="ordering == 'asc'" @click="changeOrdering('desc')" class="bi bi-arrow-down pointer-cursor" title="Descending Order"></i>
                </div>
            </div>
            <table class="table table-primary table-striped my-3">
                <thead>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Category</th>
                        <th scope="col">User</th>
                        <th scope="col">Question</th>
                        <th scope="col">Functions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(question, q) in questions" :key="q">
                        <th scope="row">{{ question.id }}</th>
                        <td>{{ question.category.name }}</td>
                        <td>{{ question.user.nickname }}</td>
                        <td>{{ question.question }}</td>
                        <td>
                            <span>
                                <router-link title="Open Question" class="text-dark"
                                    :to="{ name: 'question', params: { id: question.id } }">
                                    Open
                                </router-link>
                            </span>
                            <i @click="showEditModal(question, q)" title="Edit"
                                class="bi bi-pencil pointer-cursor mx-1">
                            </i>
                            <i @click="showDeleteModal(question.id, q)" title="Delete"
                                class="bi bi-trash pointer-cursor mx-1"> </i>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination -->
            <b-pagination v-model="currentPage" :total-rows="totalQuestion" :per-page="itemPerPage" align="center">
            </b-pagination>
            <!-- Pagination -->
        </div>
        <!--Edit Modal-->
        <b-modal v-model="editModal" no-close-on-backdrop hide-footer title="Edit Question">
            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <input v-model="editData.question" type="string" class="form-control" id="question" />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea v-model="editData.description" type="string" class="form-control" id="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="Category" class="form-label">Category</label>
                <Select id="Category" v-model="editData.categoryId" placeholder="Select Category!">
                    <Option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}
                    </Option>
                </Select>
            </div>
            <div class="mb-3">
                <label for="Tags" class="form-label">Tags</label>
                <b-form-tags v-model="editData.tags" input-id="tags-basic" :limit="5"></b-form-tags>
            </div>
            <div class="d-flex justify-content-end">
                <Button class="mx-2" @click="editModal = false">Cancel</Button>
                <Button @click="editQuestion()" type="primary" :loading="editing">Edit</Button>
            </div>
        </b-modal>
        <!--Edit Modal-->

        <delete-modal v-model="deleteModal" delete_url="/delete_question" item_name="question" :item_id="deleteId"
            :delete_index="deleteIndex" @successfulDelete="removeDeletedItem"></delete-modal>

        <createQuestionModalVue v-model="createModal" @newQuestionCreated="newQuestion"></createQuestionModalVue>
    </div>
</template>
  
<script>
import { ref, watch } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../../composables/useCallApi";
import deleteModal from "../../partials/deleteModal.vue"
import createQuestionModalVue from "../../partials/createQuestionModal.vue";
export default {
    components: { deleteModal, createQuestionModalVue },
    setup() {
        const toast = useToast();

        //get questions
        const questions = ref([]);
        const orderBy = ref('id');
        const ordering = ref('desc')
        const itemPerPage = ref(10)

        const changeOrdering = (newOrdering) =>{
            ordering.value = newOrdering
            getQuestions();
        }   

        //questions pagination
        const currentPage = ref(1)
        watch(currentPage, () => {
            getQuestions()
        })
        const totalQuestion = ref(0)

        const getQuestions = async () => {
            const res = await useCallApi(
                "get",
                `/get_questions?orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`
            );

            if (res.status == 200) {
                questions.value = res.data.data;
                totalQuestion.value = res.data.total;
            } else {
                toast.error("Failed to load question!");
            }
        };
        getQuestions();


        //edit questions
        const editModal = ref(false);
        const editing = ref(false);
        const editIndex = ref(null);
        const editData = ref({
            tags: [],
            id: null,
            question: "",
            description: "",
            categoryId: '',
        });
        const categories = ref([]);
        const tags = ref([]);

        const getCategories = async () => {
            const res = await useCallApi("get", "/get_all_categories");
            if (res.status == 200) {
                categories.value = res.data;
            } else {
                toast.error(res.data.message);
            }
        };

        getCategories()

        const showEditModal = (question, index) => {
            editData.value.id = question.id;
            editData.value.question = question.question;
            editData.value.description = question.description;
            editData.value.categoryId = question.category_id;
            editData.value.tags = question.tags.map((tag) => tag.name);
            editModal.value = true;
            editIndex.value = index;
        };

        const editQuestion = async () => {
            if (editData.value.question.trim().length < 6)
                return toast.warning("Question must be at leat 6 characters!");

            editing.value = true;
            const res = await useCallApi("post", "/edit_question", editData.value);

            if (res.status == 200) {
                toast.success("Question edited successfully");
                getQuestions();
            } else {
                toast.error(res.data.message);
            }
            editing.value = false;
            editModal.value = false;
        };

        //deleteModal
        const deleteModal = ref(false)
        const deleteId = ref(null)
        const deleteIndex = ref(null)
        const showDeleteModal = (id, index) => {
            deleteId.value = id
            deleteIndex.value = index
            deleteModal.value = true
        }
        const removeDeletedItem = (index) => {
            questions.value.splice(index, 1)
        }

        //create modal
        const createModal = ref(false)

        //handle new question

        const newQuestion = (newQuestion) => {
            questions.value.unshift(newQuestion)
        }

        return {
            questions,
            editModal,
            editQuestion,
            editData,
            showEditModal,
            editing,
            editIndex,
            deleteModal,
            showDeleteModal,
            deleteId,
            deleteIndex,
            removeDeletedItem,
            totalQuestion,
            currentPage,
            itemPerPage,
            categories,
            tags,
            createModal,
            getQuestions,
            newQuestion,
            orderBy,
            ordering,
            changeOrdering,
        };
    },
};
</script>