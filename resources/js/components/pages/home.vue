<template>
  <div class="container my-5 p-5 bg-dark text-light">
    <div class="row g-5">
      <div class="col-md-3 rounded bg-secondary">
        <div class="my-3">
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
            <li @click="createQuestionModal = true">Create question</li>
            <li>My questions</li>
            <li>My answered questions</li>
          </ul>
        </div>
      </div>
      <div class="col-md-9 rounded ps-5 pe-5">
        <h1 class="text-center mb-5">Last Five Questions</h1>
        <Carousel :autoplay="8000" :wrap-around="true">
          <Slide v-for="(question, q) in lastFiveQuestions" :key="q">
            <router-link class="carousel__item" :to="{name: 'question', params: {id: question.id}}">
                <h3>{{ question.question }}</h3>
                <p>{{ question.description }}</p>
            </router-link>
          </Slide>

          <template #addons>
            <Navigation />
            <Pagination />
          </template>
        </Carousel>
      </div>
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
  </div>
</template>

<script>
import { ref } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
export default {
  components: {
    Carousel,
    Slide,
    Pagination,
    Navigation,
  },
  setup() {
    const toast = useToast();

    //create question
    const createQuestionModal = ref(false);
    const creatingQuestion = ref(false);
    const question = ref({
      question: "",
      description: "",
      category_id: "",
    });
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
        getLastFiveQuestions();
        toast.success("Question created sucessfully!");
        createQuestionModal.value = false;
      } else {
        toast.error(res.data.message);
      }
      creatingQuestion.value = false;
    };

    const categories = ref([]);
    const getCategories = async () => {
      const res = await useCallApi("get", "/get_all_categories");
      if ((res.status = 200)) {
        categories.value = res.data;
      } else {
        toast.error(res.data.message);
      }
    };

    getCategories();

    //get last five questions
    const lastFiveQuestions = ref([]);

    const getLastFiveQuestions = async () => {
      const res = await useCallApi('get', '/get_last_five_questions')

      if (res.status == 200) {
        lastFiveQuestions.value = res.data
      } else {
        toast.error('Failed to load the last five questions!')
      }
    }

    getLastFiveQuestions();

    return { createQuestionModal, question, createQuestion, categories, lastFiveQuestions, creatingQuestion };
  },
};
</script>

<style>
.carousel__item {
  min-height: 200px;
  width: 100%;
  background-color: #0d6efd;
  color: var(--vc-clr-white);
  font-size: 20px;
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.carousel__slide {
  padding: 10px;
}

.carousel__prev,
.carousel__next {
  background-color: #0d6efd;
  box-sizing: content-box;
  border: 5px solid white;
}

.carousel__pagination-button {
  background-color: grey;
}

.carousel__pagination-button--active {
  background-color: #0d6efd;
}
</style>