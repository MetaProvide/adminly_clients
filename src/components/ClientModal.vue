<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				{{ client }}
			</div>
		</Modal>
	</div>
</template>

<script>
import { Modal } from "@nextcloud/vue";
export default {
	components: {
		Modal,
	},

	props: {
		client: {
			type: Object,
			default() {
				return {};
			},
		},
	},
	methods: {
		toggleModal() {
			this.$emit("toggle-modal", false);
		},
	},
};
</script>

<style scoped>
.modal-container {
	width: 50vw;
}

input {
	width: 100%;
}

button {
	box-shadow: 0px 0px 10.8184px rgba(0, 0, 0, 0.15);
	border-radius: 8px;
	background-color: white;
}

.modal-content {
	padding: 20px;
}
</style>
