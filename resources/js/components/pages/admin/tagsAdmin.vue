<template>
  <div>
    <div class="container my-5 p-3 bg-dark text-light">
      <div class="d-flex justify-content-end">
        <div class="d-flex align-items-center">
          <div class="text-nowrap me-3">Order By:</div>
          <select v-model="orderBy" @change="getTags" class="form-select me-3" aria-label="Default select example">
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
          <tr v-for="(tag, t) in tags" :key="t">
            <th scope="row">{{ tag.id }}</th>
            <td>{{ tag.name }}</td>
            <td>{{ tag.questions_count }}</td>
            <td>
              <i @click="showEditModal(tag, t)" title="Edit" class="bi bi-pencil pointer-cursor mx-1">
              </i>
              <i @click="showDeleteModal(tag.id, t)" title="Delete" class="bi bi-trash pointer-cursor mx-1"> </i>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Pagination -->
      <b-pagination v-model="currentPage" :total-rows="totalTags" :per-page="itemPerPage" align="center">
      </b-pagination>
      <!-- Pagination -->
    </div>
    <!--Edit Modal-->
    <b-modal v-model="editModal" hide-footer title="Edit Tag">
      <div class="mb-3">
        <label for="tag" class="form-label">Name</label>
        <input v-model="editData.name" type="string" class="form-control" id="tag" />
      </div>
      <div class="d-flex justify-content-end">
        <Button class="mx-2" @click="editModal = false">Cancel</Button>
        <Button @click="editTag()" type="primary" :loading="editing">Edit</Button>
      </div>
    </b-modal>
    <!--Edit Modal-->

    <delete-modal delete_url="/delete_tag" item_name="tag" :item_id="deleteId" v-model="deleteModal"
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

    //get tags
    const tags = ref([]);
    const orderBy = ref('id');
    const ordering = ref('desc')
    const itemPerPage = ref(10)

    //tags pagination
    const currentPage = ref(1)
    watch(currentPage, () => {
      getTags()
    })
    const totalTags = ref(0)

    const getTags = async () => {
      const res = await useCallApi(
        "get",
        `/get_tags?orderBy=${orderBy.value}&ordering=${ordering.value}&page=${currentPage.value}&itemPerPage=${itemPerPage.value}`
      );

      if (res.status == 200) {
        tags.value = res.data.data;
        totalTags.value = res.data.total
      } else {
        toast.error(res.data.message);
      }
    };

    const changeOrdering = (newOrdering) => {
      ordering.value = newOrdering
      getTags();
    }

    getTags();


    //edit tags
    const editModal = ref(false);
    const editing = ref(false);
    const editIndex = ref(null);
    const editData = ref({
      id: null,
      name: "",
    });

    const showEditModal = (tag, index) => {
      editData.value.name = tag.name;
      editData.value.id = tag.id;
      editModal.value = true;
      editIndex.value = index;
    };
    const editTag = async () => {
      if (editData.value.name.trim().length < 3)
        return toast.warning("Tag name must be at leat 3 characters!");

      editing.value = true;
      const res = await useCallApi("post", "/edit_tag", editData.value);

      if (res.status == 200) {
        toast.success("Tag edited successfully");
        tags.value[editIndex.value].name = editData.value.name
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
      tags.value.splice(index, 1)
    }

    return {
      tags,
      editModal,
      editTag,
      editData,
      showEditModal,
      editing,
      editIndex,
      deleteModal,
      showDeleteModal,
      deleteId,
      deleteIndex,
      removeDeletedItem,
      totalTags,
      currentPage,
      itemPerPage,
      orderBy,
      ordering,
      getTags,
      changeOrdering,
    };
  },
};
</script>