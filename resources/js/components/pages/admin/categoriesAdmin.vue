<template>
  <div>
    <div class="container my-5 p-3 bg-dark text-light">
      <div class="d-flex justify-content-between">
        <Button @click="createModal = true"><i class="bi bi-plus-lg"></i> Create Category</Button>
        <div class="d-flex align-items-center">
          <div class="text-nowrap me-3">Order By:</div>
          <select v-model="orderBy" @change="getCategories" class="form-select me-3"
            aria-label="Default select example">
            <option value="id">ID</option>
            <option value="name">Name</option>
            <option value="questions_count">Questions Count</option>
          </select>
          <i v-show="ordering == 'desc'" @click="changeOrdering('asc')" class="bi bi-arrow-up pointer-cursor"
            title="Ascending Order"></i>
          <i v-show="ordering == 'asc'" @click="changeOrdering('desc')" class="bi bi-arrow-down pointer-cursor"
            title="Descending Order"></i>
        </div>
      </div>
      <table class="table table-primary table-striped my-3">
        <thead>
          <tr>
            <th scope="col">#ID</th>
            <th scope="col">Name</th>
            <th scope="col">Questions Count</th>
            <th scope="col">Functions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(category, c) in categories" :key="c">
            <th scope="row">{{ category.id }}</th>
            <td>{{ category.name }}</td>
            <td>{{ category.questions_count }}</td>
            <td>
              <i @click="showEditModal(category, c)" title="Edit" class="bi bi-pencil pointer-cursor mx-1">
              </i>
              <i @click="showDeleteModal(category.id, c)" title="Delete" class="bi bi-trash pointer-cursor mx-1"> </i>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Pagination -->
      <b-pagination v-model="currentPage" :total-rows="totalCategories" :per-page="itemPerPage" align="center">
      </b-pagination>
      <!-- Pagination -->
    </div>
    <!--Create Modal-->
    <b-modal v-model="createModal" hide-footer title="Create Category">
      <div class="mb-3">
        <label for="Category" class="form-label">Name</label>
        <input v-model="category.name" type="string" class="form-control" id="Category"
          placeholder="Your Category Name..." />
      </div>
      <div class="d-flex justify-content-end">
        <Button class="mx-2" @click="createModal = false">Cancel</Button>
        <Button @click="createCategory()" type="primary" :loading="creatingCategory">Save</Button>
      </div>
    </b-modal>
    <!--Create Modal-->
    <!--Edit Modal-->
    <b-modal v-model="editModal" hide-footer title="Edit Category">
      <div class="mb-3">
        <label for="Category" class="form-label">Name</label>
        <input v-model="editData.name" type="string" class="form-control" id="Category" />
      </div>
      <div class="d-flex justify-content-end">
        <Button class="mx-2" @click="editModal = false">Cancel</Button>
        <Button @click="editCategory()" type="primary" :loading="editing">Edit</Button>
      </div>
    </b-modal>
    <!--Create Modal-->

    <delete-modal delete_url="/delete_category" item_name="category" :item_id="deleteId" v-model="deleteModal"
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

    //create category
    const createModal = ref(false);
    const creatingCategory = ref(false);
    const category = ref({
      name: "",
    });
    const createCategory = async () => {
      if (category.value.name.trim().length < 3)
        return toast.warning("Category name must be at leat 3 characters!");

      creatingCategory.value = true;

      const res = await useCallApi("post", "/create_category", category.value);

      if (res.status == 201) {
        createModal.value = false;
        category.value.name = "";
        categories.value.unshift(res.data);
        toast.success("Category created successfully!");
      } else {
        toast.error(res.data.message);
      }
      creatingCategory.value = false;
    };

    //get categories
    const categories = ref([]);
    const orderBy = ref('id');
    const ordering = ref('desc')
    const itemPerPage = ref(10)

    //categories pagination
    const currentPage = ref(1)
    watch(currentPage, () => {
      getCategories()
    })
    const totalCategories = ref(0)

    const getCategories = async () => {
      const res = await useCallApi(
        "get",
        `/get_categories?orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`
      );

      if (res.status == 200) {
        categories.value = res.data.data;
        totalCategories.value = res.data.total
      } else {
        toast.error(res.data.message);
      }
    };
    getCategories();

    const changeOrdering = (newOrdering) => {
      ordering.value = newOrdering
      getCategories();
    }


    //edit categories
    const editModal = ref(false);
    const editing = ref(false);
    const editIndex = ref(null);
    const editData = ref({
      id: null,
      name: "",
    });

    const showEditModal = (category, index) => {
      editData.value.name = category.name;
      editData.value.id = category.id;
      editModal.value = true;
      editIndex.value = index;
    };
    const editCategory = async () => {
      if (editData.value.name.trim().length < 3)
        return toast.warning("Category name must be at leat 3 characters!");

      editing.value = true;
      const res = await useCallApi("post", "/edit_category", editData.value);

      if (res.status == 200) {
        toast.success("Category edited successfully");
        categories.value[editIndex.value].name = editData.value.name
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
      categories.value.splice(index, 1)
    }

    return {
      createModal,
      category,
      createCategory,
      creatingCategory,
      categories,
      editModal,
      editCategory,
      editData,
      showEditModal,
      editing,
      editIndex,
      deleteModal,
      showDeleteModal,
      deleteId,
      deleteIndex,
      removeDeletedItem,
      totalCategories,
      currentPage,
      itemPerPage,
      orderBy,
      ordering,
      getCategories,
      changeOrdering,
    };
  },
};
</script>