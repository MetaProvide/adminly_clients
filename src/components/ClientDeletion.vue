<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<p>Do you want to delete {{ client.name }}?</p>
				<button @click="toggleModal()">No</button>
				<button @click="deleteClient()">Yes</button>
			</div>
		</Modal>
	</div>
</template>

<script>
import { Modal } from "@nextcloud/vue";
import axios from "@nextcloud/axios";

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
	data() {
		return {
			activeClass: "active",
		};
	},
	methods: {
		deleteClient() {
			axios
				.post("delete", {
					id: this.client.id,
				})
				.then((response) => {
					this.toggleModal();
					this.$emit("update-clients", true);
				})
				.catch((error) => {
					alert(error);
				});
		},
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
