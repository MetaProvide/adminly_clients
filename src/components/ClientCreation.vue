<template>
	<div>
		<button class="create-btn" @click="toggleModal()">
			<div class="add-client"></div>
			<p>Add Client</p>
		</button>
		<Modal v-if="modal" @close="toggleModal()">
			<div class="modal-content">
				<div class="modal-header">
					<button
						class="close-button"
						@click="toggleModal()"
					></button>
				</div>
				<form @submit.prevent="submitForm">
					<div>
						<label for="name">Name:</label><br />
						<input id="name" v-model="name" type="text" required />
					</div>
					<div>
						<label for="email">Email:</label><br />
						<input
							id="email"
							v-model="email"
							type="email"
							required
						/>
					</div>

					<div>
						<label for="description">Description:</label><br />
						<input
							id="description"
							v-model="description"
							type="text"
							required
						/>
					</div>
					<button class="submit" type="submit">Submit</button>
				</form>
			</div>
		</Modal>
		<ErrorModal
			v-if="errorModal"
			:message="errorMessage"
			@toggle-modal="toggleErrorModal"
		/>
	</div>
</template>

<script>
import { Modal } from "@nextcloud/vue";
import axios from "@nextcloud/axios";
import ErrorModal from "./ErrorModal";

export default {
	components: {
		Modal,
		ErrorModal,
	},
	data() {
		return {
			modal: false,
			errorModal: false,
			name: "",
			email: "",
			description: "",
			errorMessage: "",
		};
	},
	methods: {
		submitForm() {
			axios
				.post("create", {
					name: this.name,
					email: this.email,
					description: this.description,
				})
				.then((response) => {
					this.$emit("update-clients", true);
					this.toggleModal();
					this.name = "";
					this.email = "";
					this.description = "";
				})
				.catch((error) => {
					this.errorMessage = error.response
						? error.response.data
						: error;
					this.toggleErrorModal(this.errorMessage);
				});
		},
		toggleModal() {
			this.modal = !this.modal;
		},
		toggleErrorModal() {
			this.errorModal = !this.errorModal;
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
	box-shadow: 0px 0px 11px var(--adminly-grey);
	border-radius: 8px;
	background-color: white;
}

.submit {
	background-color: var(--adminly-dark-blue);
	color: white;
	margin-top: 1rem;
	margin-inline: auto;
	display: flex;
	line-height: 1.25rem;
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

.modal-content {
	padding: 1.25rem;
}

form div {
	margin: 0 1.25rem;
}

.create-btn {
	display: flex;
	box-shadow: 4px 4px 5px rgba(145, 149, 234, 0.3),
		inset 0.1px 0.1px 3px rgba(145, 149, 234, 0.3);
	border: none;
}

.create-btn p {
	align-self: center;
}

.add-client {
	width: 43px;
	height: 34px;
	padding: 0.7rem 0.7rem;
	background-image: url("../../img/add-client.svg");
	background-position: center;
	background-repeat: no-repeat;
}
</style>
