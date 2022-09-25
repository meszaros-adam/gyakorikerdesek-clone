<template>
    <div>
      <div class="container my-5 p-3 bg-dark text-light">
        <Button @click="createModal = true"><i class="bi bi-plus-lg"></i> Create Tag</Button>
        <table class="table table-primary table-striped my-3">
          <thead>
            <tr>
              <th scope="col">#ID</th>
              <th scope="col">Name</th>
              <th scope="col">Functions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(tag, t) in tags" :key="t">
              <th scope="row">{{ tag.id }}</th>
              <td>{{ tag.name }}</td>
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
      <!--Create Modal-->
      <b-modal v-model="createModal" hide-footer title="Create Tag">
        <div class="mb-3">
          <label for="tag" class="form-label">Name</label>
          <input v-model="tag.name" type="string" class="form-control" id="tag"
            placeholder="Your Tag Name..." />
        </div>
        <div class="d-flex justify-content-end">
          <Button class="mx-2" @click="createModal = false">Cancel</Button>
          <Button @click="createTag()" type="primary" :loading="creatingTag">Save</Button>
        </div>
      </b-modal>
      <!--Create Modal-->
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
      <!--Create Modal-->
  
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
  
      //create tag
      const createModal = ref(false);
      const creatingTag = ref(false);
      const tag = ref({
        name: "",
      });
      const createTag = async () => {
        if (tag.value.name.trim().length < 2)
          return toast.warning("Tag name must be at leat 2 characters!");
  
        creatingTag.value = true;
  
        const res = await useCallApi("post", "/create_tag", tag.value);
  
        if (res.status == 201) {
          createModal.value = false;
          tag.value.name = "";
          tags.value.unshift(res.data);
          toast.success("Tag created successfully!");
        } else {
          toast.error(res.data.message);
        }
        creatingTag.value = false;
      };
  
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
        createModal,
        tag,
        createTag,
        creatingTag,
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
      };
    },
  };
  </script>