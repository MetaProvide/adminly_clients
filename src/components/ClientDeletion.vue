<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<div class="modal-header">
					<button
						class="close-button"
						@click="toggleModal()"
					></button>
				</div>
				<p>
					Do you want to delete client <span>{{ client.name }}</span
					>?
				</p>
				<div class="button-options">
					<button class="btn-yes" @click="deleteClient()">Yes</button>
					<button @click="toggleModal()">No</button>
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
	box-shadow: 0px 0px 11px var(--adminly-grey);
	border-radius: 8px;
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

.button-options {
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
	background-color: var(--adminly-dark-blue);
	color: white;
}
</style>
