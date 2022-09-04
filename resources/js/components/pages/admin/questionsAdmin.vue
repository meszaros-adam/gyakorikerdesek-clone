<template>
    <div>
        <admin-nav-bar></admin-nav-bar>
        <div class="container my-5 p-5 bg-dark text-light">
            <Button><i class="bi bi-plus-lg"></i> Create Question</Button>
            <table class="table table-primary table-striped my-3">
                <thead>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Questioner</th>
                        <th scope="col">Question</th>
                        <th scope="col">Functions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(question, q) in questions" :key="q">
                        <th scope="row">{{ question.id }}</th>
                        <td>{{ question.user.nickname }}</td>
                        <td>{{ question.question }}</td>
                        <td>
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
        <b-modal v-model="editModal" hide-footer title="Edit Question">
            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <input v-model="editData.question" type="string" class="form-control" id="question" />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea v-model="editData.description" type="string" class="form-control" id="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <b-form-select id="category" v-model="editData.categoryId" value-field="id" text-field="name"
                    :options="categories"></b-form-select>
            </div>
            <div class="mb-3">
                <label for="user" class="form-label">User</label>
                <b-form-select id="user" v-model="editData.userId" value-field="id" text-field="nickname"
                    :options="users"></b-form-select>
            </div>
            <div class="d-flex justify-content-end">
                <Button class="mx-2" @click="editModal = false">Cancel</Button>
                <Button @click="editQuestion()" type="primary" :loading="editing">Edit</Button>
            </div>
        </b-modal>
        <!--Create Modal-->

        <delete-modal delete_url="/delete_question" item_name="question" :item_id="deleteId" v-model="deleteModal"
            :delete_index="deleteIndex" @successfulDelete="removeDeletedItem"></delete-modal>
    </div>
</template>
  
  <script>
  import { ref, watch } from "vue";
  import { useToast } from "vue-toastification";
  import useCallApi from "../../composables/useCallApi";
  import deleteModal from "../../partials/deleteModal.vue"
  import adminNavBar from "../../partials/adminNavBar.vue"
  export default {
      components: { deleteModal, adminNavBar },
      setup() {
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
  
  
          //edit categories
          const editModal = ref(false);
          const editing = ref(false);
          const editIndex = ref(null);
          const editData = ref({
              id: null,
              question: "",
              description: "",
              userId: null,
              categoryId: null,
          });
          const categories = ref([]);
          const users = ref([]);
  
          const getCategories = async () => {
              const res = await useCallApi("get", "/get_all_categories");
              if (res.status == 200) {
                  categories.value = res.data;
              } else {
                  toast.error(res.data.message);
              }
          };
  
          const getUsers = async () => {
              const res = await useCallApi('get', '/get_all_users')
  
              if (res.status == 200) {
                  users.value = res.data
              } else {
                  toast.error('Failed to load users!')
              }
          }
  
          const showEditModal = (question, index) => {
              if (categories.value.length == 0) getCategories();
              if (users.value.length == 0) getUsers();
              editData.value.id = question.id;
              editData.value.question = question.question;
              editData.value.description = question.description;
              editData.value.userId = question.user_id;
              editData.value.categoryId = question.category_id;
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
                  questions.value.at(editIndex.value).question = editData.value.question
                  questions.value.at(editIndex.value).description = editData.value.description
                  questions.value.at(editIndex.value).category_id = editData.value.categoryId
                  questions.value.at(editIndex.value).user_id = editData.value.userId
                  editModal.value = false;
              } else {
                  toast.error(res.data.message);
              }
              editing.value = false;
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
              users,
          };
      },
  };
  </script>