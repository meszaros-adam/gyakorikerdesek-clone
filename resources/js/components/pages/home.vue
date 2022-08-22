<template>
  <div class="container my-5 p-5 bg-dark text-light">
    <div class="row g-5">
      <div class="col-md-3">
        <sideMenu @newQuestionCreated="getLastFiveQuestions"></sideMenu>
      </div>
      <div class="col-md-9 rounded ps-5 pe-5">
        <h1 class="text-center mb-5">Last Five Questions</h1>
        <!--This v-if needed to avoid "maximum recursive updates exceeded" error-->
        <div v-if="lastFiveQuestions.length > 0">
          <Carousel :autoplay="8000" :wrap-around="true">
            <Slide v-for="(question, q) in lastFiveQuestions" :key="q">
              <router-link class="carousel__item" :to="{ name: 'question', params: { id: question.id } }">
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
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import sideMenu from "../partials/sideMenu.vue";
export default {
  components: {
    Carousel,
    Slide,
    Pagination,
    Navigation,
    sideMenu,
  },
  setup() {
    const toast = useToast();

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

    return { lastFiveQuestions, getLastFiveQuestions };
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