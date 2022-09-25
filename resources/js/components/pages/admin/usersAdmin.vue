<template>
    <div>
        <div class="container my-5 p-3 bg-dark text-light">
            <table class="table table-primary table-striped my-3">
                <thead>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Nickname</th>
                        <th scope="col">Admin</th>
                        <th scope="col">Functions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, c) in users" :key="c">
                        <th scope="row">{{ user.id }}</th>
                        <td>{{ user.nickname }}</td>
                        <td>{{ user.admin }}</td>
                        <td>
                            <i @click="showEditModal(user, c)" title="Edit" class="bi bi-pencil pointer-cursor mx-1">
                            </i>
                            <i @click="showDeleteModal(user.id, c)" title="Delete"
                                class="bi bi-trash pointer-cursor mx-1"> </i>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination -->
            <b-pagination v-model="currentPage" :total-rows="totalUsers" :per-page="itemPerPage" align="center">
            </b-pagination>
            <!-- Pagination -->
        </div>
        <!--Edit Modal-->
        <b-modal v-model="editModal" hide-footer title="Edit user">
            <div class="mb-3">
                <label for="user" class="form-label">Nickname</label>
                <input v-model="editData.nickname" type="string" class="form-control" id="user" />
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input v-model="editData.admin" class="form-check-input" type="checkbox" role="switch" id="admin">
                    <label class="form-check-label" for="admin">Admin</label>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <Button class="mx-2" @click="editModal = false">Cancel</Button>
                <Button @click="editUser()" type="primary" :loading="editing">Edit</Button>
            </div>
        </b-modal>
        <!--Create Modal-->

        <delete-modal delete_url="/delete_user" item_name="user" :item_id="deleteId" v-model="deleteModal"
            :delete_index="deleteIndex" @successfulDelete="removeDeletedItem"></delete-modal>
    </div>
</template>
  
<script>
import { ref, watch } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../../composables/useCallApi";
import deleteModal from "../../partials/deleteModal.vue";
export default {
    components: { deleteModal },
    setup() {
        const toast = useToast();

        //get users
        const users = ref([]);
        const orderBy = ref('id');
        const ordering = ref('desc')
        const itemPerPage = ref(10)

        //users pagination
        const currentPage = ref(1)
        watch(currentPage, () => {
            getUsers()
        })
        const totalUsers = ref(0)

        const getUsers = async () => {
            const res = await useCallApi(
                "get",
                `/get_users?orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`
            );

            if (res.status == 200) {
                users.value = res.data.data;
                totalUsers.value = res.data.total
            } else {
                toast.error(res.data.message);
            }
        };
        getUsers();


        //edit users
        const editModal = ref(false);
        const editing = ref(false);
        const editIndex = ref(null);
        const editData = ref({
            id: null,
            nickname: "",
            admin: null,
        });

        const showEditModal = (user, index) => {
            editData.value.nickname = user.nickname;
            editData.value.id = user.id;
            editData.value.admin = user.admin;
            editModal.value = true;
            editIndex.value = index;
        };
        const editUser = async () => {
            if (editData.value.nickname.trim().length < 3)
                return toast.warning("Nickname must be at leat 3 characters!");

            editing.value = true;
            const res = await useCallApi("post", "/edit_user", editData.value);

            if (res.status == 200) {
                toast.success("User edited successfully");
                users.value[editIndex.value].nickname = editData.value.nickname
                users.value[editIndex.value].admin = editData.value.admin
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
            users.value.splice(index, 1)
        }

        return {
            users,
            editModal,
            editUser,
            editData,
            showEditModal,
            editing,
            editIndex,
            deleteModal,
            showDeleteModal,
            deleteId,
            deleteIndex,
            removeDeletedItem,
            totalUsers,
            currentPage,
            itemPerPage,
        };
    },
};
</script>