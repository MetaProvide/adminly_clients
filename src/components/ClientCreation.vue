<template>
	<div>
		<button class="create-btn" @click="toggleModal()">
			<div class="svg add-client"></div>
			<p>Add Client</p>
		</button>
		<Modal v-if="modal" @close="toggleModal()">
			<div class="modal-content">
				<div class="modal-header">
					<button
						class="svg close-button"
						@click="toggleModal()"
					></button>
				</div>
				<h1>New Client Information</h1>
				<form @submit.prevent="submitForm">
					<input
						id="name"
						v-model="name"
						type="text"
						placeholder="Full Name"
						required
					/>

					<input
						id="email"
						v-model="email"
						type="email"
						placeholder="Email"
						required
					/>
					<h1 class="form-heading">About</h1>
					<input
						id="description"
						v-model="description"
						type="text"
						placeholder="Description"
						required
					/>
					<div class="modal-footer">
						<button class="cancel" @click="toggleModal()">
							Cancel
						</button>
						<button class="submit" type="submit">Submit</button>
					</div>
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
input {
	width: 100%;
}

.submit {
	background-color: var(--adminly-dark-blue);
	color: white;
}

.modal-header {
	margin-left: auto;
	display: flex;
	justify-content: end;
}

.modal-content {
	padding: 1.25rem;
	width: 260px;
	display: flex;
	flex-direction: column;
	align-items: center;
}

h1 {
	font-weight: 500;
	padding-bottom: 1.5rem;
}

form div {
	margin: 0 1.25rem;
}

.create-btn {
	display: flex;
	box-shadow: 4px 4px 5px rgba(145, 149, 234, 0.3),
		inset 0.1px 0.1px 3px rgba(145, 149, 234, 0.3);
	border-radius: 6px;
}

.create-btn p {
	align-self: center;
}

.add-client {
	width: 43px;
	height: 34px;
	padding: 0.7rem 0.7rem;
	background-image: url("../../img/add-client.svg");
}

.modal-footer {
	display: flex;
	padding-top: 1.5rem;
}

.modal-footer button {
	font-family: "Roc Grotesk", var(--font-face);
}

button {
	border: none;
	background-color: white;
	border-radius: 6px;
}

.form-heading {
	padding-top: 1.5rem;
	display: flex;
	justify-content: center;
}
</style>
