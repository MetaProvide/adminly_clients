<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<p>
					Do you want to delete client <span>{{ client.name }}</span
					>?
				</p>
				<div class="button-options">
					<button @click="toggleModal()">No</button>
					<button @click="deleteClient()">Yes</button>
				</div>
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
<style>
.modal-container {
	width: 350px;
}
</style>
<style scoped>
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

p {
	margin: 20px;
}

p span {
	font-weight: bold;
}

.button-options {
	display: flex;
	justify-content: flex-end;
}
</style>
