<template>
  <div>
    <div class="container my-5 p-5 bg-dark text-light">
      <Button @click="createCategoryModal = true"
        ><i class="bi bi-plus-lg"></i> Create Category</Button
      >
      <table class="table table-primary table-striped my-3">
        <thead>
          <tr>
            <th scope="col">#ID</th>
            <th scope="col">Name</th>
            <th scope="col">Functions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(category, c) in categories" :key="c">
            <th scope="row">{{ category.id }}</th>
            <td>{{ category.name }}</td>
            <td>
              <i title="Edit" class="bi bi-pencil function-icon mx-1"> </i>
              <i title="Delete" class="bi bi-trash function-icon mx-1"> </i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--Create Category Modal-->
    <b-modal v-model="createCategoryModal" hide-footer title="Create Category">
      <div class="mb-3">
        <label for="Category" class="form-label">Name</label>
        <input
          v-model="category.name"
          type="string"
          class="form-control"
          id="Category"
          placeholder="Your Category Name..."
        />
      </div>
      <div class="d-flex justify-content-end">
        <Button class="mx-2" @click="createCategoryModal = false"
          >Cancel</Button
        >
        <Button
          @click="createCategory()"
          type="primary"
          :loading="creatingCategory"
          >Save</Button
        >
      </div>
    </b-modal>
    <!--Create Category Modal-->
  </div>
</template>

<script>
import { ref } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../../composables/useCallApi";

export default {
  setup() {
    const toast = useToast();

    //create category
    const createCategoryModal = ref(false);
    const creatingCategory = ref(false);
    const category = ref({
      name: "",
    });
    async function createCategory() {
      if (category.value.name.trim().length < 3)
        return toast.warning("Category name must be at leat 3 characters!");

      creatingCategory.value = true;

      const res = await useCallApi("post", "/create_category", category.value);

      if (res.status == 201) {
        (category.value.name = ""),
          (createCategoryModal.value = false),
          toast.success("Category created successfully!");
      } else {
        toast.error(res.data.message);
      }
      creatingCategory.value = false;
    }

    //get categories
    const categories = ref([]);

    const orderBy = "id";
    const ordering = "desc";
    const itemPerPage = 10;

    async function getCategories() {
      const res = await useCallApi(
        "get",
        `/get_categories?orderBy=${orderBy}&ordering=${ordering}&itemPerPage=${itemPerPage}`
      );

      if (res.status == 200) {
        categories.value = res.data.data;
      } else {
        toast.error("Failed to load categories!");
      }
    }
    getCategories();

    return {
      createCategoryModal,
      category,
      createCategory,
      creatingCategory,
      categories,
    };
  },
};
</script>