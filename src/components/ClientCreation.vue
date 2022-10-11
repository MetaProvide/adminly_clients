<template>
	<div>
		<Modal id="create-modal" @close="toggleModal()">
			<div class="modal-header">
				<button
					class="svg close-button"
					@click="toggleModal()"
				></button>
			</div>
			<div class="modal-content">
				<h1>New Client Information</h1>
				<div>
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
						placeholder="Client Email"
						required
					/>
					<input
						id="phoneNumber"
						v-model="phoneNumber"
						type="tel"
						placeholder="Phone Number"
					/>
					<input
						id="city"
						v-model="city"
						type="text"
						placeholder="City"
					/>
					<input
						id="country"
						v-model="country"
						type="text"
						placeholder="Country"
					/>
					<h1 class="form-heading">About</h1>
					<textarea
						id="description"
						v-model="description"
						type="text"
						placeholder="Add Description"
					/>
					<div class="modal-footer">
						<button @click="toggleModal()">Cancel</button>
						<button class="submit" @click="submitForm()">
							Submit
						</button>
					</div>
				</div>
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
			phoneNumber: "",
			city: "",
			country: "",
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
					phoneNumber: this.phoneNumber,
					city: this.city,
					country: this.country,
					description: this.description,
				})
				.then((response) => {
					this.$emit("update-clients", true);
					this.toggleModal();
					this.name = "";
					this.email = "";
					this.city = "";
					this.country = "";
					this.phone = "";
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
			this.$router.push({
				path: "/",
			});
		},
		toggleErrorModal() {
			this.errorModal = !this.errorModal;
		},
	},
};
</script>
<style>
#create-modal .modal-container {
	display: flex;
	flex-direction: column;
	max-width: 320px;
	width: 100%;
}
</style>
<style scoped>
input,
textarea {
	width: 100%;
	border-radius: 4px;
}

.submit {
	background-color: var(--color-primary-element);
	color: white;
}

.modal-header {
	padding: 0.25rem 1rem 0 0;
	display: flex;
	justify-content: end;
}

.modal-content {
	align-self: center;
	padding: 0.5rem 1rem 1rem;
	width: 260px;
	display: flex;
	flex-direction: column;
	align-items: center;
}

h1 {
	font-weight: 500;
	font-size: 1rem;
	padding-bottom: 1rem;
}

form div {
	justify-content: center;
}

button {
	border: none;
	background-color: white;
	border-radius: 6px;
}

.form-heading {
	padding-top: 1rem;
	display: flex;
	justify-content: center;
}

.modal-footer {
	margin-top: 2rem;
}
</style>
