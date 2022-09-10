<template>
    <!--Create Question Modal-->
    <b-modal hide-footer size="lg" title="Create Question" @show="showModal">
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
            <Button class="mx-2" @click="closeModal">Cancel</Button>
            <Button @click="createQuestion()" :loading="creatingQuestion" type="primary">Save</Button>
        </div>
    </b-modal>
    <!--Create Question Modal-->
</template>

<script>
import { ref, watch } from "vue";
import { useToast } from "vue-toastification";
import useCallApi from "../composables/useCallApi";
export default {
    setup(props, context) {
        const toast = useToast()

        //create question
        const createQuestionModal = ref(false);
        const creatingQuestion = ref(false);
        const question = ref({});

        const closeModal = () => {
            context.emit("update:modelValue", false)
        }

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
                context.emit('newQuestionCreated')
                toast.success("Question created sucessfully!");
                createQuestionModal.value = false;
            } else {
                toast.error(res.data.message);
            }
            question.value = {};
            creatingQuestion.value = false;
            closeModal();
        };

        const categories = ref([]);
        const getCategories = async () => {

            const res = await useCallApi("get", "/get_all_categories");
            if (res.status == 200) {
                categories.value = res.data;
            } else {
                toast.error(res.data.message);
            }
        };

        const showModal = () => {
            if(categories.value.length == 0){
                getCategories();
            }
        }

        return { createQuestionModal, creatingQuestion, question, createQuestion, categories, closeModal, showModal }
    }
}
</script>