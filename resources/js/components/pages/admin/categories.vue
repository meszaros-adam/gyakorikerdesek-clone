<template>
  <div class="container my-5 p-5 bg-dark text-light">
    <Button @click="createCategoryModal = true">Create Category</Button>
  </div>
  <!--Create Category Modal-->
  <b-modal
    v-model="createCategoryModal"
    hide-footer
    title="Create Category"
  >
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
      <Button class="mx-2" @click="createCategoryModal = false">Cancel</Button>
      <Button @click="createCategory()" type="primary" :loading="creatingCategory">Save</Button>
    </div>
  </b-modal>
  <!--Create Category Modal-->
</template>

<script>
import { ref } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../../composables/useCallApi";

export default {
  setup() {
    const toast = useToast()
    const createCategoryModal = ref(false);
    const creatingCategory = ref(false);

    const category = ref({
      name: "",
    });

    async function createCategory(){
        if(category.value.name.trim().length < 3) return toast.warning('Category name must be at leat 3 characters!')

        creatingCategory.value = true

        const res = await useCallApi('post', '/create_category', category.value)

        if(res.status == 201){
            toast.success('Category created successfully!')
        }else{
             toast.error(res.data.message)
        }
        creatingCategory.value = false

    }

    return { createCategoryModal, category, createCategory, creatingCategory};
  },
};
</script>