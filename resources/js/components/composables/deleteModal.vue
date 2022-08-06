<template>
    <b-modal hide-footer :title=title>
        <div class="mb-3 text-center">
            <i class="bi bi-exclamation-octagon-fill delete-modal-icon"></i>
        </div>
        <h4 class="mb-3">Are you sure you want to delete this {{ item_name }}?</h4>
        <div class="d-flex justify-content-end">
            <Button @click="closeModal" class="mx-2">Cancel</Button>
            <Button type="primary">Delete</Button>
        </div>
    </b-modal>
</template>

<script>
import { computed } from 'vue'

export default {
    props: ['delete_url', 'item_name'],
    setup(props, context) {
        const title = computed(() => "Delete " + props.item_name)

        const closeModal = () =>{
            context.emit("update:modelValue",  false)
        }

        return { title , closeModal }
    }
}
</script>