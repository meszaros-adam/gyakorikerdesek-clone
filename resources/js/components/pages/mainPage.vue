<template>
  <div class="container my-5 p-5 bg-dark text-light">
    <div class="row g-5">
      <div class="col-md-3 rounded bg-secondary">
        <div class="my-3">
          <h1>Side Menu</h1>
          <ul class="side-menu">
            <li>Admin Panel</li>
            <li @click="createQuestionModal = true">Create question</li>
            <li>My questions</li>
            <li>My answered questions</li>
          </ul>
        </div>
      </div>
      <div class="col-md-9 rounded text-center">
        <h1>something</h1>
      </div>
    </div>

    <!--Create Question Modal-->
    <b-modal
      v-model="createQuestionModal"
      hide-footer
      size="lg"
      title="Create Question"
    >
      <div class="mb-3">
        <label for="Question" class="form-label">Question</label>
        <input
          v-model="question.question"
          type="string"
          class="form-control"
          id="Question"
          placeholder="Your question..."
        />
      </div>
      <div class="mb-3">
        <label for="Description" class="form-label">Description</label>
        <textarea
          v-model="question.description"
          class="form-control"
          id="Description"
          rows="3"
          placeholder="Description..."
        ></textarea>
      </div>
      <div class="d-flex justify-content-end">
        <Button class="mx-2" @click="createQuestionModal = false"
          >Cancel</Button
        >
        <Button @click="createQuestion()" type="primary">Save</Button>
      </div>
    </b-modal>
    <!--Create Question Modal-->
  </div>
</template>

<script>
import { ref } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
export default {
  setup() {
    const toast = useToast();

    const createQuestionModal = ref(false);
    const creatingQuestion = ref(false);
    const question = ref({
      question: "",
      description: "",
    });
    async function createQuestion() {
      if (question.value.question.trim() == "")
        return toast.warning("You must enter a question!");
      if (question.value.question.trim().length < 6)
        return toast.warning(
          "Your question must be at least six characters long!"
        );

      creatingQuestion.value = true;

      const res = await useCallApi("post", "/create_question", question.value);

      if (res.status == 201) {
        toast.success("Question created sucessfully!");
      } else {
        toast.error(res.data.message);
      }
      creatingQuestion.value = true;
    }

    return { createQuestionModal, question, createQuestion };
  },
};
</script>