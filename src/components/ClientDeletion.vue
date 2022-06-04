<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<div class="modal-header">
					<button @click="toggleModal()">
						<svg
							fill="#8E8E8E"
							width="20"
							height="20"
							viewBox="0 0 24 24"
							class="material-design-icon__svg"
						>
							<path
								d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"
							></path>
						</svg>
					</button>
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
input {
	width: 100%;
}

button {
	box-shadow: 0px 0px 11px rgba(0, 0, 0, 0.15);
	border-radius: 8px;
	background-color: white;
}

.modal-content {
	padding: 20px;
}

p {
	margin: 20px 20px 35px;
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
	background-color: #346188;
	color: white;
}
</style>
