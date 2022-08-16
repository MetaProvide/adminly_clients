<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<div class="modal-header">
					<button
						class="svg close-button"
						@click="toggleModal()"
					></button>
				</div>
				<p>
					Do you want to delete client <span>{{ client.name }}</span
					>?
				</p>
				<div class="modal-footer">
					<button class="cancel-button" @click="toggleModal()">
						Cancel
					</button>
					<button class="btn-yes" @click="deleteClient()">Yes</button>
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
<style scoped>
button {
	border-radius: 6px;
	background-color: white;
}

.modal-content {
	padding: 1.25rem;
}

p {
	margin: 1.25rem 1.25rem 2.25rem;
}

p span {
	font-weight: bold;
}

.modal-footer {
	display: flex;
	justify-content: center;
}

.modal-header {
	margin-left: auto;
	display: flex;
	justify-content: end;
}

.modal-header button {
	border: none;
	box-shadow: none;
}

.btn-yes {
	background-color: var(--adminly-blue);
	color: white;
}

.cancel-button {
	border: none;
	border-radius: 6px;
}
</style>
